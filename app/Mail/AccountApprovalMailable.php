<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class AccountApprovalMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $receiver;
    public $registered_user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,User $registered_user)
    {
        $this->receiver=$user->name;
        $this->registered_user=$registered_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.registration.approval')
                    ->subject('DMS Account Approval');
    }
}
