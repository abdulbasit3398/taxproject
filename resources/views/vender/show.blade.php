@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
{{__('Manage Vendor-Detail')}}
@endsection

@section('action-button')
<div class="row d-flex justify-content-end">
  @can('create bill')
  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
    <div class="all-button-box">
      <a href="{{ route('bill.create',$vender->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
        {{__('Create Bill')}}
      </a>
    </div>
  </div>
  @endcan
  @can('edit vender')
  <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
    <div class="all-button-box">
      <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-size="2xl" data-url="{{ route('vender.edit',$vender['id']) }}" data-ajax-popup="true" data-title="{{__('Edit Vendor')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
        <i class="fas fa-pencil-alt"></i>
      </a>
    </div>
  </div>
  @endcan
  @can('delete vender')
  <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
    <div class="all-button-box">
      <a href="#" class="btn btn-xs btn-white bg-danger btn-icon-only width-auto" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $vender['id']}}').submit();">
        <i class="fas fa-trash"></i>
      </a>
    </div>
  </div>
  {!! Form::open(['method' => 'DELETE', 'route' => ['vender.destroy', $vender['id']],'id'=>'delete-form-'.$vender['id']]) !!}
  {!! Form::close() !!}
  @endcan
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-4 col-lg-4 col-xl-4">
    <div class="card pb-0 customer-detail-box">
      <h3 class="small-title">{{__('Vendor Info')}}</h3>
      <div class="p-4">
        <h5 class="report-text gray-text mb-0">{{$vender['last_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['middle_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['email']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['contact']}}</h5>
        
      </div>
    </div>
  </div>
  <div class="col-md-4 col-lg-4 col-xl-4">
    <div class="card pb-0 customer-detail-box">
      <h3 class="small-title">{{__('Address')}}</h3>
      <div class="p-4">
        <h5 class="report-text gray-text mb-0">{{$vender['billing_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['billing_phone']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['billing_address']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['billing_zip']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['billing_city'].', '.$vender['billing_country']}}</h5>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-lg-4 col-xl-4">
    <div class="card pb-0 customer-detail-box">
      <h3 class="small-title">{{__('Contact Person Details')}}</h3>
      <div class="p-4">
        <h5 class="report-text gray-text mb-0">{{$vender['person_name']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['person_mbile']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['person_fax']}}</h5>
        <h5 class="report-text gray-text mb-0">{{$vender['person_email']}}</h5>
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
        $totalBillSum=$vender->vendorTotalBillSum($vender['id']);
        $totalBill=$vender->vendorTotalBill($vender['id']);
        $averageSale=($totalBillSum!=0)?$totalBillSum/$totalBill:0;
        @endphp
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Vendor Id')}}</h5>
            <h5 class="report-text mb-3">{{\Auth::user()->venderNumberFormat($vender->vender_id)}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Branch')}}</h5>
            <h5 class="report-text mb-3">{{$vender['branch_name']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Organization Name')}}</h5>
            <h5 class="report-text mb-3">{{$vender['organization_name']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Organization Type')}}</h5>
            <h5 class="report-text mb-3">{{$organisation['name']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('ATC')}}</h5>
            <h5 class="report-text mb-3">{{$atc_list['atc']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Tax Type')}}</h5>
            <h5 class="report-text mb-3">{{$vender['tax_type']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Line of Business')}}</h5>
            <h5 class="report-text mb-3">{{$line_of_business['business_title']}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('TIN')}}</h5>
            <h5 class="report-text gray-text mb-0">{{$vender['vendor_tin']}}</h5>
            
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Date of Creation')}}</h5>
            <h5 class="report-text mb-3">{{\Auth::user()->dateFormat($vender->created_at)}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Quantity of Bills')}}</h5>
            <h5 class="report-text mb-0">{{$totalBill}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Total Sum of Bills')}}</h5>
            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($totalBillSum)}}</h5>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Balance')}}</h5>
            <h5 class="report-text mb-3">{{\Auth::user()->priceFormat($vender->balance)}}</h5>
            <h5 class="report-text gray-text mb-0">{{__('Average Sales')}}</h5>
            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($averageSale)}}</h5>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="p-4">
            <h5 class="report-text gray-text mb-0">{{__('Overdue')}}</h5>
            <h5 class="report-text mb-3">{{\Auth::user()->priceFormat($vender->vendorOverdue($vender->id))}}</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Bills')}}</h5>
    <div class="card">
      <div class="card-body py-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 dataTable">
            <thead>
              <tr>
                <th>{{__('Bill')}}</th>
                <th>{{__('Bill Date')}}</th>
                <th>{{__('Due Date')}}</th>
                <th>{{__('Due Amount')}}</th>
                <th>{{__('Status')}}</th>
                @if(Gate::check('edit bill') || Gate::check('delete bill') || Gate::check('show bill'))
                <th> {{__('Action')}}</th>
                @endif
              </tr>
            </thead>

            <tbody>
              @foreach ($vender->vendorBill($vender->id) as $bill)
              <tr class="font-style">
                <td class="Id">
                  @if(\Auth::guard('vender')->check())
                  <a href="{{ route('vender.bill.show',$bill->id) }}">{{ AUth::user()->billNumberFormat($bill->bill_id) }}
                  </a>
                  @else
                  <a href="{{ route('bill.show',$bill->id) }}">{{ AUth::user()->billNumberFormat($bill->bill_id) }}
                  </a>
                  @endif
                </td>
                <td>{{ Auth::user()->dateFormat($bill->bill_date) }}</td>
                <td>
                  @if(($bill->due_date < date('Y-m-d')))
                  <p class="text-danger"> {{ \Auth::user()->dateFormat($bill->due_date) }}</p>
                  @else
                  {{ \Auth::user()->dateFormat($bill->due_date) }}
                  @endif
                </td>
                <td>{{\Auth::user()->priceFormat($bill->getDue())  }}</td>
                <td>
                  @if($bill->status == 0)
                  <span class="badge badge-pill badge-primary">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                  @elseif($bill->status == 1)
                  <span class="badge badge-pill badge-warning">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                  @elseif($bill->status == 2)
                  <span class="badge badge-pill badge-danger">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                  @elseif($bill->status == 3)
                  <span class="badge badge-pill badge-info">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                  @elseif($bill->status == 4)
                  <span class="badge badge-pill badge-success">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                  @endif
                </td>
                @if(Gate::check('edit bill') || Gate::check('delete bill') || Gate::check('show bill'))
                <td class="Action">
                  <span>
                    @can('duplicate bill')
                    <a href="#" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Duplicate')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$bill->id}}').submit();">
                      <i class="fas fa-copy"></i>
                      {!! Form::open(['method' => 'get', 'route' => ['bill.duplicate', $bill->id],'id'=>'duplicate-form-'.$bill->id]) !!}
                      {!! Form::close() !!}
                    </a>
                    @endcan
                    @can('show bill')
                    @if(\Auth::guard('vender')->check())
                    <a href="{{ route('vender.bill.show',$bill->id) }}" class="edit-icon bg-warning" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                      <i class="fas fa-eye"></i>
                    </a>
                    @else
                    <a href="{{ route('bill.show',$bill->id) }}" class="edit-icon bg-warning" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                      <i class="fas fa-eye"></i>
                    </a>
                    @endif
                    @endcan
                    @can('edit bill')
                    <a href="{{ route('bill.edit',$bill->id) }}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    @endcan
                    @can('delete bill')
                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$bill->id}}').submit();">
                      <i class="fas fa-trash"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['bill.destroy', $bill->id],'id'=>'delete-form-'.$bill->id]) !!}
                    {!! Form::close() !!}
                    @endcan
                  </span>
                </td>
                @endif
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
