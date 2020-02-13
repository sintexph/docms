<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DocumentReviewer;
use App\Helpers\MailHelper;
use DB;
use Carbon\Carbon;

class ReviewFollowUpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followup:review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Followup the reviewing personnel that they have documents to be reviewed!';

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
        $document_reviewers=DocumentReviewer::where('reviewed',false)
        ->where(DB::raw("date(created_at)='".Carbon::now()->subDays(2)->format('Y-m-d')."'")) # 2 days from creation
        ->whereHas('document_version',function($version){ 

            $version->where('for_review',true)->whereHas('document'); 

        })->get();


        foreach ($document_reviewers as $document_reviewer) {

            MailHelper::followup_reviewer($document_reviewer);
        }
    }
}
