<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PrintBarcode extends Component
{

    public $item_id;
    public $items=[];
    public function mount($id)
    {
        # code...
        $this->item_id=$id;
        $this->items = DB::table('invoice_items')
        ->select('invoice_items.*','items.item_name','items.measure', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name')
        ->join('items', 'items.id', '=', 'invoice_items.item_id')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->join('brands', 'brands.id', '=', 'items.brand_id')
        ->join('measurements', 'measurements.id', '=', 'items.measurement_id')
        ->where('invoice_items.id','=',$id)
        ->get();


    }

    public function render()
    {
        return view('livewire.print-barcode')->layout('layouts.master');
    }
}
