<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;
use Illuminate\Support\Facades\Auth;

class ExpenceReport extends Component
{
    public $start_date;
    public $end_date;

    public $propertyId;

    public $expence_type_name;
    public $property_name;


    // for filter
    public $filter_expence_types = [];
    public $filter_properties = [];
    public $select_expence_type;
    public $select_property;

    public function mount()
    {
        $this->propertyId = Auth::user()->property_id;
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
    }

    public function saveData()
    {
        if ($this->key == 0) {
            $this->validate(
                [
                    'start_date' => 'required',
                    'end_date' => 'required|before_or_equal:today',
                ]
            );
        }
    }

    public function render()
    {
        $this->filter_expence_types = DB::table('expence_types')->select('id', 'expence_type')->get();
        $this->filter_properties = DB::table('properties')
        ->select('id', 'property_name')
        ->where('id', '!=', 1)
        ->get();

        if ($this->select_expence_type) {
            $this->expence_type_name = DB::table('expence_types')
                ->select('expence_types.expence_type')
                ->where('id', $this->select_expence_type)
                ->value('expence_types.expence_type');
        }

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
            $list_data = DB::table('expences')
                ->select('expences.*', 'expence_types.expence_type')
                ->leftJoin('expence_types', 'expences.expence_type_id', '=', 'expence_types.id')
                ->where('expences.expence_date', '>=', $this->start_date)
                ->where('expences.expence_date', '<=', $this->end_date);
            if ($this->propertyId != 1) {
                $list_data = $list_data->where('expences.property_id', $this->propertyId);
            }

            if ($this->select_expence_type) {
                $list_data = $list_data->where('expences.expence_type_id', $this->select_expence_type);
            }

            if ($this->select_property) {
                $list_data = $list_data->where('expences.property_id', $this->select_property);
            }
            $list_data = $list_data->get();

        } else {
            $list_data = [];
        }

        return view('livewire.expence-report', compact('list_data'))->layout('layouts.master');
    }
}
