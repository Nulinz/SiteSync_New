<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SnagComment;
use App\Models\Notify;
use App\Models\EntrySnag;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Traits\common;
class SnagCommentController extends Controller
{
    use common;
    
    /**
     * Store comment for snag (API)
     */
public function store(Request $request)
{
    $request->validate([
        'snag_id' => 'required|exists:entry_snag,id',
        'comment' => 'required|string|max:1000',
    ]);

    DB::beginTransaction();

    try {
        // 🔹 Save the comment
        $comment = SnagComment::create([
            'snag_id' => $request->snag_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        $comment->makeHidden(['created_at', 'updated_at']);

        $snag = EntrySnag::findOrFail($request->snag_id);

        // 🔹 Prepare notify list (assigned + created_by)
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

            $bodyText = (auth()->user()->name ?? 'Unknown') .
            ' commented - "' . $request->comment . '"';


            // ✅ Store in notifications table
            Notify::create([
                'to_id'    => $noti,
                'f_id'     => $snag->id,
                'type'     => 'snag_comment',
                'title'    => 'Snag Comment Added',
                'body'     => $bodyText,
                'c_by'     => auth()->id(),
                'seen'     => 0,
                'reminder' => 0,
            ]);

            // 🔹 Prepare FCM data
            $data = [
                'to_id' => $noti,
                'f_id'  => $snag->id,
                'type'  => 'snag_comment',
                'title' => 'Snag Comment Added',
                'body'  => $bodyText,
                'token' => [$tokens[$noti] ?? null],
            ];

            // 🔹 Send FCM notification
            $res = $this->notify_create($data);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Comment & notification added successfully!',
            'data'    => $comment->load('user:id,name'),
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Comment store failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing your request',
        ], 500);
    }
}


    /**
     * Get all comments for a snag
     */
    public function index($snag_id)
    {
        $comments = SnagComment::with('user:id,name')
            ->where('snag_id', $snag_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->created_date = \Carbon\Carbon::parse($item->created_at)->format('d-m-Y');
                $item->created_time = \Carbon\Carbon::parse($item->created_at)->format('H:i:s');
                return $item->makeHidden(['created_at', 'updated_at']);
            });

        return response()->json([
            'success'  => true,
            'comments' => $comments
        ]);
    }
}