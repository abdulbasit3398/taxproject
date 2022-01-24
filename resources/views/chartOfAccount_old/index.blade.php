@extends('layouts.admin')
@section('page-title')
{{__('Chart of Accounts')}}
@endsection

@section('action-button')
<div class="all-button-box row d-flex justify-content-end">
  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
      <a href="#" data-url="{{ route('coa.create') }}" data-ajax-popup="true" data-title="{{__('Create new COA')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
          <i class="fas fa-plus"></i> {{__('Create')}}
      </a>
  </div>
</div>


@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body py-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 dataTable">
            <thead>
              <tr>
                <th> {{__('Account Code')}}</th>
                <th> {{__('Account Title')}}</th>
                <th> {{__('Category')}}</th>
                <th> {{__('Debit/Credit')}}</th>
                <th class="text-right"> {{__('Action')}}</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($coas as $coa)
              <tr class="font-style">
                <td>{{ $coa->account_id }}</td>
                <td>{{ $coa->account_title }}</td>
                <td>{{ $coa->category }}</td>
                <td>{{ $coa->debit_credit }}</td>
                <td class="action text-right">
                  <a href="#!" class="edit-icon" data-url="{{ route('coa.edit',$coa->id) }}" data-ajax-popup="true" data-title="{{__('Edit COA')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$coa->id}}').submit();">
                    <i class="fas fa-trash"></i>
                  </a>
                  {!! Form::open(['method' => 'DELETE', 'route' => ['coa.destroy', $coa->id],'id'=>'delete-form-'.$coa->id]) !!}
                  {!! Form::close() !!}
                </td>
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
