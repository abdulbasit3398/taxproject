@extends('layouts.admin')
@section('page-title')
{{__('Manage Invoices')}}
@endsection

@section('action-button')

<div class="row d-flex justify-content-end">
  <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
    <div class="all-button-box">
      <a href="#" data-size="2xl" data-url="{{ route('import_sale_transections') }}" class="btn btn-xs btn-white btn-icon-only width-auto commonModal" data-ajax-popup="true" data-title="{{__('Import Sales Transections')}}">
        <span class="btn-inner--icon"><i class="fas fa-file"></i></span>
        <span class="btn-inner--text"> {{__('Import')}}</span>
      </a>
    </div>
  </div>
</div>
 <br/>
 <br/>
@endsection


@section('content')
<div class="modal fade" id="importCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-2xl" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h4 class="modal-title" id="modelCommanModelLabel">Import Customer</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body py-0 mt-2">
        
        <div class="row d-flex justify-content-end">
          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="all-select-box">
              <div class="btn-box">
                {{ Form::label('issue_date', __('Date'),['class'=>'text-type']) }}
                {{ Form::text('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:null, array('class' => 'form-control month-btn datepicker-range')) }}
              </div>
            </div>
          </div>
           
          <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
            <div class="all-select-box">
              <div class="btn-box">
                 
              </div>
            </div>
          </div>
          <div class="col-auto my-auto">
            <a href="#" class="apply-btn" onclick="document.getElementById('customer_submit').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
              <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            </a>
            @if(!\Auth::guard('customer')->check())
            <a href="{{route('invoice.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
              <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
            </a>
            @else
            <a href="{{route('customer.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
              <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
            </a>
            @endif
          </div>
        </div>
        {{ Form::close() }}
        <div class="table-responsive">
          <table class="table table-striped mb-0 dataTable">
            <thead>
              <tr>
                <th> {{__('Invoice')}}</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Products</th>
                <th>Amount</th>
                <th>Net of VAT</th>
                <th>Output VAT 12%</th>
                <th>Source</th>
                <th>Order#</th>
                <th>Transaction Date</th>
                <th>Invoice Date</th>
                <th>Delivery Receipt #</th>
                <th>Payment Method</th>
                <!-- <th>{{__('Action')}}</th> -->
              </tr>
            </thead>

            <tbody>
              @foreach($sales as $sale)
              <tr>
                <td>{{$sale->invoice_no}}</td>
                <td>{{$sale->first_name}}</td>
                <td>{{$sale->last_name}}</td>
                <td>{{$sale->address}}</td>
                <td>
                  @foreach($sale->products as $product)
                    {{$product->name.' ('.$product->quantity.' Qty) - Php '.$product->price}}<br/>
                  @endforeach
                </td>
                <td>{{$sale->total_amount}}</td>
                <td>{{number_format($sale->net_of_vat,2)}}</td>
                <td>{{number_format($sale->vat,2)}}</td>
                <td>{{$sale->source}}</td>
                <td>{{$sale->order_no}}</td>
                <td>{{$sale->transaction_date}}</td>
                <td>{{$sale->invoice_date}}</td>
                <td>{{$sale->delivery_receipt}}</td>
                <td>{{$sale->payment_method}}</td>
                 
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
