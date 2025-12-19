<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\Employee;
use App\Traits\common;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use common;
    public function index(Request $request)
    {
        $userId = (string) auth()->user()->id;

        $project_progress = Project::whereJsonContains('projects.assigned_to', $userId)
            ->leftJoin('task', 'projects.id', '=', 'task.project_id')
            ->where('task.status', 'in_progress')
            ->where('task.assigned_to', $userId)
            ->select('projects.id', 'projects.project_name', DB::raw('COUNT(task.id) as in_progress_count'))
            ->groupBy('projects.id', 'projects.project_name')
            ->get();

        // $project_progress = Project::whereJsonContains('assigned_to', (string) auth()->user()->id)->select('id', 'project_name')->get()->toArray();

        // dd($project_progress->toArray());


        $tasks = Task::all();
        $year = Carbon::now()->year;


        // Prepare default 12-month data
        $months = collect(range(1, 12))->mapWithKeys(function ($month) use ($year) {
            $key = Carbon::create($year, $month)->format('Y-m');
            return [$key => [
                'month_name' => Carbon::create($year, $month)->format('M'),
                'total_tasks' => 0
            ]];
        });

        // dd($months);

        // Fetch and merge actual task data
        $taskData = Task::whereYear('created_at', $year)
            ->where('status', '!=', 'completed')
            ->where('assigned_to', auth()->id())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month_key, COUNT(*) as total')
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        $months->transform(function ($data, $key) use ($taskData) {
            $data['total_tasks'] = $taskData[$key] ?? 0;
            return $data;
        });

        // Convert to arrays for chart usage
        $pending_task_month = $months->pluck('month_name')->all();
        $pending_task_data = $months->pluck('total_tasks')->all();
        //
        // dd($project_progress);

        return view('dashboard', compact('project_progress', 'pending_task_data', 'pending_task_month'));
    }

            public function mydashboard(Request $request)
            {
                $inprogress_tasks = Task::where('assigned_to', auth()->user()->id)
                    ->where('status', 'in_progress')
                    ->with('project')
                    ->orderBy('id', 'desc') 
                    ->get()
                    ->map(function ($task) {
                        // latest task_close record
                        $taskClose = DB::table('task_close')
                            ->where('request_to_task', $task->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($taskClose && $taskClose->status == 'approved') {
                            $task->custom_status = 'Approved';
                            $task->custom_status_class = 'text-success';
                        // ✅ Completed only if both are pending
                        } elseif ($task->status === 'pending' && $taskClose && $taskClose->status === 'pending') {
                            $task->custom_status = 'Completed';
                            $task->custom_status_class = 'text-success';
                        } elseif ($task->status === 'completed') {
                            $task->custom_status = 'Completed';
                            $task->custom_status_class = 'text-success';
                        } elseif ($taskClose && in_array($taskClose->status, ['completed', 'closed'])) {
                            $task->custom_status = 'Completed';
                            $task->custom_status_class = 'text-success';
                        } elseif ($taskClose && $taskClose->status == 'pending') {
                            $task->custom_status = 'Pending';
                            $task->custom_status_class = 'text-danger';
                        } elseif ($task->end_timestamp && now()->gt(\Carbon\Carbon::parse($task->end_timestamp))) {
                            $task->custom_status = 'Pending';
                            $task->custom_status_class = 'text-danger';
                        } else {
                            $task->custom_status = 'New';
                            $task->custom_status_class = 'text-primary';
                        }
                        
                        return $task;
                    });

                $completed_tasks = Task::where('assigned_to', auth()->user()->id)
                    ->where(function ($query) {
                        $query->where('status', 'completed')
                            ->orWhere('status', 'pending');
                    })
                    ->with('project')
                    ->orderBy('id', 'desc')
                    ->get()
                    ->map(function ($lt) {
                        $lt->close_status = DB::table('task_close')->where('request_by_task', $lt->id)->value('status') ?? 0;
                        
                        // latest task_close record
                        $taskClose = DB::table('task_close')
                            ->where('request_to_task', $lt->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($taskClose && $taskClose->status == 'approved') {
                            $lt->custom_status = 'Approved';
                            $lt->custom_status_class = 'text-success';
                        // ✅ Completed only if both are pending
                        } elseif ($lt->status === 'pending' && $taskClose && $taskClose->status === 'pending') {
                            $lt->custom_status = 'Completed';
                            $lt->custom_status_class = 'text-success';
                        } elseif ($lt->status === 'completed') {
                            $lt->custom_status = 'Completed';
                            $lt->custom_status_class = 'text-success';
                        } elseif ($taskClose && in_array($taskClose->status, ['completed', 'closed'])) {
                            $lt->custom_status = 'Completed';
                            $lt->custom_status_class = 'text-success';
                        } elseif ($taskClose && $taskClose->status == 'pending') {
                            $lt->custom_status = 'Pending';
                            $lt->custom_status_class = 'text-danger';
                        } elseif ($lt->end_timestamp && now()->gt(\Carbon\Carbon::parse($lt->end_timestamp))) {
                            $lt->custom_status = 'Pending';
                            $lt->custom_status_class = 'text-danger';
                        } else {
                            $lt->custom_status = 'New';
                            $lt->custom_status_class = 'text-primary';
                        }
                        
                        return $lt;
                    });

                $projects = Project::all();
                $employees = Employee::where('status', 'active')->get();

                return view('dashboard.mydashboard', compact('inprogress_tasks', 'completed_tasks', 'employees', 'projects'));
            }



    public function task_status_update(Request $request)
    {
        $task = Task::find($request->task_id);
        $task->status = $request->task_status;
        $task->save();
        return true;
    }

    // public function task_store(Request $request)
    // {
    //     $request_data = $request->input();

    //     $filePath = $fileName = null;
    //     if ($request->hasFile('file_attachment')) {
    //         $file = $request->file('file_attachment');
    //         $fileName = $file->getClientOriginalName();

    //         $fileName = str_replace(' ', '_', $file->getClientOriginalName());

    //         $file->move(public_path('img/task/'), $fileName);
    //     }

    //     $start_timestamp = $request->input('startdate') . " " . date('H:i:s', strtotime($request->input('starttime')));
    //     $end_timestamp = $request->input('enddate') . " " . date('H:i:s', strtotime($request->input('endtime')));

    //     $category_id = $sub_category_id = 0;
    //     if ($request->task_id) {
    //         $currentTask = Task::find($request->task_id);
    //         // $category_id = $currentTask->category_id;
    //         // $sub_category_id = $currentTask->sub_category_id;
    //         $parent_id = $currentTask->parent_task_id;
    //     }

    //     $data['parent_task_id'] = $parent_id;
    //     $data['title'] = $request->input('title');
    //     $data['project_id'] = $request->input('project_id');
    //     $data['assigned_to'] = $request->input('assigned_to');
    //     $data['priority'] = $request->input('priority');
    //     $data['end_timestamp'] = $end_timestamp;
    //     $data['description'] = $request->input('description');
    //     $data['file_attachment'] = $fileName;
    //     $data['file_name'] = $fileName;
    //     $data['created_by'] = auth()->user()->id;

    //     $model = Task::create($data);

    //     if ($request->task_id) {
    //         $currentTask = Task::find($request->task_id);
    //         $currentTask->is_assigned = $model->id;
    //         $currentTask->save();
    //     }

    //     $to_id = $request->input('assigned_to');
    //     $f_id = $model->id;
    //     $type = 'task';
    //     $title = 'New Task Assigned by ' . auth()->user()->name;
    //     $body = $request->input('title') . ' in ' . $model->project->project_name;
    //     $token = [$model->user->token];

    //     $res = $this->notify_create($to_id, $f_id, $type, $title, $body, $token);


    //     if ($request->header('Authorization')) {
    //         return response()->json(['success' => 'Task Assigned successfully!']);
    //     } else {
    //         return back()->with('success', 'Task saved successfully!');
    //     }
    // }
}
