<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function cooperatives()
    {
        return $this->hasMany(Cooperative::class);
    }
}
