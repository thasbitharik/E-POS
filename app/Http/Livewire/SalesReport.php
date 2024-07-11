<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;
use Illuminate\Support\Facades\Auth;

class SalesReport extends Component
{
    public $start_date;
    public $end_date;

    public $propertyId;

    public $property_name;

    // for filter
    public $filter_properties = [];
    public $select_property;

    public function mount()
    {
        $this->propertyId = Auth::user()->property_id;
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
    }
    
    public function render()
    {
        $this->filter_properties = DB::table('properties')
        ->select('id', 'property_name')
        ->where('id', '!=', 1)
        ->get();
        
        if ($this->select_property) {
            $this->property_name = DB::table('properties')
                ->select('properties.property_name')
                ->where('id', $this->select_property)
                ->value('properties.property_name');
        }else{
            $this->property_name = DB::table('properties')
                ->select('properties.property_name')
                ->where('id', $this->propertyId)
                ->value('properties.property_name');
        }

        if ($this->start_date && $this->end_date) {
            $list_data = DB::table('bills')
                ->select('bills.*', 'properties.property_name')
                ->leftJoin('properties', 'bills.property_id', '=', 'properties.id')
                ->where('bills.date', '>=', $this->start_date)
                ->where('bills.date', '<=', $this->end_date);
            if ($this->propertyId != 1) {
                $list_data = $list_data->where('bills.property_id', $this->propertyId);
            }

            if ($this->select_property) {
                $list_data = $list_data->where('bills.property_id', $this->select_property);
            }
            $list_data = $list_data->get();

        } else {
            $list_data = [];
        }

        return view('livewire.sales-report', compact('list_data'))->layout('layouts.master');
    }
}
