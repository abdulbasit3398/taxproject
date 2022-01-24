<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Excel;
use App\User;
use App\Exports\CustomerExport;
use App\Client;
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

class ClientController extends Controller
{
  
  public function index()
  {
    if(\Auth::user()->can('manage client'))
    {
      $clients = Client::where('created_by', \Auth::user()->creatorId())->get();

      return view('client.index', compact('clients'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  
  public function create()
  {
    if(\Auth::user()->can('manage client'))
    {
      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'customer')->get();
      $user = \Auth::id();
      $atc_lists = AtcList::all();
      $line_of_business = LineofBusiness::all();
      $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts on frequently_used_coa.chart_of_accounts_id = chart_of_accounts.id  WHERE  frequently_used_coa.user_id = ".$user ));
      $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));
      $organisations = OrganisationType::all();
      return view('client.create', compact('customFields','line_of_business','atc_lists','fui_atc_lists','fui_caos','organisations'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  
  public function store(Request $request)
  {
    if(\Auth::user()->can('manage client'))
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

        return redirect()->route('client.index')->with('error', $messages->first());
      }

      $objCustomer    = \Auth::user();
      $creator        = User::find($objCustomer->creatorId());
      $total_customer = $objCustomer->countClients();
      $plan           = Plan::find($creator->plan);
      $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
      if($total_customer < $plan->max_customers || $plan->max_customers == -1)
      {
        $customer                   = new Client();
        $customer->customer_id      = $this->clientNumber();
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


      $role_r = Role::where('name', '=', 'client')->firstOrFail();
      // $customer->assignRole($role_r);

      $customer->password = $request->password;
      $customer->type     = 'Client';
      try
      {
        Mail::to($customer->email)->send(new UserCreate($customer));
      }
      catch(\Exception $e)
      {
        $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
      }

      return redirect()->route('client.index')->with('success', __('Client successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  
  public function show(Client $client)
  {
    $organisation = OrganisationType::find($client->organization_type);
    $atc_list = AtcList::find($client->atc_list_id);
    $line_of_business = LineofBusiness::find($client->line_of_business);
    return view('client.show', compact('client','organisation','atc_list','line_of_business'));
  }

  
  public function edit($id)
  {
    if(\Auth::user()->can('manage client'))
    {
      $user = \Auth::id();
      $customer              = Client::find($id);
      $customer->customField = CustomField::getData($customer, 'customer');
      $organisations = OrganisationType::all();
      $atc_lists = AtcList::all();
      $line_of_business = LineofBusiness::all();
      $fui_caos = DB::select(DB::raw("SELECT * FROM `frequently_used_coa`  RIGHT JOIN chart_of_accounts on frequently_used_coa.chart_of_accounts_id = chart_of_accounts.id  WHERE  frequently_used_coa.user_id = ".$user ));
      $fui_atc_lists = DB::select(DB::raw("SELECT * FROM `frequently_used_atc`  RIGHT JOIN atc_list on frequently_used_atc.atc_list_id = atc_list.id  WHERE  frequently_used_atc.user_id = ".$user));
      $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'customer')->get();
      return view('client.edit', compact('customer', 'customFields','line_of_business','atc_lists','fui_atc_lists','fui_caos','organisations'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  
  public function update(Request $request, $id)
  {
    if(\Auth::user()->can('manage client'))
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

      $customer = Client::where([['id',$id],['created_by',\Auth::user()->creatorId()]])->firstOrFail();
      $validator = \Validator::make($request->all(), $rules);
      if($validator->fails())
      {
        $messages = $validator->getMessageBag();

        return redirect()->route('client.index')->with('error', $messages->first());
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

      return redirect()->route('client.index')->with('success', __('Client successfully updated.'));
    }
    else
    {
      return redirect()->back()->with('error', __('Permission denied.'));
    }
  }

  
  public function destroy($id)
  {
    $customer = Client::findOrFail($id);
    if(\Auth::user()->can('manage client'))
    {
      if($customer->created_by == \Auth::user()->creatorId())
      {
        $customer->delete();

        return redirect()->route('client.index')->with('success', __('Client successfully deleted.'));
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

  function clientNumber()
  {
    $latest = Client::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
    if(!$latest)
    {
      return 1;
    }

    return $latest->customer_id + 1;
  }

}
