<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['name', 'description'];

    public function coopPrograms()
    {
        return $this->hasMany(CoopProgram::class, 'program_id');
    }

    public function checklists()
    {
        return $this->belongsToMany(ProgramChecklists::class, 'coop_program_checklists', 'program_id', 'checklist_id');
    }
}
