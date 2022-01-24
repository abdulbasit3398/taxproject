@php
  
  $image=asset(Storage::url('attachment/'));
@endphp
<div class="card bg-none card-box">
  {{ Form::model($productService, array('route' => array('productservice.update', $productService->id), 'method' => 'PUT','files' => true)) }}
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        {{ Form::label('name', __('Name'),['class'=>'form-control-label']) }}
        <div class="form-icon-user">
          <span><i class="fas fa-address-card"></i></span>
          {{ Form::text('name', $productService->name, array('class' => 'form-control','required'=>'required')) }}
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        {{ Form::label('sku', __('SKU'),['class'=>'form-control-label']) }}
        <div class="form-icon-user">
          <span><i class="fas fa-key"></i></span>
          {{ Form::text('sku', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
      </div>
    </div>
    <div class="form-group  col-md-12">
      {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
      {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
    <div class="col-md-6">
      <div class="form-group">
        {{ Form::label('sale_price', __('Sale Price'),['class'=>'form-control-label']) }}
        <div class="form-icon-user">
          <span><i class="fas fa-money-bill-alt"></i></span>
          {{ Form::number('sale_price', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        {{ Form::label('purchase_price', __('Purchase Price'),['class'=>'form-control-label']) }}
        <div class="form-icon-user">
          <span><i class="fas fa-money-bill-alt"></i></span>
          {{ Form::number('purchase_price', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
        </div>
      </div>
    </div>

    <div class="form-group  col-md-6">
      {{ Form::label('tax_id', __('Tax'),['class'=>'form-control-label']) }}
      {{ Form::select('tax_id[]', $tax,null, array('class' => 'form-control select2','multiple'=>'','required'=>'required')) }}

    </div>
    <div class="form-group  col-md-6">
      {{ Form::label('category_id', __('Category'),['class'=>'form-control-label']) }}
      {{ Form::select('category_id', $category,null, array('class' => 'form-control select2','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
      {{ Form::label('unit_id', __('Unit'),['class'=>'form-control-label']) }}
      {{ Form::select('unit_id', $unit,null, array('class' => 'form-control select2','required'=>'required')) }}
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="d-block form-control-label">{{__('Type')}}</label>
        <div class="row">
          <div class="col-md-6">
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="customRadio5" name="type" value="product" @if($productService->type=='product') checked @endif onclick="hide_show(this)">
              <label class="custom-control-label form-control-label" for="customRadio5">{{__('Product')}}</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="customRadio6" name="type" value="service" @if($productService->type=='service') checked @endif   onclick="hide_show(this)">
              <label class="custom-control-label form-control-label" for="customRadio6">{{__('Service')}}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group col-md-6">
      {{ Form::label('atc_list', __('ATC LIST'),['class'=>'form-control-label']) }}
      <select class="form-control select2" name="atc_list_id" required>
        <option value="">Select ATC</option>
        <option value="0">None</option>
        @foreach($fui_atc_lists as $atc_list)
          <option value="{{$atc_list->id}}" {{($atc_list->id == $productService->atc_list_id) ? 'selected' : ''}}>
            {{\Illuminate\Support\Str::limit($atc_list->atc.' '.$atc_list->description,45)}}
          </option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-6">
      {{ Form::label('coa', __('COA'),['class'=>'form-control-label']) }}
      <select class="form-control select2" name="coa_id" required>
        <option value="">Select COA</option>
        <option value="0">None</option>
        @foreach($fui_caos as $coa)
        <option value="{{$coa->id}}" {{($coa->id == $productService->coa_id) ? 'selected' : ''}}>
          {{\Illuminate\Support\Str::limit($coa->account_id.' '.$coa->account_title,45)}}
        </option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-6">
      <div class="choose-file ">
        <label for="company_favicon">
          <div>Choose file here</div>
          <input type="file" class="form-control" required  name="attachment" id="company_favicon" data-filename="edit-company-favicon">
        </label>
        <p class="edit-company-favicon"></p>
      </div>
    </div>
    <div class="form-group col-md-6">
     
          <div>Previous Image </div>
      
         {{-- <img src="{{ route('image.displayImage',$attachment) }}" alt="" srcset=""> --}}
         <img src="{{asset(Storage::url('attachment/'.$attachment))}}" alt="" srcset="" width="120">
         <!-- <img src="{{(!empty($attachment) ? $image.'/'.$attachment : $attachment.'.png')}}"/> -->
         {{-- <img src="{{$image.$attachment  }}" /> --}}
        
      </div>
    </div>
    @if(!$customFields->isEmpty())
    <div class="col-md-6">
      <div class="tab-pane fade show" id="tab-2" role="tabpanel">
        @include('customFields.formBuilder')
      </div>
    </div>
    @endif
    <div class="col-md-12">
      <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
      <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
  </div>
  {{ Form::close() }}
</div>
