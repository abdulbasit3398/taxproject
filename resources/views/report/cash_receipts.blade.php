@extends('layouts.admin')
@section('page-title')
{{__('Cash Receipts')}}
@endsection
@push('script-page')
<script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script>
  var filename = $('#filename').val();

  function saveAsPDF() {
    var element = document.getElementById('printableArea');
    var opt = {
      margin: 0.3,
      filename: filename,
      image: {type: 'jpeg', quality: 1},
      html2canvas: {scale: 4, dpi: 72, letterRendering: true},
      jsPDF: {unit: 'in', format: 'A2'}
    };
    html2pdf().set(opt).from(element).save();
  }

</script>
@endpush

@section('action-button')
<div class="row d-flex justify-content-end">
   

  <div class="col-auto my-auto">
    <a href="#" class="apply-btn" onclick="document.getElementById('report_trial_balance').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
      <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
    </a>
    <a href="{{route('trial.balance')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
      <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
    </a>
    <a href="#" class="action-btn" onclick="saveAsPDF()" data-toggle="tooltip" data-original-title="{{__('Download')}}">
      <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
    </a>
  </div>
</div>
{{ Form::close() }}
@endsection

@section('content')
<div id="printableArea">
   
  @if(!empty($account))
  <div class="row mt-4">
    <div class="col">
      <div class="card p-4 mb-4">
        <h5 class="report-text gray-text mb-0">{{__('Total Credit')}} :</h5>
        <h5 class="report-text mb-0">0</h5>
      </div>
    </div>
    <div class="col">
      <div class="card p-4 mb-4">
        <h5 class="report-text gray-text mb-0">{{__('Total Debit')}} :</h5>
        <h5 class="report-text mb-0">0</h5>
      </div>
    </div>
  </div>
  @endif
  <div class="row mb-4">
    <div class="col-12 mb-4">
      <table class="table table-flush table-responsive">
        <thead>
          <tr>
            <th> DATE</th>
            <th> NAME OF CUSTOMER</th>
            <th> ADDRESSE</th>
            <th> TIN</th>
            <th> Ref. TYPE</th>
            <th> Ref No.</th>
            <th> Description</th>
            <th> CASH IN BANK</th>
            <th> CASH ON HAND</th>
            <th> Creditable Withholding Tax - 2307</th>
            <th> Accounts Receivable - CR</th>
            <th> SALES</th>
            <th> VAT OUTPUT</th>
          </tr>
        </thead>
        <tbody>
           
        </tbody>
        
      </table>
    </div>
  </div>
</div>
@endsection
