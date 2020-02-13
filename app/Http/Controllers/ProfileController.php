<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChangePasswordForm()
    {
        return view('profile.change_password');
    }
    public function changePassword(Request $request)
    {
        $this->validate($request,[
            'password'=>'required',
            'new_password'=>'required|confirmed',
        ]);

        $user=auth()->user();

        if (\Hash::check($request->password, $user->password)) {
            $user->password=bcrypt($request->new_password);
            $user->save();
        }else
            return redirect()->back()->with('error','Failed to authenticate the old password');

        return redirect()->back()->with('success','Password has been successfully changed!'); 
    }


    public function show_notification_settings()
    {
        return view('profile.notifications');
    }
    public function notification_settings()
    {
        $user=auth()->user();
        return \json_encode([
            'perm_reviewer'=>$user->perm_reviewer,
            'perm_approver'=>$user->perm_approver,
            'notify_changes'=>$user->notify_changes,
            'notify_followups'=>$user->notify_followups,
            'notify_comments'=>$user->notify_comments,
            'notify_reviewed'=>$user->notify_reviewed,
            'notify_approved'=>$user->notify_approved,
            'notify_to_approve'=>$user->notify_to_approve,
            'notify_to_review'=>$user->notify_to_review,
        ]);
    }

    public function notification_settings_save(Request $request)
    {
        $this->validate($request,[
            'notify_changes'=>'required',
            'notify_followups'=>'required',
            'notify_comments'=>'required',
            'notify_reviewed'=>'required',
            'notify_approved'=>'required',
            'notify_to_approve'=>'required',
            'notify_to_review'=>'required',
        ]);
        
        $user=auth()->user();

        $user->notify_changes=$request->notify_changes;
        $user->notify_followups=$request->notify_followups;
        $user->notify_comments=$request->notify_comments;
        $user->notify_reviewed=$request->notify_reviewed;
        $user->notify_approved=$request->notify_approved;
        $user->notify_to_approve=$request->notify_to_approve;
        $user->notify_to_review=$request->notify_to_review;
        $user->save();

        return response()->json([
            'message'=>'Notification settings saved!'
        ]);
    }
}
