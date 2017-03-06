<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\BaseModel;

use Illuminate\Http\Request;

class Transactions extends BaseModel {

    const BONUS_STEP = 3;

    const DEPOSIT = 1; 
    const BONUS_DEPOSIT = 2; 
    const WIDRAW = 3; 

    protected $table = 'transactions';

}