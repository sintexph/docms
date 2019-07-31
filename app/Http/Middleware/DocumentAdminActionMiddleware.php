<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Document;


class DocumentAdminActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param   $model What model related to the document that will be updated/access 
     * @return mixed
     */
    public function handle($request, Closure $next,$action)
    {
        $user=auth()->user();
        if($user==null)
            return redirect()->route('login');

        $document=Document::find($request['id']);
        abort_if($document==null,404,'Document could not be found!');
        
        if($user->perm_administrator==false)
            abort(403,'You dont have a permission to do any further action to this document.');

        # Could not do anything except lock/unlock if the document is locked.
        if($action=='status' || $action=='archived' || $action=='trash')
        {
            if($document->locked)
                abort(403,'The document is locked and could not do any further action.');
        }

        # Could not do anything except archived/un-archived if the document is archived.
        if($action=='status' || $action=='lock')
        {
            if($document->archived)
                abort(403,'The document is archived and could not do any further action.');
        }

        # Could do anything except lock/unlock if the document is obsolete.
        if($action=='lock')
        {
            if($document->obsolete)
                abort(403,'The document is obsolete and could not do any further action.');
        }

        if($action=='trash')
        {
            if($document->archived==false)
                abort(403,'Please put the document first on the archive before putting it to trash.');
        }

        return $next($request);
    }
}
