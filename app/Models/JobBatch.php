<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBatch extends Model
{
    protected $table = 'job_batches';

    // primary key is uuid
    protected $keyType = 'string';

    public $incrementing = false;

    protected $casts = [
        'cancelled_at' => 'datetime',
        'finished_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
