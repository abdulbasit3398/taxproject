<div class="card bg-none card-box">
  {{Form::open(array('url'=>'customer','method'=>'post'))}}
  <h5 class="sub-title">{{__('Basic Info')}}</h5>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('last_name',__('Last Name'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-address-card"></i></span>
          {{Form::text('last_name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('name',__('First Name'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-address-card"></i></span>
          {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('middle_name',__('Middle Name'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-address-card"></i></span>
          {{Form::text('middle_name',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('contact',__('Contact'),['class'=>'form-control-label'])}}
        <div class="form-icon-user">
          <span><i class="fas fa-mobile-alt"></i></span>
          {{Form::text('contact',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('email',__('Email'),['class'=>'form-control-label'])}}
        <div class="form-icon-user">
          <span><i class="fas fa-envelope"></i></span>
          {{Form::text('email',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('password',__('Password'),['class'=>'form-control-label'])}}
        <div class="form-icon-user">
          <span><i class="fas fa-key"></i></span>
          {{Form::password('password',array('class'=>'form-control','required'=>'required','minlength'=>"6"))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('TIN',__('TIN'),['class'=>'form-control-label'])}}
        <div class="form-icon-user">
          <span><i class="fas fa-address-card"></i></span>
          {{Form::text('customer_tin',null,array('class'=>'form-control','id'=>'customer_tin','required'=>'required'))}}
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

  <h5 class="sub-title">{{__('Address')}}</h5>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        {{Form::label('billing_address',__('Address'),array('class'=>'form-control-label')) }}
        <div class="input-group">
          {{Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3))}}
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('billing_country',__('Country'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-flag"></i></span>
          {{Form::text('billing_country',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('billing_city',__('City'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-city"></i></span>
          {{Form::text('billing_city',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('billing_zip',__('Zip Code'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-crosshairs"></i></span>
          {{Form::text('billing_zip',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
  </div>

  <h5 class="sub-title">{{__('Company Information')}}</h5>
  <div class="row">

    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('branch_name',__('Branch'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-city"></i></span>
          {{Form::text('branch_name',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('organization_name',__('Organization / Trade Name'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-city"></i></span>
          {{Form::text('organization_name',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
      <div class="form-group">
        {{Form::label('organization_type',__('Organization Type'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <select name="organization_type" class="form-control custom-select">
            <option value="">Select Organization Type</option>
            @foreach($organisations as $organisation)
              <option value="{{$organisation->id}}">{{$organisation->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="form-group">
        {{Form::label('atc',__('ATC'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <select name="atc_list_id" class="form-control custom-select">
            <option value="">None</option>
            @foreach($fui_atc_lists as $atc_list)
              <option value="{{$atc_list->id}}">{{\Illuminate\Support\Str::limit($atc_list->atc.' '.$atc_list->description,40)}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="form-group">
        {{Form::label('tax_type',__('Tax Type'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <select name="tax_type" class="form-control custom-select">
            <option value="">Select Tax</option>
            <option value="VATABLE">VATABLE</option>
            <option value="NON VATABLE">NON VATABLE</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="form-group">
        {{Form::label('line_of_business',__('Line of Business'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <select class="select-line-of-business form-control custom-select" name="line_of_business">
            <option value="">Select Line of Business</option>
            @foreach($line_of_business as $busines)
              <option value="{{$busines->id}}">{{$busines->business_code.' '.$busines->business_title}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="form-group">
        {{Form::label('customer_since',__('Customer Since'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-calendar"></i></span>
          {{ Form::text('customer_since', null, array('class' => 'form-control datepicker')) }}
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <div class="custom-control custom-checkbox mt-4">
              <input class="custom-control-input" type="checkbox" value="Active" name="type[]" id="Active">
              <label class="custom-control-label form-control-label" for="Active">{{__('Active')}}</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="custom-control custom-checkbox mt-4">
              <input class="custom-control-input" type="checkbox" value="Withholding Agent" name="type[]" id="Withholding Agent">
              <label class="custom-control-label form-control-label" for="Withholding Agent">{{__('Withholding Agent')}}</label>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <h5 class="sub-title">{{__('Contact Person Details')}}</h5>
  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6">
      <div class="form-group">
        {{Form::label('person_name',__('Name'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-address-card"></i></span>
          {{Form::text('person_name',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
      <div class="form-group">
        {{Form::label('person_mbile',__('Mobile Number'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-mobile-alt"></i></span>
          {{Form::text('person_mbile',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
      <div class="form-group">
        {{Form::label('person_fax',__('Fax Number'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="fas fa-address-card"></i></span>
          {{Form::text('person_fax',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
      <div class="form-group">
        {{Form::label('person_email',__('Email Address'),array('class'=>'form-control-label')) }}
        <div class="form-icon-user">
          <span><i class="far fa-envelope"></i></span>
          {{Form::email('person_email',null,array('class'=>'form-control'))}}
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 px-0">
    <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
    <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
  </div>

  {{Form::close()}}

  <script type="text/javascript">
    $(document).ready(function(){
      $('.select2').select2();
      $("#customer_tin").inputmask("999-999-999-999");

    });
  </script>
</div>

