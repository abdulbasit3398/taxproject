@extends('layouts.admin')
@section('page-title')
    {{__('Manage Product & Services')}}
@endsection

@section('action-button')
    <div class="row d-flex justify-content-end">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6 pt-lg-3 pt-xl-2">
            <div class="all-button-box">
                <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-url="{{ route('productservice.create') }}" data-ajax-popup="true" data-title="{{__('Create New Product')}}">
                    <i class="fa fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-0">
                    {{ Form::open(array('route' => array('productservice.index'),'method' => 'GET','id'=>'product_service')) }}
                    <div class="row d-flex justify-content-end mt-2">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                            <div class="all-select-box">
                                <div class="btn-box">
                                    {{ Form::label('category', __('Category'),['class'=>'text-type']) }}
                                    {{ Form::select('category', $category,null, array('class' => 'form-control select2','required'=>'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <a href="#" class="apply-btn" onclick="document.getElementById('product_service').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                            </a>
                            <a href="{{route('productservice.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                                <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
                            </a>

                        </div>
                    </div>
                    {{ Form::close() }}
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr role="row">
                                <th>{{__('Name')}}</th>
                                <th>{{__('Sku')}}</th>
                                <th>{{__('Sale Price')}}</th>
                                <th>{{__('Purchase Price')}}</th>
                                <th>{{__('Tax')}}</th>
                                <th>{{__('Category')}}</th>
                                <th>{{__('Unit')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($productServices as $productService)
                                <tr class="font-style">
                                    <td>{{ $productService->name}}</td>
                                    <td>{{ $productService->sku }}</td>
                                    <td>{{ \Auth::user()->priceFormat($productService->sale_price) }}</td>
                                    <td>{{  \Auth::user()->priceFormat($productService->purchase_price )}}</td>
                                    <td>
                                        @php
                                            $taxes=\Utility::tax($productService->tax_id);
                                        @endphp

                                        @foreach($taxes as $tax)
                                            {{ !empty($tax)?$tax->name:''  }}<br>

                                        @endforeach
                                    </td>
                                    <td>{{ !empty($productService->category)?$productService->category->name:'' }}</td>
                                    <td>{{ !empty($productService->unit())?$productService->unit()->name:'' }}</td>
                                    <td>{{ $productService->type }}</td>
                                    <td>{{ $productService->description }}</td>

                                    @if(Gate::check('edit product & service') || Gate::check('delete product & service'))
                                        <td class="Action">
                                            @can('edit product & service')
                                                <a href="#" class="edit-icon" data-url="{{ route('productservice.edit',$productService->id) }}" data-ajax-popup="true" data-title="{{__('Edit Product Service')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                            @can('delete product & service')
                                                <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$productService->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['productservice.destroy', $productService->id],'id'=>'delete-form-'.$productService->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
