<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships


    public function users()
    {
        return $this->belongsToMany('App\User')->using('App\RoleUser');
    }

}
