<?php

namespace App\Models;

use App\Observers\PollObserver;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Poll\PollRelations;
use App\Traits\Poll\PollScopes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([PollObserver::class])]
class Poll extends Model
{
    /** @use HasFactory<\Database\Factories\PollFactory> */
    use HasFactory, HasUlids, SoftDeletes, PollRelations, PollScopes;

    protected $fillable = [
        'user_id',
        'question',
    ];
}
