<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Validation\Rules\Exists;

class AuthController extends Controller
{

    public function login(){
        return view('auth.login');
    }
    public function validateLoginForm(Request $request)
    {
        // $request->validate([
        //     'email'=>'required',
        //     'password'=>'required'
        // ]);
        if(Auth::attempt($request->only('email','password'))){
            return response()->json(['success'=>'hello']);
        }
        return response()->json(['failed'=>'Oops! It seems like you don\'t have an account or your email or password is invalid.']);
        // return redirect('/admin/login')->withError('Login details are not valid!..');
        // $validator = Validator::make($request->all(),[
        //     'email'=>'required|email',
        //     'password' => 'required'
        // ]);
        // if ($validator->passes()) {
        //     $user = DB::table('users')->where('email', $request->email)->first();
        //     if ($user && Hash::check($request->password, $user->password)) {
        //         $username = $user->name;
        //         Session::put('username', $username);
        //         Session::put('id',$user->id);
        //         return response()->json(['success' => $username]);
        //     }

        //     return response()->json(['failed' => 'Oops! It seems like you don\'t have an account or your email or password is invalid.']);
        // }
        // return response()->json(['error'=>$validator->errors()]);
    }
    public function validateRegForm(Request $request){
        $request->validate([
            'username'=>'required',
            'email'=>'required|unique:users|email',
            'password' => ['required','string',Password::min(8)->letters()->numbers()->mixedCase()->symbols()]
        ]);
        User::create([
            'name'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        if(Auth::attempt($request->only('email','password'))){
            return redirect('dashboard.Main.index');
        }
        // return redirect('/admin/login')->withError('Error');
        // $validator = Validator::make($request->all(),[
        //     'username'=>'required',
        //     'email'=>'required|email',
        //     'password' => ['required','string',Password::min(8)->letters()->numbers()->mixedCase()->symbols()]
        // ]);
        // if($validator->passes()){
        //     DB::table('users')->insert([
        //         'name' => $request->username,
        //         'email' => $request->email,
        //         'password' => Hash::make($request->password)
        //     ]);
        //     return response()->json(['success'=>'hello']);
        // }
        // return response()->json(['error'=>$validator->errors()]);
    }
    public function index(){
        return view('dashboard.Main.index');
    }
    public function dashboard(){
        return view('dashboard.Components.content');
    }
    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();
        return redirect('/admin/login');
        // Session::forget('username');
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // return redirect('/admin/login');
    }
}
