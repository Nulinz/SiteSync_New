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
use App\Models\Comment;
use App\Models\Notify;
use App\Models\{EntrySurvey, EntryQC, EntrySnag, EntryDrawing};
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\Activity_work;
use App\Models\Activity;
use App\Traits\common;
use Carbon\Carbon;

class mobile_cnt extends Controller
{
    // public function pro_list()
    // {
    //     try {
    //         $userId = (string) auth()->user()->id;

    //         $projects = Project::where('status', 'active')
    //             ->select('id', 'project_name', 'project_id', 'assigned_to', 'progress', 'status')
    //             ->get()
    //             ->map(function ($project) use ($userId) {

    //                 // ✅ FIX: Check if assigned_to is already an array
    //                 if (is_array($project->assigned_to)) {
    //                     $assignedIds = $project->assigned_to;
    //                 } elseif (is_string($project->assigned_to)) {
    //                     $assignedIds = json_decode($project->assigned_to, true) ?? [];
    //                 } else {
    //                     $assignedIds = [];
    //                 }

    //                 // Clean up - ensure all IDs are strings
    //                 $assignedIds = array_map('strval', array_filter($assignedIds));

    //                 // GENERAL PROJECT (no specific assignments)
    //                 if (count($assignedIds) === 0) {
    //                     return [
    //                         'id' => $project->id,
    //                         'project_name' => $project->project_name,
    //                         'project_id' => $project->project_id,
    //                         'progress' => $project->progress,
    //                         'status' => $project->status,
    //                         'is_general' => true,
    //                         'assigned_to' => [],
    //                         'assign_users' => Employee::select('id', 'name')
    //                             ->orderBy('name')
    //                             ->get()
    //                             ->toArray() // ✅ FIXED - Added ()
    //                     ];
    //                 }

    //                 // NORMAL PROJECT (specific users assigned)
    //                 if (in_array($userId, $assignedIds)) {
    //                     return [
    //                         'id' => $project->id,
    //                         'project_name' => $project->project_name,
    //                         'project_id' => $project->project_id,
    //                         'progress' => $project->progress,
    //                         'status' => $project->status,
    //                         'is_general' => false,
    //                         'assigned_to' => $assignedIds,
    //                         'assign_users' => Employee::whereIn('id', $assignedIds)
    //                             ->select('id', 'name')
    //                             ->orderBy('name')
    //                             ->get()
    //                             ->toArray() // ✅ This one is correct
    //                     ];
    //                 }

    //                 // User not assigned to this project
    //                 return null;
    //             })
    //             ->filter() // Remove null values
    //             ->values(); // Reset array keys

    //         return response()->json([
    //             'success' => true,
    //             'data' => $projects
    //         ]);

    //     } catch (\Exception $e) {
    //         \Log::error('pro_list error: ' . $e->getMessage(), [
    //             'userId' => auth()->user()->id ?? 'unknown',
    //             'line' => $e->getLine(),
    //             'file' => $e->getFile()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to load projects',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    // public function pro_list()
    // {

    //     $list = Project::whereJsonContains('assigned_to', (string) auth()->user()->id)->select('id', 'project_name', 'project_id', 'assigned_to', 'progress', 'status')->get()->map(function ($item) {

    //         $item->assign_users = $item->assigned_users;
    //         unset($item->assigned_to);

    //         return $item;
    //     });

    //     return response()->json(['data' => $list]);
    // }

    public function pro_list()
    {
        $list = Project::whereJsonContains(
            'assigned_to',
            (string) auth()->user()->id
        )
            ->select('id', 'project_name', 'project_id', 'assigned_to', 'progress', 'status')
            ->get()
            ->map(function ($item) {

                // Decode assigned_to
                $assignedIds = is_string($item->assigned_to)
                    ? json_decode($item->assigned_to, true)
                    : (array) $item->assigned_to;

                // Get assigned users with S3 image URLs
                $item->assign_users = Employee::whereIn('id', $assignedIds)
                    ->get(['name', 'image_path'])
                    ->map(function ($emp) {
                        return [
                            'name' => $emp->name,
                            'image_url' => $emp->image_path
                                ? Storage::disk('s3')->url($emp->image_path)
                                : null,
                        ];
                    });

                // Remove raw assigned_to
                unset($item->assigned_to);

                return $item;
            });

        return response()->json(['data' => $list]);
    }


