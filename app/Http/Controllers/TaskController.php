<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\Survey;
use App\Models\Snag;
use App\Models\Survey_ans;
use App\Models\Snag_ans;
use App\Models\Qc_ans;
use App\Models\QC;
use App\Models\Notify;
use App\Models\{EntrySurvey, EntryQC, EntrySnag};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Traits\common;
use Carbon\Carbon;

class TaskController extends Controller
{
    use common;
    public function index(Request $request)
    {
        if ($request->header('Authorization')) {

            $table1Data = Task::with(['created_user:id,name,role_id', 'created_user.role:id,name', 'project:id,project_name,project_id'])
                ->where('assigned_to', auth()->user()->id)
                ->orderBy('created_at', 'desc') // Add descending order
                ->get()->map(function ($item) {
                    $item->setAttribute('source_table', 'task');
                    $item->setAttribute('project_title', $item->project->project_name ?? null);
                    $item->setAttribute('cby_det', $item->created_user->name ?? null);
                    $item->setAttribute('cby_role', $item->created_user->role->name ?? null);
                    $item->setAttribute('file_attachment', $item->file_attachment ? env('AWS_URL') . $item->file_attachment : null);
                    $item->setAttribute('c_at', $item->created_at ? $item->created_at->format('d-m-Y H:i:s') : null);
                    $item->setAttribute('pro_code', optional($item->project)->project_id);
                    $item->setAttribute('close_status', DB::table('task_close')->where('request_by_task', $item->id)->value('status') ?? 0);
                    $task_status = 'New';

                    // latest task_close record
                    $taskClose = DB::table('task_close')
                        ->where('request_to_task', $item->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($taskClose && $taskClose->status == 'approved') {
                        $task_status = 'Approved';

                        // ✅ Completed only if both are pending
                    } elseif ($item->status === 'pending' && $taskClose && $taskClose->status === 'pending') {
                        $task_status = 'Completed';
                    } elseif ($item->status === 'completed') {
                        $task_status = 'Completed';
                    } elseif ($taskClose && in_array($taskClose->status, ['completed', 'closed'])) {
                        $task_status = 'Completed';
                    } elseif ($taskClose && $taskClose->status == 'pending') {
                        $task_status = 'Pending';
                    } elseif ($item->end_timestamp && now()->gt(\Carbon\Carbon::parse($item->end_timestamp))) {
                        $task_status = 'Pending';
                    }

                    $item->setAttribute('task_status', $task_status);

                    unset($item->created_user, $item->category, $item->sub_category, $item->project);

                    return $item;
                });

            $table2Data = EntrySurvey::with(['user_cby:id,name,role_id', 'user_cby.role:id,name', 'survey:id,title', 'project:id,project_name,project_id', 'survey_ans:entry_ans'])
                ->where('assigned_to', auth()->user()->id)
                ->orderBy('created_at', 'desc') // Add descending order
                ->get()->map(function ($item) {
                    $item->setAttribute('source_table', 'ent_survey');
                    $item->setAttribute('survey_title', $item->survey->title ?? null);
                    $item->setAttribute('project_title', $item->project->project_name ?? null);
                    $item->setAttribute('cby_det', $item->user_cby->name ?? null);
                    $item->setAttribute('cby_role', $item->user_cby->role->name ?? null);
                    $item->setAttribute('file_attachment', $item->file_attachment ? env('AWS_URL') . 'survey/' . $item->file_attachment : null);
                    $item->setAttribute('c_at', $item->created_at ? $item->created_at : null);
                    $item->setAttribute('pro_code', optional($item->project)->project_id);
                    $currentDateTime = now();
                    $endDateTime = $item->due_date ? Carbon::parse($item->due_date) : null;

                    if ($endDateTime && $currentDateTime->greaterThan($endDateTime) && $item->status != 'completed' && $item->status != 'approved') {
                        $statusClass = 'text-danger';
                        $statusName = 'Pending';
                    } elseif ($item->status == 'completed') {
                        $statusClass = 'text-success';
                        $statusName = 'Completed';
                    } elseif ($item->status == 'approved') {
                        $statusClass = 'text-success';
                        $statusName = 'Approved';
                    } else {
                        $statusClass = 'text-primary';
                        $statusName = 'New';
                    }

                    // $item->setAttribute('status_class', $statusClass);
                    $item->setAttribute('task_status', $statusName);

                    // ✅ Remove unwanted relations
                    unset($item->user_cby, $item->survey, $item->project, $item->survey_ans);

                    return $item;
                });

            $table3Data = EntryQC::with(['user_cby:id,name,role_id', 'user_cby.role:id,name', 'qc:id,title', 'project:id,project_name,project_id', 'qc_ans:qc_entry'])
                ->where('assigned_to', auth()->user()->id)
                ->orderBy('created_at', 'desc') // Add descending order
                ->get()->map(function ($item) {
                    $item->setAttribute('source_table', 'ent_qc');
                    $item->setAttribute('qc_name', $item->qc->title ?? null);
                    $item->setAttribute('project_title', $item->project->project_name ?? null);
                    $item->setAttribute('cby_det', $item->user_cby->name ?? null);
                    $item->setAttribute('cby_role', $item->user_cby->role->name ?? null);
                    $item->setAttribute('file_attachment', $item->file_attachment ? env('AWS_URL') . 'qc/' . $item->file_attachment : null);
                    $item->setAttribute('c_at', $item->created_at ? $item->created_at : null);
                    $item->setAttribute('pro_code', optional($item->project)->project_id);
                    $currentDateTime = now();
                    $endDateTime = $item->due_date ? Carbon::parse($item->due_date) : null;

                    if ($endDateTime && $currentDateTime->greaterThan($endDateTime) && $item->status != 'completed' && $item->status != 'approved') {
                        $statusClass = 'text-danger';
                        $statusName = 'Pending';
                    } elseif ($item->status == 'completed') {
                        $statusClass = 'text-success';
                        $statusName = 'Completed';
                    } elseif ($item->status == 'approved') {
                        $statusClass = 'text-success';
                        $statusName = 'Approved';
                    } else {
                        $statusClass = 'text-primary';
                        $statusName = 'New';
                    }

                    // $item->setAttribute('status_class', $statusClass);
                    $item->setAttribute('task_status', $statusName);
                    unset($item->user_cby, $item->qc, $item->project, $item->qc_ans);
                    return $item;
                });

            $table4Data = EntrySnag::with(['user_cby:id,name,role_id', 'user_cby.role:id,name', 'snag:id,category', 'project:id,project_name,project_id', 'snag_ans:entry_snag'])
                ->where('assigned_to', auth()->user()->id)
                ->orderBy('created_at', 'desc') // Add descending order
                ->get()->map(function ($item) {
                    $item->setAttribute('source_table', 'ent_snag');
                    $item->setAttribute('qc_name', $item->snag->category ?? null);
                    $item->setAttribute('project_title', $item->project->project_name ?? null);
                    $item->setAttribute('cby_det', $item->user_cby->name ?? null);
                    $item->setAttribute('cby_role', $item->user_cby->role->name ?? null);
                    $item->setAttribute('ans_row', $item->snag_ans->count());
                    $item->setAttribute('file_attachment', $item->file_attachment ? env('AWS_URL') . 'snag/' . $item->file_attachment : null);
                    $item->setAttribute('c_at', $item->created_at ? $item->created_at : null);
                    $item->setAttribute('pro_code', optional($item->project)->project_id);
                    $currentDateTime = now();
                    $endDateTime = $item->due_date ? Carbon::parse($item->due_date) : null;

                    if ($endDateTime && $currentDateTime->greaterThan($endDateTime) && $item->status != 'completed' && $item->status != 'approved') {
                        $statusClass = 'text-danger';
                        $statusName = 'Pending';
                    } elseif ($item->status == 'completed') {
                        $statusClass = 'text-success';
                        $statusName = 'Completed';
                    } elseif ($item->status == 'approved') {
                        $statusClass = 'text-success';
                        $statusName = 'Approved';
                    } else {
                        $statusClass = 'text-primary';
                        $statusName = 'New';
                    }

                    // $item->setAttribute('status_class', $statusClass);
                    $item->setAttribute('task_status', $statusName);
                    unset($item->user_cby, $item->snag, $item->project, $item->snag_ans);
                    return $item;
                });

            $allData = collect($table1Data)->merge(collect($table2Data))
                ->merge(collect($table3Data))
                ->merge(collect($table4Data));

            $sortedData = $allData->sortByDesc('created_at')->values()->map(function ($item) {
                unset($item->created_at, $item->updated_at);
                return $item;
            });

            $statusGroups = $sortedData->groupBy('status');
            return response()->json(['data' => $statusGroups]);
        }

        // For web requests, get data for all tabs - Add descending order here too

        // Created Tasks (tasks created by the logged-in user) - Updated to include close requests
        $createdTasks = Task::with(['project:id,project_name', 'user:id,name'])
            ->where('created_by', auth()->user()->id)
            ->whereColumn('parent_task_id', 'id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($task) {
                // ✅ Always fetch the latest close request for this task
                $taskClose = DB::table('task_close')
                    ->where('request_to_task', $task->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $task->close_request = $taskClose;

                // ✅ Default values
                $currentDateTime = now();
                $endDateTime = $task->end_timestamp ? \Carbon\Carbon::parse($task->end_timestamp) : null;

                // ✅ Determine final status
                if ($taskClose && $taskClose->status === 'approved') {
                    $task->custom_status = 'Approved';
                    $task->status_class = 'text-success';
                    $task->status_filter = 'approved';
                } elseif ($task->status === 'pending' && $taskClose && $taskClose->status === 'pending') {
                    $task->custom_status = 'Completed'; // ✅ special condition
                    $task->status_class = 'text-success';
                    $task->status_filter = 'completed';
                } elseif ($task->status === 'completed') {
                    $task->custom_status = 'Completed';
                    $task->status_class = 'text-success';
                    $task->status_filter = 'completed';
                } elseif ($taskClose && in_array($taskClose->status, ['completed', 'closed'])) {
                    $task->custom_status = 'Completed';
                    $task->status_class = 'text-success';
                    $task->status_filter = 'completed';
                } elseif ($taskClose && $taskClose->status === 'pending') {
                    $task->custom_status = 'Pending';
                    $task->status_class = 'text-danger';
                    $task->status_filter = 'pending';
                } elseif ($endDateTime && $currentDateTime->greaterThan($endDateTime)) {
                    $task->custom_status = 'Pending';
                    $task->status_class = 'text-danger';
                    $task->status_filter = 'pending';
                } else {
                    $task->custom_status = 'New';
                    $task->status_class = 'text-primary';
                    $task->status_filter = 'new';
                }

                return $task;
            });


        $assignedTasks = Task::with(['project:id,project_name', 'user:id,name', 'created_user:id,name'])
            ->where('assigned_to', auth()->user()->id)
            ->whereColumn('parent_task_id', 'id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($task) {
                // ✅ Always fetch the latest close request for this task
                $taskClose = DB::table('task_close')
                    ->where('request_to_task', $task->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $task->close_request = $taskClose;

                $currentDateTime = now();
                $endDateTime = $task->end_timestamp ? \Carbon\Carbon::parse($task->end_timestamp) : null;

                // ✅ Determine status
                if ($taskClose && $taskClose->status === 'approved') {
                    $task->custom_status = 'Approved';
                    $task->status_class = 'text-success';
                    $task->status_filter = 'approved';
                } elseif ($task->status === 'pending' && $taskClose && $taskClose->status === 'pending') {
                    $task->custom_status = 'Completed';
                    $task->status_class = 'text-success';
                    $task->status_filter = 'completed';
                } elseif ($task->status === 'completed') {
                    $task->custom_status = 'Completed';
                    $task->status_class = 'text-success';
                    $task->status_filter = 'completed';
                } elseif ($taskClose && in_array($taskClose->status, ['completed', 'closed'])) {
                    $task->custom_status = 'Completed';
                    $task->status_class = 'text-success';
                    $task->status_filter = 'completed';
                } elseif ($taskClose && $taskClose->status === 'pending') {
                    $task->custom_status = 'Pending';
                    $task->status_class = 'text-danger';
                    $task->status_filter = 'pending';
                } elseif ($endDateTime && $currentDateTime->greaterThan($endDateTime)) {
                    $task->custom_status = 'Pending';
                    $task->status_class = 'text-danger';
                    $task->status_filter = 'pending';
                } else {
                    $task->custom_status = 'New';
                    $task->status_class = 'text-primary';
                    $task->status_filter = 'new';
                }

                return $task;
            });

        return view('task.unified_list', compact('createdTasks', 'assignedTasks'));
    }

    public function completed_list(Request $request)
    {

        // dd($request->all());

        // echo "hello";


        $tasks = Task::with(['project:id,project_name', 'user:id,name'])->where('assigned_to', auth()->user()->id)->whereIn('status', ['completed'])->whereColumn('parent_task_id', 'id')->get();

        return view('task.comp_list', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();
        $sub_categories = SubCategory::where('status', 'active')->get();
        $projects = Project::all();
        return view('task.create', compact('employees', 'categories', 'sub_categories', 'projects'));
    }

    public function task_close_store(Request $req)
    {
        $fileName = null;

        if ($req->hasFile('close_file')) {
            $file = $req->file('close_file');

            // Clean file name
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());

            // S3 path inside bucket
            $filePath = 'task/' . $fileName;

            // Upload file to S3
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            // Optional: make public if you want direct access
            // Storage::disk('s3')->setVisibility($filePath, 'public');
        }

        $task = Task::find($req->close_task_id);
        $task_find = Task::where('parent_task_id', $task->parent_task_id)->first();

        $ins = DB::table('task_close')->insert([
            'request_to_task' => $task_find->id,
            'request_by_task' => $req->close_task_id,
            'assign_to' => $task_find->created_by,
            'file' => $fileName, // store only filename
            'remarks' => $req->close_description,
            'request_by' => auth()->user()->id ?? 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $task->status = 'completed';
        $task->save();

        if ($req->header('Authorization')) {
            return $ins
                ? response()->json(['status' => 'Close saved successfully!'], 200)
                : response()->json(['status' => 'Close Failed to Add'], 500);
        } else {
            return redirect('mydashboard');
        }
    }
    public function close_list(Request $req)
    {

        // dd(auth()->user()->id);


        $list = DB::table('task_close as tc')
            ->leftJoin('task as tk', 'tk.id', '=', 'tc.request_to_task')
            ->leftJoin('projects as pr', 'pr.id', '=', 'tk.project_id')
            ->where('tc.assign_to', auth()->user()->id)->select('tk.id as tk_id', 'tc.id as tc_id', 'tc.request_by as tcby', 'tk.title', 'pr.project_name', 'tc.file', 'tc.remarks', 'tk.end_timestamp', 'tc.status as tc_status')->get()->map(function ($item) {

                $item->file_name = asset('img/task/' . $item->file);
                $item->time = Carbon::parse($item->end_timestamp)->format('d-m-Y h:i:s');
                $item->user_name = Employee::where('id', $item->tcby)->value('name');

                unset($item->end_timestamp);


                return $item;
            });

        if ($req->header('Authorization')) {
            $list = $list->map(function ($item) {
                $item->file_name = asset('img/task/' . $item->file);
                return $item;
            });
        } else {
            return view('task.close_list', ['list' => $list]);
        }



        // dd($list->toArray());
    }

    public function task_close_ajax(Request $req)
    {
        // Get the task close record
        $close = DB::table('task_close')->where('id', $req->task_id)->first();

        if (!$close) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found'
            ]);
        }

        // Generate file URL from S3
        $fileLink = 'No file attached';
        if (!empty($close->file)) {
            $fileUrl = Storage::disk('s3')->url('task/' . $close->file);

            // If bucket is private, you can use temporary URL instead:
            // $fileUrl = Storage::disk('s3')->temporaryUrl('task/' . $close->file, now()->addMinutes(10));

            $fileLink = '<a href="' . $fileUrl . '" target="_blank">View File</a>';
        }

        $html = '
        <form method="POST" action="' . route('close.task') . '" class="row" id="assign_task_close" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            
            <div class="col-sm-12 col-md-6 mb-3">
                <label for="file" class="col-form-label">File</label>
                <div class="file-attachment">
                    ' . $fileLink . '
                </div>
            </div>
            
            <div class="col-sm-12 col-md-12 mb-3">
                <label for="description" class="col-form-label">Remarks</label>
                <p>' . htmlspecialchars($close->remarks ?? '', ENT_QUOTES, 'UTF-8') . '</p>
            </div>
            
            <div class="d-flex justify-content-center align-items-center mx-auto">
                <a href="' . route('close.task_update', ['id' => $close->id]) . '" class="btn modalbtn text-center">Approve</a>
            </div>
        </form>';

        return response()->json([
            'status' => 'success',
            'data' => $html
        ]);
    }


