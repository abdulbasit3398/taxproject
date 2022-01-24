@extends('layouts.admin')
@section('page-title')
    {{__('Manage Invoices')}}
@endsection

@section('action-button')
    @can('create invoice')
        <div class="row d-flex justify-content-end">
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6 col-12">
                <div class="all-button-box">
                    <a href="{{ route('invoice.create',0) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        <i class="fas fa-plus"></i> {{__('Create')}}
                    </a>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('css-page')
<style>
    .cell-price {
        FONT-SIZE: 18px;
        font-family: 'Circular-Loom';
    }
    .pos-image{
        height: 189px !important;
    }
</style>
@endpush


@section('content')
    <div class="row">
        <div class="col-lg-12 col-category col-category-tab">
             
        </div>
        <div class="col-md-6 col-lg-8">

            <div class="row row-products">
                <select name="category" id="category"  class="select-box select2 " >
                    <option value="0">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id}}" @if (isset($cat) ? $cat   == $category->id : '') selected
                            @endif>{{Str::upper($category->name)}}</option>
                    @endforeach
                </select>
            </div>

            <div class="row row-products">
                 
                <div class="col-md-12 col-lg-12 col-product">
                    <div class="row" id="pro">

                         

                         
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-6 col-lg-4">
            <div class="col-product-detail-tbl">
                <div class="max-height">
                    <div class="row gx-0 align-items-center">
                        <div class="col-12 text-right">
                            <span class="odr-token">Order token: #5 - <time datetime="1632848028939">9:53 PM</time></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" id="">
                            <div class="product-detail-tbl" id="price_correction">
                                <div class="product-detail-tbl-row product-detail-tbl-heading">
                                    <div class="product-detail-tbl-cell text-center">S/L</div>
                                    <div class="product-detail-tbl-cell">Food Item</div>
                                    <div class="product-detail-tbl-cell text-center qty">QTY</div>
                                    <div class="product-detail-tbl-cell pro-price text-center">Price</div>
                                </div>
                                 
                                 


                            </div>
                        </div>
                    </div>
                </div>
                <div class="pro-bottom-detail">
                    <div class="sub-total-row">
                        <div class="sub-total-col">sub total</div>
                        <div class="sub-total-col rt-bdr" id="total_product_price"></div>
                        <div class="sub-total-col">vat(5)%:</div>
                        <div class="sub-total-col">$38.75</div>
                    </div>
                    <div class="tag-commission-row">
                        <div class="tag-commission-col">Department Tag Commission : $0.00</div>
                    </div>
                    <div class="charges-total-row">
                        <div class="charges-total-col">service charge</div>
                        <div class="charges-total-col charges-col-input"><input type="number" step="0.01" min="0" class="text-capitalize xsm-text d-inline-block font-weight-bold t-pt-5 t-pb-5 form-control rounded-0 text-center" value="false"></div>
                        <div class="charges-total-col">discount</div>
                        <div class="charges-total-col charges-col-input"><input type="number" step="0.01" min="0" class="text-capitalize xsm-text d-inline-block font-weight-bold t-pt-5 t-pb-5 form-control rounded-0 text-center" value="false"></div>
                    </div>
                    <div class="total-bill-row">
                        <div class="total-bill-col">Total Bill</div>
                        <div class="total-bill-col" id="totalPrice">$</div>
                    </div>
                    <div class="btm-buttons">
                        <button type="button" class="btn btn-primary sm-text text-uppercase font-weight-bold" >settle</button>
                        <button type="button" class="btn btn-primary btn-submit-green sm-text text-uppercase font-weight-bold">Submit</button>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

@push('theme-script')


<script>
var flagsUrl = '{{ url('/storage') }}';

