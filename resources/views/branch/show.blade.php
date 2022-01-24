<div class="custmer-detail-wrap">
  <div class="row">
    <div class="col">
      <h4>{{__('Vendor Details')}}</h4>
    </div>
    <div class="col text-right ">
      @can('edit vender')
      <a href="#!" class="btn btn-primary btn-action mr-1" data-size="2xl" data-url="{{ route('vender.edit',$vender['id']) }}" data-ajax-popup="true" data-title="{{__('Edit Vendor')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
        <i class="fas fa-pencil-alt"></i>
      </a>
      @endcan
      @can('delete vender')
      <a href="#!" class="btn btn-danger btn-action " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{ $vender['id']}}').submit();">
        <i class="fas fa-trash"></i>
      </a>
      {!! Form::open(['method' => 'DELETE', 'route' => ['vender.destroy', $vender['id']],'id'=>'delete-form-'.$vender['id']]) !!}
      {!! Form::close() !!}
      @endcan

    </div>
  </div>
  <h4 class="sub-title">{{__('Basic Info')}}</h4>
  <div class="row">
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Vendor Id')}}</strong>
        <span>{{AUth::user()->venderNumberFormat($vender['vender_id'])}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('First Name')}}</strong>
        <span>{{$vender['name']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Middle Name')}}</strong>
        <span>{{$vender['middle_name']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Last Name')}}</strong>
        <span>{{$vender['last_name']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Email')}}</strong>
        <span>{{$vender['email']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Phone')}}</strong>
        <span>{{$vender['contact']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('TIN')}}</strong>
        <span>{{$vender['vendor_tin']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Organization Name')}}</strong>
        <span>{{$vender['organization_name']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Organization Type')}}</strong>
        <span>{{$vender['organization_type']}}</span>
      </div>
    </div>

  </div>
  <h4 class="sub-title">{{__('Address')}}</h4>
  <div class="row">
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Name')}}</strong>
        <span>{{$vender['billing_name']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Phone')}}</strong>
        <span>{{$vender['billing_phone']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Country')}}</strong>
        <span>{{$vender['billing_country']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('State')}}</strong>
        <span>{{$vender['billing_state']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('City')}}</strong>
        <span>{{$vender['billing_city']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Address')}}</strong>
        <span>{{$vender['billing_address']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Zip Code')}}</strong>
        <span>{{$vender['billing_zip']}}</span>
      </div>
    </div>
  </div>
  <!-- <h4 class="sub-title">{{__('Shipping Address')}}</h4>
  <div class="row">
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Name')}}</strong>
        <span>{{$vender['shipping_name']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Phone')}}</strong>
        <span>{{$vender['shipping_phone']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Country')}}</strong>
        <span>{{$vender['shipping_country']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('State')}}</strong>
        <span>{{$vender['shipping_state']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('City')}}</strong>
        <span>{{$vender['shipping_city']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Address')}}</strong>
        <span>{{$vender['shipping_address']}}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info">
        <strong>{{__('Zip Code')}}</strong>
        <span>{{$vender['shipping_zip']}}</span>
      </div>
    </div>
  </div> -->
</div>
<script>
  common_bind_confirmation();
</script>
