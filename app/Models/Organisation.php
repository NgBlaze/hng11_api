<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The users that belong to the organisation.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organisation_user', 'organisation_id', 'user_id');
    }
}
