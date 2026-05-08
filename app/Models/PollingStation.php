<?php

namespace App\Models;

use App\Enums\PollingStationStatus;
use Database\Factories\PollingStationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PollingStation extends Model
{
    /** @use HasFactory<PollingStationFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'village_id',
        'district_id',
        'station_number',
        'venue_name',
        'address',
        'latitude',
        'longitude',
        'status',
        'notes',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PollingStationStatus::class,
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'station_number' => 'integer',
        ];
    }

    /**
     * Get the village this polling station belongs to.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Get the district this polling station belongs to.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the assignments for this polling station.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the vote result for this polling station.
     */
    public function voteResult(): HasOne
    {
        return $this->hasOne(VoteResult::class);
    }

    /**
     * Scope a query to only include active polling stations.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PollingStationStatus::Active);
    }

    /**
     * Scope a query to filter polling stations by district.
     */
    public function scopeByDistrict(Builder $query, int $districtId): Builder
    {
        return $query->where('district_id', $districtId);
    }

    /**
     * Scope a query to only include polling stations without any officer assigned.
     */
    public function scopeWithoutOfficer(Builder $query): Builder
    {
        return $query->whereDoesntHave('assignments');
    }
}
