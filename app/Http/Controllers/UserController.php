<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class UserController extends Controller
{
    //Show Register/Create form

    public function create(){
        return view('users.register');
    }

    //create a new user
    public function store(Request $request){
        $formFields = $request->validate([
            'name'=>['required','min:3'],
            'email'=>['required','email', Rule::unique('listings','company')],
            'password'=>'required|confirmed|min:6'

        ]);

        //Hash password
        $formFields['password']=bcrypt( $formFields['password']);

        //user create

        $user=User::create($formFields);

        //log in

        auth()->login($user);

        return redirect('/')->with('message' ,'user created successfully and login');

    }


    //logout user
    public function logout(Request $request){

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message','you have been logout');

    }



        //Show login form

        public function login(){
            return view('users.login');
        }

        //authenticate user
        public function authenticate(Request $request)
        {
            $formFields = $request->validate([
                'email'=>['required','email'],
                'password'=>'required'
            ]);

            if (auth()->attempt($formFields)){
                $request->session()->regenerate();
                return redirect('/')->with('message','your now logged in');
            }

            return back()->withErrors(['email'=>'invalid information'])->onlyInput('email');
        }


}
