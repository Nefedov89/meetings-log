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
}
