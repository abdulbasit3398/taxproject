@extends('layouts.admin')

@push('css-page')
<link rel="stylesheet" href="{{ asset('assets/multiselect/css/multi-select.css') }}">
@endpush

@push('script-page')
<script src="{{ asset('assets/multiselect/js/jquery.multi-select.js') }} "></script>

<script type="text/javascript">
  $('#fui-atc').multiSelect(); 
  $('#fui-coa').multiSelect();  
</script>

@endpush

@section('page-title')
{{__('Frequently Used Items')}}
@endsection
@section('content')
<style type="text/css">
  .ms-container{
    width: 100% !important;
  }
</style>
<section class="section">
   
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
           
          <div class="card-body">
            <div class="card-body p-0">
              <div class="row">
                <div class="col-md-12">
                  <form action="{{route('fui.add_atc')}}" method="post">
                    @csrf
                    <div class="form-group">
                      <h5 class="box-title">Select your frequently used ATC</h5>
                      <select id='fui-atc' multiple='multiple' class="form-control" name="atc_id[]">
                        @foreach($atc_lists as $atc_list)
                        <?php $selected = ''; ?>
                          @foreach($fui_atc as $atc)
                          
                            @if($atc->atc_list_id == $atc_list->id)
                            <?php $selected = 'selected'; ?>
                            @endif
                          @endforeach
                          <option value="{{$atc_list->id}}" {{$selected}}>{{$atc_list->atc}}</option>
                        @endforeach

                      </select>
                    </div>
                    <div class="form-group">
                      <a type="submit" class="btn btn-xs btn-white btn-icon-only" style="float: right;">Save</a>
                    </div>
                  </form>
                  
                </div>
                <div class="col-md-12">
                  <form action="{{route('fui.add_coa')}}" method="post">
                    @csrf
                    <div class="form-group">
                      <h5 class="box-title">Select your frequently used COA</h5>
                      <select id='fui-coa' multiple='multiple' class="form-control" name="coa_id[]">
                        @foreach($chartofaccounts as $chartofaccount)
                        <?php $selected = ''; ?>
                          @foreach($fui_coa as $coa)
                          
                            @if($coa->chart_of_accounts_id == $chartofaccount->id)
                            <?php $selected = 'selected'; ?>
                            @endif
                          @endforeach
                          <option value="{{$chartofaccount->id}}" {{$selected}}>{{$chartofaccount->account_title}}</option>
                        @endforeach

                      </select>
                    </div>
                    <div class="form-group">
                      <a type="submit" class="btn btn-xs btn-white btn-icon-only " style="float: right;">Save</a>
                    </div>
                  </form>
                  
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

