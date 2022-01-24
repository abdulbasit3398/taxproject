<?php

namespace App\Http\Controllers;

use App\Client;
use App\Customer;
use App\Invoice;
use App\BillingStatement;
use App\BillingStatementProduct;
use App\ProductService;
use App\CompanyBranch;
use App\ProductServiceCategory;
use Illuminate\Http\Request;

class BillingStatementController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth', ['except' => ['invoice']]);
  }
  public function index()
  {
    if(\Auth::user()->can('manage billing statement'))
    {
      $customer = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
      $customer->prepend('All', '');

      $status = Invoice::$statues;

      $query = Invoice::where('created_by', '=', \Auth::user()->creatorId());

      if(!empty($request->customer))
      {
        $query->where('customer_id', '=', $request->customer);
      }
      if(!empty($request->issue_date))
      {
        $date_range = explode(' - ', $request->issue_date);
        $query->whereBetween('issue_date', $date_range);
      }

      if(!empty($request->status))
      {
        $query->where('status', '=', $request->status);
      }
      $invoices = $query->get();

      return view('billing_statement.index', compact('invoices', 'customer', 'status'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission Denied.'));
    }
  }

  
  public function create($customerId)
  {
    if(\Auth::user()->can('manage billing statement'))
    {
      $invoice_number = \Auth::user()->billingStatementFormat($this->invoiceNumber());
      $customers      = Client::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
      $customers->prepend('Select Client', '');
      $category = ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->where('type', 1)->get()->pluck('name', 'id');
      $category->prepend('Select Category', '');
      $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
      $product_services->prepend('--', '');

      $company_branchs = CompanyBranch::where('user_id',\Auth::id())->get();
      return view('billing_statement.create', compact('customers', 'invoice_number', 'product_services','customerId','category','company_branchs'));
    }
    else
    {
      return response()->json(['error' => __('Permission denied.')], 401);
    }
  }

  
  public function store(Request $request)
  {
    if(\Auth::user()->can('manage billing statement'))
    {
      $validator = \Validator::make(
        $request->all(), [
         'client_id' => 'required',
         'issue_date' => 'required',
         'due_date' => 'required',
         'category_id' => 'required',
         'items' => 'required',
         'branch_id' => 'required',
       ]
     );
      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->back()->with('error', $messages->first());
      }
      $status = BillingStatement::$statues;

      $invoice                 = new BillingStatement();
      $invoice->billing_statement_id     = $this->invoiceNumber();
      $invoice->client_id    = $request->client_id;
      $invoice->branch_id      = $request->branch_id;
      $invoice->status         = 0;
      $invoice->issue_date     = $request->issue_date;
      $invoice->due_date       = $request->due_date;
      $invoice->category_id    = $request->category_id;
      $invoice->ref_number     = $request->ref_number;
      $invoice->discount_apply = isset($request->discount_apply) ? 1 : 0;
      $invoice->created_by     = \Auth::user()->creatorId();
      $invoice->save();

      $products = $request->items;

      for($i = 0; $i < count($products); $i++)
      {
        $invoiceProduct              = new BillingStatementProduct();
        $invoiceProduct->billing_statement_id  = $invoice->id;
        $invoiceProduct->product_id  = $products[$i]['item'];
        $invoiceProduct->quantity    = $products[$i]['quantity'];
        $invoiceProduct->tax         = $products[$i]['tax'];
        $invoiceProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
        $invoiceProduct->price       = $products[$i]['price'];
        $invoiceProduct->save();
      }

      return redirect()->route('billing-statement.index', $invoice->id)->with('success', __('Billing Statement successfully created.'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  
  public function show($id)
  {
      //
  }

  
  public function edit($id)
  {
      //
  }

  
  public function update(Request $request, $id)
  {
      //
  }

  
  public function destroy($id)
  {
      //
  }
  function invoiceNumber()
  {
    $latest = BillingStatement::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
    if(!$latest)
    {
      return 1;
    }

    return $latest->invoice_id + 1;
  }
  public function client(Request $request)
  {
    $customer = Client::where('id', '=', $request->id)->first();

    return view('billing_statement.customer_detail', compact('customer'));
  }
}
