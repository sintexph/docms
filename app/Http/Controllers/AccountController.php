<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('accounts.index');
    }

    /**
     * Get the account information
     * @param $id The database id of the account
     */
    public function fetch($id)
    {
        $account=User::find($id);
        abort_if($account==null,404,'Account could not be found!');

        return json_encode($account);

    }
    public function list(Request $request)
    {
        $find=$request['find'];

        $accounts=User::on();

        if(!empty($find))
        {
            $accounts->where(function($condition)use($find){
                $condition->orWhere('name','like','%'.$find.'%')
                ->orWhere('email','like','%'.$find.'%')
                ->orWhere('position','like','%'.$find.'%')
                ->orWhere('username','like','%'.$find.'%');
            });
        }

        return datatables($accounts)->rawColumns([
            'perm_administrator',
            'perm_approver',
            'perm_reviewer',
        ])->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|unique:users,email',
            'password'=>'required',
            'position'=>'required',
            'username'=>'required|unique:users,username',
            'perm_administrator'=>'required',
            'perm_approver'=>'required',
            'perm_reviewer'=>'required',

            'notify_changes'=>'required',
            'notify_followups'=>'required',
            'notify_comments'=>'required',
            'notify_reviewed'=>'required',
            'notify_approved'=>'required',
            'notify_to_approve'=>'required',
            'notify_to_review'=>'required',
        ]);

        User::create([
            'name'=>strtoupper($request['name']),
            'email'=>$request['email'],
            'password'=>bcrypt($request['password']),
            'position'=>$request['position'],
            'username'=>$request['username'],
            'created_by'=>auth()->user()->name,
            'perm_administrator'=>$request['perm_administrator'],

            'perm_approver'=>$request['perm_approver'],
            'perm_reviewer'=>$request['perm_reviewer'],

            'notify_changes'=>$request['notify_changes'],
            'notify_followups'=>$request['notify_followups'],
            'notify_comments'=>$request['notify_comments'],
            'notify_reviewed'=>$request['notify_reviewed'],
            'notify_approved'=>$request['notify_approved'],
            'notify_to_approve'=>$request['notify_to_approve'],
            'notify_to_review'=>$request['notify_to_review'],


        ]);

        return response()->json(['message'=>'Account has been successfully created!']);
    }
    /**
     * Update the account information
     * @param $request Holds the data to be updated
     * @param $id The database id of the account
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$id,
            'position'=>'required',
            'username'=>'required|unique:users,username,'.$id,
            'perm_administrator'=>'required',
            'perm_approver'=>'required',
            'perm_reviewer'=>'required',

            'notify_changes'=>'required',
            'notify_followups'=>'required',
            'notify_comments'=>'required',
            'notify_reviewed'=>'required',
            'notify_approved'=>'required',
            'notify_to_approve'=>'required',
            'notify_to_review'=>'required',
        ]);

        $account=User::find($id);
        abort_if($account==null,404,'Account could not be found!');

        
        $account->name=strtoupper($request['name']);
        $account->email=$request['email'];
        $account->position=$request['position'];
        $account->username=$request['username'];
        $account->edited_by=auth()->user()->name;
        $account->perm_administrator=$request['perm_administrator'];

        $account->perm_approver=$request['perm_approver'];
        $account->perm_reviewer=$request['perm_reviewer'];

        $account->notify_changes=$request['notify_changes'];
        $account->notify_followups=$request['notify_followups'];
        $account->notify_comments=$request['notify_comments'];
        $account->notify_reviewed=$request['notify_reviewed'];
        $account->notify_approved=$request['notify_approved'];
        $account->notify_to_approve=$request['notify_to_approve'];
        $account->notify_to_review=$request['notify_to_review'];

        if(!empty($request['password']))
            if(\Hash::needsRehash($request['password']))
                $account->password=bcrypt($request['password']);

        $account->save();
        

        return response()->json(['message'=>'Account has been successfully updated!']);
    }
    public function delete($id)
    {
        $account=User::find($id);
        abort_if($account==null,404,'Account could not be found!');
        $account->delete();

        return response()->json(['message'=>'Account has been successfully deleted!']);
    }
}
