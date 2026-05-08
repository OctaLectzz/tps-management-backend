<?php

namespace App\Models;

use Database\Factories\VoteResultFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteResult extends Model
{
    /** @use HasFactory<VoteResultFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'polling_station_id',
        'party_votes',
        'total_votes',
        'dpt',
        'voters_present',
        'submitted_by',
        'submitted_at',
        'verified',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'submitted_at' => 'datetime',
            'party_votes' => 'integer',
            'total_votes' => 'integer',
            'dpt' => 'integer',
            'voters_present' => 'integer',
        ];
    }

    /**
     * Get the polling station this result belongs to.
     */
    public function pollingStation(): BelongsTo
    {
        return $this->belongsTo(PollingStation::class);
    }

    /**
     * Get the user who submitted this result.
     */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
