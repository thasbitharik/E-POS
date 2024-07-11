<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Route;

class StockByProperty extends Component
{
    public $propertyId = 0;

    public $start_date;
    public $propertyData;
    public $page_action = [];

    public $list_data = [];
    public $searchKey;

    public function mount($id)
    {
        $this->propertyId = $id;
        if ($this->propertyId) {
            $this->propertyData = Property::find($this->propertyId);
        }
        $this->start_date = date('Y-m-d');
    }

    public function fetchData()
    {

        if ($this->searchKey) {
            $this->list_data = DB::table('branch_stores')
                ->select('branch_stores.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name', 'items.id', 'items.item_name', 'items.measure', 'invoice_items.id', 'invoice_items.item_id', DB::raw('SUM(branch_stores.transfer_qty) as total_transfer'), DB::raw('SUM(branch_stores.quantity) as stock_qty'))
                ->leftJoin('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id', )
                ->leftJoin('items', 'invoice_items.item_id', '=', 'items.id', )
                ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
                ->leftJoin('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->where('branch_stores.property_id', '=', $this->propertyId)
                ->where('items.item_name', 'LIKE', '%' . $this->searchKey . '%')
                ->OrWhere('invoice_items.barcode', 'LIKE', '%' . $this->searchKey . '%')
                ->groupBy('branch_stores.invoice_items_id')
                ->get();
        } else {
            $this->list_data = DB::table('branch_stores')
                ->select('branch_stores.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name', 'items.id', 'items.item_name', 'items.measure', 'invoice_items.id', 'invoice_items.item_id', DB::raw('SUM(branch_stores.transfer_qty) as total_transfer'), DB::raw('SUM(branch_stores.quantity) as stock_qty'))
                ->leftJoin('invoice_items', 'invoice_items.id', '=', 'branch_stores.invoice_items_id', )
                ->leftJoin('items', 'invoice_items.item_id', '=', 'items.id', )
                ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
                ->leftJoin('measurements', 'measurements.id', '=', 'items.measurement_id')
                ->where('branch_stores.property_id', '=', $this->propertyId)
                ->groupBy('branch_stores.invoice_items_id')
                ->get();
        }
    }

    #comon controls
    public function pageAction()
    {
        $x = Route::currentRouteName();
        $data = DB::table('access_points')
            ->select('access_points.access_model_id', 'access_points.id as access_point', 'access_points.value', 'access_models.access_model')
            ->join('access_models', 'access_points.access_model_id', '=', 'access_models.id')
            ->where('access_models.access_model', '=', $x)
            ->get();

        $access = session()->get('Access');
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i]->access_point, $access)) {
                array_push($this->page_action, $data[$i]->value);
            }
        }
    }
    public function render()
    {
        $this->fetchData();
        $this->pageAction();

        return view('livewire.stock-by-property')->layout('layouts.master');
    }
}