    // public function pro_list() // or whatever method loads your form
    // {
    //     $userId = (string) auth()->user()->id;

    //     $projects = Project::where('status', 'active')
    //         ->select('id', 'project_name', 'assigned_to')
    //         ->get()
    //         ->map(function ($project) use ($userId) {

    //             // Check if assigned_to is already an array
    //             if (is_array($project->assigned_to)) {
    //                 $assignedIds = $project->assigned_to;
    //             } elseif (is_string($project->assigned_to)) {
    //                 $assignedIds = json_decode($project->assigned_to, true) ?? [];
    //             } else {
    //                 $assignedIds = [];
    //             }

    //             // Clean up IDs
    //             $assignedIds = array_map('strval', array_filter($assignedIds));

    //             // GENERAL PROJECT
    //             if (count($assignedIds) === 0) {
    //                 $project->is_general = true;
    //                 $project->assigned_to = [];
    //                 $project->assigned_users = Employee::select('id', 'name')
    //                     ->where('id', '!=', $userId)
    //                     ->orderBy('name')
    //                     ->get();
    //             }
    //             // NORMAL PROJECT
    //             elseif (in_array($userId, $assignedIds)) {
    //                 $project->is_general = false;
    //                 $project->assigned_to = $assignedIds;
    //                 $project->assigned_users = Employee::whereIn('id', $assignedIds)
    //                     ->select('id', 'name')
    //                     ->where('id', '!=', $userId)
    //                     ->orderBy('name')
    //                     ->get();
    //             } else {
    //                 return null; // User not assigned
    //             }

    //             return $project;
    //         })
    //         ->filter()
    //         ->values();

    //     // Get all employees for the dropdown
    //     $employees = Employee::select('id', 'name')
    //         ->where('id', '!=', auth()->user()->id)
    //         ->orderBy('name')
    //         ->get();

    //     return view('task.view', compact('projects', 'employees'));
    // }

    public function snag_cat()
    {

        $list = Snag::select('id', 'category')->get();

        return response()->json(['data' => $list]);
    }

    // shwo teh snag pictures base on id
    public function snag_pic_show(Request $req)
    {

        $list = Snag_ans::where('entry_snag', $req->entry_snag)->get();

        return response()->json(['data' => $list]);
    }

    // show the taask timeline

    public function task_timeline($id, Request $req)
    {

        if ($req->type == 'notify') {
            $id = Task::where('id', $id)->value('parent_task_id');
        }


        $task_lt = Task::with([
            'user:id,name',
            'created_user:id,name,role_id',
            'project:id,project_name',
            'created_user.role:id,name',
            // 'comments' => function ($query) {
            //     $query->select('id', 'comment_for', 'desp', 'task_id', 'c_by', 'created_at as comment_at');
            // },
            // 'comments.comment_for:id,name',
            // 'comments.c_by:id,name'

        ])->withCount('comments')->where('parent_task_id', $id)->get()->map(function ($item) {

            // Set the user's name and role title from the user_cby relationshi
            $item->setAttribute('project_title', $item->project->project_name ?? null); // Accessing user_cby's name
            $item->setAttribute('assigned_to_name', $item->user->name ?? null); // Accessing user_cby's name
            $item->setAttribute('cby_user', $item->created_user->name ?? null); // Accessing user_cby's role_title
            $item->setAttribute('cby_des', optional($item->created_user->role)->role_title ?? null); // Accessing user_cby's role_title
            // $item->setAttribute('file_at', $item->file_attachment ? Storage::url($item->file_attachment) : null);
            //             $item->setAttribute('file_at', $item->file_attachment ? [
            //     'name' => basename($item->file_attachment),
            //     'url'  => asset('img/task/' . $item->file_attachment),
            // ] : null);
            $item->setAttribute('file_at', $item->file_attachment ? Storage::disk('s3')->url($item->file_attachment) : null);



            // $firstComment = $item->comments->first();
            // // $item->setAttribute('comment_created', $firstComment ? $firstComment->comment_at->format('Y-m-d H:i:s') : null);
            // $item->setAttribute('comment_created', $firstComment);

            $item->setAttribute('c_at', $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null);


            // Hide unnecessary relationships
            //  dd($item->created_user->designation); // Check if the role is being fetched
            // $item->makeVisible(['category']);
            $item->makeHidden(['user', 'created_user', 'category', 'sub_category', 'project', 'file_attachment', 'created_user.designation']);



            // unset($item->user);
            // unset($item->created_user);
            // unset($item->category);
            // unset($item->sub_category);
            // unset($item->project);


            return $item;
        });


        return response()->json(['data' => $task_lt]);
    }

