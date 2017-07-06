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
    const SECONDS_IN_MINUTE = 60;
    const HOURS_IN_DAY = 24;

    /**
     * Add meeting
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMeeting(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
          'name'   => 'required|string|min:3|max:15',
          'from_h' => 'required|integer|min:0|max:23',
          'from_m' => 'required|integer|min:0|max:59',
          'to_h'   => 'required|integer|min:1|max:24',
          'to_m'   => 'required|integer|min:0|max:59',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->messages(),
            ]);
        }
        
        $meeting = MeetingLog::create([
            'name' => $data['name'],
            'from' => $data['from_h'] + $data['from_m'] / static::SECONDS_IN_MINUTE,
            'to'   => $data['to_h'] + $data['to_m'] / static::SECONDS_IN_MINUTE,
        ]);
        
        return response()->json(['meeting' => [
          'name' => $meeting->name,
          'from' => $this->getReadableTime($meeting->from),
          'to'   => $this->getReadableTime($meeting->to),
        ]]);
    }
    
    /**
     * Get empty day rate.
     */
    public function getEmptyRate()
    {
        $meetings = MeetingLog::all();
        $rangePairsCollection = [];
        
        foreach ($meetings as $meeting) {
            $rangePairsCollection[] = [$meeting->from, $meeting->to];
        }

        // Sort by start time asc.
        usort($rangePairsCollection, function($a, $b)
        {
            $from1 = $a[0];
            $from2 = $b[0];

            if ($from1 == $from2) {
                return 0;
            }
            return ($from1 < $from2) ? -1 : 1;
        });


        // Determine unnecessary pairs (pairs which intersects) with previous ones.
        $clearOverlappingPairs = function() use (&$rangePairsCollection, &$clearOverlappingPairs) {
            $isMetOverlap = false;

            foreach ($rangePairsCollection as $k => $pair)
            {
                if (!isset($rangePairsCollection[$k + 1])) break;

                // We put 'to' param from next pair into 'to' param of the current pair and add next pair key to remove array as unnecessary.
                if ($pair[1] > $rangePairsCollection[$k + 1][0]) {
                    if ($rangePairsCollection[$k][1] < $rangePairsCollection[$k + 1][1]) {
                        $rangePairsCollection[$k][1] = $rangePairsCollection[$k + 1][1];
                    }

                    unset($rangePairsCollection[$k + 1]);
                    $isMetOverlap = true;

                    // Reindex keys.
                    $rangePairsCollection = array_values($rangePairsCollection);
                }
            }

            if ($isMetOverlap) {
                $clearOverlappingPairs();
            }
        };

        $clearOverlappingPairs();

        // Calculate empty date rate.
        $busyHours = 0;

        foreach ($rangePairsCollection as $i => $pair) {
            $busyHours += $pair[1] - $pair[0];
        }

        $emptyRate = ((static::HOURS_IN_DAY - $busyHours) / static::HOURS_IN_DAY) * 100;

        return response()->json(['rate' => round($emptyRate, 2) . '%']);
    }
    
    /**
     * Get meetings.
     */
    public function getMeetings()
    {
        $meetings = MeetingLog::all(['name', 'from', 'to'])->toArray();

        // Sort by start time asc.
        usort($meetings, function($a, $b) {
            $from1 = $a['from'];
            $from2 = $b['from'];

            if ($from1 == $from2) {
                return 0;
            }
            return ($from1 < $from2) ? -1 : 1;
        });

        // User readable from and to format.
        foreach ($meetings as $k => $meeting) {
            $meetings[$k]['from'] = $this->getReadableTime($meeting['from']);
            $meetings[$k]['to'] = $this->getReadableTime($meeting['to']);
        }

        return response()->json(['meetings' => $meetings]);
    }

    /**
     * Get readable time.
     *
     * @param $value
     *
     * @return string
     */
    private function getReadableTime($value)
    {
        $minutes = ($value - intval($value)) * static::SECONDS_IN_MINUTE;

        return sprintf('%s:%s', intval($value), $minutes != 0 ? $minutes : '00');
    }
}
