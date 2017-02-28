<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoneyApiController extends Controller
{
    
    /**
      Balance check
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function balance(Request)
    {
        $return = $request->all();

        return json_encode($return);
    }

    /**
      Deposit money
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function deposit(Request)
    {
        $return = $request->all();

        return json_encode($return);
    }

    /**
      Widraw money
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function widraw(Request)
    {
        $return = $request->all();

        return json_encode($return);
    }
}
