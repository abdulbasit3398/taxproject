<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    public function customerTotalInvoiceSum($customerId)
    {
        $invoices = Invoice:: where('customer_id', $customerId)->get();
        $total    = 0;
        foreach($invoices as $invoice)
        {
            $total += $invoice->getTotal();
        }

        return $total;
    }
    public function customerTotalInvoice($customerId)
    {
        $invoices = Invoice:: where('customer_id', $customerId)->count();

        return $invoices;
    }
    public function customerOverdue($customerId)
    {
        $dueInvoices = Invoice:: where('customer_id', $customerId)->whereNotIn(
            'status', [
                        '0',
                        '4',
                    ]
        )->where('due_date', '<', date('Y-m-d'))->get();
        $due         = 0;
        foreach($dueInvoices as $invoice)
        {
            $due += $invoice->getDue();
        }

        return $due;
    }
    public function customerProposal($customerId)
    {
        $proposals = Proposal:: where('customer_id', $customerId)->orderBy('issue_date', 'desc')->get();

        return $proposals;
    }
    public function customerInvoice($customerId)
    {
        $invoices  = Invoice:: where('customer_id', $customerId)->orderBy('issue_date', 'desc')->get();
        $proposals = Proposal:: where('customer_id', $customerId)->orderBy('issue_date', 'desc')->get()->toArray();

        return $invoices;
    }
}
