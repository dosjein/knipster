<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\BaseModel;

use Illuminate\Http\Request;

class Customer extends BaseModel {

    protected $table = 'customers';

    public function prepareBonusLevel()
    {
        $this->bonus = rand(5 , 20);
    }

}