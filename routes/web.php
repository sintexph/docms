<?php

 
Route::get('/','HomeController@index');

# File route
Route::get('file/{id}','FileController@get_file')->name('file');
Route::get('file/download/{id}','FileController@download')->name('file.download');


Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::post('login','Auth\LoginController@login')->name('login');
Route::post('logout','Auth\LoginController@logout')->name('logout');

Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');

 

Route::prefix('home')->name('home')->group(function(){
    
    Route::get('', 'HomeController@index');

    Route::middleware('document-viewing')->group(function(){
        Route::get('view/{id}', 'HomeController@view_document')->name('.view_document');
        Route::get('view/{id}/{document_title}', 'HomeController@view_document_slug')->name('.view_document.slug');
    });
    

});




Route::prefix('content')->name('content')->group(function(){
    
    Route::get('view/{id}','DocumentContentController@view_content')->name('.view')->middleware('document-viewing');

    // No permission yet
    Route::get('view/version/{id}','DocumentContentController@view_version_content')->name('.view.version');
    Route::get('view/revision-logs/{id}','DocumentContentController@view_revision_logs')->name('.view.revision.logs');

    Route::get('download/{id}','DocumentContentController@download_content')->name('.download');
    Route::get('download/version/{id}','DocumentContentController@download_content_version')->name('.download.version');
});

Route::prefix('util')->name('util')->group(function(){

    Route::post('doc-to-html','UtilityController@convert_doc_to_html')->name('.doc_to_html');

    Route::post('category_list', 'UtilityController@category_list')->name('.category_list');
    Route::post('system_list', 'UtilityController@system_list')->name('.system_list');
    Route::post('system_list_ws', 'UtilityController@system_list_ws')->name('.system_list_ws');


    Route::post('section_list', 'UtilityController@section_list')->name('.section_list');
    Route::post('get_document', 'UtilityController@get_document')->name('.get_document');
    Route::post('find_documents', 'UtilityController@find_documents')->name('.find_documents');
    

    Route::middleware('auth')->group(function(){

        Route::post('serial-exists', 'UtilityController@serial_exits')->name('.serial_exits');
        Route::post('reviewers', 'UtilityController@reviewers')->name('.reviewers');
        Route::post('approvers', 'UtilityController@approvers')->name('.approvers');
        Route::post('users', 'UtilityController@users')->name('.users');
        Route::post('users-select2', 'UtilityController@users_select2')->name('.users_select2');

    });

    

});

//Route::get('documents','DocumentController@index')->name('documents');
//Route::get('documents/{id}','DocumentController@view')->name('documents.view');

Route::prefix('manage')->name('manage')->group(function(){
    Route::prefix('documents')->name('.documents')->group(function(){

        Route::get('', 'ManageDocumentController@index');
        Route::post('list', 'ManageDocumentController@list')->name('.list');
        Route::get('download', 'ManageDocumentController@download')->name('.download');
        Route::get('create','ManageDocumentController@create')->name('.create');
        Route::get('view/{id}', 'ManageDocumentController@view')->name('.view');

        Route::get('edit-version/{id}', 'ManageDocumentController@edit_current_version')->name('.edit_current_version');

        Route::put('save','DocumentActionController@save')->name('.save');
        Route::put('save_draft','DocumentActionController@save_draft')->name('.save_draft');
        Route::put('update_draft/{id}','DocumentActionController@update_draft')->name('.update_draft');
        
        Route::patch('update_document/{id}','DocumentActionController@update_document')
                    ->name('.update_document')
                    ->middleware('document-action:,update_doc');

        Route::middleware('document-action')->group(function(){

            Route::patch('update_access/{id}','DocumentActionController@update_access')->name('.update_access');
            Route::delete('roll_back/{id}','DocumentActionController@roll_back')->name('.roll_back'); 
            Route::put('add_reference/{id}','DocumentActionController@add_reference')->name('.add_reference');
        });
                

        Route::put('remove_reference/{id}','DocumentActionController@remove_reference')->name('.remove_reference')->middleware('document-action:reference');
        

        // Administrator document actions
        Route::patch('archive_document/{id}','DocumentAdminActionController@archive_document')
        ->name('.archive_document')->middleware('document-admin-action:archive'); 

        Route::patch('put_trash/{id}','DocumentAdminActionController@put_trash')
        ->name('.put_trash')->middleware('document-admin-action:trash'); 

        Route::patch('remove_archive_document/{id}','DocumentAdminActionController@remove_archive_document')
        ->name('.remove_archive_document')->middleware('document-admin-action:archive'); 

        Route::patch('lock_document/{id}','DocumentAdminActionController@lock_document')
        ->name('.lock_document')->middleware('document-admin-action:lock'); 

        Route::patch('unlock_document/{id}','DocumentAdminActionController@unlock_document')
        ->name('.unlock_document')->middleware('document-admin-action:lock'); 

        Route::patch('change_status/{id}','DocumentAdminActionController@change_status')
        ->name('.change_status')->middleware('document-admin-action:status'); 


        // Version actions
        Route::patch('new_version/{id}','VersionActionController@new_version')->name('.new_version')->middleware('document-action');
        Route::patch('submit-version/{id}','VersionActionController@submit_version')->name('.submit_version')->middleware('document-action:version');
        Route::patch('update_version/{id}','VersionActionController@update_version')->name('.update_version')->middleware('document-action:version');
        Route::patch('release/{id}','VersionActionController@release')->name('.release')->middleware('document-action:version,release');
        
        Route::prefix('file')->name('.file')->group(function(){
            
            Route::put('upload/{id}','VersionActionController@upload_files')->name('.upload')->middleware('document-action:version');
            Route::delete('remove/{id}','VersionActionController@remove_file')->name('.remove')->middleware('document-action:attachment');

        });
          
        
        
    });
});

