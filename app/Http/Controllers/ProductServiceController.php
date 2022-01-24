<?php

namespace App\Http\Controllers;

use DB;
use App\CustomField;
use App\ProductService;
use App\ProductServiceCategory;
use App\ProductServiceUnit;
use App\Tax;
use Illuminate\Http\Request;

class ProductServiceController extends Controller
{
  public function index(Request $request)
  {

    if(\Auth::user()->can('manage product & service'))
    {
      $category = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 0)->get()->pluck('name', 'id');
      if(!empty($request->category))
      {

        $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->where('category_id', $request->category)->get();
      }
      else
      {
        $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
      }

      return view('productservice.index', compact('productServices', 'category'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function create()
  {
    if(\Auth::user()->can('create product & service'))
    {
      $user = \Auth::id();
      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'product')->get();
      $category     = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 0)->get()->pluck('name', 'id');
      $unit         = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
      $tax          = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
      $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));

      $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts_old on frequently_used_coa.chart_of_accounts_id = chart_of_accounts_old.id  WHERE  frequently_used_coa.user_id = ".$user ));

      return view('productservice.create', compact('category', 'unit', 'tax', 'customFields','fui_caos','fui_atc_lists'));
    }
    else
    {
      return response()->json(['error' => __('Permission denied.')], 401);
    }
  }


  public function store(Request $request)
  {
    if(\Auth::user()->can('create product & service'))
    {

      $rules = [
        'name' => 'required',
        'sku' => 'required',
        'sale_price' => 'required|numeric',
        'purchase_price' => 'required|numeric',
        'tax_id' => 'required',
        'category_id' => 'required',
        'unit_id' => 'required',
        'type' => 'required',
        'atc_list_id' => 'required',
        'coa_id' => 'required',
      ];

      $validator = \Validator::make($request->all(), $rules);

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->route('productservice.index')->with('error', $messages->first());
      }

      $productService                 = new ProductService();
      $productService->name           = $request->name;
      $productService->description    = $request->description;
      $productService->sku            = $request->sku;
      $productService->sale_price     = $request->sale_price;
      $productService->purchase_price = $request->purchase_price;
      $productService->tax_id         = implode(',', $request->tax_id);
      $productService->unit_id        = $request->unit_id;
      $productService->type           = $request->type;
      $productService->category_id    = $request->category_id;
      $productService->atc_list_id    = $request->atc_list_id;
      $productService->coa_id         = $request->coa_id;
      $productService->created_by     = \Auth::user()->creatorId();
      $productService->save();

      if($request->attachment)
      {
        $imageName = 'productService_' . $productService->id . "_" . $request->attachment->getClientOriginalName();
        // $request->attachment->storeAs('public/attachment', $imageName);
        $request->attachment->storeAs('attachment', $imageName);
        $productService->attachment = $imageName;
        $productService->save();
      }
      CustomField::saveData($productService, $request->customField);

      return redirect()->route('productservice.index')->with('success', __('Product successfully created.'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  public function point_of_sale(Request $request)
  {
    $categories = ProductServiceCategory::where('type','0')->get();
    return view('productservice.point_of_sale',compact('categories'));
  }

  public function filterProducts(Request $request)
  {  
    $category = $request->category;
     if($category == 0){
      $productService = ProductService::all();
      return [$productService,$category];
     }else{
      $productService = ProductService::where('category_id',$category)->get();
      return [$productService,$category];
     }
   
  }

  public function displayImage($filename)
{
    $path = storage_public('attachment/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;

}


  public function edit($id)
  {
    $productService = ProductService::find($id);

    if(\Auth::user()->can('edit product & service'))
    {
      $user = \Auth::id();
      if($productService->created_by == \Auth::user()->creatorId())
      {
        $category = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 0)->get()->pluck('name', 'id');
        $unit     = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $tax      = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        $productService->customField = CustomField::getData($productService, 'product');
        $attachment = $productService->attachment;
        $customFields  = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'product')->get();
        $productService->tax_id      = explode(',', $productService->tax_id);
        $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));
        $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts_old on frequently_used_coa.chart_of_accounts_id = chart_of_accounts_old.id  WHERE  frequently_used_coa.user_id = ".$user ));

        return view('productservice.edit', compact('category', 'unit', 'tax', 'productService', 'customFields','fui_atc_lists','fui_caos','attachment'));
      }
      else
      {
        return response()->json(['error' => __('Permission denied.')], 401);
      }
    }
    else
    {
      return response()->json(['error' => __('Permission denied.')], 401);
    }
  }


  public function update(Request $request, $id)
  {

    if(\Auth::user()->can('edit product & service'))
    {
      $productService = ProductService::find($id);
      if($productService->created_by == \Auth::user()->creatorId())
      {

        $rules = [
          'name' => 'required',
          'sku' => 'required',
          'sale_price' => 'required|numeric',
          'purchase_price' => 'required|numeric',
          'tax_id' => 'required',
          'category_id' => 'required',
          'unit_id' => 'required',
          'type' => 'required',
          'atc_list_id' => 'required',
          'coa_id' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails())
        {
          $messages = $validator->getMessageBag();

          return redirect()->route('expenses.index')->with('error', $messages->first());
        }

        $productService->name           = $request->name;
        $productService->description    = $request->description;
        $productService->sku            = $request->sku;
        $productService->sale_price     = $request->sale_price;
        $productService->purchase_price = $request->purchase_price;
        $productService->tax_id         = implode(',', $request->tax_id);
        $productService->unit_id        = $request->unit_id;
        $productService->type           = $request->type;
        $productService->category_id    = $request->category_id;
        $productService->atc_list_id    = $request->atc_list_id;
        $productService->coa_id         = $request->coa_id;
        $productService->created_by     = \Auth::user()->creatorId();
        $productService->save();
        CustomField::saveData($productService, $request->customField);
        if($request->attachment)
        {
            if($productService->attachment)
            {
                // \File::delete(storage_path('uploads/attachment/' . $productService->attachment));
                \File::delete(storage_path('attachment/' . $productService->attachment));
            }
            $imageName = 'productService_' . $productService->id . "_" . $request->attachment->getClientOriginalName();
            $request->attachment->storeAs('attachment', $imageName);
            $productService->attachment = $imageName;
            $productService->save();
        }


        return redirect()->route('productservice.index')->with('success', __('Product successfully updated.'));
      }
      else
      {
        return redirect()->back()->with('error', __('Permission denied.'));
      }
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function destroy($id)
  {
    if(\Auth::user()->can('delete product & service'))
    {
      $productService = ProductService::find($id);
      if($productService->created_by == \Auth::user()->creatorId())
      {
        $productService->delete();

        return redirect()->route('productservice.index')->with('success', __('Product successfully deleted.'));
      }
      else
      {
        return redirect()->back()->with('error', __('Permission denied.'));
      }
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

}
