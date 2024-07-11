<?php
namespace App\Http\Livewire;

use App\Models\Item as ItemModel;
use App\Models\Category as CategoryModel;
use App\Models\Brand as BrandModel;
use App\Models\Measurement as MeasurementModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;

class Item extends Component
{
    public $page_action = [];

    //public variables basic
    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";


    //new variable data management
    public $new_item;
    public $new_category;
    public $new_brand;
    public $new_measure;
    public $new_measurement;

    // for dropwdon
    public $categories = [];
    public $brands = [];
    public $measurements = [];

    public $data_count = 0;

    // for filters
    public $filter_categories = [];
    public $filter_brands = [];
    public $select_category = 0;
    public $select_brand = 0;

    public $search_barcode;


    //open model insert
    public function openModel()
    {
        $this->clearData();
        $this->showError = false;

        $this->dispatchBrowserEvent('insert-show-form');
    }

    //close model insert
    public function closeModel()
    {
        $this->dispatchBrowserEvent('insert-hide-form');
    }

    //open model delete
    public $delete_id = 0;
    public function deleteOpenModel($id)
    {
        $this->delete_id = $id;
        $this->dispatchBrowserEvent('delete-show-form');
    }

    #delete Data
    public function deleteRecord()
    {
        # code...
        if ($this->delete_id != 0) {
            $item = ItemModel::find($this->delete_id);
            $item->delete();
            $this->deleteCloseModel();

            //show Delete message
            session()->flash('del_message', 'Record Deleted!');
        }
    }

    //close model close
    public function deleteCloseModel()
    {
        $this->dispatchBrowserEvent('delete-hide-form');
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

    //fetch data from db
    public function fetchData()
    {
        #load data
        $this->categories = CategoryModel::orderBy('category', 'asc')->latest()->get();

        # all records find brand
        $this->brands = BrandModel::orderBy('brand', 'asc')->latest()->get();

        #all record find measurements
        $this->measurements = MeasurementModel::latest()->get();

    }

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;

        # validation
        $this->validate(
            [
                'new_measure' => 'required',
                'new_category' => 'required',
                'new_brand' => 'required',
                'new_measurement' => 'required',
                'new_item' => $this->key == 0 ? 'required|unique:items,item_name' : 'required|unique:items,item_name,' . $this->key
            ],
            [
                'new_item.unique' => 'An item with this name already exists.'
            ]
        );

        if ($this->key == 0) {
            #create
            $data = new ItemModel();
            $data->category_id = $this->new_category;
            $data->brand_id = $this->new_brand;
            $data->measurement_id = $this->new_measurement;
            $data->item_name = $this->new_item;
            $data->measure = $this->new_measure;
            $data->save();
            session()->flash('message', 'Item Successfully Created!.');
        } else {
            //update
            $data = ItemModel::find($this->key);
            $data->category_id = $this->new_category;
            $data->brand_id = $this->new_brand;
            $data->measurement_id = $this->new_measurement;
            $data->item_name = $this->new_item;
            $data->measure = $this->new_measure;
            $data->save();
            session()->flash('message', 'Item Successfully Updated!.');
        }
        //clear data
        $this->clearData();

    }

    //fill box forupdate
    public function updateRecord($id)
    {

        $item = ItemModel::find($id);
        $this->new_brand = $item->brand_id;
        $this->new_category = $item->category_id;
        $this->new_measurement = $item->measurement_id;
        $this->new_measure = $item->measure;
        $this->new_item = $item->item_name;
        $this->key = $id;

        $this->showError = false;
        // open model in edit
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //clear data
    public function clearData()
    {
        # emty field
        // $this->new_item = "";
        // $this->new_measure = "";
        // $this->new_brand = "";
        // $this->new_category = "";
        // $this->new_measurement = "";
        $this->key = 0;
    }

    public function clearFilter()
    {
        $this->select_category = 0;
        $this->select_brand = 0;
        $this->search_barcode = null;
    }


    #comon controls
    public function pageAction()
    {
        # code...
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
        $this->filter_categories = DB::table('categories')->select('id', 'category')->orderBy('category', 'asc')->get();
        $this->filter_brands = DB::table('brands')->select('id', 'brand')->orderBy('brand', 'asc')->get();

        $list_data = DB::table('items')
            ->select('items.*', 'categories.category as category_name', 'brands.brand as brand_name', 'measurements.measurement as measurement_name', 'invoice_items.barcode')
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'items.brand_id')
            ->leftJoin('measurements', 'measurements.id', '=', 'items.measurement_id')
            ->leftJoin('invoice_items', 'items.id', '=', 'invoice_items.item_id')
            ->where('items.item_name', 'LIKE', "%{$this->searchKey}%")
            ->distinct();
        if ($this->select_category) {
            $list_data = $list_data->where('items.category_id', $this->select_category);
        }

        if ($this->select_brand) {
            $list_data = $list_data->where('items.brand_id', $this->select_brand);
        }

        if ($this->search_barcode) {
            $list_data = $list_data->where('invoice_items.barcode', '=', $this->search_barcode);
        }

        $list_data = $list_data->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        $this->data_count = DB::table('items')
            ->count('id');

        $filter_data_count = DB::table('items');
        if ($this->select_category) {
            $filter_data_count = $filter_data_count->where('items.category_id', $this->select_category);
        }
        if ($this->select_brand) {
            $filter_data_count = $filter_data_count->where('items.brand_id', $this->select_brand);
        }
        if ($this->select_category && $this->select_brand) {
            $filter_data_count = $filter_data_count->where('items.category_id', $this->select_category)
                ->where('items.brand_id', $this->select_brand);
        }
        $filter_data_count = $filter_data_count->count('id');

        $this->fetchData();
        $this->pageAction();
        return view('livewire.item', compact('list_data', 'filter_data_count'))->layout('layouts.master');
    }
}