Route::prefix('comments')->name('comments')->group(function(){
    
    Route::put('save/{id}','CommentController@save')->name('.save');
    Route::post('fetch/{id}','CommentController@fetch')->name('.fetch');

});

Route::prefix('drafts')->name('drafts')->group(function(){
    
    Route::get('','DocumentDraftController@index');
    Route::post('list','DocumentDraftController@list')->name('.list');
    Route::delete('delete/{id}','DocumentDraftController@delete')->name('.delete');

});


Route::middleware('reviewer')->group(function(){

    Route::prefix('for_review')->name('for_review')->group(function(){
    
        Route::get('','DocumentReviewingController@index');
        Route::get('view/{id}','DocumentReviewingController@view_document')->name('.view');
        Route::post('list','DocumentReviewingController@list')->name('.list');
        Route::patch('review/{id}','DocumentReviewingController@review')->name('.review');

    });

});

Route::middleware('approver')->group(function(){

    Route::prefix('for_approval')->name('for_approval')->group(function(){
    
        Route::get('','DocumentApprovalController@index');
        Route::get('view/{id}','DocumentApprovalController@view_document')->name('.view');
        Route::post('list','DocumentApprovalController@list')->name('.list');
        Route::patch('approve/{id}','DocumentApprovalController@approve')->name('.approve');

    });
    
});

Route::middleware('administrator')->group(function(){

    Route::prefix('systems')->name('systems')->group(function(){
        
        Route::get('','ManageSystemController@index');
        Route::post('list','ManageSystemController@list')->name('.list');
        Route::post('fetch/{id}','ManageSystemController@fetch')->name('.fetch');
        Route::put('save','ManageSystemController@save')->name('.save');
        Route::patch('update/{id}','ManageSystemController@update')->name('.update');
        
        Route::delete('delete/{id}','ManageSystemController@delete')->name('.delete');
        Route::patch('archive/{id}','ManageSystemController@archive')->name('.archive');
        Route::patch('restore/{id}','ManageSystemController@restore')->name('.restore');

    });

    Route::prefix('sections')->name('sections')->group(function(){
        
        Route::get('','ManageSectionController@index');
        Route::post('list','ManageSectionController@list')->name('.list');
        Route::post('fetch/{id}','ManageSectionController@fetch')->name('.fetch');
        Route::put('save','ManageSectionController@save')->name('.save');
        Route::patch('update/{id}','ManageSectionController@update')->name('.update');

        Route::delete('delete/{id}','ManageSectionController@delete')->name('.delete');
        Route::patch('archive/{id}','ManageSectionController@archive')->name('.archive');
        Route::patch('restore/{id}','ManageSectionController@restore')->name('.restore');
        
    });

    Route::prefix('categories')->name('categories')->group(function(){
        
        Route::get('','ManageCategoryController@index');
        Route::post('list','ManageCategoryController@list')->name('.list');
        Route::post('fetch/{id}','ManageCategoryController@fetch')->name('.fetch');
        Route::put('save','ManageCategoryController@save')->name('.save');
        Route::patch('update/{id}','ManageCategoryController@update')->name('.update');

        Route::delete('delete/{id}','ManageCategoryController@delete')->name('.delete');
        Route::patch('archive/{id}','ManageCategoryController@archive')->name('.archive');
        Route::patch('restore/{id}','ManageCategoryController@restore')->name('.restore');

    });



    Route::prefix('accounts')->name('accounts')->group(function(){
        
        Route::get('','AccountController@index');
        Route::post('list','AccountController@list')->name('.list');
        Route::post('fetch/{id}','AccountController@fetch')->name('.fetch');
        Route::put('save','AccountController@save')->name('.save');
        Route::patch('update/{id}','AccountController@update')->name('.update');
        Route::delete('delete/{id}','AccountController@delete')->name('.delete');
        
    });


    Route::prefix('trashed')->name('trashed')->group(function(){
        
        Route::get('','DocumentTrashedController@index');
        Route::post('list','DocumentTrashedController@list')->name('.list');
        Route::delete('permanent/{id}','DocumentTrashedController@permanent')->name('.list');
        Route::patch('restore/{id}','DocumentTrashedController@restore')->name('.list');

    });



    Route::prefix('traffic')->name('traffic')->group(function(){
        
        Route::get('','SiteTrafficController@index');
        Route::post('weekdays','SiteTrafficController@get_weekdays_traffic')->name('.weekdays');
        Route::post('days-month','SiteTrafficController@get_days_of_month')->name('.days-month');
        Route::post('total','SiteTrafficController@total_traffics')->name('.total');
        Route::post('reset','SiteTrafficController@reset')->name('.reset');
        
    });


});


Route::prefix('upload')->name('upload')->group(function(){
    
    
    Route::put('image','UploadingController@image')->name('.image');
    

});