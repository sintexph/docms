<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\DocumentReviewer;
use App\User;

class DocumentReviewedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $document_reviewer;
    public $receiver;
    public $title;
    public $document_number;
    public $document_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,DocumentReviewer $document_reviewer)
    {
        $this->document_reviewer=$document_reviewer;
        $document=$document_reviewer->document_version->document;

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
        return $this->view('emails.reviewed')
        ->subject($this->document_number.' - '.$this->title.' has been reviewed.');
    }
}
