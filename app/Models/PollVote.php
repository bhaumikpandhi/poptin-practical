<?php

namespace App\Models;

use App\Traits\PollVote\PollVoteRelations;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    /** @use HasFactory<\Database\Factories\PollVoteFactory> */
    use HasFactory, HasUlids, PollVoteRelations;

    protected $fillable = [
        'poll_id',
        'poll_option_id',
        'user_id',
        'ip_address',
    ];
}
