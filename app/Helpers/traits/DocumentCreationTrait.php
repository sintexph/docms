<?php

namespace App\Helpers\Traits;

use App\Helpers\DocumentActionHistoryHelper;
use App\Document;
use Illuminate\Http\Request;
use App\Helpers\DocumentContent\Util\Cast;
use App\DocumentVersion;
use Illuminate\Support\Facades\Input;
use App\System;
use App\Section;
use App\Category;
use App\Helpers\DocumentHelper;
use App\DocumentDraft;
use App\User;
use App\DocumentAccessor;

trait DocumentCreationTrait 
{
    protected function save_document(Request $request)
    {
        $request=$request['document'];

        $user=auth()->user();
        # Get the system and the section from the database
        $section=Section::where('code','=',$request['section'])->first();
        $system=System::where('code','=',$request['system'])->first();
        $category=Category::where('code','=',$request['category'])->first();

        # Check if data exist
        abort_if($section==null, 422,'Section is not exist on the system!');
        abort_if($system==null, 422,'System is not exist on the system!');
        abort_if($category==null, 422,'Category is not exist on the system!');


        $generated_document_number=DocumentHelper::generate_document_number($system,$section,$category);
        $document=new Document;
        
        $document->document_number=$generated_document_number->document_number;
        $document->serial=$generated_document_number->serial;

        $document->version=1; // Set default version to 1
        $document->title=$request['title'];
        $document->system_code=$system->code;
        $document->section_code=$section->code;
        $document->category_code=$category->code;
        $document->access=$request['access_data']['access'];
        
        $document->keywords=explode(",",$request['keywords']);

        $document->comment=$request['comment'];
        $document->created_by=$user->id;
        $document->save();

        DocumentActionHistoryHelper::create_document($document,$user);



        # Remove the draft if the current saving has a linked draft
        $draft=DocumentDraft::find(Input::get('draft'));
            if($draft!=null)
                $draft->delete();


        # Check if the access is custom
        if($document->access=="2")
        {
            # Save the new accessors
            foreach($request['access_data']['accessors'] as $accessor_id)
            {
                $accessor=User::find($accessor_id);
                if($accessor==null)
                {
                    DB::rollBack();
                    abort(422,'User could not be found on the system!');
                }
                
                DocumentAccessor::create([
                    'document_id'=>$document->id,
                    'user_id'=>$accessor->id
                ]);
            }

        }
        elseif($document->access=="4")
        {
            # Set the authenticated user as the accessor only
            DocumentAccessor::create([
                'document_id'=>$document->id,
                'user_id'=>Auth::user()->id
            ]);

        }
        


        return $document;
    }
 

}
