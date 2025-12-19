<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Progress_act;
use App\Models\Activity_work;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;
use App\Models\QCChecklist;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate; // Alias to avoid conflict with Carbon Date
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use SebastianBergmann\Diff\Diff;

class Progress_import extends Controller
{

    // Install it if not already via Laravel Excel:


    public function progress_import(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls',
        // ]);



        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true); // Get all rows as an array, preserving empty cells

        // Skip header row
        unset($rows[1]); // Assuming header is in row 1

        // dd($rows);

        $currentStage = null;
        $lastStageId = null;



        foreach ($rows as $rowNumber => $rowData) {
            // Map columns by their Excel column letters (A, B, C, D, E)
            $slNo = strtolower(trim($rowData['A'] ?? ''));
            $activityName = trim($rowData['B'] ?? '');
            $durationDays = trim($rowData['C'] ?? '');
            $startDateCell = $rowData['D'] ?? null;
            $endDateCell = $rowData['E'] ?? null;



            if ($slNo === 'stages') {
                $currentStage = $activityName;
                $lastStageId = DB::table('progress_stage')->insertGetId([
                    'pro_id' => $request->pro_id,
                    'stage' => $currentStage,
                    'duration' => $durationDays,
                    'st_date' => $this->formatDate($startDateCell),
                    'end_date' => $this->formatDate($endDateCell),
                    'sc_start' => $this->formatDate($startDateCell),
                    'sc_end' => $this->formatDate($endDateCell),
                    'c_by' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ($slNo === 'activities') {
                if ($lastStageId) {
                    try {
                        // log::info($lastStageId);
                        DB::table('progress_activity')->insert([
                            'pro_id' => $request->pro_id,
                            'stage' => $lastStageId,
                            'activity' => $activityName,
                            'qc' => 0,
                            'status' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'c_by' => auth()->user()->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error("PhpSpreadsheet import error (row {$rowNumber}): " . $e->getMessage() . " Data: " . json_encode($rowData));
                    }
                } else {
                    Log::warning("No stage found for activity (row {$rowNumber}): " . json_encode($rowData));
                }
            }
        }

        Project::where('id', $request->pro_id)->update(['progress' => 'excel']);


        // return view('projects.progress_stages', ['status' => 'success', 'message' => 'XLSX data imported successfully via PhpSpreadsheet!', 'progress' => $progress]);

        return redirect()->route('project.stages', ['pro_id' => $request->pro_id])->with('success', 'XLSX data imported successfully!!');
    }

    protected function formatDate($value)
    {
        if (!$value) return null;

        // Try to parse Excel numeric dates
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // Try to parse with expected string formats
        try {
            return \Carbon\Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning("Date parse failed for value: {$value}");
            return null;
        }
    }

    //progress update teh qc

    public function progress_update(Request $req)
    {

        $qcData = $req->input('qc_sync');

        // Get the first progress_id to fetch pro_id for redirect
        $firstProgressId = array_key_first($qcData);
        $pro_id = DB::table('progress_activity')->where('id', $firstProgressId)->value('pro_id');

        foreach ($qcData as $progress_id => $qcStatus) {
            // You can store this in a column like 'qc_status' or a related table
            $up =  DB::table('progress_activity')->where('id', $progress_id)->update([
                'qc' => $qcStatus,
            ]);
        }

        return redirect()->route('project.show', ['id' => $pro_id])->with(['status' => 'Success', 'active_tab' => 'progress']);
    }

    public function progress_single(Request $req)
    {

        $ins =  DB::table('progress_stage')->insert([
            'pro_id' => $req->pro_id,
            'stage' => $req->stage,
            // 'sub' => $req->activity,
            'duration' => $req->duration,
            'st_date' => $req->startdate,
            'end_date' => $req->enddate,
            'sc_start' => $req->startdate,
            'sc_end' => $req->enddate,
            // 'qc' => $req->qcsync,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
            'c_by' => auth()->user()->id
        ]);

        if ($req->header('Authorization')) {

            return response()->json(['status' => 'success'], 200);
        } else {

            if ($ins) {
                return back()->with('success', 'Activity saved successfully!')->with('active_tab', 'progress');
            } else {
                return back()->with('failed', 'Activity Failed to Add')->with('active_tab', 'progress');
            }
        }
    }

    public function progress_single_activity(Request $req)
    {

        $ins =  DB::table('progress_activity')->insert([
            'pro_id' => $req->pro_id,
            'stage' => $req->stage,
            'activity' => $req->activity,
            'qc' => $req->qcsync,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'c_by' => auth()->user()->id
        ]);

        if ($req->header('Authorization')) {

            return response()->json(['status' => 'success'], 200);
        } else {

            if ($ins) {
                return back()->with('success', 'Activity saved successfully!')->with('active_tab', 'progress');
            } else {
                return back()->with('failed', 'Activity Failed to Add')->with('active_tab', 'progress');
            }
        }
    }


    // progress stage function for mobile

    public function progress_stage_list(Request $req)
    {

        $pro_progress_stage = DB::table('progress_stage')->where('pro_id', $req->project_id)->orderBy('id')->select('id', 'stage')->get();

        $project_qc = DB::table('qc')->where('status', 'active')->select('id', 'title')->get();

        return response()->json(['status' => 'Success', 'project_stage' => $pro_progress_stage, 'project_qc' => $project_qc], 200);
    }

    public function progress_act_list(Request $req)
    {

        $pro_act = DB::table('progress_activity')->where('pro_id', $req->project_id)->where('stage', $req->stage)->where('status', 1)->orderBy('id')->get()->map(function ($lt) {

            $lt->stage_name = DB::table('progress_stage')->where('id', $lt->stage)->value('stage');

            return $lt;
        });

        return response()->json(['status' => 'Success', 'data' => $pro_act], 200);
    }



    public function tabs_progress(Request $req)
    {
        // dd($req->all());

        // $cap_stage = strtoupper($req->key);

        // dd($req->pro_id);
        // $req->key;((
        // $stage_id =  DB::table('progress_import')->where('pro_id', $req->pro_id)->where('stage', $req->key)->select('id', 'stage')->get();

        //  $stage_list =  DB::table('progress_import')->where('pro_id', $req->pro_id)->where('stage', $req->key)->get();

        // $sub_data = Activity_work::where('pro_id', $req->pro_id)->whereIn('import_id', $stage_id)->orderBy('created_at', 'DESC')->get()->map(function ($item) {

        //     $end = Activity::where('id', $item->import_id)->pluck('sc_end')->first();

        //     $item->end_plan = Carbon::parse($end)->format('d-m-Y');

        //     return $item;
        // });


        // Step 1: Get progress_import stages
        // $stageMap = DB::table('progress_import')
        //     ->where('pro_id', $req->pro_id)
        //     ->where('stage', $req->key)
        //     ->pluck('id', 'stage')->toArray();



        // // Step 2: Get sub_data and map stage & end date
        // $sub_data = Activity_work::where('pro_id', $req->pro_id)
        //     ->whereIn('import_id', $stageMap->values())
        //     ->orderBy('created_at', 'DESC')
        //     ->get()
        //     ->map(function ($item) use ($stageMap) {
        //         $item->stage = $stageMap[$item->import_id] ?? 'Unknown';

        //         $end = Activity::where('id', $item->import_id)->value('sc_end');
        //         $item->end_plan = $end ? Carbon::parse($end)->format('d-m-Y') : null;

        //         return $item;
        //     });

        // $grouped_by_stage = $sub_data->groupBy('stage');

        // DB::enableQueryLog();

        $activity = Progress_act::with('act_work')
            ->where('pro_id', $req->pro_id)
            ->where('stage', $req->key)
            ->get()
            ->map(function ($item) {

                // Loop through each act_work and attach labour + creator info
                $item->act_work->each(function ($work) use ($item) {

                    $attendanceDate = Carbon::parse($work->created_at)->toDateString();
                    // Attach labour
                    $work->labour = Attendance::where('pro_id', $item->pro_id)
                        ->whereDate('created_at', $attendanceDate)
                        ->select(DB::raw('SUM(skilled + mc + fc) as total_labour'))
                        ->value('total_labour') ?? 0;

                    // Attach creator info for this specific act_work
                    $creator = Employee::where('id', $work->c_by)->select('name', 'image_path')->first();
                    $work->cr_name = $creator->name ?? null;
                    $work->cr_image = $creator->image_path ?? null;


                    // Log::info('Checking attendance for date:', ['date' => $work->created_at, 'pro_id' => $item->pro_id, 'work' => $work->labour]);
                    // Log::info(DB::getQueryLog());
                });


                return $item;
            });

        $stage_name =  DB::table('progress_stage')->where('Id', $req->key)->value('stage');


        // dd($activity->toArray());

        return view('projects.ovw_preliminary', ['key' => $req->key, 'stages' => $activity, 'stage_name' => $stage_name]);
    }


    public function work_activity_export(Request $req)
    {
        // Fetch data (you can filter by pro_id or other fields if needed)

        // $req->pro_id = 5;

        $start = Carbon::parse($req->start)->startOfDay(); // e.g., 2025-07-02 00:00:00
        $end = Carbon::parse($req->end)->endOfDay();       // e.g., 2025-07-02 23:59:59

        $work_data = Activity_work::where('activity_work.pro_id', $req->pro_id)
            ->whereDate('activity_work.created_at', '>=', $start)
            ->whereDate('activity_work.created_at', '<=', $end)
            ->leftJoin('employee as emp', 'emp.id', '=', 'activity_work.c_by')
            ->select('activity_work.*', 'emp.name as employee_name') // select fields
            ->orderBy('activity_work.stage')
            ->orderBy('activity_work.sub')
            ->get()->map(function ($item) {

                $act = DB::table('progress_activity')->where('id', $item->import_id)->first();

                // log::info($item->import_id);

                if ($act) {
                    $item->qctitle = optional(DB::table('qc')->where('id', $act->qc)->first())->title ?? 'no title';
                    $item->stage = DB::table('progress_stage')->where('id', $act->stage)->value('stage');
                    $item->activity = $act->activity;
                } else {
                    $item->qctitle = 'no title';
                    $item->stage = 'no stage';
                    $item->activity = 'no activity';
                }



                return $item;
            });

        $pro_name = DB::table('projects')->where('id', $req->pro_id)->first();


        // dd($work_data);
        // // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $sheet->setCellValue('A1', 'Project');
        $sheet->setCellValue('B1', 'Start Date');
        $sheet->setCellValue('C1',  'End Date');

        $sheet->setCellValue('A2', $pro_name->project_name);
        $sheet->setCellValue('B2', date("d-m-Y", strtotime($req->start)));
        $sheet->setCellValue('C2',  date("d-m-Y", strtotime($req->end)));

        // Set header
        $sheet->setCellValue('A4', 'Stage');
        $sheet->setCellValue('B4', 'Activity Name');
        $sheet->setCellValue('C4', 'Category');
        $sheet->setCellValue('D4', 'Qc');
        $sheet->setCellValue('E4', 'Progress');
        $sheet->setCellValue('F4', 'Next Day Plan');
        $sheet->setCellValue('G4', 'Remarks');
        $sheet->setCellValue('H4', 'Status');
        $sheet->setCellValue('I4', 'created_at');
        $sheet->setCellValue('J4', 'created_by');

        // // Fill rows
        $row = 5;
        foreach ($work_data as $data) {
            $sheet->setCellValue('A' . $row, $data->stage);
            $sheet->setCellValue('B' . $row, $data->activity);
            $sheet->setCellValue('C' . $row, $data->cat);
            $sheet->setCellValue('D' . $row, $data->qctitle);
            $sheet->setCellValue('E' . $row, $data->progress);
            $sheet->setCellValue('F' . $row, $data->next_day);
            $sheet->setCellValue('G' . $row, $data->remarks);
            $sheet->setCellValue('H' . $row, $data->status);
            $sheet->setCellValue('I' . $row, date("d-m-Y", strtotime($data->created_at)));
            $sheet->setCellValue('J' . $row, $data->employee_name);
            $row++;
        }

        // // Generate file
        $writer = new Xlsx($spreadsheet);

        $fileName = 'progress_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        // ✅ Save temporarily to memory
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        // ✅ Upload to S3
        $s3Path = 'excel/' . $fileName;
        Storage::disk('s3')->put($s3Path, file_get_contents($tempFile), 'public');

        // ✅ Remove local temp file
        unlink($tempFile);

        // ✅ Get public S3 URL
        $fileUrl = Storage::disk('s3')->url($s3Path);

        if ($req->hasHeader('Authorization')) {
            return response()->json([
                'status' => 'success',
                'file_url' => $fileUrl
            ]);
        } else {
            // Force browser download directly from S3
            return redirect($fileUrl);
        }
        // if (!File::exists($tempDir)) {
        //     File::makeDirectory($tempDir, 0777, true);
        // }

        // Create a temp file in that directory
        // $tempFile = tempnam($tempDir, 'progress_');
        // $finalPath = $tempFile . '.xlsx';
        // rename($tempFile, $finalPath);


        // $writer->save($tempFile);

        // $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        // $tempFile = tempnam(asset('img/excel'), $fileName);
        // $writer->save($tempFile);

        // return response()->download($finalPath, $fileName);
        //  ->deleteFileAfterSend(true);

        // return response()->json($tempDir, 200);
    }



    // check teh date 


    public function stage_start_date(Request $req)
    {

        // dd($req->all());
        $act =  Activity::where('pro_id', $req->pro_id)->where('stage', $req->stage)->orderBy('id', 'DESC')->first(); // returns a single Activity model or null;
        $nextDate = Carbon::parse($act->end_date)->addDay()->toDateString();

        // $enddate = Carbon::parse($nextDate)->addDay()->toDateString();

        return response()->json(['start_date' => $nextDate, 'end_date' => $nextDate], 200);
    }
}
