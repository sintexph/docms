<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DocumentApprover;
use App\Helpers\MailHelper;

class ApprovalFollowUpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followup:approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Followup the approving personnel that they have documents to be approved!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $document_approvers=DocumentApprover::where('approved',false)
        ->whereHas('document_version',function($version){ 
            $version->where('for_approval',true)->whereHas('document'); 
        })->get();

        
        foreach ($document_approvers as $document_approver) {
            MailHelper::followup_approver($document_approver);
        }
    }
}
