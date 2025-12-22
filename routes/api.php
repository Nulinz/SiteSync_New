<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\ActivityController;
use App\Http\Controllers\api\api_project_controller;
use App\http\Controllers\api\Attend_controller;
use App\http\Controllers\Progress_import;
use App\Http\Controllers\mobile_cnt;
use App\Http\Controllers\Api\SnagCommentController;



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['namespace' => 'App\Http\Controllers\api', 'middleware' => 'auth:sanctum'], function () {
    // check teh permissions
    Route::post('/snag-comments-add', 'SnagCommentController@store');
    Route::get('/snag-comments/{snag_id}', 'SnagCommentController@index');

    Route::post('/permission', 'api_project_controller@permissions');
    // project details

    Route::post('/pro_details', 'api_project_controller@project_profile');
    Route::post('/project_stage', 'api_project_controller@project_stage');
    Route::post('/project_sub_mat', 'api_project_controller@project_sub_mat');
    Route::post('/project_sub_list', 'api_project_controller@project_sub_list');

    // project snag,survey,drawing list details


    Route::post('/project_survey_list', 'api_project_controller@project_survey_list');
    Route::post('/project_survey_ind', 'api_project_controller@project_survey_ind');
    Route::post('/project_qc_list', 'api_project_controller@project_qc_list');
    Route::post('/project_qc_ind', 'api_project_controller@project_qc_ind');
    Route::post('/project_snag_list', 'api_project_controller@project_snag_list');
    Route::post('/project_snag_ind', 'api_project_controller@project_snag_ind');
    Route::post('/project_draw_list', 'api_project_controller@project_draw_list');
    Route::post('/project_draw_history', 'api_project_controller@project_draw_history');

    Route::post('qc_activity', 'api_project_controller@qc_activity');

    // attendnace deatils

    Route::post('/attend_insert', 'Attend_controller@attend_insert');
    Route::post('/attend_show', 'Attend_controller@attend_show');
});


Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::post('/login', 'AuthenticationController@api_login');
    Route::post('/popup', 'AuthenticationController@popup');
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {


        // Route::get('/assignto', 'ProjectController@assignto');


    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::post('/logout', 'AuthenticationController@api_logout');


        // project based routes

        Route::post('/project_list', 'mobile_cnt@pro_list');

        Route::post('/project_emp', 'ProjectController@project_emp');

        Route::post('/category-list', 'SettingController@categorylist');
        Route::post('/sub-category-list/{id}', 'SettingController@subcategorylist');

        Route::post('/task-store', 'TaskController@store');

        Route::post('/task-list', 'TaskController@index');
        // Route::post('/assign-to-list', 'SettingController@employeelist');  // need to check its not the correct method

        Route::post('/task-timeline-list/{id}', 'mobile_cnt@task_timeline'); // need to check i dont have idea.....

        Route::post('/task-status-update', 'TaskController@task_status_update');

        Route::post('/task-assign', 'TaskController@store');

        Route::post('/task_created_by_me', 'TaskController@created_by_me');

        Route::post('/task_assign_to_me', 'TaskController@assign_to_me');

        // close task store 

        Route::post('task-close', 'TaskController@task_close_store');
        // Route::get('/task-closes-details/{id}', 'TaskController@getTaskCloseDetails');
        Route::post('/task-closes-details', 'TaskController@getTaskCloseDetailsPost');
        Route::post('/task-close-update', 'TaskController@taskCloseUpdateApi');




        //approve status update 
        Route::get('project-survey-status-update/{id?}/{status?}', 'ProjectController@survey_status_update');
        Route::get('project-qc-status-update/{id?}/{status?}', 'ProjectController@qc_status_update');
        Route::get('project-snag-status-update/{id?}/{status?}', 'ProjectController@snag_status_update');
        // Route::get('project-drawing-status-update', 'ProjectController@drawing_status_update');

        // Stage Add single

        Route::post('progress-single', 'Progress_import@progress_single');

        Route::post('progress-single-activity', 'Progress_import@progress_single_activity');

        Route::post('progress-stage-list', 'Progress_import@progress_stage_list');

        // Route::post('progress-act-list', 'Progress_import@progress_act_list');





        Route::post('snag-cat', 'mobile_cnt@snag_cat');
        // Route::post('snag-pic', 'TaskController@snag_pic');
        Route::post('snag-pic-show', 'mobile_cnt@snag_pic_show');

        // survey list
        Route::post('survey-list/{id}', 'SettingController@surveyedit');
        Route::post('qc-list/{id}', 'SettingController@qcedit');

        // answer store
        Route::post('survey-ans-store', 'TaskController@survey_ans_store');
        Route::post('qc-ans-store', 'TaskController@qc_ans_store');
        Route::post('snag-ans-store', 'TaskController@snag_ans_store');

        // notification list
        Route::post('/notify_list', 'mobile_cnt@notify_list');

        Route::post('/notify_seen_update', 'mobile_cnt@notify_seen_update');


        // Newer Version for additon controllers in qc,snag,survey - =============//////////////////////

        //snags route
        Route::post('snag-store', 'ProjectController@snag_store');


        // newer version for create the survey, Qc, Sang, Drawing

        Route::post('survey_create_mob', 'ProjectController@survey_store');
        Route::post('qc_create_mob', 'ProjectController@qc_store');
        Route::post('snag_create_mob', 'ProjectController@snag_store');
        Route::post('drawing_create_mob', 'ProjectController@drawing_store');

        Route::post('drawing_status_update_mob', 'ProjectController@drawing_status_update');

        Route::post('pro_docs_add', 'ProjectController@pro_docs_add'); // prjt_document.blade.php
        Route::post('pro_docs_list', 'ProjectController@pro_docs_list'); // prjt_document.blade.php


        // newer version api get into beare token 

        // For activity and Progress
        // Route::post('/activity_work_create', 'ActivityController@store_activity');

        Route::post('/activity_work_create', 'ActivityController@store_stage');
        // Route::post('/add_mat', 'ActivityController@add_mat');
        // Route::post('/add_file', 'ActivityController@add_file');
        // Route::post('/act_update_qc', 'ActivityController@act_update_qc');
        Route::post('/act_qc_show', 'ActivityController@act_qc_show');
        Route::post('/activity_fetch', 'ActivityController@activity_fetch');




        Route::post('/project_assigned_list', 'mobile_cnt@project_assigned_list');


        // project snag,survey,drawing list details

        Route::post('/dashboard_list', 'mobile_cnt@dashboard_list');



        // task comment 

        Route::post('/comment_list', 'mobile_cnt@comment_list');
        Route::post('/task_comment', 'mobile_cnt@comment_store');

        // details

        Route::post('/work_activity_export', 'Progress_import@work_activity_export');





        //  // answer retrive
        // Route::post('survey-ans-update', 'TaskController@survey_ans_update');
        // Route::post('qc-ans-update', 'TaskController@qc_ans_update');
    });
});
