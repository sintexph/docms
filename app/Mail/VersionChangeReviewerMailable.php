<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\DocumentReviewer;

class VersionChangeReviewerMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $receiver;
    public $title;
    public $system;
    public $section;
    public $category;
    public $document_number;
    public $version_number;
    public $creator;
    public $document_reviewer_id;

    /**
     * Create a new message instance.
     * @param $user The receiver of the email
     * @param $document_version The document version to be reviewed
     * @return void
     */
    public function __construct(DocumentReviewer $document_reviewer)
    {
        $document_version=$document_reviewer->document_version;
        $document=$document_version->document;
        $this->creator=$document_version->creator->name;
        $this->receiver=$document_reviewer->user->name;
        $this->document_reviewer_id=$document_reviewer->id;
        $this->title=$document->title;

        # Check if there are existing data and if not not then load the codes
        $this->system=$document->system!=null?$document->system->name:$document->system_code;
        $this->section=$document->section!=null?$document->section->name:$document->section_code;
        $this->category=$document->category!=null?$document->category->name:$document->category_code;


        $this->document_number=$document->document_number;
        $this->version_number=$document_version->version;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.version_changed_reviewer')
        ->subject($this->document_number.'-'.$this->title.' was modified.');
    }
}
