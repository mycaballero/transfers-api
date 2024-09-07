<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'picking_id',
        'text',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function picking()
    {
        return $this->belongsTo(Picking::class);
    }
}
