<?php

namespace App\Http\Livewire;

use App\Models\Property;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;
use Illuminate\Support\Facades\Auth;

class BranchStock extends Component
{
    public $start_date;
    public $propertyData;
    public $page_action = [];

    public $list_data = [];
    public $searchKey;

    public $filter_categories = [];
    public $filter_brands = [];
    public $select_category = 0;
    public $select_brand = 0;

    public function mount()
    {
        $this->propertyData = Property::find(Auth::user()->property_id);
        $this->start_date = date('Y-m-d');
    }

    // pagination purpose code
    public $count = 0;
    public function add()
    {
        $this->count = $this->count + 10;
    }

    public function les()
    {
        $this->count = $this->count - 10;
    }


    public function fetchData()
    {
        $this->filter_categories = DB::table('categories')->select('id', 'category')->orderBy('category', 'asc')->get();
        $this->filter_brands = DB::table('brands')->select('id', 'brand')->orderBy('brand', 'asc')->get();

        if ($this->select_category != 0 || $this->select_brand != 0 || $this->searchKey != null) {
            $this->list_data = DB::table('branch_stores')
                ->select(
                    'branch_stores.*',
                    'categories.category as category_name',
                    'brands.brand as brand_name',
                    'measurements.measurement as measurement_name',
                    'items.item_name',
                    'items.measure',
                    DB::raw('SUM(branch_stores.transfer_qty) as total_transfer'),
                    DB::raw('SUM(branch_stores.quantity) as stock_qty')
                )
                ->leftJoin('invoice_items', 'branch_stores.invoice_items_id', 'invoice_items.id')
                ->leftJoin('items', 'invoice_items.item_id', 'items.id')
                ->leftJoin('categories', 'items.category_id', 'categories.id')
                ->leftJoin('brands', 'items.brand_id', 'brands.id')
                ->leftJoin('measurements', 'items.measurement_id', 'measurements.id')
                ->where('branch_stores.property_id', '=', $this->propertyData->id)
                ->where(function ($query) {
                    $query->where('items.item_name', 'LIKE', "%{$this->searchKey}%")
                        ->orWhere('invoice_items.barcode', 'LIKE', "%{$this->searchKey}%");
                });
            if ($this->select_category) {
                $this->list_data = $this->list_data->where('items.category_id', $this->select_category);
            }
            if ($this->select_brand) {
                $this->list_data = $this->list_data->where('items.brand_id', $this->select_brand);
            }

            $this->list_data = $this->list_data->groupBy('invoice_items.item_id')
                ->latest()
                ->get();
        } else {
            $this->list_data = [];
        }

    }

    public function clearFilter()
    {
        $this->select_category = 0;
        $this->select_brand = 0;
        $this->searchKey = null;
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

        return view('livewire.branch-stock')->layout('layouts.master');
    }
}
