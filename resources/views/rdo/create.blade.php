<div class="card bg-none card-box">
{{ Form::open(array('url' => 'rdo')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('rdo_code', __('RDO Code')) }}
        {{ Form::text('rdo_code', '', array('class' => 'form-control','required'=>'required')) }}
        @error('rdo_code')
        <span class="invalid-rdo_code" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('rdo_location', __('RDO Location')) }}
        {{ Form::text('rdo_location', '', array('class' => 'form-control','required'=>'required')) }}
        @error('rdo_location')
        <span class="invalid-rdo_location" role="alert">
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