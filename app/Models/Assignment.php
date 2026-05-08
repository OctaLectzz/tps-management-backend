<?php

namespace App\Models;

use App\Enums\ConfirmationStatus;
use App\Enums\OfficerRole;
use Database\Factories\AssignmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    /** @use HasFactory<AssignmentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'polling_station_id',
        'officer_id',
        'role',
        'confirmation_status',
        'notes',
        'assigned_at',
        'confirmed_at',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'confirmation_status' => 'pending',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => OfficerRole::class,
            'confirmation_status' => ConfirmationStatus::class,
            'assigned_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    /**
     * Get the polling station for this assignment.
     */
    public function pollingStation(): BelongsTo
    {
        return $this->belongsTo(PollingStation::class);
    }

    /**
     * Get the officer for this assignment.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(Officer::class);
    }
}
