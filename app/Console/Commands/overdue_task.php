<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Task;
use App\Models\Employee;
use App\Models\EntrySurvey;
use App\Models\EntryQC;
use App\Models\EntrySnag;
use App\Models\Notify;
use Carbon\Carbon;
use App\Traits\common;

class overdue_task extends Command
{
    use common;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:overdue_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Your logic here
        $currentdate = now();

        // Fetch tasks that are due today (or change this logic as needed)
        $tasks = Task::whereDate('end_timestamp', $currentdate)
            ->get()->map(function ($item) {
                $item->type = 'task'; // Add tag to differentiate
                return $item;
            });



        $survey = EntrySurvey::with(['survey:id,title', 'project:id,project_name,project_id'])
            ->whereDate('due_date', $currentdate)->get()->map(function ($item) {
                $item->type = 'survey'; // Add tag to differentiate
                return $item;
            });



        $qc = EntryQC::with(['qc:id,title', 'project:id,project_name,project_id'])->whereDate('due_date', $currentdate)
            ->get()->map(function ($item) {
                $item->type = 'qc'; // Add tag to differentiate
                return $item;
            });



        $snag = EntrySnag::with(['snag:id,category', 'project:id,project_name,project_id'])->whereDate('due_date', $currentdate)
            ->get()->map(function ($item) {
                $item->type = 'snag'; // Add tag to differentiate
                return $item;
            });



        $items = collect($tasks)->merge($survey)->merge($qc)->merge($snag);



        // Log::info('Tasks due today: ', $items->toArray());

        foreach ($items as $task) {

            // Log::info('Tasks due today: ', $task->type);

            $exists = Notify::where('f_id', $task->id)
                ->where('type', $task->type)
                ->where('reminder', true)
                ->exists();

            if ($exists) {
                // Skip if notification already sent
                continue;
            }

            // Get FCM token for assigned employee
            $fid_token = Employee::where('id', $task->assigned_to)->value('token');

            if (!$fid_token) {
                Log::warning("No token found for employee ID: {$task->assigned_to}");
                continue;
            }

            // Determine title based on type
            $title = match ($task->type) {
                'task' => $task->title ?? 'Untitled Task',
                'survey' => $task->survey->title ?? 'Untitled Survey',
                'qc' => $task->qc->title ?? 'Untitled QC',
                'snag' => $task->snag->category ?? 'Untitled Snag',
                default => 'Untitled',
            };
            log::info($title);


            $data = [
                'to_id' => $task->assigned_to,
                'f_id'  => $task->id,
                'type'  => $task->type,
                'title' => 'Task Not Completed',
                'body'  => $title  . ' in ' . ($task->project->project_name ?? 'Unknown Project'),
                'token' => [$fid_token],
                'reminder' => true
            ];

            $res = $this->notify_create($data);
        }

        return 0;
    }
}
