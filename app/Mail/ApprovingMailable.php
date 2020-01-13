<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\DocumentVersion;
use App\User;
use App\DocumentApprover;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovingMailable extends Mailable
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
    public $document_approving_id;

    /**
     * Create a new message instance.
     * @param $user The receiver of the email
     * @param $document_version The document version to be approved
     * @return void
     */
    public function __construct(DocumentApprover $document_approver)
    {
        $document_version=$document_approver->document_version;
        $document=$document_version->document;
        $this->creator=$document_version->creator->name;
        $this->document_approving_id=$document_approver->id;
        $this->receiver=$document_approver->user->name;
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
        return $this->view('email.approving')
        ->subject($this->document_number.' - '.$this->title.' to be approved.');
    }
}
