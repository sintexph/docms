<?php

namespace App\Helpers;
use App\Document;
use App\DocumentVersionAttachment;
use App\User;
use App\DocumentVersion;
use App\DocumentApprover;
use App\DocumentReviewer;
use App\Helpers\MailHelper;
use App\Section;
use App\System;
use App\Category;

class DocumentHelper 
{

    public static function format_document_number(System $system,Section $section,Category $category,$serial)
    {
        return $system->code.'-'.$section->code.'-'.$category->code.'-'.str_pad($serial, 3, "0", STR_PAD_LEFT);
    }

    /**
     * Check if the serial is exists already based on category
     */
    public static function check_serial_exists(Category $category,$serial)
    {
        return Document::where('category_code',$category->code)->where('serial',$serial)->exists();
    }

    /**
     * Generate document number based on system, section and category and sequence based on the category
     */
    public static function generate_document_number(System $system,Section $section,Category $category)
    {
        $value=(Object)[
            'serial'=>'',
            'document_number'=>''
        ];

        # Generate new serial
        $value->serial=static::get_last_serial($category->code);

        # Set initial value of the document number
        $value->document_number=static::format_document_number($system,$section,$category,$value->serial);//$system->code.'-'.$section->code.'-'.$category->code.'-'.str_pad($value->serial, 3, "0", STR_PAD_LEFT);

        return $value;
    }

    /**
     * Generate document number based on system, section and category and sequence based on the category
     */
    public static function update_document_number(Document $document,User $user)
    {
        if($document->isDirty('category_code'))
        {
            # Generate new serial
            $document->serial=static::get_last_serial($document->category_code);
        }
        
        # Set initial value of the document number
        $document->document_number=$document->system_code.'-'.$document->section_code.'-'.$document->category_code.'-'.str_pad($document->serial, 3, "0", STR_PAD_LEFT);

        # Record modification history
        DocumentActionHistoryHelper::edit_document($document,$user);

        $document->save();

        return $document;
    }

    /**
     * Get the last created document based on category and serial ordered by descending
     */
    public static function get_last_serial($category)
    {
        $document=Document::where('category_code',$category)->orderBy('serial','desc')->first();
        if($document==null)
            return 1;
        else
            return $document->serial+1;
    }
    
    public static function save_reviewer(User $reviewer,DocumentVersion $version)
    {
        $data=DocumentReviewer::create([
            'user_id'=>$reviewer->id,
            'version_id'=>$version->id
        ]);

        return $data;
    }
    public static function save_approver(User $approver,DocumentVersion $version)
    {
        $data=DocumentApprover::create([
            'user_id'=>$approver->id,
            'version_id'=>$version->id,
        ]);
        return $data;
    }
}
