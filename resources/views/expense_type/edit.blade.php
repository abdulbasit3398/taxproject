<div class="card bg-none card-box">
  {{ Form::model($expense_type, array('route' => array('expense_type.update', $expense_type->id), 'method' => 'PUT')) }}
  <div class="row">
      <div class="form-group col-md-6">
          {{ Form::label('expense', __('Expense Type')) }}
          <input type="text" name="expense" class="form-control font-style" value="{{$expense_type->expense}}" required>
          @error('expense')
          <span class="invalid-expense" role="alert">
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