<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Designation;
use App\Models\Category;
use App\Models\Company;
use App\Models\SubCategory;
use App\Models\Drawing;
use App\Models\Snag;
use App\Models\Uom;
use App\Models\Item;
use App\Models\Project;
use App\Models\Role;
use App\Models\RoleNotification;
use App\Models\RolePermission;
use App\Models\New\Role as NewRole;
use App\Models\New\RolePermission as NewRolePermission;
use App\Models\QCChecklist;
use App\Models\QC;
use App\Models\Survey;
use App\Models\EntrySurvey;
use App\Models\SurveyQuestion;
use App\Models\SurveyChoice;
use App\Models\Survey_ans;
use App\Models\Qc_ans;
use App\Models\EntryQC;
use Spatie\Permission\Models\Role as sp_role;
use Spatie\Permission\Models\Permission as sp_per;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;
use PhpParser\Node\Expr\Cast\String_;
use Illuminate\Support\Facades\Storage;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $company = Company::first();
        return view('settings.company', compact('company'));
    }

public function companystore(Request $request)
{
    $file_array = [];

    // Upload logo
    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        $fileName = str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = 'company/' . $fileName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $file_array['logo'] = $filePath;
    }

    // Upload GST attachment
    if ($request->hasFile('gst_attachment')) {
        $file = $request->file('gst_attachment');
        $fileName = str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = 'company/' . $fileName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $file_array['gst_attachment'] = $filePath;
    }

    // Upload PAN card attachment
    if ($request->hasFile('pancard_attachment')) {
        $file = $request->file('pancard_attachment');
        $fileName = str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = 'company/' . $fileName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $file_array['pancard_attachment'] = $filePath;
    }

    // Upload MSME attachment
    if ($request->hasFile('msme_attachment')) {
        $file = $request->file('msme_attachment');
        $fileName = str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = 'company/' . $fileName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $file_array['msme_attachment'] = $filePath;
    }

    // Merge uploaded file paths into request data
    $request->merge($file_array);

    // Update company record
    $request_data = $request->input();
    $company = Company::find(1);
    $company->update($request_data);

    return redirect('/company-profile')->with('success', 'Company details updated successfully!');
}

    public function companyedit(Request $request)
    {
        return view('settings.companyedit');
    }

    public function companyprofile(Request $request)
    {
        $company = Company::find(1);
        return view('settings.companyprofile', compact('company'));
    }

    public function designationlist(Request $request)
    {
        $designations = Designation::all();
        return view('settings.designation', compact('designations'));
    }

    public function designation_store(Request $request)
    {
        $request_data = $request->except('_token');
        if ($request->input('id')) {
            $designation = Designation::find($request->input('id'));
            if ($designation) {
                $designation->update($request_data);
            }
        } else {
            Designation::create($request_data);
        }
        return redirect('/designation');
    }

    public function designation_status_update($id, $status)
    {
        $designation = Designation::find($id);
        $designation->status = $status;
        $designation->save();
        return redirect('/designation');
    }

    public function snag_status_update($id, $status)
    {
        $designation = Snag::find($id);
        $designation->status = $status;
        $designation->save();
        return redirect('/snag');
    }

    public function survey_status_update($id, $status)
    {
        $survey = Survey::find($id);
        $survey->status = $status;
        $survey->save();
        return redirect('/survey');
    }

    public function snag_store(Request $request)
    {
        $request_data = $request->except('_token');

        //echo "<pre>"; print_r($request_data); die;
        if ($request->input('id')) {
            $validated = $request->validate([
                'category' => 'required|string',
                'description' => 'required|string|max:255',
            ]);
            $snag = Snag::find($request->input('id'));
            if ($snag) {
                $snag->update($request_data);
            }
        } else {
            $validated = $request->validate([
                'category' => 'required|string|unique:snag',
                'description' => 'required|string|max:255',
            ]);
            Snag::create($validated);
        }
        return back()->with('success', 'Snag updated successfully');
    }

    public function item_store(Request $request)
    {
        $request_data = $request->except('_token');

        //echo "<pre>"; print_r($request_data); die;
        if ($request->input('id')) {
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string|max:255',
            ]);
            $model = Item::find($request->input('id'));
            if ($model) {
                $model->update($request_data);
            }
        } else {
            $validated = $request->validate([
                'name' => 'required|string|unique:item',
                'description' => 'required|string|max:255',
            ]);
            Item::create($validated);
        }
        return back()->with('success', 'Item updated successfully');
    }

    public function item_status_update($id, $status)
    {
        $model = Item::find($id);
        $model->status = $status;
        $model->save();
        return redirect('/item');
    }

    public function uom_status_update($id, $status)
    {
        $model = Uom::find($id);
        $model->status = $status;
        $model->save();
        return redirect('/unit');
    }

    public function uom_store(Request $request)
    {
        $request_data = $request->except('_token');

        //echo "<pre>"; print_r($request_data); die;
        if ($request->input('id')) {
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string|max:255',
            ]);
            $model = Uom::find($request->input('id'));
            if ($model) {
                $model->update($request_data);
            }
        } else {
            $validated = $request->validate([
                'name' => 'required|string|unique:item',
                'description' => 'required|string|max:255',
            ]);
            Uom::create($validated);
        }
        return back()->with('success', 'Uom updated successfully');
    }
    public function employee_store(Request $request)
    {
        $request_data = $request->except('_token');

        //print_r($request->input()); die;



        $fileName = null;

    if ($request->hasFile('image_path')) {
        $file = $request->file('image_path');
        $fileName = str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = 'employees/' . $fileName;

        // Upload file to S3
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        // Store only relative path (for easy access later)
        $fileName = $filePath;
    }

        if ($request->input('id')) {
            $validated = $request->validate([
                'employee_code' => 'required|string|unique:employee,employee_code,' . $request->input('id'),
                'name' => 'required|string|unique:employee,name,' . $request->input('id'),
                'contact_number' => 'required|string',
                'email_id' => 'required|string',
                'role_id' => 'required',
            ]);
            $employee = Employee::find($request->input('id'));

            // dd($validated['role_id']);

            // if ($employee->role_id != $validated['role_id']) {

            $role_name = sp_role::find($validated['role_id']);

            $employee->syncRoles($role_name->name);
            // }

            if ($fileName) {
                $validated['image_path'] = $fileName;
            }
            if ($employee) {
                $employee->update($validated);
            }
        } else {
            $validated = $request->validate([
                'employee_code' => 'required|string|unique:employee,employee_code',
                'name' => 'required|string|unique:employee,name',
                'password' => 'required|string|min:6',
                'contact_number' => 'required|string',
                'email_id' => 'required|string',
                'role_id' => 'required|string',
            ]);
            if ($fileName) {
                $validated['image_path'] = $fileName;
            }
            //print_r($validated); die;
            $employee =  Employee::create($validated);

            $role_name = sp_role::find($validated['role_id']);

            $employee->assignRole($role_name->name);
        }



        return redirect('/employee');
    }


    public function employee_status_update($id, $status)
    {
        $employee = Employee::find($id);
        $employee->status = $status;
        $employee->save();
        return redirect('/employee');
    }

    public function category_status_update($id, $status)
    {
        $category = Category::find($id);
        $category->status = $status;
        $category->save();
        return redirect('/category');
    }

    public function qc_status_update($id, $status)
    {
        $qc = QC::find($id);
        $qc->status = $status;
        $qc->save();
        return redirect('/qc');
    }

    public function surveystore(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.question_type' => 'required|string',
            'questions.*.choices' => 'nullable|array'
        ]);

        $survey_id = "";
        if ($request->input('survey_id')) {
            $survey = Survey::find($request->input('survey_id'));
            if ($survey) {
                $survey->update(['title' => $request->title, 'description' => $request->description]);
            }
            $survey_id = $request->input('survey_id');
        } else {
            $survey = Survey::create(['title' => $request->title, 'description' => $request->description]);
            $survey_id = $survey->id;
        }


        // Loop through questions and save them
        foreach ($request->questions as $questionData) {
            $question = SurveyQuestion::create([
                'survey_id' => $survey_id,
                'question' => $questionData['question'],
                'question_type' => $questionData['question_type'],
            ]);

            // Save choices if they exist
            if (!empty($questionData['choices'])) {
                foreach ($questionData['choices'] as $choice) {
                    SurveyChoice::create([
                        'question_id' => $question->id,
                        'choice' => $choice,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Survey saved successfully!', 'redirect_url' => route('settings.surveyedit', $survey_id)]);
    }

    public function surveyedit(Request $req, $id)
    {

        if ($req->header('Authorization')) {
            $survey_ids = EntrySurvey::where('id', $id)->first();

            $id =  $survey_ids->survey_id;
        }

        $surveyData = Survey::join('survey_questions', 'survey.id', '=', 'survey_questions.survey_id')
            ->leftJoin('survey_choices', 'survey_questions.id', '=', 'survey_choices.question_id')
            ->select(
                'survey.id as survey_id',
                'survey.title',
                'survey.description',
                'survey_questions.id as question_id',
                'survey_questions.question',
                'survey_questions.question_type',
                'survey_choices.id as choice_id',
                'survey_choices.choice'
            )
            ->where('survey.id', $id)
            ->get();

        if ($surveyData->isEmpty()) {
            return redirect()->back()->with('error', 'Survey not found');
        }

        // Format data properly
        $formattedSurvey = [
            'survey_id' => $surveyData[0]->survey_id,
            'title' => $surveyData[0]->title,
            'description' => $surveyData[0]->description,
            'questions' => []
        ];

        $questions = [];
        foreach ($surveyData as $row) {
            $questionId = $row->question_id;

            if (!isset($questions[$questionId])) {

                $survey_ans = Survey_ans::where('q_id', $row->question_id)->where('c_by', auth()->user()->id)->first();

                $questions[$questionId] = [
                    'question_id' => $questionId,
                    'question' => $row->question ?? 0,
                    'question_type' => $row ? $row->question_type : null,
                    // 'answer' => $survey_ans->answer ?? 'no ans',
                    'choices' => []
                ];
            }

            if ($row->choice !== null) {
                $questions[$questionId]['choices'][] = [
                    'choice_id' => $row->choice_id,
                    'choice' => $row->choice
                ];
            }
        }

        $formattedSurvey['questions'] = array_values($questions);

        // dd($formattedSurvey);

        //echo "<pre>"; print_r($formattedSurvey); die;
        if ($req->header('Authorization')) {

            return response()->json(['data' => $formattedSurvey]);
        } else {
            return view('settings.surveyedit', compact('formattedSurvey'));
        }
    }

    public function surveyupdate(Request $request)
    {
        if ($request->input('survey_id')) {
            $survey = Survey::find($request->input('survey_id'));
            if ($survey) {
                $survey->update(['title' => $request->title, 'description' => $request->description]);
            }
            $survey_id = $request->input('survey_id');

            return response()->json(['status' => 'success', 'message' => 'Survey saved successfully!', 'redirect_url' => route('settings.survey')]);
        }
    }

    public function surveyquestiondelete(Request $request)
    {
        SurveyQuestion::where('id', $request->input('id'))->delete();
        SurveyChoice::where('question_id', $request->input('id'))->delete();
        return response()->json(['status' => 'success']);
    }

    public function subcategory_status_update($id, $status)
    {
        $category = SubCategory::find($id);
        $category->status = $status;
        $category->save();
        return redirect('/subcategory');
    }

    public function drawing_status_update($id, $status)
    {
        $category = Drawing::find($id);
        $category->status = $status;
        $category->save();
        return redirect('/drawing');
    }

    public function role_status_update($id, $status)
    {
        $role = NewRole::find($id);
        $role->status = $status;
        $role->save();
        return redirect('/roles');
    }

    public function role_status_update_old($id, $status)
    {
        $role = Role::find($id);
        $role->status = $status;
        $role->save();
        return redirect('/roles');
    }

    public function employeelist(Request $request)
    {
        $designations = Designation::all();
        $roles = sp_role::where('status', 'active')->get();

        // dd($roles);
        $employees = Employee::leftjoin('roles', 'employee.role_id', '=', 'roles.id')
            ->select('employee.*', 'roles.name as designation_name')
            ->get();


        if ($request->header('Accept') === 'application/json') {
            $api_user = Employee::where('auth_token', $request->input('auth_token'))->first();
            if (!$api_user) {
                return response()->json(['status' => 'incorrect_auth_token']);
            } else {
                return response()->json(['employees' => $employees, 'roles' => $roles]);
            }
        } else {
            return view('settings.employee', compact('employees', 'designations', 'roles'));
        }
    }


    public function employee_permission(Request $req, string $id)
    {

        echo "hello";
    }

    public function roleedit(Request $request)
    {
        $notifications = RoleNotification::where('role_id', $request->input('role_id'))->get();
        $permissions = RolePermission::where('role_id', $request->input('role_id'))->get();

        $data = [];
        $data['notifications'] = $notifications;
        $data['permissions'] = $permissions;

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function roleslist(Request $request)
    {
        $roles = sp_role::all();
        return view('settings.roles', compact('roles'));
    }

    public function roleslist_old(Request $request)
    {
        //$roles = Role::all();
        $roles = Role::selectRaw('GROUP_CONCAT(employee_id) as employee_id, GROUP_CONCAT(role_title) as role_title, GROUP_CONCAT(status) as status, GROUP_CONCAT(id) as role_id, project_id')
            ->groupBy('project_id')
            ->get();

        //echo "<pre>"; print_r($roles); echo "</pre>"; die;

        $employee_array = [];

        foreach ($roles as $role) {

            $arr_employees = explode(",", $role->employee_id);
            $arr_role_title = explode(",", $role->role_title);
            $arr_status = explode(",", $role->status);
            $arr_id = explode(",", $role->role_id);

            // echo "<pre>"; print_r($arr_employees); echo "</pre>";
            // echo "<pre>"; print_r($arr_role_title); echo "</pre>";
            // echo "<pre>"; print_r($arr_status); echo "</pre>";
            // echo "<pre>"; print_r($arr_id); echo "</pre>";

            // echo "-----------------------";

            $tmp_array = [];
            foreach ($arr_employees as $index => $arr_employee) {
                $employee = Employee::find($arr_employee);
                $tmp_array[] = ["id" => $arr_id[$index], "name" => $employee->name, "status" => $arr_status[$index], "designation" => $arr_role_title[$index]];
            }
            $employee_array[$role->project_id] = $tmp_array;
        }

        //echo "<pre>"; print_r($employee_array); die;

        return view('settings.roles', compact('roles', 'employee_array'));
    }

    public function rolescreate_old(Request $request)
    {
        $projects = Project::all();
        $employees = Employee::where('status', 'active')->get();
        return view('settings.rolescreate', compact('employees', 'projects'));
    }

    public function rolescreate(Request $request)
    {
        $list = sp_role::all();
        return view('settings.rolescreate', ['list' => $list]);
    }

    public function rolesedit($id)
    {
        $role = sp_role::find($id);

        $permissionIds = DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->pluck('permission_id');

        // Optional: Load permission names
        $per = sp_per::whereIn('id', $permissionIds)->pluck('name')->toArray();
        // dd($permissions);

        // $role_permission = sp_per::where('role_id', $id)->pluck('permission_id')->toArray();

        return view('settings.rolesedit', compact('role', 'per'));
    }

    // public function rolesstore_old(Request $request)
    // {

    //     $request_data = $request->except('_token');
    //     if ($request->input('role_id')) {
    //         $role_id = $request->input('role_id');
    //     } else {
    //         $role = Role::create($request_data);
    //         $role_id = $role->id;
    //     }

    //     if ($request->input('project_detail_permission')) {
    //         $array = [];
    //         $array['role_id'] = $role_id;
    //         $array['section'] = 'project_details';
    //         $array['permission'] = $request->input('project_detail_permission');
    //         RolePermission::create($array);
    //     }
    //     if ($request->input('site_inspection_permission')) {
    //         $array = [];
    //         $array['role_id'] = $role_id;
    //         $array['section'] = 'site_inspection';
    //         $array['permission'] = $request->input('site_inspection_permission');
    //         RolePermission::create($array);
    //     }
    //     if ($request->input('design_planning_permission')) {
    //         $array = [];
    //         $array['role_id'] = $role_id;
    //         $array['section'] = 'design_planning';
    //         $array['permission'] = $request->input('design_planning_permission');
    //         RolePermission::create($array);
    //     }

    //     if ($request->input('project_detail_notification')) {
    //         foreach ($request->input('project_detail_notification') as $notification) {
    //             $array = [];
    //             $array['role_id'] = $role_id;
    //             $array['section'] = 'project_details';
    //             $array['notification'] = $notification;
    //             RoleNotification::create($array);
    //         }
    //     }
    //     if ($request->input('site_inspection_notification')) {
    //         foreach ($request->input('site_inspection_notification') as $notification) {
    //             $array = [];
    //             $array['role_id'] = $role_id;
    //             $array['section'] = 'site_inspection';
    //             $array['notification'] = $notification;
    //             RoleNotification::create($array);
    //         }
    //     }
    //     if ($request->input('design_planning_notification')) {
    //         foreach ($request->input('design_planning_notification') as $notification) {
    //             $array = [];
    //             $array['role_id'] = $role_id;
    //             $array['section'] = 'design_planning';
    //             $array['notification'] = $notification;
    //             RoleNotification::create($array);
    //         }
    //     }

    //     return redirect('/roles');
    // }

    // newer version of role creat and assign permisson to roles....

    public function rolesstore(Request $request)
    {

        // dd($request->all());
        // $request_data = $request->except('_token');

        if ($request->input('role_id')) {

            // dd('role_id');

            $role_id = $request->role_id;

            $role = sp_role::find($role_id);

            $role->syncPermissions($request->roles);
        } else {

            // insert condition .....


            // $validated = $request->validate([
            //     'role_title' => 'unique:roles,name',
            //     'description' => 'required|string|max:255',
            // ]);
            // $role = NewRole::create($validated);
            // $role_id = $role->id;

            // $old_role =  sp_role::create(['name' => $request->role_title, 'role_description' => $request->description]);

            // $per = ['tab-profile', 'tab-survey', 'tab-drawing', 'tab-progress', 'tab-qc', 'tab-snags', 'tab-docs/link'];

            // foreach ($request->roles as $p) {
            //     sp_per::create([
            //         'name' => $p,
            //         'guard_name' => 'web'
            //     ]);
            // }

            // $role_id = $old_role->id;

            // dd($request->role_prime);

            // dd($request->all());

            sp_role::find($request->role_prime)?->syncPermissions($request->roles);

            // $role = sp_role::find($request->role_prime);

            // $role->syncPermissions($request->roles);
        }

        // NewRolePermission::where('role_id', $role_id)->delete();

        // foreach ($request->input('permissions') as $permission) {
        //     $array = [];
        //     $array['role_id'] = $role_id;
        //     $array['permission'] = $permission;
        //     NewRolePermission::create($array);
        // }

        return redirect('/roles');
    }





    public function category_store(Request $request)
    {
        $request_data = $request->except('_token');
        if ($request->input('id')) {
            $category = Category::find($request->input('id'));
            if ($category) {
                $category->update($request_data);
            }
        } else {
            Category::create($request_data);
        }
        return redirect('/category');
    }

    public function subcategory_store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|integer',
            'sub_category' => 'required|string|max:100',
        ]);

        $request_data = $request->except('_token');

        if ($request->input('id')) {
            $category = SubCategory::find($request->input('id'));
            if ($category) {
                $category->update($request_data);
            }
        } else {
            $exists = SubCategory::where('category_id', $request->category_id)
                ->where('sub_category', $request->sub_category)
                ->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'Subcategory already exists for this category.');
            }
            SubCategory::create($request_data);
        }
        return redirect('/subcategory');
    }

    public function subcategorylist(Request $request, $id = 0)
    {
        $categories = Category::all();
        $sub_categories = SubCategory::join('category', 'sub_category.category_id', '=', 'category.id')
            ->select('sub_category.*', 'category.category as category_name')
            ->get();

        if ($request->header('Authorization')) {

            $sub = SubCategory::where('category_id', $id)->where('status', 'active')->select('id', 'sub_category')->get();

            return response()->json(['data' => $sub]);
        } else {
            return view('settings.subcategory', compact('sub_categories', 'categories'));
        }
    }

    public function categorylist(Request $request)
    {
        $categories = Category::all();

        if ($request->header('Authorization')) {

            $categories = Category::where('status', 'active')->get();

            return response()->json(['data' => $categories]);
        } else {
            return view('settings.category', compact('categories'));
        }
    }

    public function surveylist()
    {
        $survey_data = Survey::select('survey.id', 'survey.title', 'survey.description', 'survey.status')
            ->withCount('questions')
            ->get();
        return view('settings.survey', compact('survey_data'));
    }

    public function surveycreate(Request $request)
    {
        return view('settings.surveycreate');
    }

    public function drawinglist(Request $request)
    {
        $drawings = Drawing::all();
        return view('settings.drawing', compact('drawings'));
    }

    public function drawing_store(Request $request)
    {
        $request_data = $request->except('_token');
        if ($request->input('id')) {
            $category = Drawing::find($request->input('id'));
            if ($category) {
                $category->update($request_data);
            }
        } else {
            Drawing::create($request_data);
        }
        return redirect('/drawing');
    }

    public function qclist(Request $request)
    {
        $qcs = QC::select('qc.id', 'qc.title', 'qc.description', 'qc.status')
            ->withCount('checklists')
            ->get();
        return view('settings.qc', compact('qcs'));
    }

    public function qccreate(Request $request)
    {
        return view('settings.qccreate');
    }

    public function qcedit(Request $req, $id)
    {
        $qc = QC::find($id);
        $qc_checklists = QCChecklist::where('qc_id', $id)->get();

        if ($req->header('Authorization')) {

            $ent_qc = EntryQC::where('id', $id)->select('checklist')->first();

            $qc_checklists_api = QCChecklist::whereIn('id', $ent_qc->checklist)->get();

            // $row = Qc_ans::where('qc_entry', $id)->where('c_by', auth()->user()->id)->first();

            return response()->json(['data' => $qc_checklists_api]);
        } else {
            return view('settings.qcedit', compact('qc', 'qc_checklists'));
        }
    }

    public function snaglist(Request $request)
    {
        $snags = Snag::all();
        return view('settings.snag', compact('snags'));
    }

    public function itemlist(Request $request)
    {
        $items = Item::all();
        return view('settings.item', compact('items'));
    }

    public function uomlist(Request $request)
    {
        $uoms = Uom::all();
        return view('settings.unit', compact('uoms'));
    }

    public function permissions_list()
    {
        return view('settings.permissions');
    }

    public function permissions_create()
    {
        return view('settings.permissionscreate');
    }

    public function permissions_edit()
    {
        return view('settings.permissionsedit');
    }

    public function password(Request $request)
    {
        return view('settings.password');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function fcm()
    {
        $token = 'fej8TxgvR-6WYCZwgQAFlp:APA91bE4DgXjFCAM178Q9tx-FXbZ8Lny5OsH4sSOgpCJpt3opTBEQwHNfY1PuCuVuo3RnOUzYGB9mdllhKDq7xqkkh7sWfPHFCWb35ymxewM9azs8ggpdm4';
        $title = 'Test Notificationnnnn';
        $body = 'This is a test notification';

        $response = app(FirebaseService::class)->send_notify($token, $title, $body);

        // dd($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function password_update(Request $request)
    {

        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:6', // Ensure new password is at least 6 characters
            'confirm_password' => 'required|same:new_password'
        ]);

        $user = auth()->user();
        if ($user->password != $request->input('password')) {
            return back()->withErrors(['old_password' => 'The old password is incorrect.']);
        } else if ($request->input('new_password') != $request->input('confirm_password')) {
            return back()->withErrors(['new_password' => 'New password does not match']);
        } else {
            $employee = Employee::find($user->id);
            $employee->password = $request->input('new_password');
            $employee->save();
            return back()->with('success', 'Password updated successfully');
        }
    }

    public function qcstore(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'nullable|array',
            'questions.*' => 'required|string|max:500'
        ]);

        $request_data = $request->except('_token');

        $qc_id = "";

        if ($request->input('id')) {
            $qc_id = $request->input('id');
            $qc = QC::find($qc_id);
            if ($qc) {
                $qc->update([
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
            }
            QCChecklist::where('qc_id', $qc_id)->delete();
        } else {
            $qc = QC::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);
            $qc_id = $qc->id;
        }

        if ($request->has('questions')) {
            foreach ($request->questions as $question) {
                QCChecklist::create([
                    'qc_id' => $qc_id,
                    'question' => $question,
                ]);
            }
        }

        return redirect('/qc')->with('success', 'QC and Checklist saved successfully.');
    }
}
