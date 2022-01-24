<style type="text/css">
  .modal-2xl {
    width: 100% !important;
    max-width: 85% !important;
}
</style>
<form method="POST" action="{{route('branch.store')}}">
  @csrf
  <h4 class="sub-title">{{__('Basic Info')}}</h4>
  <div class="card-header-action">
    <div class="row">
      <div class="col-md-12">
        <div id="repeater table-responsive">
          <table class="table" id="customFields">
            <thead>
              <tr>
                <th>Branch Code</th>
                <th>Address</th>
                <th>City</th>
                <th>Postal</th>
                <th>Phone</th>
                <th>RDO</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr valign="top">
                <td>
                  <input type="text" class="form-control code"  name="branch_code[]" value="" placeholder="e.g. 001" required />
                </td>
                <td>
                  <input type="text" class="form-control code" name="address[]" value="" placeholder="e.g 145 Yakal St." required />
                </td>
                <td>
                  <input type="text" class="form-control code" name="city[]" value="" placeholder="e.g Makati" required />
                </td>
                <td>
                  <input type="text" class="form-control code" name="postal[]" value="" placeholder="1203" required />
                </td>
                <td>
                  <input type="text" class="form-control code" name="phone[]" value="" placeholder="09123456789" required />
                </td>
                <td>
                  <select class="form-control code" name="rdo[]" required>
                    <option value="">Select RDO</option>
                    @foreach($rdos as $rdo)
                    <option value="{{$rdo->id}}">{{$rdo->rdo_code." ".$rdo->rdo_location}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  
                </td>
              </tr>
            </tbody>
            
          </table>
          <div class="row">
            <div class="col-md-12">

              <a href="javascript:void(0);" class="btn btn-sm btn-primary rounded-pill addCF" style="border-radius: 25px">Add Item</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 text-right">
        <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal" style="border-radius: 10px;">
        <input type="submit" value="{{__('Create')}}" class="btn-submit text-white">
      </div>
    </div>
  </div>
</form>
  

<script type="text/javascript">

$(document).ready(function(){
  $(".addCF").click(function(){
    $("#customFields").append('<tr valign="top"><td><input type="text" class="form-control code"  name="branch_code[]" value="" placeholder="e.g. 001" required /></td><td><input type="text" class="form-control code" name="address[]" value="" placeholder="e.g 145 Yakal St." required /></td><td><input type="text" class="form-control code" name="city[]" value="" placeholder="e.g Makati" required /></td><td><input type="text" class="form-control code" name="postal[]" value="" placeholder="1203" required /></td><td><input type="text" class="form-control code" name="phone[]" value="" placeholder="09123456789" required /></td><td><select class="form-control code" name="rdo[]" required><option value="">Select RDO</option>@foreach($rdos as $rdo)<option value="{{$rdo->id}}">{{$rdo->rdo_code." ".$rdo->rdo_location}}</option>@endforeach</select></td><td><a href="javascript:void(0);" class="btn btn-danger remCF"><i class="fas fa-times"></i></a></td></tr>');
  });
    $("#customFields").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });
});
</script>
