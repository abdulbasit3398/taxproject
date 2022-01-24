<div class="card bg-none card-box">
  {{ Form::model($atc, array('route' => array('atc.update', $atc->id), 'method' => 'PUT')) }}
  <div class="row">
      <div class="form-group col-md-6">
          {{ Form::label('rdo_code', __('ATC Code')) }}
          <input type="text" name="atc_code" class="form-control font-style" value="{{$atc->atc}}" required>
          @error('atc_code')
          <span class="invalid-atc_code" role="alert">
          <strong class="text-danger">{{ $message }}</strong>
      </span>
          @enderror
      </div>
      <div class="form-group col-md-6">
          {{ Form::label('rdo_location', __('Rate %')) }}
          <input type="number" min="0" name="rate" class="form-control" required value="{{$atc->rate}}">
          @error('rate')
          <span class="invalid-rate" role="alert">
          <strong class="text-danger">{{ $message }}</strong>
      </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          {{ Form::label('description', __('Description')) }}
          <textarea class="form-control" rows="9" name="description" required>{{$atc->description}}</textarea>
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