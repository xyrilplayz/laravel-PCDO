<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramChecklists extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    function programs()
    {
        return $this->belongsToMany(Program::class, 'coop_program_checklists', 'checklist_id', 'program_id');
    }
}
