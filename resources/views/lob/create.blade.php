<div class="card bg-none card-box">
  {{ Form::open(array('url' => 'lob')) }}
  <div class="row">
      <div class="form-group col-md-6">
          {{ Form::label('business_code', __('Business Code')) }}
          {{ Form::text('business_code', '', array('class' => 'form-control','required'=>'required')) }}
          @error('business_code')
          <span class="invalid-business_code" role="alert">
          <strong class="text-danger">{{ $message }}</strong>
      </span>
          @enderror
      </div>
      <div class="form-group col-md-6">
          {{ Form::label('business_title', __('Business Title')) }}
          {{ Form::text('business_title', '', array('class' => 'form-control','required'=>'required')) }}
          @error('business_title')
          <span class="invalid-business_title" role="alert">
          <strong class="text-danger">{{ $message }}</strong>
      </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
        {{Form::submit(__('Create'),array('class'=>'btn-create badge-blue'))}}
        <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
      </div>
  </div>
  {{ Form::close() }}
  
</div>