<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturnHistoryReport extends Component
{
    public $select_invoice_number;
    public $start_date;
    public $end_date;

    public $invoice_number;

    public $returned_bills = [];
    public $propertyId;
    public $property_name;

    public function mount()
    {
        $this->propertyId = Auth::user()->property_id;
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
    }

    public function render()
    {
        $this->returned_bills = DB::table('bills')
            ->select('bills.id as invoice_no')
            ->where('bills.is_returned_bill', '=', 1)
            ->get();

        $this->property_name = DB::table('properties')
            ->select('properties.property_name')
            ->where('id', $this->propertyId)
            ->value('properties.property_name');

        if ($this->select_invoice_number) {
            $this->invoice_number = DB::table('bills')
                ->select('bills.id as invoice_no')
                ->where('id', $this->select_invoice_number)
                ->value('invoice_no');
        }

        if ($this->start_date && $this->end_date) {
            $return_data = DB::table('return_histories')
                ->select('return_histories.*', 'users.name as user_name', 'properties.property_name', 'bills.id as invoice_number')
                ->leftJoin('bills', 'return_histories.bill_id', '=', 'bills.id')
                ->leftJoin('users', 'return_histories.user_id', '=', 'users.id')
                ->leftJoin('properties', 'return_histories.property_id', '=', 'properties.id')
                ->where('return_histories.date', '>=', $this->start_date)
                ->where('return_histories.date', '<=', $this->end_date);

            if ($this->select_invoice_number) {
                $return_data = $return_data->where('bills.id', $this->select_invoice_number);
            }

            $return_data = $return_data->get();

        } else {
            $return_data = [];
        }
        return view('livewire.return-history-report', compact('return_data'))->layout('layouts.master');
    }
}