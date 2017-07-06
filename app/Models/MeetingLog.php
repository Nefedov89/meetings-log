<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MeetingLog
 * @package App
 */
class MeetingLog extends Model
{
    /** @var string  */
    protected $table = 'meeting_logs';
    
    protected $fillable = [
      'name',
      'from',
      'to'
    ];

    /**
     * From attr accessor.
     *
     * @param $value
     *
     * @return float
     */
    public function getFromAttribute($value)
    {
        return floatval($value);
    }

    /**
     * To attr accessor.
     *
     * @param $value
     *
     * @return float
     */
    public function getToAttribute($value)
    {
        return floatval($value);
    }
}
