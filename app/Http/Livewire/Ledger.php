<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\CashInHistoryModel;
use App\Models\CashOutHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Route;

class Ledger extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];
    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";
    public $start_date;
    public $end_date;
    public $propertyId;
    public $property_name;
    public $select_property;
    public $card_payment;
    public $credit_cus;
    public $filter_properties = [];
    public $cashin_data = [];
    public $cashout_data = [];

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


    public function fetchData()
    {

    }

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
        $this->cashin_data = DB::table('cash_in_histories')
                                ->select('cash_in_histories.*', 'properties.property_name', 'users.name', 'counters.counter', 'customers.customer_name', 'incomes.title')
                                ->leftJoin('properties', 'cash_in_histories.property_id','properties.id')
                                ->leftJoin('users', 'cash_in_histories.staff_id','users.id')
                                ->leftJoin('counters', 'cash_in_histories.counter_id','counters.id')
                                ->leftJoin('customers', 'cash_in_histories.customer_id','customers.id')
                                ->leftJoin('incomes', 'cash_in_histories.income_id','incomes.id')
                                ->where('cash_in_histories.date', '>=', $this->start_date)
                                ->where('cash_in_histories.date', '<=', $this->end_date)
                                ->get();

        $this->cashout_data =DB::table('cash_out_histories')
                                ->select('cash_out_histories.*', 'properties.property_name', 'expences.title')
                                ->leftJoin('properties', 'cash_out_histories.property_id','properties.id')
                                ->leftJoin('expences', 'cash_out_histories.expence_id','expences.id')
                                ->where('cash_out_histories.date', '>=', $this->start_date)
                                ->where('cash_out_histories.date', '<=', $this->end_date)
                                ->get();

        $this->card_payment = DB::table('bills')
                                ->select('bills.*')
                                ->where('bills.payment_type','=', 'Card')
                                ->where('bills.date', '>=', $this->start_date)
                                ->where('bills.date', '<=', $this->end_date)
                                ->get();


        $this->credit_cus = DB::table('bills')
                                ->select('bills.*','customers.customer_name')
                                ->leftJoin('customers', 'bills.customer_id','customers.id')
                                ->where('bills.payment_type','=', 'Credit')
                                ->where('bills.date', '>=', $this->start_date)
                                ->where('bills.date', '<=', $this->end_date)
                                ->get();
                        // dd( $this->card_payment);


        } else {
         $this->cashin_data = [];
         $this->cashout_data = [];
         $this->card_payment = [];
         $this->card_payment = [];

        }

        return view('livewire.ledger')->layout('layouts.master');
    }
}
