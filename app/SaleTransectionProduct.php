<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleTransectionProduct extends Model
{
    protected $table = 'sales_transection_products';


    protected $fillable = ['sales_transection_id','name','quantity','price'];
}
