<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transaction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['confirmation_number','date_paid','total_cost',
                             'route','comment','passenger_name','phone_number','user_id','base_currency'];

}
