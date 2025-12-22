<?php

namespace App\Http\Controllers;
use App\Models\SnagComment;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Survey;
use App\Models\EntrySurvey;
use App\Models\EntryDrawing;
use App\Models\EntryQC;
use App\Models\EntrySnag;
use App\Models\Designation;
use App\Models\Category;
use App\Models\Company;
use App\Models\SubCategory;
use App\Models\Drawing;
use App\Models\Snag;
use App\Models\Role;
use App\Models\RoleNotification;
use App\Models\RolePermission;
use App\Models\QCChecklist;
use App\Models\QC;
use App\Models\SurveyQuestion;
use App\Models\SurveyChoice;
use App\Models\Task;
use App\Models\Pro_docs;
use App\Models\Activity_work;
use App\Models\Activity_material;
use App\Models\{Snag_ans, Qc_ans, Survey_ans};
use App\Models\{Activity};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use App\Traits\common;
use PHPUnit\TextUI\Configuration\Php;

class ProjectController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // dd(auth()->user()->id);
        // $projects = Project::where('c_by', auth()->user()->id)->get();

        $projects = Project::whereJsonContains('assigned_to', (string) auth()->user()->id)->select('id', 'project_name', 'project_id', 'client_name', 'assigned_to', 'pro_city')->get()->map(function ($item) {

            $item->assigned_users = $item->assign_users;
            unset($item->assigned_to);

            return $item;
        });

        // dd($projects);

        // $tasks = Task::all();

        // $project_progress = [];

        // foreach ($projects as $project) {
        //     $totalTasks = 0;
        //     $completedTasks = 0;
        //     foreach ($tasks as $task) {
        //         if ($task->project_id == $project->id) { // Ensure task belongs to the project
        //             $totalTasks++;
        //             if ($task->status === 'completed') {
        //                 $completedTasks++;
        //             }
        //         }
        //     }
        //     $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        //     $project_progress[$project->id] = $percentage;
        // }

        return view('projects.list', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();

        return view('projects.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $request_data = $request->except('_token');

        if (isset($request_data['assigned_to'])) {
            // $request_data['assigned_to'][] = auth()->user()->id;
            $request_data['assigned_to'] = $request->assigned_to;
        }

        //echo "<pre>"; print_r($request_data); die;
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');

            // Clean and prepare file name
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());

            // Define S3 folder path
            $folder = 'pro_docs';
            $filePath = $folder . '/' . $fileName;

            // Upload file to S3
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            // Make file public
            Storage::disk('s3')->setVisibility($filePath, 'public');

            // Save only the file name in DB
            $request_data['file_attachment'] = $fileName;
        }



        if ($request->input('id')) {
            // If ID exists, update the existing project
            $project = Project::find($request->input('id'));

            // dd($request->all());
            // $request_data['assigned_to'] = array_merge($project->assigned_to, $request->assigned_to);
            if ($project) {
                $project->update($request_data);
            }
        } else {

            $request_data['c_by'] = auth()->user()->id; // Set current user ID
            // If ID doesn't exist, create a new project
            $pro_create = Project::create($request_data);

            if ($pro_create) {
                foreach ($request->assigned_to as $assign_to) {
                    $fid_token = Employee::where('id', $assign_to)->select('name', 'token')->first();
                    $data = [
                        'to_id' => $assign_to,
                        'f_id' => $pro_create->id,
                        'type' => 'project',
                        'title' => 'Project -  ' . $request->project_name,
                        'body' => 'New Project Assigned By ' . auth()->user()->name,
                        'token' => [$fid_token->token]
                    ];
                    $res = $this->notify_create($data);
                }
            }
        }
        return redirect('/project-list');
    }

    // public function assignto(Request $request)
    // {
    //     $project = Project::find($request->project_id);
    //     if (!$project)
    //         return response()->json(['data' => []]);

    //     // If project is general or assigned_to is empty
    //     if ($project->is_general || empty($project->assigned_to)) {
    //         $users = Employee::select('id', 'name')->orderBy('name')->get();
    //     } else {
    //         // assigned_users relation returns only assigned employees
    //         $users = $project->assigned_users;
    //     }

    //     return response()->json(['data' => $users]);
    // }

    // public function downloadFile($id)
    // {
    //     $project = Project::findOrFail($id);

    //     if ($project->file_attachment && Storage::disk('public')->exists(str_replace('storage/', '', $project->file_attachment))) {
    //         return response()->download(public_path($project->file_attachment), $project->file_name);
    //     }

    //     return back()->with('error', 'File not found!');
    // }

    public function drawing_store(Request $request)
    {
        log::info('drawing', $request->all());

        if (!$request->header('Authorization')) {
            $request->validate([
                'drawing_id' => 'required|integer',
                'version' => 'required|string',
                'file_attachment' => 'nullable|file'
            ]);
        }

        $filePath = $fileName = null;
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');

            // Clean file name
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());

            // Define S3 path inside bucket
            $folder = 'draw';
            $filePath = $folder . '/' . $fileName;

            // Upload file to S3
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            // Make file public (optional)
            Storage::disk('s3')->setVisibility($filePath, 'public');
        }
        // updating the entry drawign ....
        if ($request->input('id')) {
            $survey = EntryDrawing::find($request->input('id'));
            if ($survey) {
                $updateData = [
                    'drawing_id' => $request->drawing_id,
                    'version' => $request->version,
                    'uploaded_by' => auth()->user()->id,
                    'uploaded_on' => date('Y-m-d H:i:s'),
                ];

                // Only update file fields if a new file was uploaded
                if ($filePath) {
                    $updateData['file_attachment'] = $fileName;
                    $updateData['file_name'] = $fileName;
                }

                $survey->update($updateData);
            }
        }
        // If creating a new entry
        else {

            // $model = EntryDrawing::where(['drawing_id' => $request->drawing_id, 'project_id' => $request->project_id, 'status' => 'approved']);
            // if ($model) {
            //     $model->update(['is_draft' => 1]);
            // }

            $createData = [
                'drawing_id' => $request->drawing_id,
                'version' => $request->version,
                'uploaded_by' => auth()->user()->id,
                'uploaded_on' => date('Y-m-d H:i:s'),
                'project_id' => $request->project_id,
                'c_by' => auth()->user()->id ?? 1
            ];

            // Include file fields only if a file was uploaded
            if ($filePath) {
                $createData['file_attachment'] = $fileName;
                $createData['file_name'] = $fileName;
            }

            $ent_draw = EntryDrawing::create($createData);

            $draw_det = DB::table('drawing')->where('id', $request->drawing_id)->first();

            $assigned_ids = Project::where('id', $request->project_id)->value('assigned_to'); // already array or int[]

            if (!is_array($assigned_ids)) {
                $assigned_ids = [$assigned_ids]; // fallback if it's a single int
            }

            $employees = Employee::whereIn('id', $assigned_ids)
                ->where('role_id', 2)
                ->get();
            // Step 3: Send notification to each matching employee
            foreach ($employees as $employee) {
                // if (!empty($employee->token)) {
                $data = [
                    'to_id' => $employee->id,
                    'f_id' => $ent_draw->id,
                    'type' => 'draw',
                    'title' => 'New Drawing Created by ' . (auth()->user()->name ?? 'unknown'),
                    'body' => $draw_det->title . ' - ' . $draw_det->file_type . '-' . $ent_draw->project->project_name,
                    'token' => [$employee->token] // Make sure each employee has a valid token
                ];

                $res = $this->notify_create($data); // Send the notification
                // }
            }
        }
        if ($request->header('Authorization')) {
            return response()->json(['status' => 'Success'], 200);
        } else {

            return back()->with('success', 'Drawing saved successfully!')->with('active_tab', 'drawing');
        }
    }

    public function drawing_status_update(Request $req)
    {
        // Log::info($req->all());


        $model = EntryDrawing::find($req->id);

        $draw_det = DB::table('drawing')->where('id', $model->drawing_id)->first();
        // $action = $req->input('action'); // 'Approve', 'Reject', or 'Delete'
        // dd($req->all());
        if ($model) {

            $model->update(['status' => $req->status, 'approved_by' => auth()->user()->id ?? 0]);

            $employee = Employee::where('id', $model->uploaded_by)->value('token');

            // log::info($employee);
            // dd($employee);

            // if (!empty($employee->token)) {
            $data = [
                'to_id' => $model->uploaded_by,
                'f_id' => $model->id,
                'type' => 'draw',
                'title' => 'Drawing ' . $req->status . ' by ' . (auth()->user()->name ?? 'unknown'),
                'body' => $draw_det->title . ' - ' . $draw_det->file_type . '-' . $model->project->project_name,
                'token' => [$employee] // Make sure each employee has a valid token
            ];

            $res = $this->notify_create($data); // Send the notification
            // }
            return response()->json(['status' => 'success', 'message' => 'Saved successfully!'], 200);
        }
        return response()->json(['status' => 'failed', 'message' => 'Errorto fail successfully!'], 200);
        // }
        // return redirect()->with(['status' => 'success', 'message' => 'Saved successfully!'])->with('active_tab', 'drawing');
    }

    public function qc_store(Request $request)
    {

        // Log::info($request->allFiles);
        Log::info($request->all());


        if (!$request->header('Authorization')) {
            $request->validate([
                'qc_title' => 'required|string',
                'assigned_to' => 'required|integer',
                'due_date' => 'nullable|date',
                // 'file_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,txt,svg|max:12048'
            ]);
        }

        $filePath = $fileName = null;
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');

            // Clean filename
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());
            $folder = 'qc'; // S3 folder name
            $filePath = $folder . '/' . $fileName;

            // Upload to S3
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            // Optional: make file public
            Storage::disk('s3')->setVisibility($filePath, 'public');
        }

        // dd($request->all());

        try {

            if ($request->input('id')) {
                $qc_model = EntryQC::find($request->input('id'));
                if ($qc_model) {
                    $updateData = [
                        'qc_title' => $request->qc_title,
                        'checklist' => $request->checklist,
                        'assigned_to' => $request->assigned_to,
                        'due_date' => $request->due_date,
                    ];
                    if ($filePath) {
                        $updateData['file_attachment'] = $fileName;
                        $updateData['file_name'] = $fileName;
                    }
                    $qc_model->update($updateData);
                }
            } else {
                $createData = [
                    'qc_title' => $request->qc_title,
                    'checklist' => $request->checklist,
                    'assigned_to' => $request->assigned_to,
                    'due_date' => $request->due_date,
                    'project_id' => $request->project_id,
                    'c_by' => auth()->user()->id ?? 1
                ];
                if ($filePath) {
                    $createData['file_attachment'] = $fileName ?? null;
                    $createData['file_name'] = $fileName ?? null;
                }
                $last = EntryQC::create($createData);

                $qc_name = QC::find($request->qc_title);

                // $to_id = $request->assigned_to;
                // $f_id = $last->id;
                // $type = 'qc';
                // $title = 'New QC Assigned by ' . auth()->user()->name;
                // $body = $qc_name->title . ' in ' . $last->project->project_name;
                // $token = [$last->user->token];

                $data = [
                    'to_id' => $request->assigned_to,
                    'f_id' => $last->id,
                    'type' => 'qc',
                    'title' => 'New QC Assigned by ' . auth()->user()->name ?? 'unknown',
                    'body' => $qc_name->title . ' in ' . $last->project->project_name,
                    'token' => [$last->user->token]
                ];

                $res = $this->notify_create($data);

                // $res = $this->notify_create($to_id, $f_id, $type, $title, $body, $token);
            }
        } catch (Exception $e) {
            // Log for debugging
            Log::error('QC Save Error: ' . $e->getMessage());
        }

        if ($request->header('Authorization')) {
            return response()->json(['status' => 'Success'], 200);
        } else {
            return back()->with(['success' => 'QC saved successfully!', 'active_tab' => 'qc']);
        }
    }

    public function survey_store(Request $request)
    {

        // dd($request->all());

        if (!$request->header('Authorization')) {
            $request->validate([
                'survey_id' => 'required|integer',
                'instruction' => 'nullable|string',
                'assigned_to' => 'required|integer',
                'due_date' => 'nullable|date',
                'file_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,txt,svg|max:12048'
            ]);
        }

        $filePath = null;
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());
            $folder = 'survey';
            $filePath = $folder . '/' . $fileName;

            // Upload file to S3
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            // Make file public (optional)
            Storage::disk('s3')->setVisibility($filePath, 'public');
        }
        if ($request->input('id')) {
            $survey = EntrySurvey::find($request->input('id'));
            if ($survey) {
                $updateData = [
                    'survey_id' => $request->survey_id,
                    'instruction' => $request->instruction,
                    'assigned_to' => $request->assigned_to,
                    'due_date' => $request->due_date,
                ];
                if ($filePath) {
                    $updateData['file_attachment'] = $filePath;
                }
                $survey->update($updateData);
            }
            // log::info($request->all());
        } else {
            $createData = [
                'survey_id' => $request->survey_id,
                'instruction' => $request->instruction,
                'assigned_to' => $request->assigned_to,
                'due_date' => $request->due_date,
                'file_attachment' => $fileName ?? null,
                'project_id' => $request->project_id,
                'c_by' => auth()->user()->id ?? 1,
            ];
            // if ($filePath) {
            //     $createData['file_attachment'] = $filePath;
            // }
            $last = EntrySurvey::create($createData);

            // log::info($createData);

            $survey_name = Survey::find($request->survey_id);
            $data = [
                'to_id' => $request->assigned_to,
                'f_id' => $last->id,
                'type' => 'survey',
                'title' => 'New Survey Assigned by ' . auth()->user()->name ?? 'Unknown',
                'body' => $survey_name->title . ' in ' . $last->project->project_name,
                'token' => [$last->user->token]
            ];

            $res = $this->notify_create($data);
        }

        if ($request->header('Authorization')) {
            return response()->json(['status' => 'Survey saved successfully!'], 200);
        } else {
            return back()->with(['success' => 'Survey saved successfully!', 'active_tab' => 'survey']);
        }
    }

    public function show($id)
    {
        $project = Project::find($id);
        $project_id = $id;

        $surveys = Survey::where('status', 'active')->get();
        $tasks = Task::with('parentTask')->where('parent_task_id', 0)->where('project_id', $id)->get();
        $qcs = QC::where('status', 'active')->get();
        $qc_checklists = QCChecklist::all();
        $employees = Employee::where('status', 'active')->get();
        $snags = Snag::where('status', 'active')->get();

        $pro_docs = Pro_docs::where('status', 'Active')
            ->where('pro_id', $project_id)
            ->get()
            ->map(function ($doc) {
                if ($doc->file_attachment && !str_starts_with($doc->file_attachment, 'http')) {
                    $doc->file_attachment = Storage::disk('s3')->url($doc->file_attachment);
                }
                return $doc;
            });

        $ent_surveys = EntrySurvey::with('user', 'survey')
            ->where('project_id', $id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($lt) {
                $lt->setAttribute('approved', Employee::where('id', $lt->approved_by)->value('name') ?? 'Not approved');
                return $lt;
            });

        // ✅ Fixed: no extra ->get() since getActive2DFloorPlanDrawings already returns a collection
        $drawings = $this->getActive2DFloorPlanDrawings($id);

        $ent_qcs = EntryQC::with('user', 'qc')
            ->where('project_id', $id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($lt) {
                $lt->setAttribute('approved', Employee::where('id', $lt->approved_by)->value('name') ?? 'Not approved');
                return $lt;
            });

        $rejected_ent_drawings = Drawing::join('entry_drawing', 'drawing.id', '=', 'entry_drawing.drawing_id')
            ->leftJoin('employee', 'entry_drawing.uploaded_by', '=', 'employee.id')
            ->where(function ($query) use ($id) {
                $query->where(['entry_drawing.project_id' => $id, 'entry_drawing.is_draft' => 1])
                    ->orWhereNull('entry_drawing.project_id');
            })
            ->select('drawing.*', 'entry_drawing.status', 'employee.name as uploaded_by', 'entry_drawing.uploaded_on', 'entry_drawing.version', 'entry_drawing.file_attachment')
            ->get();

        $ent_snags = EntrySnag::with('user', 'snag')
            ->where('project_id', $id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($lt) {
                $lt->setAttribute('approved', Employee::where('id', $lt->approved_by)->value('name') ?? 'Not approved');
                $lt->setAttribute('comment_count', SnagComment::where('snag_id', $lt->id)->count());
                return $lt;
            });

        $project_employees = Employee::whereIn('employee.id', $project->assigned_to)
            ->select('id', 'name')
            ->get();

        $pro_progress_stage = DB::table('progress_stage')->where('pro_id', $project_id)->orderBy('id')->get();

        $pro_progress_tab = DB::table('progress_activity')->where('pro_id', $project_id)
            ->selectRaw('stage, count(activity) as sub_count')
            ->groupBy('stage')->get()
            ->map(function ($item) {
                $stage_det = DB::table('progress_stage')->where('id', $item->stage)
                    ->select('id', 'stage', 'sc_start', 'sc_end')
                    ->first();

                $item->stage_name = $stage_det->stage ?? null;
                $item->sc_start = $stage_det->sc_start ?? null;
                $item->sc_end = $stage_det->sc_end ?? null;

                $act_id = DB::table('progress_activity')->where('stage', $item->stage)->pluck('id');

                $check_status2 = DB::table('activity_work')->whereIn('import_id', $act_id)->where('cat', 'block')->count();

                $item->status_2 = ($check_status2 > 0) ? 'delayed' : 'on_time';

                return $item;
            });

        $material = Activity_work::where('pro_id', $project_id)->pluck('id');

        $act_mat = Activity_material::whereIn('act_id', $material)
            ->selectRaw('category, SUM(qty) as total, unit')
            ->groupBy('category', 'unit')
            ->get();

        return view('projects.profile', compact(
            'project',
            'surveys',
            'employees',
            'ent_surveys',
            'drawings',
            'ent_drawings',
            'rejected_ent_drawings',
            'tasks',
            'qcs',
            'qc_checklists',
            'ent_qcs',
            'snags',
            'ent_snags',
            'project_employees',
            'project_id',
            'pro_docs',
            'pro_progress_stage',
            'pro_progress_tab',
            'act_mat'
        ));
    }

    private function getActive2DFloorPlanDrawings($projectId)
    {
        // Fetch all active 2D Floor Plans with their latest entry
        $drawings = Drawing::with([
            'latestEntry' => function ($query) use ($projectId) {
                $query->select('id', 'drawing_id', 'status', 'version', 'file_attachment', 'uploaded_on')
                    ->where('project_id', $projectId)
                    ->where('is_draft', 0)
                    ->whereIn('status', ['Approved', 'pending'])
                    ->latest('id'); // pick the latest entry per drawing
            }
        ])
            ->where('file_type', '2D Floor Plans')
            ->where('status', 'active')
            ->get()
            ->sortBy(function ($d) {
                $title = strtolower($d->title);
                if (str_contains($title, 'ground'))
                    return 1;
                if (str_contains($title, 'first'))
                    return 2;
                if (str_contains($title, 'second') || str_contains($title, '2nd'))
                    return 3;
                return 4;
            })
            ->values(); // reindex collection

        // Flatten the data for frontend
        $flattened = $drawings->map(function ($drawing) {
            return [
                'drawing_id' => $drawing->id,
                'title' => $drawing->title,
                'file_type' => $drawing->file_type,
                'drawing_status' => $drawing->status,
                'entry_id' => $drawing->latestEntry->id ?? null,
                'entry_status' => $drawing->latestEntry->status ?? 'No Entry Yet',
                'version' => $drawing->latestEntry->version ?? null,
                'file_attachment' => $drawing->latestEntry->file_attachment ?? null,
                'uploaded_on' => $drawing->latestEntry->uploaded_on ?? null,
            ];
        });

        return $flattened;
    }


    public function storeSnagComment(Request $request)
    {
        $request->validate([
            'snag_id' => 'required|exists:entry_snag,id',
            'comment' => 'required|string|max:1000',
        ]);
        // 1. Store comment
        $snagComment = SnagComment::create([
            'snag_id' => $request->snag_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);
        // 2. Get snag details
        $snag = EntrySnag::findOrFail($request->snag_id);
        // 3. Build notify list (assigned_to + created_by)
        $notify_arr = array_unique(array_filter([
            $snag->assigned_to ?? null,
            $snag->c_by ?? null,
        ]));

        // 🔹 Fetch tokens of receivers
        $tokens = Employee::whereIn('id', $notify_arr)->pluck('token', 'id');

        foreach ($notify_arr as $noti) {
            if ($noti == auth()->id()) {
                continue; // skip self notifications
            }

            $bodyText = (auth()->user()->name ?? 'Unknown') . ' commented - "' . $request->comment . '"';

            \App\Models\Notify::create([
                'to_id' => $noti,                // ✅ dynamic receiver
                'f_id' => $snagComment->id,     // reference to the comment
                'type' => 'snag_comment',
                'title' => 'Snag Comment Added',
                'body' => $bodyText,
                'c_by' => auth()->id(),
                'seen' => 0,
                'reminder' => 0,
            ]);

            // 🔹 Prepare FCM data
            $data = [
                'to_id' => $noti,
                'f_id' => $snagComment->id,
                'type' => 'snag_comment',
                'title' => 'Snag Comment Added',
                'body' => $bodyText,
                'token' => [$tokens[$noti] ?? null],
            ];

            // 🔹 Send FCM notification
            $res = $this->notify_create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment & notifications added successfully!',
            'data' => $snagComment->load('user:id,name'),
        ]);
    }

    public function getSnagComments($snag_id)
    {
        $comments = SnagComment::with('user:id,name')
            ->where('snag_id', $snag_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }

    public function survey_status_update(Request $req, $id = null, $status = null)
    {
        if ($req->header('Authorization')) {
            $id = $req->id;
            $status = $req->status;
        }
        $survey = EntrySurvey::find($id);
        $survey->status = $status;
        $survey->approved_by = auth()->user()->id;
        // $survey->save();
        if ($survey->save()) {

            $survey_name = Survey::find($survey->survey_id);
            $fid_token = Employee::where('id', $survey->assigned_to)->value('token');
            $data = [
                'to_id' => $survey->assigned_to,
                'f_id' => $id,
                'type' => 'survey',
                'title' => 'Survey ' . $status . ' by ' . auth()->user()->name,
                'body' => $survey_name->title . ' in ' . $survey->project->project_name,
                'token' => [$fid_token]
            ];

            $res = $this->notify_create($data);
        }

        if ($req->header('Authorization')) {

            return response()->json(['status' => 'Status updated successfully'], 200);
        } else {

            return back()->with('success', 'Status updated successfully');
        }
    }

    public function qc_status_update(Request $req, $id = null, $status = null)
    {
        if ($req->header('Authorization')) {
            $id = $req->id;
            $status = $req->status;
        }

        $qc = EntryQC::find($id);
        $qc->status = $status;
        $qc->approved_by = auth()->user()->id;
        // $qc->save();

        if ($qc->save()) {

            $qc_name = QC::find($qc->qc_title);
            $fid_token = Employee::where('id', $qc->assigned_to)->value('token');
            $data = [
                'to_id' => $qc->assigned_to,
                'f_id' => $id,
                'type' => 'qc',
                'title' => 'QC ' . $status . ' by ' . auth()->user()->name,
                'body' => $qc_name->title . ' in ' . $qc->project->project_name,
                'token' => [$fid_token]
            ];

            $res = $this->notify_create($data);
        }
        if ($req->header('Authorization')) {

            return response()->json(['status' => 'Status updated successfully'], 200);
        } else {

            return back()->with('success', 'Status updated successfully');
        }
    }

    public function snag_status_update(Request $req, $id = null, $status = null)
    {
        if ($req->header('Authorization')) {
            $id = $req->id;
            $status = $req->status;
        }
        $snag = EntrySnag::find($id);
        $snag->status = $status;
        $snag->approved_by = auth()->user()->id;
        // $snag->save();

        if ($snag->save()) {

            $snag_name = Snag::find($snag->category_id);
            $fid_token = Employee::where('id', $snag->assigned_to)->value('token');
            $data = [
                'to_id' => $snag->assigned_to,
                'f_id' => $id,
                'type' => 'snag',
                'title' => 'snag ' . $status . ' by ' . auth()->user()->name,
                'body' => $snag_name->category . ' in ' . $snag->project->project_name,
                'token' => [$fid_token]
            ];

            $res = $this->notify_create($data);
        }
        if ($req->header('Authorization')) {

            return response()->json(['status' => 'Status updated successfully'], 200);
        } else {

            return back()->with('success', 'Status updated successfully');
        }
    }

    public function snag_store(Request $request)
    {
        Log::info('Snag Data', $request->all());
        //echo "<pre>"; print_r($request->input()); die;
        if (!$request->header('Authorization')) {

            $request->validate([
                'category_id' => 'required|integer',
                'description' => 'nullable|string',
                'assigned_to' => 'required|integer',
                'due_date' => 'nullable|date',
                'file_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,txt,svg|max:12048'
            ]);
        }


        $filePath = $fileName = null;
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = 'snag/' . $fileName;

            // Upload to S3
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            // Optional: make it public
            Storage::disk('s3')->setVisibility($filePath, 'public');
        }
        if ($request->input('id')) {
            $qc_model = EntrySnag::find($request->input('id'));
            if ($qc_model) {
                $updateData = [
                    'category_id' => $request->category_id,
                    'description' => $request->description,
                    'assigned_to' => $request->assigned_to,
                    'due_date' => $request->due_date,
                ];
                if ($filePath) {
                    $updateData['file_attachment'] = $fileName;
                    $updateData['file_name'] = $fileName;
                }
                $qc_model->update($updateData);
            }
        } else {
            $createData = [
                'category_id' => $request->category_id,
                'description' => $request->description,
                'assigned_to' => $request->assigned_to,
                'due_date' => $request->due_date,
                'project_id' => $request->project_id,
                'location' => $request->location,
                'c_by' => auth()->user()->id ?? 1
            ];
            if ($filePath) {
                $createData['file_attachment'] = $fileName;
                $createData['file_name'] = $fileName;
            }
            $last = EntrySnag::create($createData);

            $snag_name = Snag::find($request->category_id);

            $data = [
                'to_id' => $request->assigned_to,
                'f_id' => $last->id,
                'type' => 'snag',
                'title' => 'New Snag  Assigned by ' . auth()->user()->name ?? 'unknown',
                'body' => $snag_name->category . ' in ' . $last->project->project_name,
                'token' => [$last->user->token]
            ];

            $res = $this->notify_create($data);
        }


        if ($request->header('Authorization')) {
            return response()->json(['status' => 'Snag saved successfully!'], 200);
        } else {
            return back()->with(['success' => 'Snag saved successfully!', 'active_tab' => 'snag']);
        }
    }

    public function task_view(Request $request)
    {
        $request_data = $request->input();
        $task = Task::find($request->input('task_id'));
        $sub_tasks = Task::where('parent_task_id', $request->input('task_id'))->get();

        $class = ($task->status === 'completed') ? 'entry completed' : 'entry';

        $html = '<div class="' . $class . '">
            <div class="title"></div>
            <div class="entrybody">
                <div class="taskct">
                    <div class="taskname">
                        <h4 class="mb-1">' . $task->title . '</h4>
                        <h6 class="mb-1">' . $task->description . '</h6>
                        <h5 class="mb-1">' . ($task->created_user->name ?? 'Unknown') . '</h5>
                    </div>
                    <div class="taskimg">
                        <img src="' . asset($task->file_attachment) . '" alt="">
                    </div>
                </div>
                <div class="taskdate">
                    <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                        ' . date('d/m/Y', strtotime($task->start_timestamp)) . '</h6>
                    <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp; ' . date('d/m/Y', strtotime($task->end_timestamp)) . '
                    </h6>
                </div>
            </div>
        </div>';

        $totalTask = $completedTask = 0;

        if ($task->status === 'completed') {
            $totalTask += 1;
            $completedTask += 1;
        } else {
            $totalTask += 1;
        }

        foreach ($sub_tasks as $sub_task) {
            if ($sub_task->status === 'completed') {
                $totalTask += 1;
                $completedTask += 1;
            } else {
                $totalTask += 1;
            }
            $class = ($sub_task->status === 'completed') ? 'entry completed' : 'entry';
            $html .= '<div class="' . $class . '">
                <div class="title"></div>
                <div class="entrybody">
                    <div class="taskct">
                        <div class="taskname">
                            <h4 class="mb-1">' . $sub_task->title . '</h4>
                            <h6 class="mb-1">' . $sub_task->description . '</h6>
                            <h5 class="mb-1">' . ($sub_task->created_user->name ?? 'Unknown') . '</h5>
                        </div>
                        <div class="taskimg">
                            <img src="' . asset($sub_task->file_attachment) . '" alt="">
                        </div>
                    </div>
                    <div class="taskdate">
                        <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                            ' . date('d/m/Y', strtotime($sub_task->start_timestamp)) . '</h6>
                        <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp; ' . date('d/m/Y', strtotime($sub_task->end_timestamp)) . '
                        </h6>
                    </div>
                </div>
            </div>';
        }

        if ($task->is_assigned != 0) {
            $task1 = Task::find($task->is_assigned);
            $sub_tasks1 = Task::where('parent_task_id', $task1->id)->get();
            $class = ($task1->status === 'completed') ? 'entry completed' : 'entry';
            $html .= '<div class="' . $class . '">
                <div class="title"></div>
                <div class="entrybody">
                    <div class="taskct">
                        <div class="taskname">
                            <h4 class="mb-1">' . $task1->title . '</h4>
                            <h6 class="mb-1">' . $task1->description . '</h6>
                            <h5 class="mb-1">' . ($task1->created_user->name ?? 'Unknown') . '</h5>
                        </div>
                        <div class="taskimg">
                            <img src="' . asset($task1->file_attachment) . '" alt="">
                        </div>
                    </div>
                    <div class="taskdate">
                        <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                            ' . date('d/m/Y', strtotime($task1->start_timestamp)) . '</h6>
                        <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp; ' . date('d/m/Y', strtotime($task1->end_timestamp)) . '
                        </h6>
                    </div>
                </div>
            </div>';

            foreach ($sub_tasks1 as $sub_task) {
                if ($sub_task->status === 'completed') {
                    $totalTask += 1;
                    $completedTask += 1;
                } else {
                    $totalTask += 1;
                }
                $class = ($sub_task->status === 'completed') ? 'entry completed' : 'entry';
                $html .= '<div class="' . $class . '">
                    <div class="title"></div>
                    <div class="entrybody">
                        <div class="taskct">
                            <div class="taskname">
                                <h4 class="mb-1">' . $sub_task->title . '</h4>
                                <h6 class="mb-1">' . $sub_task->description . '</h6>
                                <h5 class="mb-1">' . ($sub_task->created_user->name ?? 'Unknown') . '</h5>
                            </div>
                            <div class="taskimg">
                                <img src="' . asset($sub_task->file_attachment) . '" alt="">
                            </div>
                        </div>
                        <div class="taskdate">
                            <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                                ' . date('d/m/Y', strtotime($sub_task->start_timestamp)) . '</h6>
                            <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp; ' . date('d/m/Y', strtotime($sub_task->end_timestamp)) . '
                            </h6>
                        </div>
                    </div>
                </div>';
            }
        }

        $task_percentage = $totalTask > 0 ? round(($completedTask / $totalTask) * 100) : 0;

        $data = [];
        $data['task_title'] = $task->title;
        $data['task_description'] = $task->description;
        $data['task_html'] = $html;
        $data['task_percentage'] = $task_percentage;

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function task_store(Request $request)
    {
        $request_data = $request->input();

        $start_timestamp = $request->input('start_date') . " 00:00:00";
        $end_timestamp = $request->input('end_date') . " 00:00:00";

        $filePath = $fileName = null;
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/tasks', $fileName, 'public');
            $filePath = 'storage/' . $filePath;
        }

        if ($request->input('id')) {
            $task = Task::find($request->input('id'));
            if ($task) {
                $data['title'] = $request->input('title');
                $data['assigned_to'] = $request->input('assigned_to');
                $data['start_timestamp'] = $start_timestamp;
                $data['end_timestamp'] = $end_timestamp;
                $data['description'] = $request->input('description');
                $data['created_by'] = auth()->user()->id;
                if ($filePath) {
                    $data['file_attachment'] = $filePath;
                    $data['file_name'] = $fileName;
                }
                $task->update($data);
            }
        } else {
            $data['title'] = $request->input('title');
            $data['project_id'] = $request->input('project_id');
            $data['parent_task_id'] = $request->input('parent_task_id') ?? 0;
            $data['assigned_to'] = $request->input('assigned_to');
            $data['category_id'] = "0";
            $data['sub_category_id'] = "0";
            $data['priority'] = "Low";
            $data['start_timestamp'] = $start_timestamp;
            $data['end_timestamp'] = $end_timestamp;
            $data['description'] = $request->input('description');
            $data['additional_info'] = "";
            $data['created_by'] = auth()->user()->id;
            if ($filePath) {
                $data['file_attachment'] = $filePath;
                $data['file_name'] = $fileName;
            }
            Task::create($data);
        }

        return back()->with('success', 'Task saved successfully!');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employees = Employee::where('status', 'active')->get();
        $project = Project::find($id);
        return view('projects.edit', compact('project', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function project_emp(Request $request)
    {
        $project = Project::where('id', $request->project_id)->select('assigned_to')->first();

        $employees = Employee::whereIn('id', $project->assigned_to)->whereNotIn('id', [auth()->user()->id])->select('id', 'name')->get();

        return response()->json(['status' => 'success', 'data' => $employees]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function ans_ajax(Request $req)
    {
        $ans_id = $req->ajax_id;
        $type = $req->type;

        if ($type == 'snag') {
            $ent_snag = Snag_ans::where('entry_snag', $ans_id)->get();
            $entry_snag = EntrySnag::find($ans_id);
            $Snag_title = Snag::find($entry_snag->category_id);

            // Get S3 or local URL for created file
            $entry_image = $entry_snag->file_attachment
                ? (Storage::disk('s3')->exists('snag/' . $entry_snag->file_attachment)
                    ? Storage::disk('s3')->url('snag/' . $entry_snag->file_attachment)
                    : asset('img/snag/' . $entry_snag->file_attachment))
                : null;

            $html = '<h4 class="modal-title fs-5" id="viewsnagLabel">' . e($Snag_title->category) . '</h4>
                <label class="col-form-label">' . e($entry_snag->description) . '</label>
                <div class="col-sm-12 col-md-12 pe-0 mb-1">';

            if ($entry_image) {
                $html .= '<h6 class="my-2">Snag Created File</h6>
                      <div class="d-flex align-items-start justify-content-start gap-2 mb-2 w-100">
                          <img src="' . $entry_image . '" height="200px" width="200px" style="object-fit:cover;object-position:center;" class="mb-1 rounded-2" alt="">
                      </div>';
            }

            $html .= '<h6 class="my-2">Snag Updated File</h6>';

            foreach ($ent_snag as $snag) {
                $imagePath = $snag->file
                    ? (Storage::disk('s3')->exists('snag/' . $snag->file)
                        ? Storage::disk('s3')->url('snag/' . $snag->file)
                        : asset('img/snag/' . $snag->file))
                    : null;

                $html .= '<div class="d-flex align-items-start justify-content-start gap-2 mb-2 w-100">';
                if ($imagePath) {
                    $html .= '<img src="' . $imagePath . '" height="200px" width="200px" style="object-fit:cover;object-position:center;" class="mb-1 rounded-2" alt="">';
                }
                $html .= '<h6 class="my-2">' . e($snag->desp) . '</h6></div>';
            }

            $html .= '</div>';
        } elseif ($type == 'qc') {
            $ent_qc = QC_ans::where('qc_entry', $ans_id)->first();
            $qc_id = QC::find($ent_qc->q_id);

            $html = '<h4 class="modal-title mb-2 fs-5" id="viewqcLabel">' . e($qc_id->title) . '</h4>
                 <div class="col-sm-12 col-md-12 mb-1">';

            foreach (json_decode($ent_qc->answer) as $qc_ans) {
                $ques = QCChecklist::find($qc_ans);
                $html .= '<ul><li class="completed">' . e($ques->question) . '</li></ul>';
            }

            $html .= '</div>';
        } else {
            $ent_sur = Survey_ans::where('entry_ans', $ans_id)->get();
            $survey_ins = EntrySurvey::find($ans_id);
            $survey_main = Survey::find($survey_ins->survey_id);

            $html = '<h4 class="modal-title mb-0 fs-5" id="viewsurveyLabel">' . e($survey_main->title) . '</h4>
                 <label class="col-form-label mb-2">' . e($survey_ins->instruction) . '</label>';

            foreach ($ent_sur as $es) {
                $qc_id = SurveyQuestion::find($es->q_id);

                if ($es->q_type == "Text" || $es->q_type == "Textarea") {
                    $html .= '<div class="col-sm-12 col-md-12 mb-1">
                            <label>' . e($qc_id->question) . '</label>
                            <h6 class="mb-1">' . e($es->answer) . '</h6>
                          </div>';
                } elseif ($es->q_type == "Checkbox") {
                    $options = json_decode($es->answer, true);
                    $html .= '<div class="col-sm-12 col-md-12 mb-1">
                            <label>' . e($qc_id->question) . '</label>';
                    foreach ($options as $option) {
                        $ans = SurveyChoice::find($option);
                        $html .= '<h6 class="mb-1">' . e($ans->choice) . '</h6>';
                    }
                    $html .= '</div>';
                } elseif ($es->q_type == "File") {
                    $files = json_decode($es->answer, true);
                    $html .= '<div class="col-sm-12 col-md-12 mb-1">
                            <label>' . e($qc_id->question) . '</label> ';

                    if (is_array($files)) {
                        foreach ($files as $file) {
                            $filePath = Storage::disk('s3')->exists('survey/' . $file)
                                ? Storage::disk('s3')->url('survey/' . $file)
                                : asset('img/survey/' . $file);
                            $html .= '<a href="' . $filePath . '" target="_blank" class="me-2">Files</a>';
                        }
                    } else {
                        $filePath = Storage::disk('s3')->exists('survey/' . $es->answer)
                            ? Storage::disk('s3')->url('survey/' . $es->answer)
                            : asset('img/survey/' . $es->answer);
                        $html .= '<a href="' . $filePath . '" target="_blank">Files</a>';
                    }

                    $html .= '</div>';
                } elseif ($es->q_type == 'location') {
                    $loc = explode(',', $es->answer);
                    $lat = trim($loc[0]);
                    $lng = trim($loc[1]);
                    $zoom = 15;

                    $html .= '<div class="col-sm-12 col-md-12 mb-1">
                            <label>' . e($qc_id->question) . '</label>
                            <h6 class="mb-1">
                                <a href="https://www.google.com/maps?q=' . $lat . ',' . $lng . '&z=' . $zoom . '" target="_blank">'
                        . e($es->answer) .
                        '</a>
                            </h6>   
                          </div>';
                } else {
                    $html .= '<div class="col-sm-12 col-md-12 mb-1">
                            <label>' . e($qc_id->question) . '</label>
                            <h6 class="mb-1">' . e($es->answer) . '</h6>
                          </div>';
                }
            }
        }

        return response()->json(['data' => $html]);
    }
    public function file_type(Request $req)
    {

        // Subquery to get latest entry_drawing id per drawing_id and project_id
        $latestEntrySub = DB::table('entry_drawing')
            ->select(DB::raw('MAX(id) as id'))
            ->where('project_id', $req->project_id)
            ->groupBy('drawing_id');

        // Main query
        $get = Drawing::where('file_type', $req->file_type)
            ->leftJoin('entry_drawing as ed', function ($join) use ($latestEntrySub) {
                $join->on('ed.drawing_id', '=', 'drawing.id')
                    ->whereIn('ed.id', $latestEntrySub);
            })
            ->select(
                'drawing.id',
                'drawing.title',
                'drawing.file_type',
                'ed.id as entry_id',
                'ed.status',
                'ed.version'
            )
            ->get();



        $options = $get->pluck('title', 'id')->toArray();
        $statuses = $get->pluck('status', 'id')->toArray();

        return response()->json([
            'option' => $options,
            'status' => $statuses
        ]);
    }

    public function file_version(Request $req)
    {

        $count = EntryDrawing::where('drawing_id', $req->file_title)->where('project_id', $req->project_id)->count();

        if ($count == 0) {
            $version = 'Version 1';
        } else {

            $version = 'Version ' . ($count + 1);
        }



        return response()->json(['version' => $version]);
    }

    public function history_table($projectid, $ent_id)
    {
        $entry = EntryDrawing::where('drawing_id', $ent_id)->where('project_id', $projectid)->with([
            'drawing:id,file_type,title', // Select columns directly in the relationship
            'user:id,name' // Select specific columns from the 'user' relationship
        ])->get();

        return view('projects.prjt_history', compact('entry'));
    }

    public function stages_table(string $pro_id)
    {

        $progress = DB::table('progress_activity as pa')
            ->leftJoin('progress_stage as ps', 'pa.stage', '=', 'ps.id')
            ->where('pa.pro_id', $pro_id)
            ->select('pa.id as act_id', 'pa.activity', 'pa.qc', 'ps.*', 'ps.pro_id as st_pro_id')
            ->get()->map(function ($lt) {

                $act_id = DB::table('progress_activity')->where('pro_id', $lt->st_pro_id)->pluck('id');

                $lt->remove = Activity_work::whereIn('import_id', $act_id)->exists();

                return $lt; // make sure to return the modified object
            });

        // $progress = $progress->groupBy('stage');

        // dd($progress->toArray());

        $qc = DB::table('qc')->get();


        return view('projects.progress_stages', ['progress' => $progress, 'qc_drop' => $qc]);
    }


    public function pro_docs_add(Request $req)
    {
        // dd($req->all());
        $file_name = null;
        if ($req->hasFile('file_attachment_doc')) {
            $file = $req->file('file_attachment_doc');

            // Clean file name (replace spaces with underscores)
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());

            // Define S3 folder path
            $folder = 'pro_docs';
            $filePath = $folder . '/' . $fileName;

            // Upload file to S3
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            // Make it public (optional)
            Storage::disk('s3')->setVisibility($filePath, 'public');
        }


        $ins = Pro_docs::create([
            "pro_id" => $req->project_id,
            "type" => $req->doc_type,
            "title" => $req->doc_title,
            "desp" => $req->doc_description,
            "link" => $fileName ?? $req->doc_link,
            "status" => 'Active',
            "c_by" => auth()->user()->id
        ]);

        if ($req->header('Authorization')) {
            if ($ins) {
                return response()->json(['status' => 'Document saved successfully!'], 200);
            } else {
                return response()->json(['status' => 'Document Failed to Add'], 500);
            }
        } else {
            if ($ins) {
                return back()->with('success', 'Document saved successfully!')->with('active_tab', 'document');
            } else {
                return back()->with('failed', 'Document Failed to Add')->with('active_tab', 'document');
            }
        }




        // return view('projects.progress_stages');
    }

    public function pro_docs_list(Request $req)
    {
        $pro_docs = Pro_docs::where('status', 'Active')->where('pro_id', $req->project_id)->get()->map(function ($lt) {

            $lt->docs = ($lt->type == 'Document')
                ? Storage::disk('s3')->url('pro_docs/' . $lt->link)
                : $lt->link;

            return $lt;
        });

        return response()->json(['status' => 'success', 'data' => $pro_docs]);
    }
}
