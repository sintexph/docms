<?php

namespace App\Http\Middleware;

use Closure;
use App\Document;
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
        # Get the document based on the id
        $document=Document::find($request['id']);
        abort_if($document==null,404);
    
        $user=auth()->user();
 

        # Get the current version of the document
        $current_version=$document->current_version;
        
        if($user!=null)
        {
            # Approver and reviewer of the document version can view
            if($current_version->reviewers()->where('user_id','=',$user->id)->exists()==true || $current_version->approvers()->where('user_id','=',$user->id)->exists()==true)
                return $next($request);
        }
        
        # Cannot view if not approved
        if($current_version->released==false || $current_version->approved==false || $current_version->reviewed==false)
            abort(403,'Document is not available for viewing.');

        # Cannot view based on the type of access of the document
        if($document->access==Access::_CONFIDENTIAL)
            abort_if($user==null,403);
        elseif ($document->access==Access::_CUSTOM || $document->access==Access::_ONLY_ME) {
            abort_if($user==null,403);
            $accessor=$document->accessors()->where('user_id','=',$user->id);
            abort_if($accessor==null,403);
        }
         
        return $next($request);
    }
}
