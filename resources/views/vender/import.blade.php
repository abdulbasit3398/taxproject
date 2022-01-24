<div class="card bg-none card-box">
  <form method="POST" id="import_vendors_form" accept-charset="UTF-8" enctype="multipart/form-data" action="{{route('import-vendors')}}">
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
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6">
        
        <div class="form-group" style="display: flex;">
          <input type="submit" id="import_file" value="Import" class="btn-submit text-white" style="margin-right: 5px">
           
          <!-- <input class="btn btn-primary" type="submit" id="import_file" value="Import"> -->
          <a class="btn btn-warning btn-xs btn-icon" href="{{asset('assets/template/import_template.csv')}}" target="_blank" style="padding: 0.75rem 1.75rem;">Download Sample</a>
        </div>
      </div>
    </div>

  <div class="row" id="column_maping_row" style="display: none;">
    <br/>
    <div class="col-md-12">
      <h5 style="text-align: center;">Column Mapping</h5>
      <p style="text-align: center;">Map the fields in your CSV file to the required contacts fields</p>
    </div>

    <div class="col-md-6">
      <label>TIN*</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_tin" name="select_tin" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Organization Type</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_organization_type" name="select_organization_type" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Organization Name</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_organization_name" name="select_organization_name" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>First Name</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_name" name="select_name" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Middle Name</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_middle_name" name="select_middle_name" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Last Name</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_last_name" name="select_last_name" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Address</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_address" name="select_address" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>City</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_city" name="select_city" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Country</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_country" name="select_country" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Postal</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_postal" name="select_postal" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Phone</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_phone" name="select_phone" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label>Email</label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" id="select_email" name="select_email" > 
          
        </select>
      </div>
    </div>
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary" style="float: right;">Save</button>

    </div>
  </div>
</form>
</div>


<script>
  $(document).ready(function(){
    $('#import_vendors_form').submit(function(e){
      if($('#select_tin').val() == null)
      {
        e.preventDefault();
        $('#loading').show();
        
        $.ajax({
         url:"{{ route('import-vendors-file') }}",
         method:"POST",
         data:new FormData(this),
         dataType:'JSON',
         contentType: false,
         cache: false,
         processData: false,
         success:function(response)
         {
          var selected = select_tin = select_organization_name = select_organization_type = select_name = select_middle_name = select_last_name = select_address = select_city = select_country = select_postal = select_phone = select_email = '';
          $('#select_tin, #select_name, #select_middle_name, #select_last_name, #select_organization_type, #select_organization_name, #select_address, #select_city, #select_country, #select_postal, #select_phone, #select_email').append('<option value="">Select Column</option>');

          for(var i = 0; i < response.length; i++){
            select_tin = select_organization_name = select_organization_type = select_name = select_middle_name = select_last_name = select_address = select_city = select_country = select_postal = select_phone = select_email = '';

            if(i == 0)
              select_tin = 'selected';
            else if(i == 1)
              select_organization_name = 'selected';
            else if(i == 2)
              select_organization_type = 'selected';
            else if(i == 3)
              select_name = 'selected';
            else if(i == 4)
              select_middle_name = 'selected';
            else if(i == 5)
              select_last_name = 'selected';
            else if(i == 6)
              select_address = 'selected';
            else if(i == 7)
              select_city = 'selected';
            else if(i == 8)
              select_country = 'selected';
            else if(i == 9)
              select_postal = 'selected';
            else if(i == 10)
              select_phone = 'selected';
            else if(i == 11)
              select_email = 'selected';
            else
              select_tin = select_organization_name = select_organization_type = select_name = select_middle_name = select_last_name = select_address = select_city = select_country = select_postal = select_phone = select_email = '';

            $('#select_tin').append('<option '+select_tin+' value="'+i+'">'+response[i]+'</option>');
            $('#select_organization_name').append('<option '+select_organization_name+' value="'+i+'">'+response[i]+'</option>');
            $('#select_organization_type').append('<option '+select_organization_type+' value="'+i+'">'+response[i]+'</option>');
            $('#select_name').append('<option '+select_name+' value="'+i+'">'+response[i]+'</option>');
            $('#select_middle_name').append('<option '+select_middle_name+' value="'+i+'">'+response[i]+'</option>');
            $('#select_last_name').append('<option '+select_last_name+' value="'+i+'">'+response[i]+'</option>');
            $('#select_address').append('<option '+select_address+' value="'+i+'">'+response[i]+'</option>');
            $('#select_city').append('<option '+select_city+' value="'+i+'">'+response[i]+'</option>');
            $('#select_country').append('<option '+select_country+' value="'+i+'">'+response[i]+'</option>');
            $('#select_postal').append('<option '+select_postal+' value="'+i+'">'+response[i]+'</option>');
            $('#select_phone').append('<option '+select_phone+' value="'+i+'">'+response[i]+'</option>');
            $('#select_email').append('<option '+select_email+' value="'+i+'">'+response[i]+'</option>');
          }

          $('#import_file_row').hide();
          $('#column_maping_row').show();
          $('#select_tin, #select_name, #select_middle_name, #select_last_name, #select_organization_type, #select_organization_name, #select_address, #select_city, #select_country, #select_postal, #select_phone, #select_email').prop('required',true);
          $('#loading').hide();
         }
        });
      }
      else{
        // $.ajax({
        //  url:"{{ route('import-customers') }}",
        //  method:"POST",
        //  data:new FormData(this),
        //  dataType:'JSON',
        //  contentType: false,
        //  cache: false,
        //  processData: false,
        //  success:function(response)
        //  {
        //   if(response['message'] == 'success')
        //   {
        //     $('#loading').hide();
        //     window.setTimeout(toastr['success']('Customers imported successfully.', 'Success'), 4000 );
        //      location.reload();
        //   }
        //  }
        // });

      }
      
    });
  });
  $(document).on('click', '#billing_data', function () {
    $("[name='shipping_name']").val($("[name='billing_name']").val());
    $("[name='shipping_country']").val($("[name='billing_country']").val());
    $("[name='shipping_state']").val($("[name='billing_state']").val());
    $("[name='shipping_city']").val($("[name='billing_city']").val());
    $("[name='shipping_phone']").val($("[name='billing_phone']").val());
    $("[name='shipping_zip']").val($("[name='billing_zip']").val());
    $("[name='shipping_address']").val($("[name='billing_address']").val());
  })
  $(document).on('click', '#vend_detail', function () {
    $('#cust_table').addClass('col-6').removeClass('col-12')
    $('#customer_details').removeClass('d-none');
    $('#customer_details').addClass('d-block');
    var id = $(this).data('id');
    var url = $(this).data('url');
    $.ajax({
      url: url,
      type: 'GET',
      cache: false,
      success: function (data) {
        $('#customer_details').html(data);
      },

    });
  });

  $(document).ready(function () {
    $('.commonModal').click(function () {
      $('#commonModal').modal({
        backdrop: 'static'
      });
    });
  });
  
</script>