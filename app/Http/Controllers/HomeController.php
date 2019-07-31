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
        $url_search = Input::get('search', false);


         $system_db=null;
         $section_db=null;


        $approved_documents=[];
        $search_result=[];
        $documents=[];

        if($url_section!=false && $url_system!=false && $url_search==false)
        {
            $section_db=Section::find($url_section);
            $system_db=System::find($url_system);

            $documents=EloquentHelper::document_public()->where('section_code','=',$section_db->code)
            ->where('system_code','=',$system_db->code)->paginate(10);
        }
        elseif (!empty($url_search)) {
            $search_result=EloquentHelper::document_public()->where(function($condition)use($url_search){
                $condition->orWhere('document_number','like','%'.$url_search.'%')
                ->orWhere('keywords','like','%'.$url_search.'%')
                ->orWhere('title','like','%'.$url_search.'%');
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
            'url_search'=>$url_search,
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
        $document=Document::find($id);
        abort_if($document==null,404);


        $slug=str_replace("/","-",$document->url_title);
        return redirect()->route('home.view_document.slug',[$document->id,$slug]);
    }
    public function view_document_slug($id,$document_title)
    {
        $document=Document::find($id);
        abort_if($document==null,404);


        $reference_documents=$document->reference_documents;
        $current_version=$document->current_version;
        
        $current_version_revision=$current_version->revision;

        return view('home.view_document',[
            'document'=>$document,
            'current_version'=>$current_version,
            'reference_documents'=>$reference_documents,
            'current_version_revision'=>$current_version_revision,
        ]);
    }
}
