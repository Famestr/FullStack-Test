<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class members extends Model
{
    public $timestamps = false;

    protected $table = 'members';

    protected $fillable = ['id', 'firstname', 'surname', 'email', 'gender', 'joined_date'];
}
