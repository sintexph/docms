<?php

namespace App\Http\Middleware;

use Closure;
use App\DocumentVersion;
use Auth;
use App\Helpers\Access; 

class DocumentViewingMiddleware
{
    /**
     * Handle permission in viewing the document by its access type
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $document_version=DocumentVersion::find($request['id']);
        abort_if($document_version==null,404);
        
        # Get the document based document version
        $document=$document_version->document;
        abort_if($document==null,404);
       
        $user=auth()->user();

        # If there is a user then check for permission if can view
        if($user!=null)
        {
            if(!$user->can('review',$document_version) && !$user->can('view',$document) && !$user->can('approve',$document_version))
                abort(403,'Document is not available for viewing.');
        }
        elseif($document->access==Access::_CONFIDENTIAL) {
            abort(403,'Document is not available for viewing.');
        }
        

        # Cannot view if not approved or document archived
        if($document->archived==true || $document_version->released==false || $document_version->approved==false || $document_version->reviewed==false)
            abort(403,'Document is not available for viewing.');

        return $next($request);
    }
}
