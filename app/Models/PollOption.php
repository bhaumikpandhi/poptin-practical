<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\PollOption\PollOptionRelations;

class PollOption extends Model
{
    /** @use HasFactory<\Database\Factories\PollOptionFactory> */
    use HasFactory, SoftDeletes, HasUlids, PollOptionRelations;

    protected $fillable = [
        'poll_id',
        'option',
    ];
}
