<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\DocumentVersionComment;
use App\DocumentVersion;
use App\User;

class CommentMailable extends Mailable
{
    use Queueable, SerializesModels;

    
    public $document_version;
    public $commenter; 
    public $document_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DocumentVersion $document_version,User $commenter,$document_url)
    {
        $this->document_version=$document_version;
        $this->commenter=$commenter;
        $this->document_url=$document_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.comment')
        ->subject($this->commenter->name.' has made a comment to '.$this->document_version->document->document_number.'-'.$this->document_version->document->title);
    }
}
