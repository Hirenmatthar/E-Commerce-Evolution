<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ContactServiceProvider extends Model{
    protected $fillable = ['username','email','password'];
}
