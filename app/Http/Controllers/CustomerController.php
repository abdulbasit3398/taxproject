<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Excel;
use App\User;
use App\Exports\CustomerExport;
use App\Customer;
use App\CustomField;
use App\Mail\UserCreate;
use App\Plan;
use App\AtcList;
use App\Transaction;
use App\LineofBusiness;
use App\OrganisationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{

  public function dashboard()
  {
    $data['invoiceChartData'] = \Auth::user()->invoiceChartData();

    return view('customer.dashboard', $data);
  }

  public function index()
  {
    if(\Auth::user()->can('manage customer'))
    {
      $customers = Customer::where('created_by', \Auth::user()->creatorId())->get();

      return view('customer.index', compact('customers'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  public function create()
  {
    if(\Auth::user()->can('create customer'))
    {
      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'customer')->get();
      $user = \Auth::id();
      $atc_lists = AtcList::all();
      $line_of_business = LineofBusiness::all();
      $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts on frequently_used_coa.chart_of_accounts_id = chart_of_accounts.id  WHERE  frequently_used_coa.user_id = ".$user ));
      $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));
      $organisations = OrganisationType::all();
      return view('customer.create', compact('customFields','line_of_business','atc_lists','fui_atc_lists','fui_caos','organisations'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function store(Request $request)
  {
    if(\Auth::user()->can('create customer'))
    {

      $rules = [
        'name' => 'required',
        'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        'email' => 'required|email|unique:customers',
        'password' => 'required',

      ];



      $validator = \Validator::make($request->all(), $rules);

      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->route('customer.index')->with('error', $messages->first());
      }

      $objCustomer    = \Auth::user();
      $creator        = User::find($objCustomer->creatorId());
      $total_customer = $objCustomer->countCustomers();
      $plan           = Plan::find($creator->plan);

      $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
      if($total_customer < $plan->max_customers || $plan->max_customers == -1)
      {
        $customer                   = new Customer();
        $customer->customer_id      = $this->customerNumber();
        $customer->name             = $request->name;
        $customer->middle_name      = ($request->middle_name) ? $request->middle_name : '';
        $customer->last_name        = $request->last_name;
        $customer->contact          = $request->contact;
        $customer->email            = ($request->email) ? $request->email : '';
        $customer->password         = ($request->password) ? Hash::make($request->password) : '';
        $customer->created_by       = \Auth::user()->creatorId();
        $customer->billing_country  = $request->billing_country;
        $customer->billing_city     = $request->billing_city;
        $customer->billing_zip      = $request->billing_zip;
        $customer->billing_address  = $request->billing_address;
        $customer->customer_tin    = $request->customer_tin;
        $customer->organization_name = ($request->organization_name) ? $request->organization_name : '' ;
        $customer->organization_type   = ($request->organization_type) ? $request->organization_type : '' ;

        $customer->line_of_business  = $request->line_of_business;
        $customer->branch_name  = $request->branch_name;
        $customer->atc_list_id  = $request->atc_list_id;
        $customer->tax_type     = $request->tax_type;
        $customer->customer_since  = $request->customer_since;
        $customer->coa_id         = $request->coa_id;
        $customer->type           = json_encode($request->type);
        $customer->person_name    = $request->person_name;
        $customer->person_mbile   = $request->person_mbile;
        $customer->person_fax     = $request->person_fax;
        $customer->person_email   = $request->person_email;
        $customer->save();
        CustomField::saveData($customer, $request->customField);
      }
      else
      {
        return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
      }


      $role_r = Role::where('name', '=', 'customer')->firstOrFail();
      $customer->assignRole($role_r);

      $customer->password = $request->password;
      $customer->type     = 'Customer';
      try
      {
        Mail::to($customer->email)->send(new UserCreate($customer));
      }
      catch(\Exception $e)
      {
        $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
      }

      return redirect()->route('customer.index')->with('success', __('Customer successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function show(Customer $customer)
  {
    $organisation = OrganisationType::find($customer->organization_type);
    $atc_list = AtcList::find($customer->atc_list_id);
    $line_of_business = LineofBusiness::find($customer->line_of_business);
    return view('customer.show', compact('customer','organisation','atc_list','line_of_business'));
  }


  public function edit($id)
  {
    
    if(\Auth::user()->can('edit customer'))
    {
      $user = \Auth::id();
      $customer              = Customer::find($id);
      $customer->customField = CustomField::getData($customer, 'customer');
      $organisations = OrganisationType::all();
      $atc_lists = AtcList::all();
      $line_of_business = LineofBusiness::all();
      $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts on frequently_used_coa.chart_of_accounts_id = chart_of_accounts.id  WHERE  frequently_used_coa.user_id = ".$user ));
      $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));
      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'customer')->get();
      return view('customer.edit', compact('customer', 'customFields','line_of_business','atc_lists','fui_atc_lists','fui_caos','organisations'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function update(Request $request, Customer $customer)
  {

    if(\Auth::user()->can('edit customer'))
    {

      $rules = [
        'name' => 'required',
        'last_name' => 'required',
        'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
        'billing_country' => 'required',
        'billing_city' => 'required',
        'billing_zip' => 'required',
        'billing_address' => 'required',
        'customer_tin' => 'required',
        // 'organization_name' => 'required',
        // 'organization_type' => 'required',
      ];


      $validator = \Validator::make($request->all(), $rules);
      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->route('customer.index')->with('error', $messages->first());
      }

      $customer->name             = $request->name;
      $customer->middle_name      = ($request->middle_name) ? $request->middle_name : '';
      $customer->last_name        = $request->last_name;
      $customer->email            = ($request->email) ? $request->email : $customer->email;
      $customer->contact          = $request->contact;
      $customer->created_by       = \Auth::user()->creatorId();
      $customer->billing_country  = $request->billing_country;
      $customer->billing_city     = $request->billing_city;
      $customer->billing_zip      = $request->billing_zip;
      $customer->billing_address  = $request->billing_address;
      $customer->customer_tin    = $request->customer_tin;
      $customer->organization_name = ($request->organization_name) ? $request->organization_name : '';
      $customer->organization_type   = ($request->organization_type) ? $request->organization_type : '';

      $customer->line_of_business  = $request->line_of_business;
      $customer->branch_name  = $request->branch_name;
      $customer->atc_list_id  = $request->atc_list_id;
      $customer->tax_type     = $request->tax_type;
      $customer->customer_since  = $request->customer_since;
      $customer->coa_id         = $request->coa_id;
      $customer->type           = json_encode($request->type);
      $customer->person_name    = $request->person_name;
      $customer->person_mbile   = $request->person_mbile;
      $customer->person_fax     = $request->person_fax;
      $customer->person_email   = $request->person_email;
      $customer->save();

      CustomField::saveData($customer, $request->customField);

      return redirect()->route('customer.index')->with('success', __('Customer successfully updated.'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }


  public function destroy(Customer $customer)
  {
    if(\Auth::user()->can('delete customer'))
    {
      if($customer->created_by == \Auth::user()->creatorId())
      {
        $customer->delete();

        return redirect()->route('customer.index')->with('success', __('Customer successfully deleted.'));
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

  function customerNumber()
  {
    $latest = Customer::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
    if(!$latest)
    {
      return 1;
    }

    return $latest->customer_id + 1;
  }

  public function customerLogout(Request $request)
  {
    \Auth::guard('customer')->logout();

    $request->session()->invalidate();

    return redirect()->route('customer.login');
  }

  public function payment(Request $request)
  {

    if(\Auth::user()->can('manage customer payment'))
    {
      $category = [
        'Invoice' => 'Invoice',
        'Deposit' => 'Deposit',
        'Sales' => 'Sales',
      ];

      $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Customer')->where('type', 'Payment');
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

      return view('customer.payment', compact('payments', 'category'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  public function transaction(Request $request)
  {
    if(\Auth::user()->can('manage customer payment'))
    {
      $category = [
        'Invoice' => 'Invoice',
        'Deposit' => 'Deposit',
        'Sales' => 'Sales',
      ];

      $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Customer');

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

      return view('customer.transaction', compact('transactions', 'category'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  public function profile()
  {
    $userDetail              = \Auth::user();
    $userDetail->customField = CustomField::getData($userDetail, 'customer');
    $customFields            = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'customer')->get();

    return view('customer.profile', compact('userDetail', 'customFields'));
  }

  public function editprofile(Request $request)
  {
    $userDetail = \Auth::user();
    $user       = Customer::findOrFail($userDetail['id']);

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
    $user       = Customer::findOrFail($userDetail['id']);
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
    $user       = Customer::findOrFail($userDetail['id']);
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
        $obj_user           = Customer::find($user_id);
        $obj_user->password = Hash::make($request_data['new_password']);;
        $obj_user->save();

        return redirect()->back()->with('success', __('Password updated successfully.'));
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

    return redirect()->back()->with('success', __('Language Change Successfully!'));


  }
  public function import()
  {
    if(\Auth::user()->can('create customer'))
    {
      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'customer')->get();

      return view('customer.import', compact('customFields'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }
  public function import_customers_file(Request $request)
  {
    $this->validate($request,[
      'select_file' => 'required',
    ]);

    $data = Excel::toArray(new Customer,request()->file('select_file'));

    for($i=0; $i < count($data[0][0]); $i++)
    {
      $response[] = $data[0][0][$i];
    }
    return $response;
  }

  public function import_customers(Request $request)
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

    $data = Excel::toArray(new Customer,request()->file('select_file'));

    //   return redirect()->back()->with('error', 'File formate not supported.');

    for($i = 1; $i < count($data[0]); $i++)
    {
      $prev_customer = Customer::where('email',$data[0][$i][$request->select_email])->first();
      if(!$prev_customer){
        $customer = new Customer;
        $customer->customer_id  = $this->customerNumber();
        $customer->customer_tin = $data[0][$i][$request->select_tin];
        $customer->organization_type = $data[0][$i][$request->select_organization_type];
        $customer->organization_name = $data[0][$i][$request->select_organization_name];
        $customer->name = $data[0][$i][$request->select_name];
        $customer->middle_name = $data[0][$i][$request->select_middle_name];
        $customer->last_name = $data[0][$i][$request->select_last_name];
        $customer->billing_address = $data[0][$i][$request->select_address];
        $customer->billing_city = $data[0][$i][$request->select_city];
        $customer->billing_country = $data[0][$i][$request->select_country];
        $customer->billing_zip = $data[0][$i][$request->select_postal];
        $customer->contact = $data[0][$i][$request->select_phone];
        $customer->email = $data[0][$i][$request->select_email];
        $customer->password = Hash::make('1234');
        $customer->created_by = \Auth::user()->creatorId();
        $customer->save();
      }
      
    }

    return redirect()->back()->with('success', 'Customers imported successfully.');

    $data = array(
      'message' => 'success',
    );
    return $data;
     
  }

  public function exports_customers()
  {
    
    return Excel::download(new CustomerExport, 'customers.csv');
  }

}
