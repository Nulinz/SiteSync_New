<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Models\Stage;
use App\Models\Activity_material;
use App\Models\Activity_work;
use App\Models\QC;
use App\Models\Project;
use App\Models\Employee;
use App\Traits\common;
use Carbon\Carbon;
use Exception;

class ActivityController extends Controller
{
    use common;

    // function for fetch teh activity

    public function activity_fetch(Request $req)
    {

        // $act_prime = $req->act_prime;

        $today = Carbon::now()->toDateString();

        // Try to fetch today’s activity
        $activity = Activity_work::where('import_id', $req->act_prime)
            ->where('cat', 'work')
            ->whereDate('created_at', $today)
            ->first();

        $work = new \stdClass();

        if ($activity) {
            // ✅ Today's activity found
            $work = $activity;

            if ($work) {
                $work_mat = Activity_material::where('act_id', $work->id)->get();

                // Normalize file paths
                $files = is_array($work->file) ? $work->file : json_decode($work->file, true) ?? [];
                $work_files = collect($files)->map(function ($filePath) {
                    return asset('img/activity_work/' . $filePath);
                });
            }
        } else {
            // ❌ No activity today — check most recent previous entry
            $previous = Activity_work::where('import_id', $req->act_prime)
                ->where('cat', 'work')
                ->whereDate('created_at', '<', $today)
                ->orderBy('created_at', 'desc')
                ->first();

            $work->qc = $previous->qc ?? null;
            $work->qc_per = $previous->qc_per ?? null;
            $work->progress = $previous->progress ?? null;
        }

        // $work = Activity_work::where('pro_id', $req->pro_id)
        //     ->where('stage', $req->stage)
        //     ->where('sub', $req->sub)
        //     ->whereDate('created_at', $date)
        //     ->first();

        // $work = Activity_work::find($req->act_prime);


        if ($work) {
            return response()->json([
                'status' => 'success',
                'work' =>  $work,
                'work_mat' =>  $work_mat ?? null,
                'work_files' =>  $work_files ?? null

            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No data Found for actitivity.']);
        }
    }

    public function store_stage(Request $req)
    {

        // dd($req->all());

        // Log::info('Work', $req->all());

        $act_prime = $req->act_prime;

        $activities_current = DB::table('progress_activity')->where('id', $act_prime)->first(); // more concise than where->first()

        // $daysToAdd = $activity->days_to_add ?? 0; // Use 0 if null
        $daysToAdd = 1; // Use 0 if null

        $today = Carbon::now()->toDateString();

        // dd($today);



        if ($req->cat === 'block') {

            $stage_current = Stage::find($activities_current->stage); // more concise than where->first()

            if ($stage_current) {
                // Update current activity's sc_end

                if ($stage_current->sc_start == $today) {

                    $stage_current->sc_start = Carbon::parse($stage_current->sc_start)
                        ->addDays($daysToAdd)
                        ->toDateString();
                }

                $stage_current->sc_end = Carbon::parse($stage_current->sc_end)
                    ->addDays($daysToAdd)
                    ->toDateString();


                $stage_current->save();



                // Get all future activities for same project, ordered by ID
                $stage_future = Stage::where('pro_id', $stage_current->pro_id)
                    ->where('id', '>', $stage_current->id)
                    // ->where('sc_start', '>=', $today)
                    ->orderBy('id')
                    ->get();

                // dd($activities_future);

                foreach ($stage_future as $stage_f) {
                    // Safely parse and update dates only if they exist

                    $act_id = DB::table('progress_activity')->where('stage', $stage_f->id)->where('status', 1)->pluck('id')->toArray();

                    $start_count = Activity_work::whereIn('import_id', $act_id)->count();


                    if (($stage_f->sc_start) && ($start_count == 0)) {
                        $stage_f->sc_start = Carbon::parse($stage_f->sc_start)
                            ->addDays($daysToAdd)
                            ->toDateString();
                    }

                    if ($stage_f->sc_end) {
                        $stage_f->sc_end = Carbon::parse($stage_f->sc_end)
                            ->addDays($daysToAdd)
                            ->toDateString();
                    }

                    $stage_f->save();
                }


                $act_block =  new Activity_work();

                $act_block->import_id = $req->act_prime;
                $act_block->pro_id = $activities_current->pro_id;
                // $act_block->stage = $activities_current->stage;
                // $act_block->sub = $activities_current->sub;
                $act_block->cat = $req->cat;
                $act_block->next_day = $req->block_cat;
                $act_block->remarks = $req->remarks;
                $act_block->c_by = auth()->user()->id ?? 1;

                $block_save =  $act_block->save();


                $block_table = DB::table('block_table')->insert([
                    'cat' => $req->cat,
                    'work_id' => $act_block->id,
                    'c_by' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);



                // $pro_cby = Project::where('id', $activities_current->pro_id)->select('id', 'project_name', 'c_by')->first();

                // $fid_token = Employee::where('id', $pro_cby->c_by)->value('token');

                // $data = [
                //     'to_id' => $pro_cby->c_by,
                //     'f_id' => $act_block->id,
                //     'type' => 'progress_work',
                //     'title' => 'Progress Blocker',
                //     'body' =>  'Progress Blocked For Project -  ' . $pro_cby->project_name . ' Stage ' . $activities_current->stage . '- Activity ' . $activities_current->sub,
                //     'token' => [$fid_token]
                // ];

                // $res = $this->notify_create($data);


                if ($block_save) {
                    return response()->json(['success' => 'sucess', 'message' => 'Blocker Added Successfully'], 200);
                } else {
                    return response()->json(['success' => 'sucess', 'message' => 'Blocker Failed to Add'], 200);
                }
            }
        } else {


            // $pro_id = $req->pro_id;
            // $stage = $req->stage;
            // $sub = $req->sub;

            $fileData = [];

            try {

                if ($req->extension == 'yes') {


                    $daysToAdd = 1;

                    $stage_current = Stage::find($activities_current->stage); // more concise than where->first()


                    if ($stage_current) {
                        // Update current activity's sc_end

                        // if ($stage_current->sc_start == $today) {

                        //     $stage_current->sc_start = Carbon::parse($stage_current->sc_start)
                        //         ->addDays($daysToAdd)
                        //         ->toDateString();
                        // }

                        $stage_current->sc_end = Carbon::parse($stage_current->sc_end)
                            ->addDays($daysToAdd)
                            ->toDateString();


                        $stage_current->save();

                        // Get all future activities for same project, ordered by ID
                        $stage_future = Stage::where('pro_id', $stage_current->pro_id)
                            ->where('id', '>', $stage_current->id)
                            // ->where('sc_start', '>=', $today)
                            ->orderBy('id')
                            ->get();

                        // dd($activities_future);

                        foreach ($stage_future as $stage_f) {
                            // Safely parse and update dates only if they exist

                            $act_id = DB::table('progress_activity')->where('stage', $stage_f->id)->where('status', 1)->pluck('id')->toArray();

                            $start_count = Activity_work::whereIn('import_id', $act_id)->count();


                            if (($stage_f->sc_start) && ($start_count == 0)) {
                                $stage_f->sc_start = Carbon::parse($stage_f->sc_start)
                                    ->addDays($daysToAdd)
                                    ->toDateString();
                            }

                            if ($stage_f->sc_end) {
                                $stage_f->sc_end = Carbon::parse($stage_f->sc_end)
                                    ->addDays($daysToAdd)
                                    ->toDateString();
                            }

                            $stage_f->save();
                        }
                    }
                }


                // store teh images  $fileData = [];

                if ($req->hasFile('file_attachment')) {
                    foreach ($req->file('file_attachment') as $file) {

                        $filename = str_replace(' ', '_', $file->getClientOriginalName());

                        $file->move(public_path('img/activity_work/'), $filename);

                        $fileData[] = $filename;
                    }
                }


                $date = Carbon::now()->toDateString();

                // Try to find existing work by import_id and created_at DATE (ignoring time)
                $ac_work = Activity_work::where('import_id', $req->act_prime)
                    ->whereDate('created_at', $date)
                    ->first();

                if (!$ac_work) {
                    // No matching record, so create a new one
                    $ac_work = new Activity_work();
                    $ac_work->import_id = $req->act_prime;
                    // $ac_work->created_at = now(); // Optional - Laravel does this automatically
                }

                // Always update/assign values
                $ac_work->pro_id = $activities_current->pro_id;
                $ac_work->cat = $req->cat;
                $ac_work->qc = json_decode($req->qc, true);
                $ac_work->qc_per = $req->qc_per;
                $ac_work->progress = $req->progress;
                $ac_work->next_day = $req->next_day;
                $ac_work->remarks = $req->remarks;
                $ac_work->status = $req->status;
                $ac_work->c_by = auth()->user()->id ?? 1;
                // Merge files if already exists
                if (!empty($fileData)) {
                    $existingFiles = is_array($ac_work->file) ? $ac_work->file : json_decode($ac_work->file, true) ?? [];
                    $ac_work->file = array_merge($existingFiles, $fileData);
                }

                $ac_work->save();

                if ($req->extension == 'yes') {
                    $block_table = DB::table('block_table')->insert([
                        'cat' => 'extension',
                        'work_id' => $ac_work->id,
                        'c_by' => auth()->user()->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }


                // if (!$work) {

                //     Log::info('Work', $req->all());
                //     // Create new record
                //     $ac_work = new Activity_work();

                //     $ac_work->pro_id = $req->pro_id;
                //     $ac_work->import_id = $req->sub_id;
                //     $ac_work->stage = $req->stage;
                //     $ac_work->sub = $req->sub;
                //     $ac_work->cat = $req->cat;
                //     $ac_work->qc = json_decode($req->qc, true);
                //     $ac_work->qc_per = $req->qc_per;
                //     $ac_work->progress = $req->progress;
                //     $ac_work->next_day = $req->next_day;
                //     $ac_work->remarks = $req->remarks;
                //     $ac_work->file = $fileData ?? [];
                //     $ac_work->status = $req->status;
                //     $ac_work->c_by = auth()->user()->id ?? 1;
                // } else {
                //     // Update the existing record
                //     $ac_work = $work;

                //     $ac_work->next_day = $req->next_day;
                //     $ac_work->qc = json_decode($req->qc, true);
                //     $ac_work->qc_per = $req->qc_per;
                //     $ac_work->progress = $req->progress;
                //     $ac_work->remarks = $req->remarks;
                //     $ac_work->status = $req->status;

                //     // Append new files if any
                //     if (!empty($fileData)) {
                //         $existingFiles = is_array($ac_work->file) ? $ac_work->file : json_decode($ac_work->file, true) ?? [];
                //         $ac_work->file = array_merge($existingFiles, $fileData);
                //     }
                // }

                // $ac_work->save(); // Don't forget to save the record


                // if ($req->status == 'completed') {

                //     $pro_cby = Project::where('id', $ac_work->pro_id)->select('id', 'project_name', 'c_by')->first();

                //     $fid_token = Employee::where('id', $pro_cby->c_by)->value('token');

                //     $data = [
                //         'to_id' => $pro_cby->c_by,
                //         'f_id' => $ac_work->id,
                //         'type' => 'progress_work',
                //         'title' => 'Progress Submitted',
                //         'body' =>  'Progress Submitted For Project -  ' . $pro_cby->project_name . ' Stage ' . $req->stage . '- Activity ' . $req->sub,
                //         'token' => [$fid_token]
                //     ];
                //     log::info('data-attd', $data);


                //     $res = $this->notify_create($data);
                // }

                $materials = json_decode($req->material, true); // decode JSON to array

                if (!empty($materials) && is_array($materials)) {

                    foreach ($materials as $mat) {

                        $ac_mat = new Activity_material();

                        $ac_mat->act_id = $ac_work->id ?? 1;
                        $ac_mat->category = $mat['category'] ?? null;
                        $ac_mat->unit = $mat['unit'] ?? null;
                        $ac_mat->qty = $mat['quantity'] ?? 0;
                        $ac_mat->c_by = auth()->user()->id ?? 1;

                        $success = $ac_mat->save();
                    }
                }

                if ($ac_work) {
                    return response()->json(['success' => 'sucess', 'message' => 'Work Added Successfully'], 200);
                } else {
                    return response()->json(['success' => 'sucess', 'message' => 'Work Failed to Add'], 200);
                }
            } catch (Exception $e) {
                Log::error('Exception caught: ' . $e->getMessage());
            }
        }
        return response()->json(['success' => 'sucess'], 200);
    }


    public function store_activity(Request $req)
    {

        // dd($req->all());

        // Log::info('Work', $req->all());

        $act_prime = $req->act_prime;

        // $daysToAdd = $activity->days_to_add ?? 0; // Use 0 if null
        $daysToAdd = 1; // Use 0 if null

        $today = Carbon::now()->toDateString();

        // dd($today);



        if ($req->cat === 'block') {
            $activities_current = Activity::find($act_prime); // more concise than where->first()

            if ($activities_current) {
                // Update current activity's sc_end

                if ($activities_current->sc_start == $today) {

                    $activities_current->sc_start = Carbon::parse($activities_current->sc_start)
                        ->addDays($daysToAdd)
                        ->toDateString();
                }

                $activities_current->sc_end = Carbon::parse($activities_current->sc_end)
                    ->addDays($daysToAdd)
                    ->toDateString();



                $activities_current->save();

                // Get all future activities for same project, ordered by ID
                $activities_future = Activity::where('pro_id', $activities_current->pro_id)
                    ->where('id', '>', $act_prime)
                    ->where('sc_start', '>=', $today)
                    ->orderBy('id')
                    ->get();

                // dd($activities_future);

                foreach ($activities_future as $activity) {
                    // Safely parse and update dates only if they exist
                    if (($activity->sc_start)) {
                        $activity->sc_start = Carbon::parse($activity->sc_start)
                            ->addDays($daysToAdd)
                            ->toDateString();
                    }

                    if ($activity->sc_end) {
                        $activity->sc_end = Carbon::parse($activity->sc_end)
                            ->addDays($daysToAdd)
                            ->toDateString();
                    }

                    $activity->save();
                }


                $act_block =  new Activity_work();

                $act_block->import_id = $req->act_prime;
                $act_block->pro_id = $activities_current->pro_id;
                $act_block->stage = $activities_current->stage;
                $act_block->sub = $activities_current->sub;
                $act_block->cat = $req->cat;
                $act_block->next_day = $req->block_cat;
                $act_block->remarks = $req->remarks;
                $act_block->c_by = auth()->user()->id ?? 1;

                $block_save =  $act_block->save();

                $pro_cby = Project::where('id', $activities_current->pro_id)->select('id', 'project_name', 'c_by')->first();

                $fid_token = Employee::where('id', $pro_cby->c_by)->value('token');

                $data = [
                    'to_id' => $pro_cby->c_by,
                    'f_id' => $act_block->id,
                    'type' => 'progress_work',
                    'title' => 'Progress Blocker',
                    'body' =>  'Progress Blocked For Project -  ' . $pro_cby->project_name . ' Stage ' . $activities_current->stage . '- Activity ' . $activities_current->sub,
                    'token' => [$fid_token]
                ];

                $res = $this->notify_create($data);


                if ($block_save) {
                    return response()->json(['success' => 'sucess', 'message' => 'Blocker Added Successfully'], 200);
                } else {
                    return response()->json(['success' => 'sucess', 'message' => 'Blocker Failed to Add'], 200);
                }
            }
        } else {


            $pro_id = $req->pro_id;
            $stage = $req->stage;
            $sub = $req->sub;

            $fileData = [];

            try {

                if ($req->extension == 'yes') {


                    $daysToAdd = 1;

                    // $work =  Activity_work::find(act_prime);

                    $activities_current = Activity::find($req->sub_id); // more concise than where->first()

                    if ($activities_current) {
                        // Update current activity's sc_end

                        // if ($activities_current->sc_start == $today) {

                        //     $activities_current->sc_start = Carbon::parse($activities_current->sc_start)
                        //         ->addDays($daysToAdd)
                        //         ->toDateString();
                        // }

                        $activities_current->sc_end = Carbon::parse($activities_current->sc_end)
                            ->addDays($daysToAdd)
                            ->toDateString();



                        $activities_current->save();

                        // Get all future activities for same project, ordered by ID
                        $activities_future = Activity::where('pro_id', $activities_current->pro_id)
                            ->where('id', '>', $activities_current->id)
                            ->where('sc_start', '>=', $today)
                            ->orderBy('id')
                            ->get();

                        // log::info('futrue', $activities_current->toArray());

                        foreach ($activities_future as $activity) {
                            // Safely parse and update dates only if they exist
                            if (($activity->sc_start)) {
                                $activity->sc_start = Carbon::parse($activity->sc_start)
                                    ->addDays($daysToAdd)
                                    ->toDateString();
                            }

                            if ($activity->sc_end) {
                                $activity->sc_end = Carbon::parse($activity->sc_end)
                                    ->addDays($daysToAdd)
                                    ->toDateString();
                            }

                            $activity->save();
                        }
                    }
                }


                // store teh images  $fileData = [];

                if ($req->hasFile('file_attachment')) {
                    foreach ($req->file('file_attachment') as $file) {

                        $filename = str_replace(' ', '_', $file->getClientOriginalName());

                        $file->move(public_path('img/activity_work/'), $filename);

                        $fileData[] = $filename;
                    }
                }



                $date = Carbon::now()->toDateString();

                $work = Activity_work::where('pro_id', $pro_id)->where('stage', $req->stage)->where('sub', $req->sub)->whereDate('created_at', $date)->first();

                if (!$work) {

                    Log::info('Work', $req->all());
                    // Create new record
                    $ac_work = new Activity_work();

                    $ac_work->pro_id = $req->pro_id;
                    $ac_work->import_id = $req->sub_id;
                    $ac_work->stage = $req->stage;
                    $ac_work->sub = $req->sub;
                    $ac_work->cat = $req->cat;
                    $ac_work->qc = json_decode($req->qc, true);
                    $ac_work->qc_per = $req->qc_per;
                    $ac_work->progress = $req->progress;
                    $ac_work->next_day = $req->next_day;
                    $ac_work->remarks = $req->remarks;
                    $ac_work->file = $fileData ?? [];
                    $ac_work->status = $req->status;
                    $ac_work->c_by = auth()->user()->id ?? 1;
                } else {
                    // Update the existing record
                    $ac_work = $work;

                    $ac_work->next_day = $req->next_day;
                    $ac_work->qc = json_decode($req->qc, true);
                    $ac_work->qc_per = $req->qc_per;
                    $ac_work->progress = $req->progress;
                    $ac_work->remarks = $req->remarks;
                    $ac_work->status = $req->status;

                    // Append new files if any
                    if (!empty($fileData)) {
                        $existingFiles = is_array($ac_work->file) ? $ac_work->file : json_decode($ac_work->file, true) ?? [];
                        $ac_work->file = array_merge($existingFiles, $fileData);
                    }
                }

                $ac_work->save(); // Don't forget to save the record


                if ($req->status == 'completed') {

                    $pro_cby = Project::where('id', $ac_work->pro_id)->select('id', 'project_name', 'c_by')->first();

                    $fid_token = Employee::where('id', $pro_cby->c_by)->value('token');

                    $data = [
                        'to_id' => $pro_cby->c_by,
                        'f_id' => $ac_work->id,
                        'type' => 'progress_work',
                        'title' => 'Progress Submitted',
                        'body' =>  'Progress Submitted For Project -  ' . $pro_cby->project_name . ' Stage ' . $req->stage . '- Activity ' . $req->sub,
                        'token' => [$fid_token]
                    ];
                    log::info('data-attd', $data);


                    $res = $this->notify_create($data);
                }

                $materials = json_decode($req->material, true); // decode JSON to array

                if (!empty($materials) && is_array($materials)) {

                    foreach ($materials as $mat) {

                        $ac_mat = new Activity_material();

                        $ac_mat->act_id = $ac_work->id ?? 1;
                        $ac_mat->category = $mat['category'] ?? null;
                        $ac_mat->unit = $mat['unit'] ?? null;
                        $ac_mat->qty = $mat['quantity'] ?? 0;
                        $ac_mat->c_by = auth()->user()->id ?? 1;

                        $success = $ac_mat->save();
                    }
                }

                if ($ac_work) {
                    return response()->json(['success' => 'sucess', 'message' => 'Work Added Successfully'], 200);
                } else {
                    return response()->json(['success' => 'sucess', 'message' => 'Work Failed to Add'], 200);
                }
            } catch (Exception $e) {
                Log::error('Exception caught: ' . $e->getMessage());
            }
        }
        // return response()->json(['success' => 'sucess'], 200);
    }


    // public material insert

    public function add_mat(Request $req)
    {


        $ac_mat = new Activity_material();

        $date = Carbon::now()->toDateString();

        $work = Activity_work::where('pro_id', $req->pro_id)
            ->where('stage', $req->stage)
            ->where('sub', $req->sub)
            ->whereDate('created_at', $date)
            ->first();

        // dd($work);

        try {
            $ac_mat->act_id = $work->id;
            $ac_mat->category = $req->category;
            $ac_mat->unit = $req->unit;
            $ac_mat->qty = $req->qty;
            $ac_mat->c_by = auth()->user()->id ?? 1;

            $success = $ac_mat->save();
        } catch (Exception $e) {
            Log::error('Exception caught: ' . $e->getMessage());
        }

        if ($success) {
            return response()->json(['status' => 'success', 'message' => 'Data saved successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to save data.']);
        }
    }


    // add file for activity

    public function add_file(Request $req)
    {


        $fileData = [];

        if ($req->hasFile('file')) {
            foreach ($req->file('file') as $file) {

                $filename = str_replace(' ', '_', $file->getClientOriginalName());

                $fileData[] = 'img/activity_work/' . time() . '_' . $filename;
            }
        }

        $date = Carbon::now()->toDateString();

        $mergedFiles = [];

        $work = Activity_work::where('pro_id', $req->pro_id)
            ->where('stage', $req->stage)
            ->where('sub', $req->sub)
            ->whereDate('created_at', $date)
            ->first();

        if (!$work) {
            // Create new record
            $ac_work = new Activity_work();

            try {
                $ac_work->file = $fileData;
            } catch (Exception $e) {
                Log::error('Exception caught: ' . $e->getMessage());
            }
        } else {

            $ac_work = $work;

            $old_file = $ac_work->file;
            // dd($old_file);

            $mergedFiles = array_merge($old_file, $fileData); // combine old and new

            $ac_work->file = $mergedFiles;
        }

        $success = $ac_work->save();

        if ($success) {
            return response()->json(['status' => 'success', 'message' => 'Files saved successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to save data.']);
        }
    }


    // public material insert

    public function act_update_qc(Request $req)
    {


        $date = Carbon::now()->toDateString();

        // $date = '2025-06-11';

        $progress_import = Activity::where('pro_id', $req->pro_id)
            ->where('stage', $req->stage)
            ->where('sub', $req->sub)
            ->first();

        $work = Activity_work::where('pro_id', $req->pro_id)
            ->where('stage', $req->stage)
            ->where('sub', $req->sub)
            ->whereDate('created_at', $date)
            ->first();


        // dd($work);
        $qc_check = QC::with(['checklists:id,qc_id,question'])->where('id', $progress_import->qc)->first();

        $arr =  ($qc_check->checklists);

        $qc_arr = [];

        foreach ($arr as $checklist) {
            $qc_arr[] = $checklist->id; // Access properties of each checklist
        }

        $qc_per = (count($req->qc_check) / count($qc_arr)) * 100;


        if (!$work) {

            $ac_work = new Activity_work();
            $ac_work->pro_id = $req->pro_id;
            $ac_work->stage = $req->stage;
            $ac_work->sub = $req->sub;
            $ac_work->qc = $req->qc_check;
            $ac_work->qc_per = round($qc_per);
            $ac_work->cat = 'work';
            $ac_work->c_by = auth()->user()->id ?? 1;
        } else {

            $ac_work = $work;
            $ac_work->qc = $req->qc_check;
            $ac_work->qc_per = round($qc_per);
        }

        $success = $ac_work->save();
        // dd($req->qc_check);
        if ($success) {
            return response()->json(['status' => 'success', 'message' => 'Qc saved successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'QC Failed to save data.']);
        }
    }


    // acr qc show 

    public function act_qc_show(Request $req)
    {


        $work = Activity::where('pro_id', $req->pro_id)
            ->where('stage', $req->stage)
            ->where('sub', $req->sub)
            ->first();

        // dd($work);

        $qc_check = QC::with(['checklists:id,qc_id,question'])->where('id', $work->qc)->first();

        $arr =  ($qc_check->checklists);

        // foreach ($arr as $checklist) {
        //     echo $checklist->id . " - " . $checklist->question . "<br>"; // Access properties of each checklist
        // }

        // dd($qc_check);
        // $qc_check = QC::with(['checklists'])->where('id', $work->qc)->get();

        if ($qc_check) {
            return response()->json(['status' => 'success', 'data' => $arr]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No data Available For QC']);
        }
    }
}
