<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBatch extends Model
{
    protected $table = 'job_batches';

    protected $casts = [
        'cancelled_at' => 'datetime',
        'finished_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
