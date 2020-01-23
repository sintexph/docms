<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\System;
use App\Document;
use App\Section;
use App\Helpers\EloquentHelper;
use App\Helpers\SiteVisitHelper;
use Cache;
use App\DocumentVersion;

class HomeController extends Controller
{
    public function index()
    {
        
        # Call site visit
        $site_visit=SiteVisitHelper::save_visit();


        # Cache the system list every after half day
        $systems=Cache::remember('system', 720, function () {
            return System::orderBy('name','asc')->get();
        });


        # Get the url parameter
        $url_system = Input::get('system', false);
        $url_section = Input::get('sec', false);
        $url_find = Input::get('find', false);

        $system_db=null;
        $section_db=null;


        $approved_documents=[];
        $search_result=[];
        $documents=[];

        if(($url_section!=false || $url_system!=false) && $url_find==false)
        {
            $section_db=Section::where('code',$url_section)->first();
            $system_db=System::where('code',$url_system)->first();

            $documents=EloquentHelper::document_public();
            if(!empty($section_db))
                $documents->where('section_code','=',$section_db->code);
            if(!empty($system_db))
                $documents->where('system_code','=',$system_db->code);
            
            $documents=$documents->paginate(10);
        }
        elseif (!empty($url_find)) {
            $search_result=EloquentHelper::document_public()->where(function($condition)use($url_find){
                $condition->orWhere('document_number','like','%'.$url_find.'%')
                ->orWhere('keywords','like','%'.$url_find.'%')
                ->orWhere('title','like','%'.$url_find.'%');
            })->paginate(10);
        }
        else
        {
            $documents=[];
            $approved_documents=EloquentHelper::document_public()->paginate(5);

     
        }

 
        
        return view('home.index',[
            'systems'=>$systems,
            'url_system'=>$url_system,
            'url_section'=>$url_section,
            'documents'=>$documents,
            'approved_documents'=>$approved_documents,
            'url_find'=>$url_find,
            'search_result'=>$search_result,
            'section_db'=>$section_db,
            'system_db'=>$system_db,
            'site_visit'=>$site_visit,
        ]);
    }

    /**
     * Redirect the page to slug
     */
    public function view_document($id)
    {
        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404);
        $document=$document_version->document;


        $slug=str_replace("/","-",$document->url_title);
        return redirect()->route('home.view_document.slug',[$document_version->id,$slug]);
    }
    public function view_document_slug($id,$document_title)
    {
        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404);
        $document=$document_version->document;

        $references=$document->references;
 
        $document_version_revision=$document_version->revision;

        return view('home.view_document',[
            'document'=>$document,
            'document_version'=>$document_version,
            'references'=>$references,
            'document_version_revision'=>$document_version_revision,
        ]);
    }
}