    public function notify_list()
    {
        $list = Notify::where('to_id', auth()->user()->id)
            ->orderByDesc('id')
            ->get()
            ->map(function ($item) {
                $typeModelMap = [
                    'snag' => EntrySnag::class,
                    'survey' => EntrySurvey::class,
                    'qc' => EntryQC::class,
                    'draw' => EntryDrawing::class,
                    'attendance' => Attendance::class,
                ];

                if (in_array($item->type, ['snag', 'survey', 'qc', 'draw', 'attendance'])) {
                    if (array_key_exists($item->type, $typeModelMap)) {
                        $modelClass = $typeModelMap[$item->type];

                        $relations = match ($item->type) {
                            'snag' => ['project', 'snag'],
                            'survey' => ['project', 'survey'],
                            'qc' => ['project', 'qc'],
                            default => [],
                        };

                        $model = !empty($relations)
                            ? $modelClass::with($relations)->find($item->f_id)
                            : $modelClass::find($item->f_id);

                        if (!$model)
                            return null;

                        $project = optional($model?->project);

                        $item->pro_name = $project->project_name;
                        $item->pro_code = $project->project_id;
                        $item->pro_id = $project->id;

                        switch ($item->type) {
                            case 'snag':
                                $item->snag_id = $model->id;
                                $item->due_date = $model->due_date;
                                $item->snag_status = $model->status;
                                $item->ind_title = optional($model->snag)->category;
                                $item->ind_description = optional($model->snag)->description;
                                break;

                            case 'survey':
                                $item->survey_id = $model->id;
                                break;

                            case 'qc':
                                $item->qc_id = $model->id;
                                $item->ind_title = optional($model->qc)->title;
                                $item->ind_description = optional($model->qc)->description;
                                break;
                        }
                    }
                } elseif ($item->type === 'snag_comment') {
                    // ✅ Handle snag_comment separately
                    $comment = \App\Models\SnagComment::with('user:id,name')->find($item->f_id);

                    if ($comment) {
                        $snag = EntrySnag::with(['project', 'snag'])->find($comment->snag_id);

                        if ($snag) {
                            $item->snag_id = $snag->id;
                            $item->due_date = $snag->due_date;
                            $item->snag_status = $snag->status;
                            $item->ind_title = optional($snag->snag)->category;
                            $item->ind_description = optional($snag->snag)->description;

                            $project = optional($snag->project);
                            $item->pro_name = $project->project_name;
                            $item->pro_code = $project->project_id;
                            $item->pro_id = $project->id;
                        }

                        $item->comment_id = $comment->id;
                        $item->comment_text = $comment->comment;
                        $item->comment_by = optional($comment->user)->name;
                        $item->comment_at = optional($comment->created_at)?->format('Y-m-d H:i:s');
                    }
                } elseif (in_array($item->type, ['project', 'progress_work', 'attendance', 'Project'])) {
                    if ($item->type === 'progress_work') {
                        $act_work = Activity_work::find($item->f_id);

                        if ($act_work) {
                            $act_progress = Activity::where('id', $act_work->import_id)->first();
                            $project = Project::find($act_progress?->pro_id);
                        }

                        $item->pro_name = $project->project_name ?? null;
                        $item->pro_code = $project->project_id ?? null;
                        $item->pro_id = $project->id ?? null;
                        $item->stage = $act_work->stage ?? null;
                        $item->activity = $act_work->sub ?? null;
                        $item->activity_id = $act_work->import_id ?? null;
                        $item->activity_start = $act_progress->sc_start ?? null;
                        $item->activity_end = $act_progress->sc_end ?? null;
                    } else {
                        if ($item->type == 'attendance') {
                            $attd = Attendance::find($item->f_id);
                            $pro_id = $attd->pro_id ?? null;
                        } else {
                            $pro_id = $item->f_id;
                        }

                        $project = Project::find($pro_id);
                        $item->pro_name = $project->project_name ?? null;
                        $item->pro_code = $project->project_id ?? null;
                        $item->pro_id = $project->id ?? null;
                    }
                }

                // Format and hide attributes
                $item->c_at = optional($item->created_at)->format('Y-m-d H:i:s');
                $item->makeHidden(['created_at', 'updated_at', 'c_by']);

                return $item;
            })->filter(); // removes nulls

        return response()->json(['data' => $list]);
    }



