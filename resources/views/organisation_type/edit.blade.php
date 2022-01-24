<div class="card bg-none card-box">
  {{ Form::model($organisation_type, array('route' => array('organisation-type.update', $organisation_type->id), 'method' => 'PUT')) }}
  <div class="row">
      <div class="form-group col-md-6">
          {{ Form::label('name', __('Organisation Type')) }}
          <input type="text" name="name" class="form-control font-style" value="{{$organisation_type->name}}" required>
          @error('name')
          <span class="invalid-name" role="alert">
          <strong class="text-danger">{{ $message }}</strong>
      </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
        {{Form::submit(__('Update'),array('class'=>'btn-create badge-blue'))}}
        <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
      </div>
  </div>
  {{ Form::close() }}

</div>