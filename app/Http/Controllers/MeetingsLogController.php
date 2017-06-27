<?php

namespace App\Http\Controllers;

use App\Models\MeetingLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

/**
 * Class MeetingsLogController
 * @package App\Http\Controllers
 */
class MeetingsLogController extends BaseController
{
    /**
     * Add meeting
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMeeting(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|min:3|max:15',
            'from' => 'required|numeric|min:0|max:23',
            'to' => 'required|numeric|min:1|max:24',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ]);
        }

        MeetingLog::create($data);

        return response()->json([]);
    }

    /**
     * Get empty day rate.
     */
    public function getEmptyRate()
    {
        $meetings = MeetingLog::all();
        $dayHours = range(0, 24);

        foreach ($meetings as $meeting) {
            $from = intval($meeting->from);
            $to = intval($meeting->to);

            for ($i = $from; $i <= $to; $i++) {
                if (in_array($i, $dayHours) && ($key = array_search($i, $dayHours)) !== false) {
                    unset($dayHours[$key]);
                }
            }
        }

        return response()->json(['rate' => (count($dayHours) / 25) * 100 . '%']);
    }
}
