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
use App\Category;

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
        $url_category = Input::get('cat', false);
        $url_find = Input::get('find', false);
        $url_status=Input::get('status',false);

        $system_db=null;
        $section_db=null;
        $category_db=null;


        $approved_documents=[];
        $search_result=[];
        $documents=[];

        if($url_section!=false || $url_system!=false || $url_category!=false || $url_status!=false || $url_find!=false)
        {
            
            if(!empty($url_section))
                $section_db=Section::where('code',$url_section)->first();
            if(!empty($url_system))
                $system_db=System::where('code',$url_system)->first();
            if(!empty($url_category))
                $category_db=Category::where('code',$url_category)->first();

        
            $documents=EloquentHelper::document_public();
            if(!empty($section_db))
                $documents->where('section_code','=',$section_db->code);
            if(!empty($system_db))
                $documents->where('system_code','=',$system_db->code);

            if(!empty($category_db))
                $documents->where('category_code','=',$category_db->code);



            switch ($url_status) {
                case 'active':
                    $documents->where('obsolete',false);
                    break;
                case 'obsolete':
                    $documents->where('obsolete',true);
                    break;
            }

            if(!empty($url_find))
            {
                $documents->where(function($condition)use($url_find){
                    $condition->orWhere('document_number','like','%'.$url_find.'%')
                    ->orWhere('keywords','like','%'.$url_find.'%')
                    ->orWhere('title','like','%'.$url_find.'%');
                });
            }

 
            $documents=$documents->paginate(8);
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
            'url_category'=>$url_category,
            'url_find'=>$url_find,
            'url_status'=>$url_status,

            'documents'=>$documents,
            'approved_documents'=>$approved_documents,
             

            'section_db'=>$section_db,
            'system_db'=>$system_db,
            'category_db'=>$category_db,

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

        return view('home.view_document',[
            'document'=>$document,
            'document_version'=>$document_version,
            'references'=>$references,
        ]);
    }
}
