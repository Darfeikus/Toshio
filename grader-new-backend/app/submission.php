<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class submission extends Model
{
    protected $primaryKey = 'submission_id';
    protected $fillable = ['assignment_id','grade','user_id'];
}
