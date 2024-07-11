<?php
namespace App\Http\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\AccessModel as AccessDataModel;
use App\Models\AccessPoint as AccessDataPoint;
use App\Models\Permission as PermissionModel;
use App\Models\UserType as UserTypeModel;

class Permission extends Component
{
    public $user_type_id;
    public $user_type;
    public $permissionData=[];
    public $access_model;
    // public $access_model_all;
    public $access_point;
    public $PermissionsCollect;
    public $dis;

    //collect id
    public function mount($id)
    {
        # code...
        $this->user_type_id=$id;
        $this->fetchData();
    }

    //fetch data
    public function fetchData()
    {
        #all access model data
        $this->access_model=AccessDataModel::get();
        $this->access_point=AccessDataPoint::get();
        $this->user_type=UserTypeModel::find($this->user_type_id);
        # code...
       $this->permissionData=DB::table('permissions')
        ->select('permissions.*')
        ->where('permissions.user_type_id','=',$this->user_type_id)
        ->get();
      // return dd($this->permissionData);

    }

    public function givePermission($id)
    {
        # code...
        //check give permission or not
        if(count($this->permissionData)!=0){
       // return dd($this->permissionData[0]['permission']);
       $val = json_decode($this->permissionData[0]['permission']);
        $count=sizeof($val);
        // return dd($count);


        if(in_array($id, $val)){

            if (($key = array_search($id, $val)) !== false) {
                unset($val[$key]);
                $val = array_values($val);
            }
         // remove from permission
        //   return dd($val[1]);

        }else{
         //give permission
         $val[$count]=$id;
        }

        $countx=sizeof($val);
        //count details
        //return dd($countx);
        $det="";

        for($i=0; $i<$countx; $i++){

            if($i==0){
                $det=$val[$i];
            }else{
                $det=$det.','.$val[$i];
            }
        }

        $finalData= "[".$det."]";
        $this->dis=$finalData;
        //update permission into user permission record
          $data=PermissionModel::find($this->permissionData[0]['id']);
          $data->permission=$finalData;
          $data->save();
    }else{
        $finalData= "[".$id."]";
        $data = new PermissionModel();
        $data->permission=$finalData;
        $data->user_type_id=$this->user_type_id;
        $data->save();
    }

    }

    public function render()
    {
        $this->fetchData();
        return view('livewire.permission')->layout('layouts.master');
    }
}
