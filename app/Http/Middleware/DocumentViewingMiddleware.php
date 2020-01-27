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

        if($user!=null)
        {
            # Approver and reviewer of the document version can view
            if($document_version->reviewers()->where('user_id','=',$user->id)->exists()==true || $document_version->approvers()->where('user_id','=',$user->id)->exists()==true)
                return $next($request);

            
            abort_if($user->can('view',$document)==false,403);
        }
        

        # Cannot view if not approved or document archived
        if($document->archived==true || $document_version->released==false || $document_version->approved==false || $document_version->reviewed==false)
            abort(403,'Document is not available for viewing.');

    
         
        return $next($request);
    }
}
