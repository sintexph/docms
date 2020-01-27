<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Document;
use App\DocumentVersion;
use App\Reference;
use App\DocumentVersionAttachment;

class DocumentActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param   $model What model related to the document that will be updated/access 
     * @return mixed
     */
    public function handle($request, Closure $next,$model=null,$action=null)
    {
        $user=Auth::user();
        if($user==null)
            return redirect()->route('login');

        $document=null;
   
        
        if(empty($model))
        {
            $document=Document::find($request['id']);
            abort_if($document==null,404,'Could not find the the document.');

            # Can update the document if the current version is not approved yet
            if($action=='update_doc')
                abort_if($document->current_version->approved==true,404,'Could not update the document if it is approved already.');

        }
        elseif($model=='version') {
            $version=DocumentVersion::find($request['id']);
            abort_if($version==null,404,'Could not find the the document version.');

            # Get the document
            $document=$version->document;
            
        }
        elseif($model=='reference') {
            $reference_document=Reference::find($request['id']);
            abort_if($reference_document==null,404,'Could not find the the document version.');

            $document=$reference_document->document;
        }
        elseif ($model=='attachment') {
            $attachment=DocumentVersionAttachment::find($request['id']);
            abort_if($attachment==null,404,'Could not find the the document attachment.');

            $document=$attachment->version->document;
        }
        else
            abort(404,'Page could not be found!');


        if($document->archived)
             abort(422,'The document is archived and could not do any further action.'); 
        if($document->obsolete)
             abort(422,'The document is obsolete and could not do any further action.'); 
        if($document->locked)
             abort(422,'The document is locked and could not do any further action.'); 


        # Cannot view if not administrator or creator of the document
        if($user->perm_administrator==false && $document->created_by!=$user->id)
            abort(403,'You don\'t have a permission to do any further action to this document.');

        return $next($request);
    }
}
