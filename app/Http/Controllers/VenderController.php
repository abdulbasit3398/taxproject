<?php

namespace App\Http\Controllers;

use App\Exports\VendorExport;
use App\CustomField;
use App\Mail\UserCreate;
use App\Plan;
use App\Transaction;
use App\LineofBusiness;
use App\ChartofAccountsOld as ChartofAccounts;
use App\AtcList;
use App\OrganisationType;
use App\User;
use App\Vender;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Excel;

class VenderController extends Controller
{

  public function dashboard()
  {
    $data['billChartData'] = \Auth::user()->billChartData();

    return view('vender.dashboard', $data);
  }

  public function index()
  {
    if(\Auth::user()->can('manage vender'))
    {
      $venders = Vender::where('created_by', \Auth::user()->creatorId())->get();

      return view('vender.index', compact('venders'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function create()
  {
    if(\Auth::user()->can('create vender'))
    {
      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'vendor')->get();
      $user = \Auth::id();
      $line_of_business = LineofBusiness::all();
      $atc_lists = AtcList::all();
      $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts_old on frequently_used_coa.chart_of_accounts_id = chart_of_accounts_old.id  WHERE  frequently_used_coa.user_id = ".$user ));
      $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));
      $organisations = OrganisationType::all();
      return view('vender.create', compact('customFields','line_of_business','atc_lists','fui_atc_lists','fui_caos','organisations'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function store(Request $request)
  {
    if(\Auth::user()->can('create vender'))
    {
      $rules = [
        'name' => 'required',
        // 'middle_name' => 'required',
        'last_name' => 'required',
        'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        // 'landline' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        // 'line_of_business' => 'regex:/^([0-9\s\-\+\(\)]*)$/',
        'email' => 'email|unique:venders',
        // 'password' => 'required',
        'billing_country' => 'required',
        'billing_city' => 'required',
        // 'billing_zip' => 'required',
        'billing_address' => 'required',
        'vendor_tin' => 'required',
        // 'organization_name' => 'required',
        // 'organization_type' => 'required',
        // 'atc_id' => 'required',
        // 'tax_type' => 'required',
      ];


      $validator = \Validator::make($request->all(), $rules);

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->route('vender.index')->with('error', $messages->first());
      }

      $objVendor    = \Auth::user();
      $total_vendor = $objVendor->countVenders();
      $plan         = Plan::find($objVendor->plan);
      if($total_vendor < $plan->max_venders || $plan->max_venders == -1)
      {
        $vender                   = new Vender();
        $vender->vender_id        = $this->venderNumber();
        $vender->name             = $request->name;
        $vender->middle_name      = ($request->middle_name) ? $request->middle_name :'';
        $vender->last_name        = $request->last_name;
        $vender->contact          = $request->contact;
        $vender->branch_name      = $request->branch_name;
        $vender->line_of_business = ($request->line_of_business) ? $request->line_of_business : '';
        $vender->email            = ($request->email) ? $request->email : '';
        $vender->password         = ($request->password) ? Hash::make($request->password) : '';
        $vender->created_by       = \Auth::user()->creatorId();
        $vender->billing_country  = $request->billing_country;
        $vender->billing_city     = $request->billing_city;
        $vender->billing_zip      = $request->billing_zip;
        $vender->billing_address  = $request->billing_address;
        $vender->vendor_tin    = $request->vendor_tin;
        $vender->organization_name = $request->organization_name;
        $vender->organization_type   = ($request->organization_type) ? $request->organization_type : '';
        
        $vender->atc_list_id   = $request->atc_id;
        $vender->tax_type   = $request->tax_type;
        $vender->customer_since   = $request->customer_since;
        $vender->coa_id   = $request->coa_id;
        $vender->type   = $request->type;
        $vender->person_name   = $request->person_name;
        $vender->person_mbile   = $request->person_mbile;
        $vender->person_fax   = $request->person_fax;
        $vender->person_email   = $request->person_email;
        $vender->save();
        CustomField::saveData($vender, $request->customField);
      }
      else
      {
        return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
      }


      $role_r = Role::where('name', '=', 'vender')->firstOrFail();
      $vender->assignRole($role_r); //Assigning role to user

      $vender->password = $request->password;
      $vender->type     = 'Vender';
      try
      {

        Mail::to($vender->email)->send(new UserCreate($vender));
      }
      catch(\Exception $e)
      {
        $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
      }

      return redirect()->route('vender.index')->with('success', __('Vendor successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function show($id)
  {
    $vender = Vender::find($id);
    $line_of_business = LineofBusiness::find($vender->line_of_business);
    $coa = ChartofAccounts::find($vender->coa_id);

    $organisation = OrganisationType::find($vender->organization_type);
    $atc_list = AtcList::find($vender->atc_list_id);

    return view('vender.show', compact('vender','line_of_business','coa','organisation','atc_list'));
  }


  public function edit($id)
  {
    if(\Auth::user()->can('edit vender'))
    {
      $vender              = Vender::find($id);
      $vender->customField = CustomField::getData($vender, 'vendor');
      $line_of_business = LineofBusiness::all();
      $atc_lists = AtcList::all();
      $customFields = CustomField::where('module', '=', 'vendor')->get();

      $user = \Auth::id();
      $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));
      $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts_old on frequently_used_coa.chart_of_accounts_id = chart_of_accounts_old.id  WHERE  frequently_used_coa.user_id = ".$user ));
      $organisations = OrganisationType::all();

      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'vendor')->get();

      return view('vender.edit', compact('vender', 'customFields','line_of_business','atc_lists','fui_atc_lists','fui_caos','organisations'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function update(Request $request, Vender $vender)
  {
    if(\Auth::user()->can('edit vender'))
    {

      $rules = [
        'name' => 'required',
        'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
      ];


      $validator = \Validator::make($request->all(), $rules);

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->route('vender.index')->with('error', $messages->first());
      }

      if($request->email){
        $prev_email = Vender::where([['email',$request->email],['id','!=',$vender->id]])->first();

        if($prev_email)
          return redirect()->route('vender.index')->with('error', 'Email already exists.');
      }

      $vender->name             = $request->name;
      $vender->middle_name      = ($request->middle_name) ? $request->middle_name : '';
      $vender->last_name        = $request->last_name;
      $vender->email            = ($request->email) ? $request->email : $vender->email;
      $vender->branch_name      = $request->branch_name;
      $vender->created_by       = \Auth::user()->creatorId();
      $vender->billing_country  = $request->billing_country;
      $vender->billing_city     = $request->billing_city;
      $vender->billing_zip      = $request->billing_zip;
      $vender->billing_address  = $request->billing_address;
      $vender->vendor_tin    = $request->vendor_tin;
      $vender->line_of_business = ($request->line_of_business) ? $request->line_of_business : '';
      $vender->organization_name = $request->organization_name;
      $vender->organization_type   = ($request->organization_type) ? $request->organization_type :'';
      $vender->atc_list_id   = $request->atc_id;
      $vender->tax_type   = $request->tax_type;

      $vender->customer_since   = $request->customer_since;
      $vender->coa_id   = $request->coa_id;
      $vender->type   = $request->type;
      $vender->person_name   = $request->person_name;
      $vender->person_mbile   = $request->person_mbile;
      $vender->person_fax   = $request->person_fax;
      $vender->person_email   = $request->person_email;
      $vender->save();
      CustomField::saveData($vender, $request->customField);

      return redirect()->route('vender.index')->with('success', __('Vender successfully updated.'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function destroy(Vender $vender)
  {
    if(\Auth::user()->can('delete vender'))
    {
      if($vender->created_by == \Auth::user()->creatorId())
      {
        $vender->delete();

        return redirect()->route('vender.index')->with('success', __('Vendor successfully deleted.'));
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

  function venderNumber()
  {
    $latest = Vender::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
    if(!$latest)
    {
      return 1;
    }

    return $latest->vender_id + 1;
  }

  public function venderLogout(Request $request)
  {
    \Auth::guard('vender')->logout();

    $request->session()->invalidate();

    return redirect()->route('vender.login');
  }

  public function payment(Request $request)
  {

    if(\Auth::user()->can('manage vender payment'))
    {
      $category = [
        'Bill' => 'Bill',
        'Deposit' => 'Deposit',
        'Sales' => 'Sales',
      ];

      $query = Transaction::where('user_id', \Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->where('user_type', 'Vender')->where('type', 'Payment');
      if(!empty($request->date))
      {
        $date_range = explode(' - ', $request->date);
        $query->whereBetween('date', $date_range);
      }

      if(!empty($request->category))
      {
        $query->where('category', '=', $request->category);
      }
      $payments = $query->get();


      return view('vender.payment', compact('payments', 'category'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  public function transaction(Request $request)
  {

    if(\Auth::user()->can('manage vender transaction'))
    {


      $category = [
        'Bill' => 'Bill',
        'Deposit' => 'Deposit',
        'Sales' => 'Sales',
      ];

      $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Vender');

      if(!empty($request->date))
      {
        $date_range = explode(' - ', $request->date);
        $query->whereBetween('date', $date_range);
      }

      if(!empty($request->category))
      {
        $query->where('category', '=', $request->category);
      }
      $transactions = $query->get();


      return view('vender.transaction', compact('transactions', 'category'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  public function profile()
  {
    $userDetail              = \Auth::user();
    $userDetail->customField = CustomField::getData($userDetail, 'vendor');
    $customFields            = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'vendor')->get();

    return view('vender.profile', compact('userDetail', 'customFields'));
  }

  public function editprofile(Request $request)
  {

    $userDetail = \Auth::user();
    $user       = Vender::findOrFail($userDetail['id']);
    $this->validate(
      $request, [
        'name' => 'required|max:120',
        'contact' => 'required',
        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
      ]
    );
    if($request->hasFile('profile'))
    {
      $filenameWithExt = $request->file('profile')->getClientOriginalName();
      $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
      $extension       = $request->file('profile')->getClientOriginalExtension();
      $fileNameToStore = $filename . '_' . time() . '.' . $extension;

      $dir        = storage_path('uploads/avatar/');
      $image_path = $dir . $userDetail['avatar'];

      if(File::exists($image_path))
      {
        File::delete($image_path);
      }

      if(!file_exists($dir))
      {
        mkdir($dir, 0777, true);
      }

      $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);

    }

    if(!empty($request->profile))
    {
      $user['avatar'] = $fileNameToStore;
    }
    $user['name']    = $request['name'];
    $user['email']   = $request['email'];
    $user['contact'] = $request['contact'];
    $user->save();
    CustomField::saveData($user, $request->customField);

    return redirect()->back()->with(
      'success', 'Profile successfully updated.'
    );
  }

  public function editBilling(Request $request)
  {

    $userDetail = \Auth::user();
    $user       = Vender::findOrFail($userDetail['id']);
    $this->validate(
      $request, [
        'billing_name' => 'required',
        'billing_country' => 'required',
        'billing_state' => 'required',
        'billing_city' => 'required',
        'billing_phone' => 'required',
        'billing_zip' => 'required',
        'billing_address' => 'required',
      ]
    );
    $input = $request->all();
    $user->fill($input)->save();

    return redirect()->back()->with(
      'success', 'Profile successfully updated.'
    );
  }

  public function editShipping(Request $request)
  {
    $userDetail = \Auth::user();
    $user       = Vender::findOrFail($userDetail['id']);
    $this->validate(
      $request, [
        'shipping_name' => 'required',
        'shipping_country' => 'required',
        'shipping_state' => 'required',
        'shipping_city' => 'required',
        'shipping_phone' => 'required',
        'shipping_zip' => 'required',
        'shipping_address' => 'required',
      ]
    );
    $input = $request->all();
    $user->fill($input)->save();

    return redirect()->back()->with(
      'success', 'Profile successfully updated.'
    );
  }

  public function updatePassword(Request $request)
  {

    if(Auth::Check())
    {
      $request->validate(
        [
          'current_password' => 'required',
          'new_password' => 'required|min:6',
          'confirm_password' => 'required|same:new_password',
        ]
      );
      $objUser          = Auth::user();
      $request_data     = $request->All();
      $current_password = $objUser->password;
      if(Hash::check($request_data['current_password'], $current_password))
      {
        $user_id            = Auth::User()->id;
        $obj_user           = Vender::find($user_id);
        $obj_user->password = Hash::make($request_data['new_password']);;
        $obj_user->save();

        return redirect()->back()->with('success', __('Password successfully updated.'));
      }
      else
      {
        return redirect()->back()->with('error', __('Please enter correct current password.'));
      }
    }
    else
    {
      return redirect()->back()->with('error', __('Something is wrong.'));
    }

  }

  public function changeLanquage($lang)
  {


    $user       = Auth::user();
    $user->lang = $lang;
    $user->save();

    return redirect()->back()->with('success', __('Language successfully change.'));
  }
  public function import()
  {
    if(\Auth::user()->can('manage vender'))
    {
      $venders = Vender::where('created_by', \Auth::user()->creatorId())->get();

      return view('vender.import', compact('venders'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
  public function import_vendors_file(Request $request)
  {
    $this->validate($request,[
      'select_file' => 'required',
    ]);

    $data = Excel::toArray(new Vender,request()->file('select_file'));

    for($i=0; $i < count($data[0][0]); $i++)
    {
      $response[] = $data[0][0][$i];
    }
    return $response;
  }

  public function import_vendors(Request $request)
  {
    $this->validate($request,[
      'select_file' => 'required',
      'select_tin' => 'required',
      'select_organization_type' => 'required',
      'select_organization_name' => 'required',
      'select_name' => 'required',
      'select_middle_name' => 'required',
      'select_last_name' => 'required',
      'select_address' => 'required',
      'select_city' => 'required',
      'select_country' => 'required',
      'select_postal' => 'required',
      'select_phone' => 'required',
      'select_email' => 'required',
    ]);

    // $extension = array($request->file('select_file')->getClientOriginalExtension());
    // if($extension[0] != 'xlsx' && $extension[0] != 'xls' && $extension[0] != 'csv')
    //   return redirect()->back()->with('error', 'File type not supported.');

    $data = Excel::toArray(new Vender,request()->file('select_file'));

    //   return redirect()->back()->with('error', 'File formate not supported.');

    for($i = 1; $i < count($data[0]); $i++)
    {
      $prev_vendor = Vender::where('email',$data[0][$i][$request->select_email])->first();
      if(!$prev_vendor){
        $vendor = new Vender;
        $vendor->vender_id  = $this->venderNumber();
        $vendor->vendor_tin = $data[0][$i][$request->select_tin];
        $vendor->organization_type = $data[0][$i][$request->select_organization_type];
        $vendor->organization_name = $data[0][$i][$request->select_organization_name];
        $vendor->name = $data[0][$i][$request->select_name];
        $vendor->middle_name = $data[0][$i][$request->select_middle_name];
        $vendor->last_name = $data[0][$i][$request->select_last_name];
        $vendor->billing_address = $data[0][$i][$request->select_address];
        $vendor->billing_city = $data[0][$i][$request->select_city];
        $vendor->billing_country = $data[0][$i][$request->select_country];
        $vendor->billing_zip = $data[0][$i][$request->select_postal];
        $vendor->contact = $data[0][$i][$request->select_phone];
        $vendor->email = $data[0][$i][$request->select_email];
        $vendor->password = Hash::make('1234');
        $vendor->created_by = \Auth::user()->creatorId();
        $vendor->save();
      }
      
    }

    return redirect()->back()->with('success', 'Vendors imported successfully.');

    $data = array(
      'message' => 'success',
    );
    return $data;
     
  }

  public function exports_vendors()
  {
    return Excel::download(new VendorExport, 'vendors.csv');
  }
}