$(document).ready(function(){
    var category = null;
        
    $.ajax({
        url: window.location.origin+'/productservice/pos/'+category,
        method: "GET",
        success:function(response){
            // $('#prod').html(result);
            $('#pro').empty();
            var bodyData = '';
             if(response.length>0){
                $.each(response[0],function(index,response){
                    let res = [];
                    res.push(response.sale_price);
                    res.push("'"+response.name+"'");
                    let myImg = !response.attachment ? 'defualt-img.jpg': response.attachment;
                    res.push(response.id);
                    bodyData+= '<div class="col-sm-6 col-md-6 col-lg-3 col-xl-3" onclick="addProduct('+res+')">'
                        +"<div class='product-container'>"+
                        "<div class='product-img'><img class='pos-image' src='"+flagsUrl+'/'+myImg+"'></div>"+
                        "<div class='product-title'>"+response.name+"<br>In Stock: "+response.sale_price+"</div>"+
                        "</div>"+"</div>";
            });
            $('#pro').append(bodyData);
         }
        }
    });
});
function addProduct(price,name,id){
    console.log(price,name,id);
    // let prod = JSON.stringify(product) ;
    // console.log('product',JSON.stringify(product))
    // console.log('product',prod.sale_price)
    // console.log('product',prod[0]['sale_price'])
    let html = '';
    let price_corr =$('#price_correction');
    // console.log( $( "div:contains('Orange')" ).include);
    console.log( );
    let myId =id;
    let product =`'prod-'+${id}`
    if($("#prod-"+id).length ==0){

    html+="<div class='product-detail-tbl-row ' id='prod-"+id+"''>"+
        "<div class='product-detail-tbl-cell text-center'>1</div>"
        +"<div class='product-detail-tbl-cell pro-name'>"+name +
        '<div class="pro-cross" id="prod-'+id+'" onclick="deleteProduct('+"'"+id+"'"+')">X</div></div>'
        +"<div class='product-detail-tbl-cell qty'><input type='number'  min='1' onkeyup='onChangeQty("+'"'+id+','+price+'"'+")' onclick='onChangeQty("+'"'+id+','+price+'"'+")' class='text-center 'id='pro-qty_"+id+"' name='pro-qty_"+id+"'  value='1'></div>"
        +" <div class='product-detail-tbl-cell  cell-price pro-price-"+id+"'  id='pro-price_"+id+"' text-center'>"+parseInt(price)+"</div>"
        +'</div>';
        $('#price_correction').append(html);

    }else{
        var prod =$('#pro-qty_'+id).val();
        // console.log('22',prod)
        let multiple = parseInt(prod)+1;
        // console.log( multiple)
        var mul = parseInt(multiple);
        $('#pro-qty_'+id).val(multiple);
        item_price  =  parseInt(price * multiple);
        var test = $('#pro-price_'+id).text(price * multiple);
        // console.log('res',test)
    }
    var sum=0;
    $('.product-detail-tbl-cell-price').each(function(){
        sum += parseFloat($(this).text());
    });
    $('#total_product_price').text('$ '+sum)
    $('#totalPrice').text('$ '+sum)
}



function deleteProduct(cart_id) {
    $('#prod-'+cart_id).remove();
    totalSumOfPrice();
}

function totalSumOfPrice(cart_id) {
    var sum=0;
    $('.product-detail-tbl-cell-price').each(function(){
        sum += parseFloat($(this).text());
    });
    $('#total_product_price').text('$ '+sum)
    $('#totalPrice').text('$ '+sum)

}



function updatePrice(cart_id) {
    var prod =$('#pro-qty_'+id).val();
        // console.log('22',prod)
        let multiple = parseInt(prod)+1;
        // console.log( multiple)
        var mul = parseInt(multiple);
        $('#pro-qty_'+id).val(multiple);
        item_price  =  parseInt(price * multiple);
        var test = $('#pro-price_'+id).text(price * multiple);
}


function onChangeQty(id) {
    let re= id.split(',');
    let p_id =re[0];
    let p_price =re[1];
    // alert(p_price)

    var prod =$('#pro-qty_'+p_id).val();
        let multiple = parseInt(prod)+1;
        var mul = parseInt(multiple);
        $('#pro-qty_'+p_id).val(multiple);
        item_price  =  parseInt(p_price * multiple);
        var test = $('#pro-price_'+p_id).text(p_price * multiple);
    var sum=0;
    $('.product-detail-tbl-cell-price').each(function(){
        sum += parseFloat($(this).text());
    });
    $('#total_product_price').text('$ '+sum)
    $('#totalPrice').text('$ '+sum)
}


function save_to_db(cart_id, new_quantity) {
	var inputQuantityElement = $("#input-quantity-"+cart_id);
    $.ajax({
		url : "update_cart_quantity.php",
		data : "cart_id="+cart_id+"&new_quantity="+new_quantity,
		type : 'post',
		success : function(response) {
			$(inputQuantityElement).val(new_quantity);
		}
	});
}

$('.pro-cross').on('click',function(event) {
    alert(event.target.id)

});

$('#category').on('change',function() {
        if($(this).val() != '')
        {
            var category = $("#category").val();
            $.ajax({
                url: window.location.origin+'/productservice/pos/'+category,
                method: "GET",
                success:function(response){
                    $('#pro').empty();

            var bodyData = '';
            if(response.length>0){
                $.each(response[0],function(index,response){
                    let res = [];
                    let myImg = !response.attachment ? 'defualt-img.jpg': response.attachment;
                    res.push(response.sale_price);
                    res.push("'"+response.name+"'");
                    res.push(response.id);
                    bodyData+='<div class="col-sm-6 col-md-6 col-lg-3 col-xl-3" onclick="addProduct('+res+')">'+
                        "<div class='product-container'>"+
                        "<div class='product-img'><img class='pos-image' src='"+flagsUrl+'/'+myImg+"'></div>"+
                        "<div class='product-title'>"+response.name+"<br>In Stock: "+parseInt(response.sale_price)+"</div>"+
                        "</div>"+"</div>";
            });
            $('#pro').append(bodyData);
         }
                }

            });
        }else{

        }
    });

</script>
@endpush

