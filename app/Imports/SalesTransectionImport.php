<?php

namespace App\Imports;

use Auth;
use App\SalesTransection;
use App\SaleTransectionProduct;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SalesTransectionImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {

        $request = request()->all();
        if (!isset($row[0])) {
            return null;
        }

        $net_of_vat = $row[7] / 1.12;
        $vat = $net_of_vat * 0.12;

        $sale = SalesTransection::create([
            'user_id'     => Auth::id(),
            'invoice_no'     => $row[1],
            'first_name'    => $row[2],
            'last_name'    => $row[3],
            'address'    => $row[4],
            'order_no'    => $row[11],
            'total_amount'    => $row[7],
            'source'    => $row[10],
            'transaction_date'    => $row[12],
            'invoice_date'    => $row[13],
            'delivery_receipt'    => $row[14],
            'payment_method'    => $row[15],
            'vat'    => $vat,
            'net_of_vat'    => $net_of_vat,
        ]);

        $products = $this->product($row[5]);
        foreach ($products as $product) {
            SaleTransectionProduct::create([
                'sales_transection_id' => $sale->id,
                'name' => str_replace(' ', '', $product['name']),
                'quantity' => str_replace(' ', '', $product['qty']),
                'price' => str_replace(' ', '', $product['price']),
            ]);
        }

        return $sale;
    }

    public function product($data)
    {
        $product = str_replace("\n", "<br>", $data);
        // $_products = "STARTER - RN26EFZQUGHBAMJC ( 1 Qty) - Php6000<br>STARTER - TA4E69SN83RQYM2B ( 1 Qty) - Php6000<br>STARTER - XB4UYZ72KET31HPR ( 1 Qty) - Php6000<br>STARTER - QHJ3MEGWSCZPNVXD ( 1 Qty) - Php6000<br>STARTER - TXQZ637RAM4BYVCF ( 1 Qty) - Php6000<br>STARTER - ZU43VQ5JKM8XTWYS ( 1 Qty) - Php6000<br>STARTER - HAF8B2U3CZ9RWX75 ( 1 Qty) - Php6000<br>STARTER - AFVN61QU45EXZ3MD ( 1 Qty) - Php6000<br>STARTER - NA48YGU96WFMTR3X ( 1 Qty) - Php6000<br>STARTER - B2FSZH84RVGUPACY ( 1 Qty) - Php6000<br>";
        $objProducts = array_filter(explode("<br>", $product));
        $products = [];
        foreach ($objProducts as $key => $product) {
            $tempProdct = explode("(", $product);
            $prodName = $tempProdct[0];
            $arr = explode("Qty)", $tempProdct[1], 2);
            $arr2 = substr($tempProdct[1], strpos($tempProdct[1], "Php") + 3);
            $products[] = ['name' => $prodName, 'qty' => $arr[0], 'price' => $arr2];
        }
        $objInvoice['objProducts'] = $products;
        return $products;
    }
}
