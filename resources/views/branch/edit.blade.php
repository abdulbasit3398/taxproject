{{Form::model($vender,array('route' => array('vender.update', $vender->id), 'method' => 'PUT')) }}
<h4 class="sub-title">{{__('Basic Info')}}</h4>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="form-group">
      {{Form::label('name',__('First Name'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-address-card"></i>
          </div>
        </div>
        {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="form-group">
      {{Form::label('middle_name',__('Middle Name'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-address-card"></i>
          </div>
        </div>
        {{Form::text('middle_name',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="form-group">
      {{Form::label('last_name',__('Last Name'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-address-card"></i>
          </div>
        </div>
        {{Form::text('last_name',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="form-group">
      {{Form::label('contact',__('Contact'))}}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-mobile-alt"></i>
          </div>
        </div>
        {{Form::text('contact',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="form-group">
      {{Form::label('TIN',__('TIN'))}}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-mobile-alt"></i>
          </div>
        </div>
        {{Form::text('vendor_tin',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="form-group">
      {{Form::label('organization_name',__('Organization Name'))}}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-mobile-alt"></i>
          </div>
        </div>
        {{Form::text('organization_name',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="form-group">
      {{Form::label('organization_type',__('Organization Type'))}}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-mobile-alt"></i>
          </div>
        </div>
        {{Form::text('organization_type',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  @if(!$customFields->isEmpty())
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="tab-pane fade show" id="tab-2" role="tabpanel">
      @include('customFields.formBuilder')
    </div>
  </div>
  @endif
</div>
<h4 class="sub-title">{{__('Address')}}</h4>
<div class="row">
  <!-- <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('billing_name',__('Name'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-address-card"></i>
          </div>
        </div>
        {{Form::text('billing_name',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div> -->
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('billing_country',__('Country'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="far fa-flag"></i>
          </div>
        </div>
        {{Form::text('billing_country',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
 <!--  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('billing_state',__('State'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-chess-pawn"></i>
          </div>
        </div>
        {{Form::text('billing_state',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div> -->
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('billing_city',__('City'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-city"></i>
          </div>
        </div>
        {{Form::text('billing_city',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>

  <!-- <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('billing_phone',__('Phone'),array('class'=>'')) }}
      <label class="form-control-label" for="example2cols1Input"></label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-mobile-alt"></i>
          </div>
        </div>
        {{Form::text('billing_phone',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div> -->
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('billing_zip',__('Zip Code'),array('class'=>'')) }}
      <label class="form-control-label" for="example2cols1Input"></label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-crosshairs"></i>
          </div>
        </div>
        {{Form::text('billing_zip',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      {{Form::label('billing_address',__('Address'),array('class'=>'')) }}
      <label class="form-control-label" for="example2cols1Input"></label>
      <div class="input-group">
        {{Form::textarea('billing_address',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>

</div>
<!-- <div class="col-md-12 text-right">
  <a href="#" class="btn btn-info"  id="billing_data">{{__('Shipping Same As Billing')}}</a>
</div>
<h4 class="sub-title">{{__('Shipping Address')}}</h4>
<div class="row">
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('shipping_name',__('Name'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-address-card"></i>
          </div>
        </div>
        {{Form::text('shipping_name',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('shipping_country',__('Country'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="far fa-flag"></i>
          </div>
        </div>
        {{Form::text('shipping_country',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('shipping_state',__('State'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-chess-pawn"></i>
          </div>
        </div>
        {{Form::text('shipping_state',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('shipping_city',__('City'),array('class'=>'')) }}
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-city"></i>
          </div>
        </div>
        {{Form::text('shipping_city',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('shipping_phone',__('Phone'),array('class'=>'')) }}
      <label class="form-control-label" for="example2cols1Input"></label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-mobile-alt"></i>
          </div>
        </div>
        {{Form::text('shipping_phone',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-6">
    <div class="form-group">
      {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'')) }}
      <label class="form-control-label" for="example2cols1Input"></label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-crosshairs"></i>
          </div>
        </div>
        {{Form::text('shipping_zip',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      {{Form::label('shipping_address',__('Address'),array('class'=>'')) }}
      <label class="form-control-label" for="example2cols1Input"></label>
      <div class="input-group">
        {{Form::textarea('shipping_address',null,array('class'=>'form-control','required'=>'required'))}}
      </div>
    </div>
  </div>
</div> -->
<div class="col-md-12 text-right">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
  {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{Form::close()}}


