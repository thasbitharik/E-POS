<?php
namespace App\Http\Livewire;

use App\Models\PurchaseInvoice as PurchaseInvoiceModel;
use Illuminate\Support\Facades\Redirect;
use App\Models\Company as CompanyModel;
use App\Models\Dealer as DealerModel;
use App\Models\DealerCredit;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\TemTransfer;
use App\Models\InvoiceItem as InvoiceItemModel;
use Illuminate\Support\Facades\Auth;
use Route;

class PurchaseInvoice extends Component
{
    public $page_action = [];
    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";
    //for filtering
    public $filter_companies = [];
    public $filter_dealers = [];
    public $select_company = 0;
    public $select_dealer = 0;
    public $select_date;
    //new variable data management
    public $companies = [];
    public $dealers = [];
    public $new_invoice_number;
    public $new_company;
    public $new_dealer;
    public $new_amount;
    public $new_discount = 0;
    public $new_vat = 0;
    public $new_return_amount = "";
    public $new_description;
    public $new_date;
    public $invoice_id = 0;
    public $invoice_items = [];
    public $not_transfered = false;
    public $has_return = false;
    public $is_credit = false;
    public $data_count = 0;
    public $view = [];
    public $view_id = 0;
    public $transfer_id = 0;
    public $contain_return = false;
    public $contain_credit = false;

    public function openViewModel($id)
    {
        $this->view = PurchaseInvoiceModel::select('purchase_invoices.*', 'companies.company_name', 'dealers.name as dealer_name')
            ->join('companies', 'purchase_invoices.company_id', 'companies.id')
            ->join('dealers', 'purchase_invoices.dealer_id', 'dealers.id')
            ->where('purchase_invoices.id', '=', $id)
            ->get();
        $this->dispatchBrowserEvent('view-show-form');
    }

    public function viewCloseModel()
    {
        $this->dispatchBrowserEvent('view-hide-form');
        $this->view = [];
    }

    public function openTransferConfirmModel($id)
    {
        $this->transfer_id = $id;
        $this->dispatchBrowserEvent('transfer-confirm-show');
    }

    public function closeTransferConfirmModel()
    {
        $this->dispatchBrowserEvent('transfer-confirm-hide');
    }

    //open model insert
    public function openModel()
    {
        $this->clearData();
        $this->showError = false;
        $this->has_return == false;
        $this->is_credit = false;

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
            $invoice = PurchaseInvoiceModel::find($this->delete_id);
            $invoice->delete();
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
        $this->companies = CompanyModel::select('companies.*')->orderBy('companies.company_name', 'ASC')->get();
        $this->dealers = DealerModel::where('company_id', '=', $this->new_company)->orderBy('dealers.name', 'ASC')->get();

        if ($this->has_return == false) {
            $this->new_return_amount = "";
        }
    }

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;
        //validate data
        $this->validate(
            [
                'new_company' => 'required',
                'new_dealer' => 'required',
                'new_invoice_number' => 'required|max:255',
                'new_amount' => 'required',
                'new_date' => 'required',
                'new_return_amount' => $this->has_return == true ? 'required|numeric' : '',
                'new_description' => 'max:255|'
            ]
        );

        $net_total_amount = ($this->new_amount - ($this->new_discount == "" ? 0 : $this->new_discount)) + ($this->new_vat == "" ? 0 : $this->new_vat);

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new PurchaseInvoiceModel();
            $data->invoice_number = $this->new_invoice_number;
            $data->company_id = $this->new_company;
            $data->dealer_id = $this->new_dealer;
            $data->description = $this->new_description;
            $data->amount = $this->new_amount;
            if ($this->has_return == true) {
                $data->has_return = 1;
                $data->return_amount = $this->new_return_amount;
            } else {
                $data->has_return = 0;
                $data->return_amount = 0;
            }
            if ($this->is_credit == true) {
                $data->is_credit = 1;
            }
            $data->discount = $this->new_discount;
            $data->vat = $this->new_vat;
            $data->net_amount = $net_total_amount;
            $data->invoice_date = $this->new_date;
            $data->save();

            if ($this->is_credit == true) {
                $dealer_credit = new DealerCredit();
                $dealer_credit->property_id = Auth::user()->property_id;
                $dealer_credit->company_id = $this->new_company;
                $dealer_credit->dealer_id = $this->new_dealer;
                $dealer_credit->purchase_invoice_id = $data->id;
                $dealer_credit->auth_id = Auth::user()->id;
                $dealer_credit->invoice_date = $this->new_date;
                $dealer_credit->credit_amount = $net_total_amount;
                $dealer_credit->save();

            }

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = PurchaseInvoiceModel::find($this->key);
            $data->invoice_number = $this->new_invoice_number;
            $data->company_id = $this->new_company;
            $data->dealer_id = $this->new_dealer;
            $data->description = $this->new_description;
            $data->amount = $this->new_amount;
            $data->discount = $this->new_discount;
            if ($this->has_return == true) {
                $data->has_return = 1;
                $data->return_amount = $this->new_return_amount;
            } else {
                $data->has_return = 0;
                $data->return_amount = 0;
            }
            if ($this->is_credit == true) {
                $data->is_credit = 1;
            }
            $data->vat = $this->new_vat;
            $data->net_amount = $net_total_amount;
            $data->invoice_date = $this->new_date;
            $data->save();

            if ($this->is_credit == true) {
                $dealer_credit = new DealerCredit();
                $dealer_credit->property_id = Auth::user()->property_id;
                $dealer_credit->company_id = $this->new_company;
                $dealer_credit->dealer_id = $this->new_dealer;
                $dealer_credit->purchase_invoice_id = $data->id;
                $dealer_credit->auth_id = Auth::user()->id;
                $dealer_credit->invoice_date = $this->new_date;
                $dealer_credit->credit_amount = $net_total_amount;
                $dealer_credit->save();

            }

