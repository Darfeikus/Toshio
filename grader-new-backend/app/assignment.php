<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class assignment extends Model
{
    protected $primaryKey = 'assignment_id';
    protected $fillable = ['crn','name','start_date','end_date','tries','language','runtime'];
}
