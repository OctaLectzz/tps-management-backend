<?php

namespace App\Models;

use App\Enums\OfficerRole;
use App\Enums\OfficerStatus;
use Database\Factories\OfficerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Officer extends Model
{
    /** @use HasFactory<OfficerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'role',
        'district_id',
        'status',
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
            'role' => OfficerRole::class,
            'status' => OfficerStatus::class,
        ];
    }

    /**
     * Get the district this officer is assigned to.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the assignments for this officer.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
