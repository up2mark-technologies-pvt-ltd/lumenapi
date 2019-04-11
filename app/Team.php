<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

    protected $fillable = ["title"];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships


    public function users()
    {
        return $this->belongsToMany('App\User');
    }

}
