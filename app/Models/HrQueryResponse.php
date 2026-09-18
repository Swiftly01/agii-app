<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrQueryResponse extends Model
{
    protected $fillable = [
        'hr_query_id',
        'user_id',
        'response'
    ];

    public function query()
    {
        return $this->belongsTo(HrQuery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
