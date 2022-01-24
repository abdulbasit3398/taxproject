<div class="card bg-none card-box">
  {{ Form::open(array('url' => 'expense_type')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('expense', __('Expense Type')) }}
        {{ Form::text('expense', '', array('class' => 'form-control','required'=>'required')) }}
        @error('expense')
        <span class="invalid-expense" role="alert">
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