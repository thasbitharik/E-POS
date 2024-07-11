<?php

namespace App\Http\Livewire;
use App\Models\DealerCredit as DealerCreditModel;
use App\Models\Property;
use App\Models\CashOutHistory;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Route;
class DealerCredit extends Component
{
    public $page_action = [];
    public $filter_companies = [];
    public $list_data = [];
    public $selected_id;
    public $dealer_credit_details = [];
    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";
    public $data_count = 0;
    public $new_paid_amount;
    public $new_paid_date;
    public $select_company;

    public function openModel()
    {
        $this->clearData();
        $this->showError = false;
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //close model insert
    public function closeModel()
    {
        $this->dealer_credit_details = [];
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
            $dealer_credit = DealerCreditModel::find($this->delete_id);
            $dealer_credit->delete();
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
    public $count=0;
    public function add()
    {
        $this->count=$this->count+10;
    }

    public function les()
    {
        $this->count=$this->count-10;
    }

    public function updateData()
    {
        $dealer_credit =DealerCreditModel::find($this->key);
        $payable_amount = $dealer_credit->credit_amount - $dealer_credit->paid_amount;
        $this->showError = true;
        $this->validate([
            "new_paid_amount" => 'required|numeric|max:' . ($payable_amount),
            "new_paid_date" => 'required'
        ],
    [
        "new_paid_amount.max" => "Paid Amount coudn't be exceeded to Payable Amount",
    ]);
        $dealer_credit->paid_amount += $this->new_paid_amount;
        $dealer_credit->paid_date = $this->new_paid_date;
        $dealer_credit->save();


        $new_cash_out = new CashOutHistory();
        $new_cash_out->cash_out_type = "Dealer Credit";
        $new_cash_out->amount = $this->new_paid_amount;
        $new_cash_out->date = $this->new_paid_date;
        $new_cash_out->property_id = Auth::user()->property_id;
        $new_cash_out->save();
        // dd($new_cash_out);



        session()->flash('message', 'Update Successfully!');
        $this->clearData();
    }

    public function clearData()
    {
        $this->key = 0;
        $this->new_paid_amount = 0.00;
        $this->new_paid_date = "";
    }
    public function fetchData()
    {
         $this->filter_companies = DB::table('companies')
        ->select('id', 'company_name')
        ->orderBy('company_name', 'asc')
        ->get();
        if (!$this->searchKey) {
            $this->list_data = DB::table('dealer_credits')
                                    ->select('dealer_credits.*','companies.company_name','dealers.name','purchase_invoices.invoice_number')
                                    ->leftJoin('companies','dealer_credits.company_id','companies.id')
                                    ->leftJoin('dealers','dealer_credits.dealer_id','dealers.id')
                                    ->leftJoin('purchase_invoices','dealer_credits.purchase_invoice_id','purchase_invoices.id');
                                    if ($this->select_company) {

                                        $this->list_data = $this->list_data->where('dealer_credits.company_id', $this->select_company);
                                    }
                                    $this->list_data = $this->list_data
                                    ->skip($this->count)
                                    ->limit(10)
                                    ->get();
        } else {
            $this->list_data = DB::table('dealer_credits')
                                    ->select('dealer_credits.*','companies.company_name','dealers.name','purchase_invoices.invoice_number')
                                    ->leftJoin('companies','dealer_credits.company_id','companies.id')
                                    ->leftJoin('dealers','dealer_credits.dealer_id','dealers.id')
                                    ->leftJoin('purchase_invoices','dealer_credits.purchase_invoice_id','purchase_invoices.id');
                                    if ($this->select_company) {

                                        $this->list_data = $this->list_data->where('dealer_credits.company_id', $this->select_company);
                                    }
                                    $this->list_data = $this->list_data
                                    ->where('dealers.name', 'LIKE', '%' . $this->searchKey . '%')
                                    ->orWhere('purchase_invoices.invoice_number', 'LIKE', '%' . $this->searchKey . '%')
                                    ->skip($this->count)
                                    ->limit(10)
                                    ->get();
        }
        $this->data_count = DB::table('dealer_credits')
        ->count('id');
    }

    public function updateRecord($id)
    {

        $dealer_credit = DealerCreditModel::find($id);
        $this->key = $id;
        $this->selected_id = $id;
        $this->showError = false;
        $this->dispatchBrowserEvent('insert-show-form');
    }
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
        if ($this->selected_id) {
            $this->dealer_credit_details = DB::table('dealer_credits')
                                        ->select('dealer_credits.*','companies.company_name','dealers.name as dealer_name','purchase_invoices.invoice_number')
                                        ->leftJoin('companies','dealer_credits.company_id','companies.id')
                                        ->leftJoin('dealers','dealer_credits.dealer_id','dealers.id')
                                        ->leftJoin('purchase_invoices','dealer_credits.purchase_invoice_id','purchase_invoices.id')
                                        ->where('dealer_credits.id',$this->selected_id)
                                        ->get();
        }

        $this->fetchData();
        $this->pageAction();
        return view('livewire.dealer-credit')->layout('layouts.master');
    }
}