    public function taskCloseUpdateApi(Request $request)
    {
        $request->validate([
            'request_to_task' => 'required|integer',
        ]);

        $taskId = $request->input('request_to_task'); // works with JSON, form-data, or x-www-form-urlencoded

        $updated = DB::table('task_close')
            ->where('request_to_task', $taskId)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'updated_at' => now()
            ]);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => "Task close updated successfully"
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => "Task close record not found or already updated"
        ]);
    }


    public function getTaskCloseDetailsPost(Request $req)
    {
        $taskId = $req->task_id;

        $taskClose = DB::table('task_close')
            ->where('request_to_task', $taskId)
            ->select('id', 'remarks', 'file', 'created_at')
            ->first();

        if ($taskClose) {
            return response()->json([
                'status' => true,
                'data' => [
                    'remarks' => $taskClose->remarks,
                    'file' => $taskClose->file
                        ? env('AWS_URL') . 'task/' . $taskClose->file
                        : null,
                    'created_at' => $taskClose->created_at
                        ? Carbon::parse($taskClose->created_at)->format('Y-m-d H:i:s')
                        : null,
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No close details found for this task'
            ], 404);
        }
    }



    public function task_close_update(string $id)
    {

        DB::table('task_close')->where('id', $id)->update(['status' => 'approved']);

        $close = DB::table('task_close')->where('id', $id)->first();

        $up = DB::table('task')->where('id', $close->request_by_task)->update(['status' => 'completed']);

        return redirect()->back();
    }


    public function store(Request $request)
    {
        Log::info('Task assign', $request->all());

        // ✅ ADD THIS BLOCK HERE ↓↓↓
        $fileName = null;
        $filePath = null;
        $fileUrl = null;

        if ($request->hasFile('file_attachment')) {

            $file = $request->file('file_attachment');

            if (!$file || !$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload'
                ], 422);
            }

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $cleanName = pathinfo($originalName, PATHINFO_FILENAME);
            $cleanName = Str::slug($cleanName);

            $fileName = $cleanName . '_' . time() . '.' . $extension;
            $filePath = 'task/' . $fileName;

            Storage::disk('s3')->put(
                $filePath,
                file_get_contents($file),
                'public'
            );

            $fileUrl = Storage::disk('s3')->url($filePath);
        }
        // ✅ FILE HANDLING ENDS HERE

        // Store only the date
        $due_date = $request->input('enddate');

        $data = [
            'title' => $request->input('title'),
            'project_id' => $request->input('project_id'),
            'assigned_to' => $request->input('assigned_to'),
            'priority' => $request->input('priority'),
            'end_timestamp' => $due_date,
            'description' => $request->input('description'),
            'file_attachment' => $filePath, // nullable
            'file_name' => $fileName,       // nullable
            'status' => 'in_progress',
            'created_by' => auth()->user()->id,
        ];

        $model = Task::create($data);

        if ($request->input('t_type') == 'fresh') {
            $model->update(['parent_task_id' => $model->id]);
        } else {
            $parent_task = Task::find($request->input('old_task_id'));

            $model->update(['parent_task_id' => $parent_task->parent_task_id]);

            $parent_task->update([
                'is_assigned' => 1,
                'status' => 'completed'
            ]);
        }

        // Send notification
        $fid_token = Employee::where('id', $request->assigned_to)->value('token');

        $projectName = ($model->project_id == 1)
            ? 'General'
            : optional($model->project)->project_name;

        $notify_data = [
            'to_id' => $request->assigned_to,
            'f_id' => $model->id,
            'type' => 'task',
            'title' => 'New Task Assigned by ' . (auth()->user()->name ?? 'unknown'),
            'body' => $request->input('title') . ' in ' . $projectName,
            'token' => [$fid_token],
        ];

        $res = $this->notify_create($notify_data);

        if ($request->header('Authorization')) {
            return response()->json(['status' => 'success']);
        } else {
            return redirect($request->t_type == 'fresh' ? '/task-list' : 'mydashboard');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {

        $task = Task::find($id);
        // $taskDetails = [];
        if ($task) {

            $taskDetails = Task::with(['comments', 'comments.created_user:id,name'])->where('parent_task_id', $task->parent_task_id)->orderBy('id', 'DESC')->get();
        }

        // dd($taskDetails->first()->comments->toArray());

        if ($request->header('Authorization')) {
            return response()->json(['data' => $taskDetails]);
        } else {
            return view('task.profile', compact('taskDetails'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function task_status_update(Request $req)
    {
        $table = $req->table;

        $modelMap = [
            'task' => Task::class,
            'ent_survey' => EntrySurvey::class,
            'ent_qc' => EntryQC::class,
            'ent_snag' => EntrySnag::class,
        ];

        $model = $modelMap[$table];


        $task = $model::find($req->task_id);
        $task->status = $req->task_status;
        $task->save();
        if ($table != 'task') {

            $title_column_map = [
                'ent_survey' => ['survey_id', 'title', 'survey'], // Assuming 'survey_title' exists in Survey
                'ent_qc' => ['qc_title', 'title', 'qc'],        // Assuming 'qc_title' exists in Qc
                'ent_snag' => ['category_id', 'category', 'snag'],    // Assuming 'snag_title' exists in Snag
            ];


            $title_column = $title_column_map[$table];

            if ($table == 'ent_survey') {
                $title_col = $task->survey->title;
                $ty = 'survey';
            } elseif ($table == 'ent_qc') {  // Duplicate condition
                $title_col = $task->qc->title;
                $ty = 'qc';
            } else {
                $title_col = $task->snag->category;
                $ty = 'snag';
            }

            $to_id = $task->c_by;
            $f_id = $task->id;
            $type = $title_column[2];
            $title = $title_col . ' Completion';
            $body = $title_col . ' has been completed by  ' . auth()->user()->name . ' in ' . $task->project->project_name;
            $token = $task->user_cby ? [$task->user_cby->token] : [];

            $res = $this->notify_create($to_id, $f_id, $type, $title, $body, $token);
        }

        if ($req->header('Authorization')) {
            return response()->json(['status' => 'Task updated Successfully']);
        } else {
            // return view('task.profile', compact('taskDetails'));
        }
    }

    public function survey_ans_store(Request $req)
    {


        $answ = json_decode($req->answers, true); // Decode JSON array
        $fileAttachmentMap = $req->file('file_attachment1');  // Uploaded files



        Log::info('Decoded Answers:', $answ);

        try {

            $survey_ans = new Survey_ans();


            foreach ($answ as $index => $ans) {

                $survey_ans = new Survey_ans();

                $qType = $ans['q_type'] ?? $ans['qtype'] ?? 'Unknown'; // Handle both spellings
                $qId = $ans['q_id'];
                $answer = $ans['answer'];
                // $fileAttachments = $ans['file_attachment'];

                if ($qType === 'File') {
                    $filePaths = [];

                    // Handle file uploads dynamically based on q_id
                    if (!empty($fileAttachmentMap[$qId])) {
                        $files = $fileAttachmentMap[$qId];

                        if (!is_array($files)) {
                            $files = [$files];
                        }

                        foreach ($files as $file) {
                            if ($file && $file->isValid()) {
                                $fileName = str_replace(' ', '_', $file->getClientOriginalName());
                                $folder = 'survey';
                                $filePath = $folder . '/' . $fileName;

                                // ✅ Upload file to S3
                                Storage::disk('s3')->put($filePath, file_get_contents($file));

                                // ✅ Make public
                                Storage::disk('s3')->setVisibility($filePath, 'public');

                                // ✅ Save only file name (not URL)
                                $filePaths[] = $fileName;
                            }
                        }
                    }

                    // ✅ Store file names as JSON
                    $answer = json_encode($filePaths);
                } elseif ($qType === 'Checkbox') {
                    $answer = json_encode($answer); // It's an array
                } else {
                    $answer = is_array($answer) ? json_encode($answer) : $answer;
                }

                // Log::info("Processing QID $qId of type $qType with answer: $answer");

                $survey_ans->entry_ans = $req->entry_ans; // If entry_ans is the same for all answers
                $survey_ans->q_type = $qType; // Assuming q_type is part of the individual answer data
                $survey_ans->q_id = $qId; // Assuming q_id is part of the individual answer data
                $survey_ans->answer = $answer; // The answer provided by the user
                $survey_ans->status = 'pending'; // Set the status as pending
                $survey_ans->c_by = auth()->user()->id; // The user ID of the person who is creating the answer

                // Save the survey answer to the database
                $survey_ans->save();

                // Optional: Save to DB here


            }

            $survey_ans_entry = EntrySurvey::where('id', $req->entry_ans)->update(['status' => $req->status]);

            if ($survey_ans_entry) {
                $survey_prime = EntrySurvey::find($req->entry_ans);
                $survey_name = Survey::find($survey_prime->survey_id);
                $fid_token = Employee::where('id', $survey_prime->c_by)->value('token');
                $data = [
                    'to_id' => $survey_prime->c_by,
                    'f_id' => $survey_prime->id,
                    'type' => 'survey',
                    'title' => 'Survey Completed by ' . auth()->user()->name,
                    'body' => $survey_name->title . ' in ' . $survey_prime->project->project_name,
                    'token' => [$fid_token]
                ];

                $res = $this->notify_create($data);
            }
        } catch (\Exception $e) {
            Log::error('Error processing answers: ' . $e->getMessage());
        }





        return response()->json(['status' => 'success']);
    }



    public function snag_ans_store(Request $request)
    {
        Log::info($request->all());

        if (!$request->hasFile('file_attachment')) {
            return response()->json(['status' => 'error', 'message' => 'No files found for upload']);
        }

        try {
            $files = $request->file('file_attachment');
            $entrySnagId = $request->entry_snag;

            foreach ($files as $file) {
                // Create clean filename
                $fileName = str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = 'snag/' . $fileName;

                // ✅ Upload to S3
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                Storage::disk('s3')->setVisibility($filePath, 'public');

                // ✅ Save record in DB
                $snag_ins = new Snag_ans();
                $snag_ins->entry_snag = $entrySnagId;
                $snag_ins->file = $fileName; // only store file name (not full URL)
                $snag_ins->desp = $request->desp ?? null;
                $snag_ins->status = 'Pending';
                $snag_ins->c_by = auth()->user()->id;
                $snag_ins->save();
            }

            // ✅ Update parent entry status
            $snag_ans_entry = EntrySnag::where('id', $entrySnagId)->update(['status' => $request->status]);

            if ($snag_ans_entry) {
                // Fetch related details for notification
                $snag_entry_find = EntrySnag::with('project')->find($entrySnagId);
                $snag_name = Snag::find($snag_entry_find->category_id);
                $fid_token = Employee::where('id', $snag_entry_find->c_by)->value('token');

                // ✅ Prepare and send notification
                $data = [
                    'to_id' => $snag_entry_find->c_by,
                    'f_id' => $snag_entry_find->id,
                    'type' => 'snag',
                    'title' => 'Snag Completed by ' . auth()->user()->name,
                    'body' => $snag_name->category . ' in ' . ($snag_entry_find->project->project_name ?? 'Unknown Project'),
                    'token' => [$fid_token],
                ];

                $this->notify_create($data);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Snag updated successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Snag upload failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function survey_list(Request $request)
    {
        try {

            $survey = Survey::with('questions.choices') // Eager load related questions and choices
                ->where('id', $request->survey_id) // Filter by survey_id
                ->first();
            // $survey = Survey::where('id', $request->survey_id)->first();

            return response()->json([
                'status' => 'success',
                'data' => $survey
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'data' => $e->getMessage()
            ]);
        }
    }
    public function created_by_me(Request $req)
    {
        $tasks = Task::with(['project:id,project_name', 'user:id,name'])
            ->where('created_by', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($lt) {
                // 🔹 Force project data when project_id = 1
                if ($lt->project_id == 1) {

                    $project = new Project();
                    $project->forceFill([
                        'id' => 1,
                        'project_name' => 'General',
                    ]);

                    $project->exists = true;

                    // 🔑 Force id visibility
                    $project->makeVisible('id');

                    $lt->setRelation('project', $project);
                }

                // Format created_at
                $lt->cr_at = \Carbon\Carbon::parse($lt->created_at)->format('Y-m-d H:i:s');

                // File attachment full path (existing)
                $lt->setAttribute('file_attachment', $lt->file_attachment
                    ? env('AWS_URL') . $lt->file_attachment
                    : null);

                // Default status
                $task_status = 'New';
                $approved_file_attachment = null;

                // Get latest task_close record for this specific task
                $taskClose = \DB::table('task_close')
                    ->where('request_to_task', $lt->id)
                    ->orderBy('id', 'desc')
                    ->first();

                // ✅ Priority 1: Approved in task_close (highest priority)
                if ($taskClose && $taskClose->status == 'approved') {
                    $task_status = 'Approved';
                    // Set approved file attachment
                    $approved_file_attachment = $taskClose->file
                        ? env('AWS_URL') . 'task/' . $taskClose->file
                        : null;

                    // ✅ Priority 2: Completed only if task.status == 'pending' && task_close.status == 'pending'
                } elseif ($lt->status === 'completed' && $taskClose && $taskClose->status === 'pending') {
                    $task_status = 'Completed';

                    // ✅ Priority 3: Other task_close statuses
                } elseif ($taskClose) {
                    if ($taskClose->status == 'completed' || $taskClose->status == 'closed') {
                        $task_status = 'Completed';
                    } elseif ($taskClose->status == 'pending') {
                        $task_status = 'Pending';
                    }
                } else {
                    // No task_close record exists - check if task is overdue
                    if ($lt->end_timestamp) {
                        if (now()->gt(\Carbon\Carbon::parse($lt->end_timestamp))) {
                            $task_status = 'Pending'; // Overdue
                        } else {
                            $task_status = 'New'; // Still within deadline
                        }
                    }
                }

                $lt->setAttribute('task_status', $task_status);

                // Set approved_file_attachment only if status is Approved
                if ($task_status === 'Approved') {
                    $lt->setAttribute('approved_file_attachment', $approved_file_attachment);
                }

                return $lt;
            });

        return response()->json(['status' => 'success', 'data' => $tasks]);
    }


    // public function assign_to_me(Request $req)
    // {
    //     $tasks = Task::with(['project:id,project_name', 'user:id,name'])
    //         ->where('assigned_to', auth()->user()->id)
    //         ->orderBy('created_at', 'desc')
    //         ->get()
    //         ->map(function ($lt) {
    //             // Format created_at
    //             $lt->setAttribute('cr_at', $lt->created_at
    //                 ? $lt->created_at->format('Y-m-d H:i:s')
    //                 : null);

    //             // File path
    //             $lt->setAttribute(
    //                 'file_attachment',
    //                 $lt->file_attachment
    //                     ? env('AWS_URL') . $lt->file_attachment
    //                     : null
    //             );
    //             // Default status
    //             $task_status = 'New';

    //             // Get latest task_close record
    //             $taskClose = \DB::table('task_close')
    //                 ->where('request_to_task', $lt->id)
    //                 ->orderBy('id', 'desc')
    //                 ->first();

    //             if ($taskClose) {
    //                 if ($taskClose->status == 'approved') {
    //                     $task_status = 'Approved';

    //                     // ✅ Completed only if task.status == pending && task_close.status == pending
    //                 } elseif ($lt->status === 'completed') {
    //                     $task_status = 'Completed';
    //                 }
    //             } else {
    //                 // If not closed → check date
    //                 if ($lt->end_timestamp) {
    //                     $endDate = \Carbon\Carbon::parse($lt->end_timestamp)->toDateString();
    //                     $today = now()->toDateString();

    //                     if ($today > $endDate) {
    //                         $task_status = 'Pending';
    //                     } else {
    //                         $task_status = 'New';
    //                     }
    //                 }
    //             }

    //             $lt->setAttribute('task_status', $task_status);

    //             return $lt;
    //         });

    //     return response()->json(['status' => 'success', 'data' => $tasks]);
    // }


    public function assign_to_me(Request $req)
    {
        $tasks = Task::with(['project:id,project_name', 'user:id,name'])
            ->where('assigned_to', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($lt) {

                // 🔹 Force project data when project_id = 1
                // if ($lt->project_id == 1 && !$lt->project) {
                //     $lt->setRelation(
                //         'project',
                //         new \App\Models\Project([
                //             'id' => 1,
                //             'project_name' => 'General',
                //         ])
                //     );
                // }

                if ($lt->project_id == 1) {

                    $project = new Project();
                    $project->forceFill([
                        'id' => 1,
                        'project_name' => 'General',
                    ]);

                    $project->exists = true;

                    // 🔑 Force id visibility
                    $project->makeVisible('id');

                    $lt->setRelation('project', $project);
                }

                // Format created_at
                $lt->setAttribute('cr_at', $lt->created_at
                    ? $lt->created_at->format('Y-m-d H:i:s')
                    : null);

                // File path
                $lt->setAttribute(
                    'file_attachment',
                    $lt->file_attachment
                        ? env('AWS_URL') . $lt->file_attachment
                        : null
                );
                // Default status
                $task_status = 'New';

                // Get latest task_close record
                $taskClose = \DB::table('task_close')
                    ->where('request_to_task', $lt->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($taskClose) {
                    if ($taskClose->status == 'approved') {
                        $task_status = 'Approved';

                        // ✅ Completed only if task.status == pending && task_close.status == pending
                    } elseif ($lt->status === 'completed') {
                        $task_status = 'Completed';
                    }
                } else {
                    // If not closed → check date
                    if ($lt->end_timestamp) {
                        $endDate = \Carbon\Carbon::parse($lt->end_timestamp)->toDateString();
                        $today   = now()->toDateString();

                        if ($today > $endDate) {
                            $task_status = 'Pending';
                        } else {
                            $task_status = 'New';
                        }
                    }
                }

                $lt->setAttribute('task_status', $task_status);

                return $lt;
            });

        return response()->json(['status' => 'success', 'data' => $tasks]);
    }

    public function qc_ans_store(Request $req)
    {

        Log::info($req->all());


        try {


            $qc_ins = new Qc_ans();

            $answer = json_encode($req->answer, true);

            // $row_check = Qc_ans::where('qc_entry', $req->qc_entry)->where('q_id', $req->q_id)->first();

            // if (!$row_check) {

            // Populate the answer object
            $qc_ins->qc_entry = $req->qc_entry;
            $qc_ins->q_id = $req->q_id; // Assuming `q_id` is part of the individual answer data
            $qc_ins->answer = $answer; // The answer provided by the user
            $qc_ins->status = 'pending'; // Set the status as pending
            $qc_ins->c_by = auth()->user()->id; // The user ID of the person who is creating the answer

            // Save the survey answer to the database
            $qc_ins->save();


            $qc_ans_entry = EntryQc::where('id', $req->qc_entry)->update(['status' => $req->status]);

            if ($qc_ans_entry) {
                $qc_entry_find = EntryQc::find($req->qc_entry);
                $qc_name = QC::find($qc_entry_find->qc_title);
                $fid_token = Employee::where('id', $qc_entry_find->c_by)->value('token');
                $data = [
                    'to_id' => $qc_entry_find->c_by,
                    'f_id' => $qc_entry_find->id,
                    'type' => 'qc',
                    'title' => 'QC Completed by ' . auth()->user()->name,
                    'body' => $qc_name->title . ' in ' . $qc_entry_find->project->project_name,
                    'token' => [$fid_token]
                ];

                $res = $this->notify_create($data);
            }
            // }
            // else {

            //     $row_check->answer = $answer; // Update the answer
            //     // Save the updated record
            //     $row_check->save();
            // }

            return response()->json(['status' => $req->all()]);
        } catch (Exception $e) {
            return response()->json(['status' => $e->getMessage()]);
        }
    }
}
