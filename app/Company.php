<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    
    /**
     * Fillable fields
     * @var [type]
     */
    protected $fillable = [
        'name',
        'vatcode',
        'address',
        'postcode',
        'city',
        'country',
        'www',
        'email',
        'phone',
        'lat',
        'lng',
        'industry',
        'information_last_updated',
        'date_founded',
    ];

    /**
    * The attributes excluded from the model's JSON form.
    *
    * @var array
    */
    //protected $hidden = ['active'];

}