    // new version  dashbboard list

    public function project_assigned_list(Request $req)
    {

        $assinged_user = auth()->user()->id ?? $req->user_id;

        $pro = Project::whereJsonContains('assigned_to', $assinged_user)->get();

        if ($pro) {

            return response()->json(['status' => 'success', 'data' => $pro]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'no Project found for the employees']);
        }
    }

    public function dashboard_list(Request $req)
    {

        $assinged_user = auth()->user()->id ?? $req->user_id;

        $today = Carbon::today()->toDateString(); // e.g., '2025-06-11'

        $today_labour = Attendance::selectRaw('category,DATE(created_at) as date, SUM(skilled) as total_skilled,SUM(mc) as total_mc,SUM(fc) as total_fc,SUM(skilled + mc + fc) as total_all,c_by,pro_id')
            ->whereDate('created_at', $today) // ✅ Filter by today's date
            ->groupBy(DB::raw('category,DATE(created_at),c_by,pro_id'))
            ->orderBy('date', 'DESC')
            ->get()->map(function ($item) {

                $emp = Employee::where('id', $item->c_by ?? 1)->select('name', 'image_path')->first();

                $pro = Project::where('id', $item->pro_id)->select('project_name')->first();

                $item->pro_name = $pro->project_name;

                $item->name = $emp->name ?? 'unknown';
                $item->image = $emp && $emp->image_path
                    ? asset('img/snag/' . $emp->image_path)
                    : null;
                $item->date = Carbon::parse($item->date)->format('d-m-Y');

                return $item;
            });

        $today_act = Activity_work::whereDate('created_at', $today)->get()
            ->map(function ($item) {
                $item->file = collect($item->file)
                    ->filter(fn($file) => is_string($file))
                    ->map(fn($file) => asset('img/activity_work/' . $file))
                    ->values();

                $act = DB::table('progress_activity')->where('id', $item->import_id)->first();

                $stage_det = DB::table('progress_stage')->where('id', $act->stage)->first();

                $item->created_date = Carbon::parse($item->created_at)->toDateString();

                $project = Project::find($item->pro_id);
                $item->pro_name = $project ? $project->project_name : 'no Project';
                $item->project_id = $item->pro_id ? $item->pro_id : null;
                $item->project_code = $project ? $project->project_id : 'no code';
                $item->end_plan = $stage_det->sc_end;

                $item->stage_name = $stage_det->stage;
                $item->act_name = $act->activity;

                $item->makeHidden(['created_at', 'updated_at']);
                return $item;
            });


        $today_act_eng = Activity_work::whereDate('created_at', $today)->where('c_by', auth()->user()->id)->get()
            ->map(function ($item) {
                $item->file = collect($item->file)
                    ->filter(fn($file) => is_string($file))
                    ->map(fn($file) => asset('img/activity_work/' . $file))
                    ->values();

                $act = DB::table('progress_activity')->where('id', $item->import_id)->first();

                $stage_det = DB::table('progress_stage')->where('id', $act->stage)->first();

                $item->created_date = Carbon::parse($item->created_at)->toDateString();

                $project = Project::find($item->pro_id);
                $item->pro_name = $project ? $project->project_name : 'no Project';
                $item->project_id = $item->pro_id ? $item->pro_id : null;
                $item->project_code = $project ? $project->project_id : 'no code';
                $item->end_plan = $stage_det->sc_end;

                $item->stage_name = $stage_det->stage;
                $item->act_name = $act->activity;

                $item->makeHidden(['created_at', 'updated_at']);
                return $item;
            });


        $attd_group = $today_labour->groupBy('date');  // Group by already formatted date





        if ($attd_group || $today_act) {
            return response()->json(['status' => 'success', 'today_labour' => $attd_group, 'today_act' => $today_act, 'today_act_eng' => $today_act_eng]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'no labour or Activity found']);
        }
    }

    // function to stoe comment

    public function comment_store(Request $req)
    {

        $last_task = Task::where('id', $req->task_id)->first();

        $notify_person = Task::where('parent_task_id', $last_task->parent_task_id)->pluck('assigned_to')->toArray();

        $latest_created_by = Task::where('parent_task_id', $last_task->parent_task_id)->value('created_by'); // gets single value, not collection

        // Merge both, avoiding nulls
        $notify_arr = array_merge($notify_person, $latest_created_by ? [$latest_created_by] : []);

        log::info('notify', $notify_arr);

        $comment = new Comment();

        $comment->task_id = $req->task_id;
        $comment->comment_for = $last_task->assigned_to;
        $comment->desp = $req->desp;
        $comment->c_by = auth()->user()->id ?? 1;

        $ins = $comment->save();

        if ($ins) {

            $task = Task::find($req->task_id);

            foreach ($notify_arr as $noti) {

                // if (auth()->user()->id == $noti) {
                //     continue;
                // }

                $fid_token = Employee::where('id', $noti)->value('token');

                $data = [
                    'to_id' => $noti ?? 0,
                    'f_id' => $req->task_id,
                    'type' => 'comment',
                    'title' => 'Comment Added',
                    'body' => (auth()->user()->name ?? 'Unknown') . ' Commented - "' . $req->desp . '" for task - ' . $task->title,
                    'token' => [$fid_token]
                ];

                $res = $this->notify_create($data);
            }
        }

        if ($ins) {
            if ($req->header('Authorization')) {
                return response()->json(['status' => 'success', 'message' => 'Comment Added Successfully']);
            } else {
                return redirect()->back();
            }
        } else {
            if ($req->header('Authorization')) {
                return response()->json(['status' => 'error', 'message' => 'Comment Failed to Add']);
            } else {
                return redirect()->back();
            }
        }
    }


    // comment list

    public function comment_list(Request $req)
    {

        // dd($req->all());

        $comments = Comment::where('task_id', $req->task_id)
            ->get()
            ->map(function ($item) {
                $emp = Employee::find($item->c_by);
                $item->employee = $emp ? $emp->name : null; // optional: attach specific fields
                $item->created_date = Carbon::parse($item->created_at)->format('d-m-Y');
                $item->created_time = Carbon::parse($item->created_at)->format('H:i:s');
                return $item;
            });

        if ($comments) {
            return response()->json(['status' => 'success', 'data' => $comments]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Comment Failed to show']);
        }
    }

    public function notify_seen_update(Request $req)
    {
        $notify = Notify::where('id', $req->notify_id)->first();



        if ($notify) {
            $notify->seen = 1;
            $notify->save();
            // log::info($notify);
            return response()->json(['status' => 'success', 'message' => 'Notification marked as seen']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Notification not found']);
        }
    }


    public function activity_remove($act_id, Request $req)
    {

        $pro_id = DB::table('progress_activity')->where('id', $act_id)->value('pro_id');

        $deleted = DB::table('progress_activity')->where('id', $act_id)->delete();

        if ($deleted) {
            if ($req->header('Authorization')) {
                return response()->json(['status' => 'success', 'message' => 'Activity deleted ']);
            } else {
                return redirect()->route('project.stages', ['pro_id' => $pro_id])->with('Activity Deleted');
            }
        } else {
            return response()->json(['status' => 'success', 'message' => 'Activity deleted']);
        }
    }
}
