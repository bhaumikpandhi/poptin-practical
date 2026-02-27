<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Poll\PollRelations;
use App\Traits\Poll\PollScopes;

class Poll extends Model
{
    /** @use HasFactory<\Database\Factories\PollFactory> */
    use HasFactory, HasUlids, SoftDeletes, PollRelations, PollScopes;

    protected $fillable = [
        'user_id',
        'question',
    ];
}
