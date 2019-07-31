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
    private function download_pdf($template,$filename)
    {



        /*
        # instantiate and use the dompdf class
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($template);
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);
        # (Optional) Setup the paper size and orientation
        $dompdf->setPaper('letter', 'portrait');
        # Render the HTML as PDF
        $dompdf->render();
        # Output the generated PDF to Browser
        $dompdf->stream($filename, array("Attachment" => true));
        */
        
    }
    
    private function content_view($current_version,$document,$download=false)
    {
        # Add new template if there is a new template for other category
        switch ($document->category_code) {
            case 'PO':
                return view('templates.content.policy',[
                    'current_version'=>$current_version,
                    'document'=>$document,
                    'download'=>$download,
                ]);
                break;
            default:
                return view('templates.content.policy',[
                    'current_version'=>$current_version,
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
        
        $document=Document::find($id);
        abort_if($document==null,404);
        $version=$document->current_version;

        return $this->content_view($version,$document);
    }

    public function view_version_content($id)
    {
        $current_version=DocumentVersion::find($id);
        abort_if($current_version==null,404);
        $document=$current_version->document;
        return $this->content_view($current_version,$document);
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
        $current_version=$document->current_version;
        $template=$this->content_view($current_version,$document,true);
        
        # Download in pdf
        $pdf = \PDF::loadHTML($template)->setPaper('letter')->setOrientation('portrait');

        $pdf->setOption('dpi',300);

        return $pdf->download($document->document_number.' - '.$document->title.'.pdf');
    }
    /**
     * Download the content to pdf
     */
    public function download_content_version($id)
    {
        $current_version=DocumentVersion::find($id);
        $document=$current_version->document;
        $template=$this->content_view($current_version,$document,true);
        
        # Download in pdf
        $pdf = \PDF::loadHTML($template)->setPaper('letter')->setOrientation('portrait');
        $pdf->setOption('dpi',300);
        return $pdf->download($document->document_number.' - '.$document->title.'.pdf');
    }


}
