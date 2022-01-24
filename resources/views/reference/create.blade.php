<div class="card bg-none card-box">
  {{ Form::open(array('url' => 'reference')) }}
  <div class="row">
      <div class="form-group col-md-6">
          {{ Form::label('reference_code', __('Reference Code')) }}
          {{ Form::text('reference_code', '', array('class' => 'form-control','required'=>'required')) }}
          @error('reference_code')
          <span class="invalid-reference_code" role="alert">
          <strong class="text-danger">{{ $message }}</strong>
      </span>
          @enderror
      </div>
      <div class="form-group col-md-6">
          {{ Form::label('reference_title', __('Reference Title')) }}
          {{ Form::text('reference_title', '', array('class' => 'form-control','required'=>'required')) }}
          @error('reference_title')
          <span class="invalid-reference_title" role="alert">
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