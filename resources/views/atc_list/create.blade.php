<div class="card bg-none card-box">
  {{ Form::open(array('url' => 'atc')) }}
  <div class="row">
    <div class="form-group col-md-6">
      {{ Form::label('atc_code', __('ATC Code')) }}
      {{ Form::text('atc_code', '', array('class' => 'form-control','required'=>'required')) }}
      @error('atc_code')
      <span class="invalid-atc_code" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      {{ Form::label('rate', __('Rate %')) }}
      {{ Form::number('rate', '', array('class' => 'form-control','required'=>'required','min'=>0)) }}
      @error('rate')
      <span class="invalid-rate" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-12">
      {{ Form::label('description', __('Description')) }}
      <textarea class="form-control" name="description" rows="9" required></textarea>
      @error('description')
      <span class="invalid-description" role="alert">
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