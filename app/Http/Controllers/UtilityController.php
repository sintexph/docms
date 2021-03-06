<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\DocumentHelper;

class UtilityController extends Controller
{


    /**
     * CHECK IF SERIAL IS EXISTS ALREADY
     * @param $request->document_id EXEMPTION DURING CHECKING
     */
    public function serial_exits(Request $request)
    {
        $this->validate($request,[
            'category_code'=>'required',
            'serial'=>'required',
            'document_id'=>'required',
        ]);

        $document=\App\Document::find($request->document_id);
        $category=\App\Category::where('code',$request->category_code)->first();

        if(DocumentHelper::check_serial_exists($category,$request->serial))
        {
            return response()->json([
                'errors'=>[
                    'serial'=>['Serial has been used already on the selected category!']
                ]
            ],422);
        }
        return "";
    }

    public function contentTitles()
    {
        $systems=\App\ContentTitle::orderBy('name','asc')->get();
        return json_encode($systems);
    }

    public function getSystem($code)
    {
        $system=\App\System::where('code',$code)->first();
        return response()->json($system);
    }

    /**
     * List of all systems with corresponding codes
     * @return JSON list of systems
     */
    public function system_list()
    {
        $systems=\App\System::orderBy('name','asc')->get();
        return response()->json($systems);
    }

    /**
     * List of all systems with corresponding codes and a list of sections
     */
    public function system_list_ws()
    {
        $systems=\App\System::orderBy('name','asc')->with('sections')->get();
        return response()->json($systems); 
    }

    /**
     * List of all sections with corresponding codes
     * @param $request Holds the filters
     * @return JSON list of sections
     */
    public function section_list(Request $request)
    {
        $filter_system_code=$request['system_code'];
        $sections=\App\Section::orderBy('name','asc');
        
        # Filter by system code
        if(!empty($filter_system_code))
            $sections->where('system_code','=',$filter_system_code);
            
        $sections=$sections->get();
        return response()->json($sections);
    }
    /**
     * List of all categories with corresponding codes
     * @return JSON list of categories
     */
    public function category_list()
    {
        $categories=\App\Category::orderBy('name','asc')->get();
        return response()->json($categories);
    }


    /**
     * Get the document details
     * @param $document_number The document number of the document
     * @return JSON type of the document model
     */
    public function get_document_system(Request $request,$id)
    {
        $document=\App\Document::find($id);
        abort_if($document==null,404,'Document could not be found!');
        
        $system=\App\System::where('code',$document->system_code)->first();

        return response()->json($system);
    }

    public function find_documents(Request $request)
    {
        $find=$request['find'];
        
        $documents=\App\Document::on();
        $documents->where(function($query)use($find){
            $query->orWhere('document_number','like','%'.$find.'%');
        });

        return datatables($documents)->toJson();
    }
  
    /**
     * Get all the reviewers on the system
     */
    public function reviewers()
    {
        $reviewers=\App\User::select([
            'name',
            'id',
        ])->where('perm_reviewer','=',true)->get();

        return json_encode($reviewers);
    }

    /**
     * Get all the approvers on the system
     */
    public function approvers()
    {
        $approvers=\App\User::select([
            'name',
            'id',
        ])->where('perm_approver','=',true)->get();

        return json_encode($approvers);
    }

    /**
     * Get all the approvers on the system
     */
    public function users()
    {
        $users=\App\User::select([
            'name',
            'id',
        ])->get();

        return json_encode($users);
    }
    public function users_select2(Request $request)
    {
        $term=$request->term;

        $users=\App\User::select([
            'name as text',
            'id',
        ]);
        
        if(!empty($term))
        {
            $users->where(function($condition)use($term){
                $condition->orWhere('name','like','%'.$term.'%');
            });
        }


        return json_encode([
            'results'=>$users->get(),
        ]);
    }
    /**
     * Convert docx to html for document content
     */
    public function convert_doc_to_html(Request $request)
    {
        # Generate unique temporary file
        $temp_file=\Carbon\Carbon::now()->format('ymdhms').'-uploading-test.html';

        $phpWord = \PhpOffice\PhpWord\IOFactory::load($request->file('file'));
        // Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

        return $objWriter->getContent();
        # Save the word as temporary html
        $objWriter->save($temp_file);
        # Read the html content
        $contents= file_get_contents($temp_file);
        # Remove the temporary html file
        unlink($temp_file);

        return $contents;
    }
}
