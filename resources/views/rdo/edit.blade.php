<div class="card bg-none card-box">
{{ Form::model($rdo, array('route' => array('rdo.update', $rdo->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('rdo_code', __('RDO Code')) }}
        {{ Form::text('rdo_code', null, array('class' => 'form-control font-style','required'=>'required')) }}
        @error('name')
        <span class="invalid-name" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('rdo_location', __('RDO Location')) }}
        {{ Form::text('rdo_location', null, array('class' => 'form-control','required'=>'required')) }}
        @error('rate')
        <span class="invalid-rate" role="alert">
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