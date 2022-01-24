@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
{{__('Manage Client-Detail')}}
@endsection

@section('action-button')
<div class="row d-flex justify-content-end">
  @can('create invoice')
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
    <div class="all-button-box">
      <a href="{{ route('invoice.create',$client->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
        {{__('Create Invoice')}}
      </a>
    </div>
  </div>
  @endcan
  @can('create proposal')
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
    <div class="all-button-box">
      <a href="{{ route('proposal.create',$client->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
        {{__('Create Proposal')}}
      </a>
    </div>
  </div>
  @endcan
  @can('manage client')
  <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
    <div class="all-button-box">
      <a href="#" data-size="2xl" data-url="{{ route('client.edit',$client['id']) }}" data-ajax-popup="true" data-title="{{__('Edit Customer')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
        <i class="fa fa-pencil-alt"></i>
      </a>
    </div>
  </div>
  @endcan
  @can('manage client')
  <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
    <div class="all-button-box">
      <a href="#" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $client['id']}}').submit();" class="btn btn-xs btn-white bg-danger btn-icon-only width-auto">
        <i class="fa fa-trash"></i>
      </a>
      {!! Form::open(['method' => 'DELETE', 'route' => ['client.destroy', $client['id']],'id'=>'delete-form-'.$client['id']]) !!}
      {!! Form::close() !!}
    </div>
  </div>
  @endcan
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-4 col-lg-4 col-xl-4">
    <div class="card pb-0 customer-detail-box">
      <h3 class="small-title">{{__('Client Info')}}</h3>
      <div class="p-4">
        <h5 class="report-text gray-text mb-0">{{$client['last_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['middle_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['email']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['contact']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['customer_tin']}}</h5>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-lg-4 col-xl-4">
    <div class="card pb-0 customer-detail-box">
      <h3 class="small-title">{{__('Address')}}</h3>
      <div class="p-4">
        <h5 class="report-text gray-text mb-0">{{$client['billing_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['billing_phone']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['billing_address']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['billing_zip']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['billing_city'].', '.$client['billing_country']}}</h5>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-lg-4 col-xl-4">
    <div class="card pb-0 customer-detail-box">
      <h3 class="small-title">{{__('Contact Person Details')}}</h3>
      <div class="p-4">
        <h5 class="report-text gray-text mb-0">{{$client['person_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['person_mbile']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['person_fax']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$client['person_email']}}</h5>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card pb-0">
      <h3 class="small-title">{{__('Company Info')}}</h3>
      <div class="row">
        @php
        $totalInvoiceSum=$client->customerTotalInvoiceSum($client['id']);
        $totalInvoice=$client->customerTotalInvoice($client['id']);
        $averageSale=($totalInvoiceSum!=0)?$totalInvoiceSum/$totalInvoice:0;
        @endphp
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Customer Id')}}</h5>
            <h5 class="report-text mb-3">{{Auth::user()->customerNumberFormat($client['customer_id'])}}</h5>
            
            <h5 class="report-text gray-text mb-0">{{__('Branch')}}</h5>
            <h5 class="report-text mb-3">{{$client['branch_name']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Organization Name')}}</h5>
            <h5 class="report-text mb-3">{{$client['organization_name']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Organization Type')}}</h5>
            <h5 class="report-text mb-3">{{$organisation['name']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('ATC')}}</h5>
            <h5 class="report-text mb-3">{{$atc_list['atc']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Tax Type')}}</h5>
            <h5 class="report-text mb-3">{{$client['tax_type']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Line of Business')}}</h5>
            <h5 class="report-text mb-3">{{$line_of_business['business_title']}}</h5>

          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Date of Creation')}}</h5>
            <h5 class="report-text mb-3">{{\Auth::user()->dateFormat($client['created_at'])}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Quantity of Invoice')}}</h5>
            <h5 class="report-text mb-0">{{$totalInvoice}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Total Sum of Invoices')}}</h5>
            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($totalInvoiceSum)}}</h5>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Balance')}}</h5>
            <h5 class="report-text mb-3">{{\Auth::user()->priceFormat($client['balance'])}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Average Sales')}}</h5>
            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($averageSale)}}</h5>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Overdue')}}</h5>
            <h5 class="report-text mb-3">{{\Auth::user()->priceFormat($client->customerOverdue($client['id']))}}</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Proposal')}}</h5>
    <div class="card">
      <div class="card-body py-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 dataTable">
            <thead>
              <tr>
                <th>{{__('Proposal')}}</th>
                <th>{{__('Issue Date')}}</th>
                <th>{{__('Amount')}}</th>
                <th>{{__('Status')}}</th>
                @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                <th> {{__('Action')}}</th>
                @endif
              </tr>
            </thead>
             
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Invoice')}}</h5>
    <div class="card">
      <div class="card-body py-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 dataTable">
            <thead>
              <tr>
                <th>{{__('Invoice')}}</th>
                <th>{{__('Issue Date')}}</th>
                <th>{{__('Due Date')}}</th>
                <th>{{__('Due Amount')}}</th>
                <th>{{__('Status')}}</th>
                @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                <th> {{__('Action')}}</th>
                @endif
              </tr>
            </thead>
             
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
