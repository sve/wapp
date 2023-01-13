<?php

namespace App\Models\Traits;

use App\Models\UserLocation;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasLocations
{
    /**
     * @return HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(UserLocation::class);
    }
}
