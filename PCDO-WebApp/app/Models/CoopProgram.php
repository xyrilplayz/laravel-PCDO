<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoopProgram extends Model
{
    /** @use HasFactory<\Database\Factories\CoopProgramFactory> */
    use HasFactory;

    protected $fillable = [
        'coop_id',
        'program_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'coop_id' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function checklist()
    {
        return $this->hasMany(CoopProgramChecklist::class);
    }
}