<div class="card bg-none card-box">
  {{ Form::open(array('url' => 'organisation-type')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Organisation Type')) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        @error('name')
        <span class="invalid-name" role="alert">
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