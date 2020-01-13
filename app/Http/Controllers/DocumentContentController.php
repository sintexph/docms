<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\DocumentVersion;
use App\DocumentVersionRevision;
use App\Helpers\DocumentHelper;
use App\Helpers\DocumentContent\Util;
use Spipu\Html2Pdf\Html2Pdf;

class DocumentContentController extends Controller
{
    private function content_view($document_version,$document,$download=false)
    {
        # Add new template if there is a new template for other category
        switch ($document->category_code) {
            case 'PO':
                return view('templates.content.policy',[
                    'document_version'=>$document_version,
                    'document'=>$document,
                    'download'=>$download,
                ]);
                break;
            default:
                return view('templates.content.policy',[
                    'document_version'=>$document_version,
                    'document'=>$document,
                    'download'=>$download,
                ]);
                break;
        }
    }

    /**
     * View the current version content of the document
     */
    public function view_content($id)
    {
        $version=DocumentVersion::find($id);
        abort_if($version==null,404);
        $document=$version->document;
        
        return $this->content_view($version,$document);
    }

    public function view_version_content($id)
    {
        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404);
        $document=$document_version->document;
        return $this->content_view($document_version,$document);
    }
    
    
    /**
     * View the revision logs in table format
     * @param $id The document database id
     * @return HTML View
     */
    public function view_revision_logs($id)
    {
        $document=Document::find($id);
        abort_if($document==null,404);
    
        return view('templates.revision_log',['document'=>$document]);
    }

    /**
     * Download the content to pdf
     */
    public function download_content($id)
    {
        $document=Document::find($id);
        $document_version=$document->active_version;
        $template=$this->content_view($document_version,$document,true);
        
        # Download in pdf
        $pdf = \PDF::loadHTML($template)->setPaper('letter')->setOrientation('portrait');

        $pdf->setOption('dpi',300);
        $pdf->setOption('margin-top',20);
        $pdf->setOption('margin-right',20);
        $pdf->setOption('margin-bottom',20);
        $pdf->setOption('margin-left',20);

        return $pdf->stream($document->document_number.' - '.$document->title.'.pdf');
    }
    /**
     * Download the content to pdf
     */
    public function download_content_version($id)
    {
        $document_version=DocumentVersion::find($id);
        $document=$document_version->document;
        $template=$this->content_view($document_version,$document,true);
        
        # Download in pdf
        $pdf = \PDF::loadHTML($template)->setPaper('letter')->setOrientation('portrait');
        $pdf->setOption('dpi',300);
        $pdf->setOption('margin-top',20);
        $pdf->setOption('margin-right',20);
        $pdf->setOption('margin-bottom',20);
        $pdf->setOption('margin-left',20);
 
        return $pdf->stream($document->document_number.' - '.$document->title.'.pdf');
    }

}
