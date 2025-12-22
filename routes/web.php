
<?php
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/', 'login')->name('home');

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('/login', 'AuthenticationController@login')->name('login');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function () {

    // Route::middleware(['auth'])->group(function () {

    // Route::get('/role', 'CommonController@assign_role');

    Route::get('/assign_role', 'CommonController@assign_role');


    Route::get('download_file/{file_path}', 'CommonController@downloadFile')->name('download_file');

    // Dashboard
    Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::get('mydashboard', 'DashboardController@mydashboard')->name('dashboard.mydashboard');
    Route::post('task_status_update', 'DashboardController@task_status_update')->name('dashboard.task_status_update');
    Route::post('task-assign', 'TaskController@store')->name('task.task_store');

    // Projects
    Route::get('project-list', 'ProjectController@index')->name('project.index');
    Route::get('project-create', 'ProjectController@create')->name('project.create');
    Route::get('project-profile/{id}', 'ProjectController@show')->name('project.show');
    Route::get('project-edit/{id}', 'ProjectController@edit')->name('project.edit');
    Route::post('progress-import', 'Progress_import@progress_import')->name('progress.import');
// Add these routes to your web.php file

// Existing route
// Route::get('project-profile/{id}', 'ProjectController@show')->name('project.show');

// New routes for comment system
Route::post('snag-comment', 'ProjectController@storeSnagComment')->name('snag.comment.store');
Route::get('snag-comments/{snag_id}', 'ProjectController@getSnagComments')->name('snag.comments.get');
    Route::post('project-store', 'ProjectController@store')->name('project.store');
    Route::post('project-survey-store', 'ProjectController@survey_store')->name('project.survey_store');
    Route::post('project-drawing-store', 'ProjectController@drawing_store')->name('project.drawing_store');
    Route::post('project-drawing-status-update', 'ProjectController@drawing_status_update')->name('project.drawing_status_update');
    Route::post('project-task-store', 'ProjectController@task_store')->name('project.task_store');
    Route::post('project-qc-store', 'ProjectController@qc_store')->name('project.qc_store');
    Route::post('project-snag-store', 'ProjectController@snag_store')->name('project.snag_store');
    Route::get('project-download/{id}', 'ProjectController@downloadFile')->name('project.download');
    Route::post('project-task-view', 'ProjectController@task_view')->name('project.task_view');
    Route::get('project-survey-status-update/{id}/{status}', 'ProjectController@survey_status_update')->name('project.surveystatusupdate');
    Route::get('project-qc-status-update/{id}/{status}', 'ProjectController@qc_status_update')->name('project.qcstatusupdate');
    Route::get('project-snag-status-update/{id}/{status}', 'ProjectController@snag_status_update')->name('project.snagstatusupdate');
    Route::get('project-history/{projectid}/{ent_id}', 'ProjectController@history_table')->name('project.history');
    Route::get('progress-stages/{pro_id}', 'ProjectController@stages_table')->name('project.stages');
    Route::post('progress-update', 'Progress_import@progress_update')->name('progress.update');
    Route::post('progress-single', 'Progress_import@progress_single')->name('progress.single');
    Route::post('progress-single-activity', 'Progress_import@progress_single_activity')->name('progress.single_activity');
    Route::post('stage_start_date', 'Progress_import@stage_start_date')->name('stage.start_date');

    // Task
    Route::get('task-list', 'TaskController@index')->name('task.index');
    Route::get('task-create', 'TaskController@create')->name('task.create');
    Route::get('task-profile/{id}', 'TaskController@show')->name('task.show');
    Route::post('task-store', 'TaskController@store')->name('task.store');
    Route::get('task-download/{id}', 'TaskController@downloadFile')->name('task.download');

    Route::get('completed-task-list', 'TaskController@completed_list')->name('completed.task');

    Route::get('close-task-list', 'TaskController@close_list')->name('close.task_list');

    Route::post('task-close', 'TaskController@task_close_store')->name('close.task');
    Route::post('task-close-ajax', 'TaskController@task_close_ajax')->name('close.task_ajax');
    Route::get('task-close-update/{id}', 'TaskController@task_close_update')->name('close.task_update');

    // Settings
    Route::get('company-create', 'SettingController@index')->name('settings.index');
    Route::get('company-edit', 'SettingController@companyedit')->name('settings.companyedit');
    Route::get('company-profile', 'SettingController@companyprofile')->name('settings.companyprofile');
    Route::post('company-store', 'SettingController@companystore')->name('settings.companystore');
    Route::post('role-edit', 'SettingController@roleedit')->name('settings.role_edit');

    Route::get('designation', 'SettingController@designationlist')->name('settings.designation');
    Route::post('designation-store', 'SettingController@designation_store')->name('settings.designationstore');
    Route::get('designation-status-update/{id}/{status}', 'SettingController@designation_status_update')->name('settings.designationstatusupdate');

    Route::get('employee', 'SettingController@employeelist')->name('settings.employee');
    Route::get('employee_permission/{id}', 'SettingController@employee_permission')->name('settings.employee_permission');
    Route::post('employee-store', 'SettingController@employee_store')->name('settings.employeestore');
    Route::get('employee-status-update/{id}/{status}', 'SettingController@employee_status_update')->name('settings.employeestatusupdate');

    Route::get('roles', 'SettingController@roleslist')->name('settings.roles');
    Route::get('roles-create', 'SettingController@rolescreate')->name('settings.rolescreate');
    Route::get('roles-edit/{id}', 'SettingController@rolesedit')->name('settings.rolesedit');
    Route::post('role_update', 'SettingController@rolesstore')->name('settings.role_update');
    Route::post('roles-store', 'SettingController@rolesstore')->name('settings.rolesstore');
    Route::get('roles-status-update/{id}/{status}', 'SettingController@role_status_update')->name('settings.rolestatusupdate');

    Route::get('category', 'SettingController@categorylist')->name('settings.category');
    Route::post('category-store', 'SettingController@category_store')->name('settings.categorystore');
    Route::get('category-status-update/{id}/{status}', 'SettingController@category_status_update')->name('settings.categorystatusupdate');

    Route::get('subcategory', 'SettingController@subcategorylist')->name('settings.subcategory');
    Route::post('subcategory-store', 'SettingController@subcategory_store')->name('settings.subcategorystore');
    Route::get('subcategory-status-update/{id}/{status}', 'SettingController@subcategory_status_update')->name('settings.subcategorystatusupdate');

    Route::get('survey', 'SettingController@surveylist')->name('settings.survey');
    Route::get('survey-create', 'SettingController@surveycreate')->name('settings.surveycreate');
    Route::get('survey-edit/{id}', 'SettingController@surveyedit')->name('settings.surveyedit');
    Route::post('survey-store', 'SettingController@surveystore')->name('settings.surveystore');
    Route::post('survey-update', 'SettingController@surveyupdate')->name('settings.surveyupdate');
    Route::post('survey-question-delete', 'SettingController@surveyquestiondelete')->name('settings.surveyquestiondelete');
    Route::get('survey-status-update/{id}/{status}', 'SettingController@survey_status_update')->name('settings.surveystatusupdate');

    Route::get('drawing', 'SettingController@drawinglist')->name('settings.drawing');
    Route::post('drawing-store', 'SettingController@drawing_store')->name('settings.drawingstore');
    Route::get('drawing-status-update/{id}/{status}', 'SettingController@drawing_status_update')->name('settings.drawingstatusupdate');

    Route::get('qc', 'SettingController@qclist')->name('settings.qc');
    Route::get('qc-create', 'SettingController@qccreate')->name('settings.qccreate');
    Route::get('qc-edit/{id}', 'SettingController@qcedit')->name('settings.qcedit');
    Route::post('qc-store', 'SettingController@qcstore')->name('settings.qcstore');
    Route::get('qc-status-update/{id}/{status}', 'SettingController@qc_status_update')->name('settings.qcstatusupdate');

    Route::get('snag', 'SettingController@snaglist')->name('settings.snag');
    Route::post('snag-store', 'SettingController@snag_store')->name('settings.snagstore');
    Route::get('snag-status-update/{id}/{status}', 'SettingController@snag_status_update')->name('settings.snagstatusupdate');

    Route::get('item', 'SettingController@itemlist')->name('settings.item');
    Route::post('item-store', 'SettingController@item_store')->name('settings.itemstore');
    Route::get('item-status-update/{id}/{status}', 'SettingController@item_status_update')->name('settings.itemstatusupdate');

    Route::get('unit', 'SettingController@uomlist')->name('settings.unit');
    Route::post('uom-store', 'SettingController@uom_store')->name('settings.uomstore');
    Route::get('uom-status-update/{id}/{status}', 'SettingController@uom_status_update')->name('settings.uomstatusupdate');

    Route::get('permissions', 'SettingController@permissions_list')->name('settings.permissions');
    Route::get('permissions-create', 'SettingController@permissions_create')->name('settings.permissionscreate');
    Route::get('permissions-edit', 'SettingController@permissions_edit')->name('settings.permissionsedit');

    Route::get('password', 'SettingController@password')->name('settings.password');
    Route::post('password-update', 'SettingController@password_update')->name('settings.passwordupdate');

    // });
    // answer popup for snag,qc,survey  ajax request
    Route::post('ajax-snag-ans', 'ProjectController@ans_ajax')->name('ans.ajax');

    //ajax request
    Route::post('file_type', 'ProjectController@file_type')->name('file_type'); // prjt_drawing.blade.php
    Route::post('file_version', 'ProjectController@file_version')->name('file_version'); // prjt_drawing.blade.php



    Route::get('/send-notification', 'SettingController@fcm');

    // project document CRUD

    Route::post('pro_docs_add', 'ProjectController@pro_docs_add')->name('add.pro_docs'); // prjt_document.blade.php


    Route::post('/work_activity_export', 'Progress_import@work_activity_export')->name('report');

    // comment store datas..

    Route::post('/task_comment', 'mobile_cnt@comment_store')->name('comment.store');
    Route::get('/activity_remove/{act_id}', 'mobile_cnt@activity_remove')->name('activity.remove');


    // project progress inside tabs 

    Route::post('progress-ovw_preliminary', 'Progress_import@tabs_progress')->name('projects.ovw_preliminary');
    // Route::get('progress-sub-structure-works', 'Progress_import@tabs_progress')->name('projects.ovw_sub');
    // Route::get('super-structure-works', 'Progress_import@tabs_progress')->name('projects.ovw_super');
    // Route::get('finishing-works', 'Progress_import@tabs_progress')->name('project.ovw_finish');
});
