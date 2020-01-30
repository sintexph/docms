<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function show_notification_settings()
    {
        return view('profile.notifications');
    }
    public function notification_settings()
    {
        $user=auth()->user();
        return \json_encode([
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
