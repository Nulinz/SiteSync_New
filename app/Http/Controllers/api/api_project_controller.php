<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Progress_import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Pro_docs;
use App\Models\Activity;
use App\Models\Activity_material;
use App\Models\Activity_work;
use App\Models\Attendance;
use App\Models\EntrySurvey;
use App\Models\SurveyChoice;
use App\Models\Survey_ans;
use App\Models\Survey;
use App\Models\EntryQC;
use App\Models\EntrySnag;
use App\Models\Snag_ans;
use App\Models\Qc_ans;
use App\Models\Drawing;
use App\Models\EntryDrawing;
use App\Models\New\RolePermission;
use App\Models\New\Permission;
use App\Models\New\Role;
use App\Models\QC;
use App\Models\QCChecklist;
use App\Models\Snag;
use Carbon\Carbon;

class api_project_controller extends Controller
{


    public function permissions(Request $request)
    {
        $user = auth()->user();

        // if ($user->can('doc_view')) {
        //     Log::info("User {$user->id} has permission 'doc_view'");
        // } else {
        //     Log::warning("User {$user->id} lacks 'doc_view' permission");
        // }

        $roleId = $user->role_id; // Assuming you have role_id in users table
        $role = Role::find($roleId);


        // $permissions = auth()->user()->getAllPermissions()->pluck('name');

        // Get permissions IDs from pivot table
        $permissionIds = RolePermission::where('role_id', $roleId)->pluck('permission_id');

        // Get permission names
        $permissions = Permission::whereIn('id', $permissionIds)->pluck('name');
        return response()->json([
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

public function project_profile(Request $req)
{
    $pro_id = $req->pro_id;

    $pro = Project::find($pro_id);

    if (!$pro) {
        return response()->json([
            'status' => 'error',
            'message' => 'No project found for the given ID.',
        ], 404);
    }

    // ✅ Get latest active progress stage
    $actual = DB::table('progress_stage')
        ->where('pro_id', $pro_id)
        ->where('status', 'Active')
        ->orderByDesc('id')
        ->first();

    $pro->planned = $actual->end_date ?? null;
    $pro->actual = $actual->sc_end ?? null;

    // ✅ Decode assigned_to properly
    $assignedIds = is_string($pro->assigned_to)
        ? json_decode($pro->assigned_to, true)
        : (array) $pro->assigned_to;

    // ✅ Replace asset() with S3 URLs for employee images
    $pro->assigned_to = Employee::whereIn('id', $assignedIds)
        ->get(['name', 'image_path'])
        ->map(function ($employee) {
            return [
                'name' => $employee->name,
                'image_url' => $employee->image_path
                    ? Storage::disk('s3')->url($employee->image_path)
                    : null,
            ];
        });

    // ✅ Use S3 for project document
    $pro_docs = collect([$pro])->map(function ($p) {
        return [
            'file_path' => $p->file_attachment
                ? Storage::disk('s3')->url('pro_docs/' . $p->file_attachment)
                : null,
            'file_name' => $p->file_attachment ?? null,
        ];
    });

    return response()->json([
        'status' => 'success',
        'data' => $pro,
        'pro_docs' => $pro_docs,
    ]);
}

    public function project_stage(Request $req)
    {
        $pro_id = $req->pro_id;


        $stage_det = DB::table('progress_stage')->where('pro_id', $pro_id)->where('status', 'Active')->select('id', 'stage', 'sc_start', 'sc_end')->get()->map(function ($lt) {

            $act_id =  DB::table('progress_activity')->where('stage', $lt->id)->pluck('id');

            $check_status1 = DB::table('activity_work')->whereIn('import_id', $act_id)->exists();

            if ($check_status1) {
                $last_act = $act_id->max(); // returns the highest (latest) id

                $check_status1 = DB::table('activity_work')->where('import_id', $last_act)->where('status', 'completed')->count();

                // log::info($check_status1);

                $lt->status_1 =  ($check_status1 > 0) ? 'completed' : 'in_progress';
            } else {
                $lt->status_1 = 'yet_to_start';
            }

            $check_status2 = DB::table('activity_work')->whereIn('import_id', $act_id)->where('cat', 'block')->count();

            $lt->status_2 = ($check_status2 > 0) ? 'delayed' : 'on_time';

            return $lt;
        });

        // dd($stage_det);



        //     // $count_val =  Activity_work::where('stage', $item->stage)->where('status', 'completed')->count();

        //     // $item->pro_per = $count_val > 0
        //     //     ? ($count_val / $item->sub_count) * 100
        //     //     : 0;

        //     return $item;
        // });

        $attd = Attendance::where('pro_id', $req->pro_id)->selectRaw('category,DATE(created_at) as date, SUM(skilled) as total_skilled,SUM(mc) as total_mc,SUM(fc) as total_fc,SUM(skilled + mc + fc) as total_all,c_by')
            ->groupBy(DB::raw('category,DATE(created_at),c_by'))
            ->orderBy('date', 'DESC')
            ->get()->map(function ($item) {

                // log::info($item);
                $c_by = $item->c_by ?? 1;

                $emp = Employee::where('id', $c_by)->select('name', 'image_path')->first();

                $item->name = $emp->name ?? 'Unknown';
                $item->image = $emp && $emp->image_path
                    ? asset('img/snag/' . $emp->image_path)
                    : null;
                $item->date = Carbon::parse($item->date)->format('d-m-Y');

                return $item;
            });

        $attd_group = $attd->groupBy('date');  // Group by already formatted date

        if ($stage_det) {
            return response()->json(['status' => 'success', 'stage' => $stage_det, 'attd' => $attd_group]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No project Stage Found']);
        }
    }


    public function project_sub_mat(Request $req)
    {
        $pro_id = $req->pro_id;
        $stage = $req->stage;


        $pro_stage = DB::table('progress_activity')->where('pro_id', $pro_id)->where('stage', $stage)->select('id', 'stage', 'activity')->get()->map(function ($lt) {

            $exists =  Activity_work::where('import_id', $lt->id)->exists();

            if ($exists) {
                $count =  Activity_work::where('import_id', $lt->id)->where('status', 'completed')->count();

                $lt->act_status = ($count > 0) ? 'completed' : 'in_progress';
            } else {
                $lt->act_status = 'yet_to_start';
            }

            return $lt;
        });

        // $stage_group = $pro_stage->groupBy('stage');



        $act_id = DB::table('progress_activity')->where('stage', $stage)->pluck('id');

        $material = Activity_work::whereIn('import_id', $act_id)->pluck('id');
        // dd($material);

        $act_mat = Activity_material::whereIn('act_id', $material)->selectRaw('act_id,DATE(created_at) as date, category, SUM(qty) as total')
            ->groupBy(DB::raw('DATE(created_at)'), 'category', 'act_id')
            ->orderBy('date')
            ->get()->map(function ($item) {

                $work =  Activity_work::where('id', $item->act_id)->first();

                $act = DB::table('progress_activity')->where('id', $work->import_id)->value('activity');

                // $stage_det = DB::table('progress_stage')->where('id', $act->stage)->first();

                // log::info($act);

                $item->sub_stage = $act ?? null;
                $item->date = Carbon::parse($item->date)->format('d-m-Y');

                return $item;
            });

        $act_mat_group = $act_mat->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('d-m-Y');
        });



        if ($pro_id) {
            return response()->json(['status' => 'success', 'stage' => $pro_stage, 'material' => $act_mat_group]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No project Substage/Material Found']);
        }
    }

    public function project_sub_list(Request $req)
    {

        $act_prime = $req->act_prime;

        $act_table =   DB::table('progress_activity')->where('id', $act_prime)->first();

        $end_date = DB::table('progress_stage')->where('id', $act_table->stage)->value('sc_end');

        $sub_list = Activity_work::where('import_id', $act_prime)->orderBy('created_at', 'DESC')->get()
            ->map(function ($item) {
                $item->file = collect($item->file)
                    ->filter(fn($file) => is_string($file))
                    ->map(fn($file) => asset('/img/activity_work/' . $file))
                    ->values();

                $item->created_date = Carbon::parse($item->created_at)->toDateString();

                $item->labour = Attendance::where('pro_id', $item->pro_id)->whereDate('created_at', $item->created_date)->select(DB::raw('SUM(skilled + mc + fc) as total_labour'))
                    ->value('total_labour') ?? 0;

                $item->makeHidden(['created_at', 'updated_at']);
                return $item;
            });

        if ($sub_list) {
            return response()->json(['status' => 'success', 'data' => $sub_list, 'end_date' => $end_date]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No project Sub list Found']);
        }
    }



    public function project_survey_list(Request $req)
    {
        $pro_id = $req->pro_id;

        $survey_list = EntrySurvey::with(['survey:id,title,description', 'user:id,name,image_path', 'user_cby:id,name,image_path'])
            ->where('project_id', $pro_id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {
                // Set full image path for user
                if ($item->user) {
                    $item->user->image_path = $item->user->image_path
                        ? asset($item->user->image_path)
                        : null;
                }

                // Set full image path for created-by user
                if ($item->user_cby) {
                    $item->user_cby->image_path = $item->user_cby->image_path
                        ? asset($item->user_cby->image_path)
                        : null;
                }

                $currentDateTime = now();
                $endDateTime = \Carbon\Carbon::parse($item->due_date);
                
                // ✅ Priority: Completed > Approved > Pending > New
                if ($item->status == 'completed') {
                    $item->status = 'completed';
                } elseif ($item->status == 'approved') {
                    $item->status = 'approved';
                } elseif ($currentDateTime->greaterThan($endDateTime)) {
                    $item->status = 'pending';
                } else {
                    $item->status = 'new';
                }
                
                return $item;

            });

        $survey = Survey::where('status', 'active')->select('id', 'title')->get();

        $tasks = Project::where('id', $pro_id)->select('assigned_to')->first();
        if ($tasks) {
            $tasks->assign_users = $tasks->assigned_users;
            unset($tasks->assigned_to);
        }

        $add_survey = [
            'survey_drop' => $survey,
            'assigned_user' => $tasks,
        ];

        if ($survey_list->count()) {
            return response()->json([
                'status' => 'success',
                'data' => $survey_list,
                'add_survey' => $add_survey
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'No project Survey list Found'
            ]);
        }
    }
    public function project_survey_ind(Request $req)
    {
        $survey_prime = $req->survey_prime;

        $survey_ind = EntrySurvey::with([
            'survey:id,title,description',
            'survey.questions:id,survey_id,question,question_type'
        ])->where('id', $survey_prime)->first();

        // ✅ Handle missing record early
        if (!$survey_ind || !$survey_ind->survey) {
            return response()->json([
                'status' => 'error',
                'message' => 'No survey found for the given ID.'
            ], 404);
        }

        // Get all answers in one query
        $all_answers = DB::table('survey_ans')
            ->where('entry_ans', $survey_prime)
            ->select('q_id', 'answer', 'q_type')
            ->get()
            ->groupBy('q_id');

        // Map questions + answers
        $q_with_answers = $survey_ind->survey->questions->map(function ($question) use ($all_answers) {
            $question->choice = SurveyChoice::where('question_id', $question->id)
                ->select('id', 'choice')
                ->get() ?? collect();

            $answerRow = $all_answers->get($question->id)?->first();

            $question->answers = null;

            if ($answerRow) {
                if ($answerRow->q_type === 'File') {
                    $filePaths = json_decode($answerRow->answer, true) ?? [];
                    $question->answers = collect($filePaths)->map(function ($f) {
                        return $f
                            ? env('AWS_URL') . 'survey/' . ltrim($f, '/')
                            : null;
                    });
                } else {
                    $question->answers = $answerRow->answer;
                }
            }

            return $question;
        });

        // ✅ Build final response
        $response = [
            'id' => $survey_ind->id,
            'project_id' => $survey_ind->project_id,
            'survey_id' => $survey_ind->survey_id,
            'instruction' => $survey_ind->instruction,
            'survey_title' => $survey_ind->survey->title,
            'survey_desp' => $survey_ind->survey->description,
            'q_with_answers' => $q_with_answers,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    }

    public function project_qc_list(Request $req)
    {
        $pro_id = $req->pro_id;

        $qc_list = EntryQC::with(['qc:id,title,description', 'user:id,name,image_path', 'user_cby:id,name,image_path'])
            ->where('project_id', $pro_id)
             ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {
                // âœ… User image
                if ($item->user) {
                    $item->user->image_path = $item->user->image_path
                        ? asset($item->user->image_path)
                        : null;
                }

                // âœ… Created-by user image
                if ($item->user_cby) {
                    $item->user_cby->image_path = $item->user_cby->image_path
                        ? asset($item->user_cby->image_path)
                        : null;
                }

                // âœ… Status logic (same as survey list)
                $currentDateTime = now();
                $endDateTime = \Carbon\Carbon::parse($item->due_date);

                // ✅ Priority: Completed > Approved > Pending > New
                if ($item->status == 'completed') {
                    $item->status = 'completed';
                } elseif ($item->status == 'approved') {
                    $item->status = 'approved';
                } elseif ($currentDateTime->greaterThan($endDateTime)) {
                    $item->status = 'pending';
                } else {
                    $item->status = 'new';
                }

                return $item;
            });

        $qc_title = QC::with(['checklists:id,qc_id,question'])
            ->where('status', 'active')
            ->select('id', 'title')
            ->get();

        $tasks = Project::where('id', $pro_id)
            ->get(['assigned_to'])
            ->map(fn($task) => [
                'assign_users' => $task->assigned_users
            ]);

        $add_qc = collect([
            'qc_drop' => $qc_title,
            'assigned_user' => $tasks
        ]);

        if ($qc_list->count()) {
            return response()->json([
                'status' => 'success',
                'data' => $qc_list,
                'add_qc' => $add_qc
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'No project QC list Found'
            ]);
        }
    }


    public function project_qc_ind(Request $req)
    {

        // $pro_id = $req->pro_id;
        // $pro_stage = $req->pro_stage;
        // $pro_sub = $req->pro_sub;
        $qc_prime = $req->qc_prime;
        // $qc_prime = 15;



        $qc_ind = EntryQC::where('id', $qc_prime)->first();

        $check = $qc_ind->checklist_names;

        $qc_ans = Qc_ans::where('qc_entry', $qc_prime)->first();

        $selectedIds = [];

        if ($qc_ans && !empty($qc_ans->answer)) {
            $decoded = json_decode($qc_ans->answer, true);
            if (is_array($decoded)) {
                $selectedIds = $decoded;
            }
        }

        $result = collect($check)->mapWithKeys(function ($questionText, $id) use ($selectedIds) {
            return [$questionText => in_array((int) $id, $selectedIds)];
        });

        if ($qc_ind) {
            return response()->json(['status' => 'success', 'data' => $result]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No project Qc Individual Found']);
        }
    }


    public function project_snag_list(Request $req)
    {
        $pro_id = $req->pro_id;

        $snag_list = EntrySnag::with(['snag:id,category,description', 'user:id,name,image_path', 'user_cby:id,name,image_path'])
            ->where('project_id', $pro_id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {
                // âœ… User image
                if ($item->user) {
                    $item->user->image_path = $item->user->image_path
                        ? asset($item->user->image_path)
                        : null;
                }

                // âœ… Created-by user image
                if ($item->user_cby) {
                    $item->user_cby->image_path = $item->user_cby->image_path
                        ? asset($item->user_cby->image_path)
                        : null;
                }

                // âœ… Status logic (same as survey + QC)
                $currentDateTime = now();
                $endDateTime = \Carbon\Carbon::parse($item->due_date);

                if ($item->status == 'completed') {
                    $item->status = 'completed';
                } elseif ($item->status == 'approved') {
                    $item->status = 'approved';
                } elseif ($currentDateTime->greaterThan($endDateTime)) {
                    $item->status = 'pending';
                } else {
                    $item->status = 'new';
                }
                return $item;
            });

        $snag_title = Snag::where('status', 'active')
            ->select('id', 'category')
            ->get();

        $tasks = Project::where('id', $pro_id)
            ->get(['assigned_to'])
            ->map(fn($task) => [
                'assign_users' => $task->assigned_users
            ]);

        $add_snag = [
            'snag_drop' => $snag_title,
            'assigned_user' => $tasks
        ];

        if ($snag_list->count()) {
            return response()->json([
                'status' => 'success',
                'data' => $snag_list,
                'add_snag' => $add_snag
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'No project Snag list Found'
            ]);
        }
    }


    public function project_snag_ind(Request $req)
    {

        // $pro_id = $req->pro_id;
        // $pro_stage = $req->pro_stage;
        // $pro_sub = $req->pro_sub;
        $snag_prime = $req->snag_prime;
        // $snag_prime = 13;


        $snag_ind = Snag_ans::where('entry_snag', $snag_prime)->get()->map(function ($item) {

            $item->filepath = $item->file
            ? env('AWS_URL') . 'snag/' . $item->file  // S3 path
            : null;
            return collect($item)->only(['filepath']);
        });

        $snag_assign = EntrySnag::with(['user:id,name', 'user_cby:id,name'])->where('id', $snag_prime)->select('assigned_to', 'c_by', 'due_date', 'file_attachment', 'location')->first();
        if ($snag_assign) {
            $snag_assign->setAttribute('file_url', $snag_assign->file_attachment
            ? env('AWS_URL') . 'snag/' . $snag_assign->file_attachment  // S3 path
            : null
        );            // $snag_assign->setAttribute('check', $snag_assign->file_attachment);
        }


        if ($snag_ind) {
            return response()->json(['status' => 'success', 'snag_due' => $snag_assign, 'snag_file' => $snag_ind]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No project Snag Individual Found']);
        }
    }


    // public function project_draw_list(Request $req)
    // {

    //     // $pro_id = $req->pro_id;
    //     // $pro_stage = $req->pro_stage;
    //     // $pro_sub = $req->pro_sub;
    //     $pro_id = $req->pro_id;

    //     // Step 1: Get all active drawings and group by file_type
    //     $drawings = Drawing::where('status', 'active')->select('id', 'title', 'file_type')->get();
    //     $groupedByFileType = $drawings->groupBy('file_type');

    //     // Step 2: For each file_type group, fetch the latest EntryDrawing per drawing_id
    //     $latestEntryDrawingsGrouped = $groupedByFileType->map(function ($drawingsGroup) use ($pro_id) {
    //         return $drawingsGroup->map(function ($drawing) use ($pro_id) {
    //             // Get latest EntryDrawing for this Drawing
    //             $entry = EntryDrawing::with([
    //                 'drawing:id,title,file_type,description',
    //                 'user:id,name,image_path'
    //             ])
    //                 ->where('drawing_id', $drawing->id)
    //                 ->where('project_id', $pro_id)
    //                 ->latest('created_at')
    //                 ->first();

    //             // âœ… Add file_path for attachment
    //         if ($entry && $entry->file_attachment) {
    //             $entry->file_path = env('AWS_URL') . 'draw/' . $entry->file_attachment;
    //         }

    //         // ✅ Use S3 URL for user image
    //         if ($entry && $entry->user && $entry->user->image_path) {
    //             $entry->user->image_path = env('AWS_URL')  . $entry->user->image_path;
    //         }

    //             return $entry;
    //         })->filter(); // Remove null entries
    //     });

    //     // $latestEntryDrawingsGrouped = $groupedByFileType->map(function ($drawingsGroup) use ($pro_id) {
    //     //     return $drawingsGroup->map(function ($drawing) use ($pro_id) {
    //     //         // Get latest EntryDrawing for this Drawing
    //     //         $entry =  EntryDrawing::with([
    //     //             'drawing:id,title,file_type,description',
    //     //             'user:id,name,image_path'
    //     //         ])
    //     //             ->where('drawing_id', $drawing->id)
    //     //             ->where('project_id', $pro_id)
    //     //             // ->where('status', 'Approved')
    //     //             ->latest('created_at')
    //     //             ->first();

    //     //         // dd($pro_id);


    //     //         // âœ… Add file_path before returning
    //     //         if ($entry && $entry->file_attachment) {
    //     //             $entry->file_path = asset($entry->file_attachment);
    //     //         }

    //     //         // âœ… Add full image_path for user, if exists
    //     //         if ($entry && $entry->user && $entry->user->image_path) {
    //     //             $entry->user->image_path = asset($entry->user->image_path);
    //     //         }

    //     //         return $entry;
    //     //     })->filter(); // Remove nulls (if no EntryDrawing found)
    //     // });


    //     $updatedGroup = collect($groupedByFileType)->map(function ($drawings, $fileType) use ($latestEntryDrawingsGrouped, $pro_id) {
    //         return collect($drawings)->map(function ($drawing) use ($latestEntryDrawingsGrouped, $fileType, $pro_id) {
    //             $entries = collect($latestEntryDrawingsGrouped[$fileType] ?? []);

    //             // Filter entries that match this drawing ID
    //             $matchedEntries = $entries->where('drawing_id', $drawing['id']);

    //             // If any entry has status 'pending', mark drawing status as false
    //             $hasPending = $matchedEntries->contains(function ($entry) {
    //                 return $entry['status'] === 'pending';
    //             });

    //             $count = EntryDrawing::where('drawing_id', $drawing['id'])->where('project_id', $pro_id)->count();

    //             if ($count == 0) {
    //                 $version  = 'Version 1';
    //             } else {

    //                 $version = 'Version ' . ($count + 1);
    //             }

    //             // Add new key to drawing
    //             $drawing['status'] = $hasPending ? false : true;
    //             $drawing['next-version'] = $version;

    //             return $drawing;
    //         });
    //     });

    //     if ($latestEntryDrawingsGrouped) {
    //         return response()->json(['status' => 'success', 'data' => $latestEntryDrawingsGrouped, 'drawing_group' => $updatedGroup]);
    //     } else {
    //         return response()->json(['status' => 'success', 'message' => 'No project Drawing list Found']);
    //     }
    // }


       public function project_draw_list(Request $req)
    {

        // $pro_id = $req->pro_id;
        // $pro_stage = $req->pro_stage;
        // $pro_sub = $req->pro_sub;
        $pro_id = $req->pro_id;

        // Step 1: Get all active drawings and group by file_type
        $drawings = Drawing::where('status', 'active')->select('id', 'title', 'file_type')->get();
        $groupedByFileType = $drawings->groupBy('file_type');

        // Step 2: For each file_type group, fetch the latest EntryDrawing per drawing_id
        $latestEntryDrawingsGrouped = $groupedByFileType->map(function ($drawingsGroup) use ($pro_id) {
            return $drawingsGroup->map(function ($drawing) use ($pro_id) {
                // Get latest EntryDrawing for this Drawing
                $entry = EntryDrawing::with([
                    'drawing:id,title,file_type,description',
                    'user:id,name,image_path'
                ])
                    ->where('drawing_id', $drawing->id)
                    ->where('project_id', $pro_id)
                    ->latest('created_at')
                    ->first();

                // âœ… Add file_path for attachment
            if ($entry && $entry->file_attachment) {
                $entry->file_path = env('AWS_URL') . 'draw/' . $entry->file_attachment;
            }

            // ✅ Use S3 URL for user image
            if ($entry && $entry->user && $entry->user->image_path) {
                $entry->user->image_path = env('AWS_URL')  . $entry->user->image_path;
            }

                return $entry;
            })->filter(); // Remove null entries
        });

        // $latestEntryDrawingsGrouped = $groupedByFileType->map(function ($drawingsGroup) use ($pro_id) {
        //     return $drawingsGroup->map(function ($drawing) use ($pro_id) {
        //         // Get latest EntryDrawing for this Drawing
        //         $entry =  EntryDrawing::with([
        //             'drawing:id,title,file_type,description',
        //             'user:id,name,image_path'
        //         ])
        //             ->where('drawing_id', $drawing->id)
        //             ->where('project_id', $pro_id)
        //             // ->where('status', 'Approved')
        //             ->latest('created_at')
        //             ->first();

        //         // dd($pro_id);


        //         // âœ… Add file_path before returning
        //         if ($entry && $entry->file_attachment) {
        //             $entry->file_path = asset($entry->file_attachment);
        //         }

        //         // âœ… Add full image_path for user, if exists
        //         if ($entry && $entry->user && $entry->user->image_path) {
        //             $entry->user->image_path = asset($entry->user->image_path);
        //         }

        //         return $entry;
        //     })->filter(); // Remove nulls (if no EntryDrawing found)
        // });


        $updatedGroup = collect($groupedByFileType)->map(function ($drawings, $fileType) use ($latestEntryDrawingsGrouped, $pro_id) {
            return collect($drawings)->map(function ($drawing) use ($latestEntryDrawingsGrouped, $fileType, $pro_id) {
                $entries = collect($latestEntryDrawingsGrouped[$fileType] ?? []);

                // Filter entries that match this drawing ID
                $matchedEntries = $entries->where('drawing_id', $drawing['id']);

                // If any entry has status 'pending', mark drawing status as false
                $hasPending = $matchedEntries->contains(function ($entry) {
                    return $entry['status'] === 'pending';
                });

                $count = EntryDrawing::where('drawing_id', $drawing['id'])->where('project_id', $pro_id)->count();

                if ($count == 0) {
                    $version  = 'Version 1';
                } else {

                    $version = 'Version ' . ($count + 1);
                }

                // Add new key to drawing
                $drawing['status'] = $hasPending ? false : true;
                $drawing['next-version'] = $version;

                return $drawing;
            });
        });

        if ($latestEntryDrawingsGrouped) {
            return response()->json(['status' => 'success', 'data' => $latestEntryDrawingsGrouped, 'drawing_group' => $updatedGroup]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No project Drawing list Found']);
        }
    }


    public function project_draw_history(Request $req)
    {

        // Log::info('drawing_history', $req->all());
        $drawing_id = $req->drawing_id;
        $pro_id = $req->project_id;
        // $drwaing_id = 7;
        // $pro_id = 6;

        $draw_list =  EntryDrawing::with(['drawing:id,title,file_type,description', 'user:id,name,image_path'])->where('project_id', $pro_id)->where('drawing_id', $drawing_id)->orderBy('created_at', 'DESC')->get()
            ->map(function ($item) {

                // ✅ Generate S3 URL for drawing file
            if ($item->file_attachment) {
                $item->file_path = \Storage::disk('s3')->url('draw/' . $item->file_attachment);
            } else {
                $item->file_path = null;
            }

            // ✅ Generate S3 URL for user image (if available)
            if ($item->user && $item->user->image_path) {
                $item->user->image_path = \Storage::disk('s3')->url('employees/' . $item->user->image_path);
            } else {
                $item->user->image_path = null;
            }
                return $item;
            });


        if ($draw_list) {
            return response()->json(['status' => 'success',  'draw_history' => $draw_list]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No project Snag Individual Found']);
        }
    }


    public function qc_activity(Request $req)
    {


        // Log::info($req->all());
        $act_qc = DB::table('progress_activity')->where('id', $req->act_prime)->first();

        $qc_ind = QC::with(['checklists'])->where('id', $act_qc->qc)->first();

        $qc_act_ans = Activity_work::where('import_id', $req->act_prime)->where('cat', 'work')->whereDate('created_at', today())->first();

        $selectedIds = [];

        if (!empty($qc_act_ans?->qc) && is_array($qc_act_ans->qc)) {
            $selectedIds = $qc_act_ans->qc;
        }


        $result = collect($qc_ind->checklists)->map(function ($checklist) use ($selectedIds, $qc_ind) {
            return [
                'title' => $qc_ind->title,
                'description' => $qc_ind->description,
                'id' => $checklist->id,
                'question' => $checklist->question,
                'selected' => in_array((int) $checklist->id, $selectedIds),
            ];
        });

        // $result = [

        //     'checklist' => $checklistStatus,
        //     'question_id' => $checklist->id
        // ];


        if ($qc_ind) {
            return response()->json(['status' => 'success',  'qc_list' => $result]);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No QC List Found']);
        }
    }
}

