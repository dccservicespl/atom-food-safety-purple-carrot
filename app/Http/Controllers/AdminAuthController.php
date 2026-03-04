<?php

namespace App\Http\Controllers;

use App\Helpers\WebHookHelper;
use App\Models\DailyMeasure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function admin_login(){
        return view('auth.login');
    }

    //LOGIN CODE
    public function admin_login_action(Request $req){
        $emp_code = $req->email;
        $password = $req->password;
        if (Auth::attempt(["email" => $emp_code, "password" => $password])) {
            $user = Auth::user();
            if ($user->status == 1) {
                // return redirect()->route('dashboard');
                return redirect()->route('work_type');
            }else{
                return redirect()->back()->with('error', 'The user is not active.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid Email Address Or Password.');
        }
    }

    public function dashboard(Request $request){

        $user_details = User::join('role_msts', 'role_msts.id','=', 'users.role_id')
                            ->where('users.id', Auth::user()->id)
                            ->select('users.*', 'role_msts.name as role_name')
                            ->first();

        if ($request->has('start_date') && $request->has('end_date')) {
            $query = DailyMeasure::query();
            $query->whereBetween('measure_date', [$request->start_date, $request->end_date]);
            $measure_date = $query->orderBy('id', 'DESC')->limit(10)->get();
        }else{
            $measure_date = DailyMeasure::orderBy('id', 'DESC')->limit(10)->get();
        }
        $get_menu_access = WebHookHelper::check_user_role_map(Auth::user()->id);
        return view('dashboard', compact('get_menu_access', 'user_details','measure_date'));
    }

    // public function company_list(){
    //     $get_route = '';
    //     return view('outlet.outlet_list', compact('get_route'));
    // }

    public function select_location(){
        $get_route = '';
        return view('outlet.select_location', compact('get_route'));
    }
    public function work_type(){
        // $get_route = route('company_list');
        $get_route = route('work_type');
        return view('outlet.work_type_list', compact('get_route'));
    }

    public function work_type_item_master(){
        $get_route = route('work_type');
        return view('outlet.work_type_item_master', compact('get_route'));
    }

    //LOGOUT CODE
    public function logout(){
        session()->flush();
        Auth::logout();
        return redirect()->route('login');
    }

    //STORING DATA IN SESSION
    public function get_session_data(Request $req){
        $req->session()->put('user_id', Auth::user()->id);
        $req->session()->put('user_name', Auth::user()->name);
        $value = session()->all();
        dd($value);
    }
}
