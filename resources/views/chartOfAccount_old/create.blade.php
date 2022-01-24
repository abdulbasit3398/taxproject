<div class="card bg-none card-box">
  {{ Form::open(array('url' => 'coa')) }}
  <div class="row">
    <div class="form-group col-md-6">
      {{ Form::label('account_code', __('Account Code')) }}
      {{ Form::text('account_code', '', array('class' => 'form-control','required'=>'required')) }}
      @error('account_code')
      <span class="invalid-account_code" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      {{ Form::label('account_title', __('Account Title')) }}
      {{ Form::text('account_title', '', array('class' => 'form-control','required'=>'required')) }}
      @error('account_title')
      <span class="invalid-account_title" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      {{ Form::label('category', __('Category')) }}
      <select class="form-control" required name="category">
        <option value="">Select Category</option>
        @foreach($categories as $category)
        <option value="{{$category[0]->category}}">{{$category[0]->category}}</option>
        @endforeach
      </select>
      @error('category')
      <span class="invalid-category" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      {{ Form::label('debit_credit', __('Debit/Credit')) }}
      <select class="form-control" required name="debit_credit">
        <option value="">Select Debit/Credit</option>
        @foreach($debit_credit as $debit)
        <option value="{{$debit[0]->debit_credit}}">{{$debit[0]->debit_credit}}</option>
        @endforeach
      </select>
      @error('debit_credit')
      <span class="invalid-debit_credit" role="alert">
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