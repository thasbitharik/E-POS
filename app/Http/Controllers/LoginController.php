<?php

namespace App\Http\Controllers;

use App\Models\User as UserModel;
use App\Models\Permission as Permission;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function login(Request $request)
    {
        //validate the details
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:5|max:12"
        ]);

        //  $user=DB::table('users')->select('users.*')->where('email', '=', $request->input('email'))->first();

        $user = UserModel::where('email', '=', $request->input('email'))->first();
        // return $user;
        // if user
        if ($user) {
            //check entered password
            if (Hash::check($request->input('password'), $user->password)) {

                // here auth login
                Auth::login($user);

                //get permission for route access
                $permisions = Permission::Where('user_type_id', $user->user_type_id)->first();

                if ($permisions) {
                    $permis = json_decode($permisions->permission);
                    $controls = array();
                    //validate size of permis

                    for ($i = 0; $i < sizeof($permis); $i++) {
                        $data = DB::table('access_points')
                            ->select('access_points.access_model_id', 'access_models.access_model')
                            ->join('access_models', 'access_points.access_model_id', '=', 'access_models.id')
                            ->where('access_points.id', '=', $permis[$i])
                            ->get();
                        if ($data) {
                            if (in_array($data[0]->access_model, $controls)) {
                            } else {

                                array_push($controls, $data[0]->access_model);
                            }
                        }
                    }

                    $request->session()->put('Access', $permis);
                    $request->session()->put('Controls', $controls);
                }

                $user_type_root = UserType::find(Auth::user()->user_type_id);

                if (session('counter_id') != null) {
                    $path = ($user_type_root->default_root != null) ? $user_type_root->default_root : "sales-view";
                } else {
                    $path = ($user_type_root->id != 5) ? $user_type_root->default_root : "select-counter";
                }

                if (session('counter_id')) {
                    $counter_id = session()->get('counter_id');
                    session()->put('counter_id', $counter_id);
                }

                return redirect()->route($path);

            } else {
                //invalid user
                return view('auth.login')->with('fail', 'Invalid email or password!');
            }
        } else {
            // not register this email
            return view('auth.login')->with('fail', 'Invalid email or password!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        //logout
        Auth::logout();
        if (!Auth::check()) {
            return view('auth.login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