            //show success message
            session()->flash('message', ' Update Successfuly!');

            //clear data
            $this->clearData();
        }
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $data = PurchaseInvoiceModel::find($id);
        $this->new_invoice_number = $data->invoice_number;
        $this->new_company = $data->company_id;
        $this->new_dealer = $data->dealer_id;
        $this->new_amount = $data->amount;
        $this->new_discount = $data->discount;
        $this->has_return = $data->has_return;
        $this->is_credit = $data->is_credit;
        $this->new_return_amount = $data->return_amount;
        $this->new_vat = $data->vat;
        $this->new_date = $data->invoice_date;
        $this->new_description = $data->description;
        $this->key = $id;

        $this->showError = false;
        // open model in edit
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //clear data
    public function clearData()
    {
        # emty field
        $this->key = 0;
        $this->new_invoice_number = "";
        $this->new_company = "";
        $this->new_dealer = "";
        $this->new_description = "";
        $this->new_amount = "";
        $this->new_discount = 0;
        $this->has_return = false;
        $this->is_credit = false;
        $this->new_return_amount = "";
        $this->new_vat = 0;
        $this->new_date = "";
    }

    public function transferStocks()
    {
        $this->invoice_id = $this->transfer_id;

        $this->invoice_items = DB::table('invoice_items')
            ->select('invoice_items.*')
            ->where('invoice_items.invoice_id', $this->invoice_id)
            ->get();

        foreach ($this->invoice_items as $invoice_item) {
            $check = TemTransfer::where('property_id', '=', Auth::user()->property_id)
                ->where('auth_id', '=', Auth::user()->id)
                ->where('invoice_items_id', '=', $invoice_item->id)
                ->where('invoice_id', $this->invoice_id)
                ->first();

            // new item
            if ($check) {
                $tem = TemTransfer::find($check->id);
                $tem->buy_price = $invoice_item->buy;
                $tem->sell_price = $invoice_item->sell;
                $tem->quantity = $check->quantity + $invoice_item->quantity;
                $tem->save();
            } else {
                $tem = new TemTransfer();
                $tem->property_id = Auth::user()->property_id;
                $tem->auth_id = Auth::user()->id;
                $tem->invoice_id = $this->invoice_id;
                $tem->invoice_items_id = $invoice_item->id;
                $tem->buy_price = $invoice_item->buy;
                $tem->sell_price = $invoice_item->sell;
                $tem->quantity = $invoice_item->quantity;
                $tem->save();
            }

            // update main store balance
            $main = InvoiceItemModel::find($invoice_item->id);
            $main->quantity = $main->quantity - $invoice_item->quantity;
            $main->save();
        }

        return Redirect::to('/stock-transfer/' . $this->invoice_id);
    }

    public function clearFilter()
    {
        $this->select_company = 0;
        $this->select_dealer = 0;
        $this->select_date = null;
        $this->not_transfered = false;
        $this->contain_return = false;
        $this->contain_credit = false;
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
        $this->filter_companies = DB::table('companies')
            ->select('id', 'company_name')
            ->orderBy('company_name', 'asc')
            ->get();

        $this->filter_dealers = DB::table('dealers')
            ->select('id', 'name', 'tp')
            ->where('dealers.company_id', '=', $this->select_company)
            ->orderBy('name', 'asc')->get();

        $this->data_count = DB::table('purchase_invoices')
            ->count('id');

        $list_data = DB::table('purchase_invoices')
            ->select('purchase_invoices.*', 'companies.company_name', 'dealers.name as dealer_name')
            ->leftJoin('companies', 'purchase_invoices.company_id', '=', 'companies.id')
            ->leftJoin('dealers', 'dealers.id', '=', 'purchase_invoices.dealer_id')
            ->where('purchase_invoices.invoice_number', 'LIKE', "%{$this->searchKey}%");
        if ($this->select_company) {
            $list_data = $list_data->where('purchase_invoices.company_id', $this->select_company);
        }
        if ($this->select_dealer) {
            $list_data = $list_data->where('purchase_invoices.dealer_id', $this->select_dealer);
        }

        if ($this->select_date) {
            $list_data = $list_data->where('purchase_invoices.invoice_date', $this->select_date);
        }

        if ($this->not_transfered == true) {
            $list_data = $list_data->where('purchase_invoices.is_transfered', 0);
        }
        if ($this->contain_return == true) {
            $list_data = $list_data->where('purchase_invoices.has_return', 1);
        }
        if ($this->contain_credit == true) {
            $list_data = $list_data->where('purchase_invoices.is_credit', 1);
        }
        $list_data = $list_data->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        $filter_data_count = DB::table('purchase_invoices');
        if ($this->select_company) {
            $filter_data_count = $filter_data_count->where('purchase_invoices.company_id', $this->select_company);
        }
        if ($this->select_dealer) {
            $filter_data_count = $filter_data_count->where('purchase_invoices.dealer_id', $this->select_dealer);
        }
        if ($this->select_company && $this->select_dealer) {
            $filter_data_count = $filter_data_count->where('purchase_invoices.company_id', $this->select_company)
                ->where('purchase_invoices.dealer_id', $this->select_dealer);
        }
        $filter_data_count = $filter_data_count->count('id');

        $this->fetchData();
        $this->pageAction();
        return view('livewire.purchase-invoice', compact('list_data', 'filter_data_count'))->layout('layouts.master');
    }
}
