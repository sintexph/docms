<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\DocumentVersion;
use App\User;

class ApprovedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $receiver;
    public $title;
    public $document_number;
    public $document_id;

    /**
     * Create a new message instance.
     * @param $user The receiver of the email
     * @param $document_version The document version that has approved
     * @return void
     */
    public function __construct(User $user,DocumentVersion $document_version)
    {
       
        $document=$document_version->document;
        $this->receiver=$user->name;
        $this->title=$document->title;
        $this->document_number=$document->document_number;
        $this->document_id=$document->id;
        

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.approved')
        ->subject($this->document_number.' - '.$this->title.' has been approved.');
    }
}
