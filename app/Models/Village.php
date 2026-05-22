<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\VillageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Village Model.
 */
class Village extends Model
{
    use HasFactory, VillageTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'villages';

    protected $fillable = [
        'id',
        'district_id',
        'name',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'district_id',
    ];

    /**
     * Village belongs to District.
     *
     * @return BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Village has many polling stations.
     *
     * @return HasMany
     */
    public function pollingStations()
    {
        return $this->hasMany(PollingStation::class);
    }
}
