<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'state',
        'lga',
        'ward',
        'latitude',
        'longitude',
    ];

    /**
     * A location can have many users.
     * users.local_government → locations.lga
     */
    public function users()
    {
        return $this->hasMany(User::class, 'local_government', 'lga');
    }
}
