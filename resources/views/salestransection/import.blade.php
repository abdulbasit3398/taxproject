<div class="card bg-none card-box">
  <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data" action="{{route('save_import_sale_transections')}}">
    @csrf
    <h4 class="sub-title">Import CSV</h4>
    <div class="row" id="import_file_row">
      <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="form-group">
          <div class="input-group">
             
            <div class="choose-file ">
              <label for="company_logo">
                <div>{{__('Choose file here')}}</div>
                <input type="file" class="form-control" name="select_file" id="select_file" data-filename="edit-company_logo" required accept=".csv">
              </label>
              <p class="edit-company_logo"></p>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
      </div>
       
    </div>

    
  </form>
</div>


 