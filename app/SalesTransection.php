<?php

namespace App;

use App\SaleTransectionProduct;
use Illuminate\Database\Eloquent\Model;

class SalesTransection extends Model
{
    protected $table = 'sales_transection';

    protected $fillable = ['user_id','invoice_no','first_name','last_name','address','total_amount','source','order_no','transaction_date','invoice_date','delivery_receipt','payment_method','vat','net_of_vat'];

    public function products()
    {
        return $this->hasMany(SaleTransectionProduct::class,'sales_transection_id');
    }
}
