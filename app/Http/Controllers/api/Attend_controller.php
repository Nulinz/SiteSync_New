<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\common;

class Attend_controller extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function attend_insert(Request $req)
    {

        Log::info("Attend", $req->all());

        // $pro_attend = new Attendance();

        foreach ($req->attendance as $att) {
            try {
                $pro_attend = new Attendance();

                $pro_attend->pro_id   = $req->pro_id ?? 0; // or from $att if it's nested
                $pro_attend->category = $att['category'] ?? null;
                $pro_attend->skilled  = $att['skilled'] ?? 0.0;
                $pro_attend->mc       = $att['mc'] ?? 0.0;
                $pro_attend->fc       = $att['fc'] ?? 0.0;
                $pro_attend->c_by     = auth()->user()->id ?? 1;
                $pro_attend->save();

                if ($pro_attend->save()) {
                    $last_attendance = $pro_attend; // ✅ Store the saved object
                }
            } catch (Exception $e) {
                Log::error('Attendance Insert Error: ' . $e->getMessage());
            }
        }

        // log::info('attd', $last_attendance->toArray());

        if ($last_attendance) {

            $pro_cby = Project::where('id', $req->pro_id)->select('id', 'project_name', 'c_by')->first();

            $fid_token = Employee::where('id', $pro_cby->c_by)->value('token');

            $data = [
                'to_id' => $pro_cby->c_by,
                'f_id' => $last_attendance->id,
                'type' => 'Attendance',
                'title' => 'Attendance Submitted',
                'body' =>  'Attendance Submitted For Project -  ' . $pro_cby->project_name . '- Date ' . date('d-m-Y'),
                'token' => [$fid_token]
            ];
            // log::info('data-attd', $data);


            $res = $this->notify_create($data);
        }

        if ($pro_attend) {
            return response()->json(['status' => 'success', 'message' => 'Attendance Data saved successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to save Attendacne data.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function attend_show(Request $req)
    {
        $attd = Attendance::where('pro_id', $req->pro_id)->selectRaw('DATE(created_at) as date')
            ->selectRaw('SUM(skilled) as total_skilled')
            ->selectRaw('SUM(mc) as total_mc')
            ->selectRaw('SUM(fc) as total_fc')
            ->selectRaw('SUM(skilled + mc + fc) as total_all')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        if ($attd) {
            return response()->json(['status' => 'success', 'data' => $attd]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No data in Attendance']);
        }
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
}
