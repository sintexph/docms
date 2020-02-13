<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Helpers\MailHelper;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'=>'required',
            'email'=>'required|unique:users,email',
            'password'=>'required|confirmed',
            'position'=>'required',
            'username'=>'required|unique:users,username',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'username'=>$data['username'],
            'position'=>$data['position'],
            'created_by'=>'Registration',
            'active'=>false,
        ]);
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = $this->validator($request->all());

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator->errors());

            $user=$this->create($request->all());

            MailHelper::user_registered($user);
            MailHelper::account_approval($user);

            DB::commit();

            return redirect()->back()->with('success','Thank you for applying for an account. Your account is currently pending approval by the site administrator. We will be sending you a message once your application will be approved.');
        } catch (\Throwable $th) {

            DB::rollBack();
            return redirect()->back()->withInput()->with('error',$th->getMessage());
        }
    }
}
