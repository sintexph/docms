<?php

namespace App\Observers;

use App\DocumentVersion;
use App\Helpers\State;
use DB;

class DocumentVersionObserver
{
    /**
     * Handle the document version "updated" event.
     *
     * @param  \App\DocumentVersion  $documentVersion
     * @return void
     */
    public function updated(DocumentVersion $documentVersion)
    {
        $version=DB::table('document_versions')->where('id',$documentVersion->id)->update([
            'state'=>State::getState($documentVersion)
        ]);
    }
}
