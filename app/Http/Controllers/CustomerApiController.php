<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;


class CustomerApiController extends Controller
{
    
    /**
      Create Customer 
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function create(Request $request)
    {
        $return = $request->all();

        $return['status'] = 0;

        //@todo : on intenet rewrite to requests
        //need a specific requests [ gender, first name, last name, country, email ]
        if (
            isset($return['gender']) && // 0 = man  1 = women
            isset($return['first_name']) && //string validator
            isset($return['last_name']) && //string validator
            isset($return['country']) &&   //string validator + country list check
            isset($return['email']) //email validator
        ) {
            if (Customer::where('email' , $return['email'])->count() == 0) {
                $customer = new Customer();
                $customer->gender = $return['gender'];
                $customer->first_name = $return['first_name'];      
                $customer->last_name = $return['last_name'];   
                $customer->country = $return['country'];   
                $customer->email = $return['email'];  
                $customer->prepareBonusLevel(); 

                if ($customer->save()){
                    $return['customer_id'] = $customer->id;
                    $return['status'] = 1;
                }else{
                    $return['error'] = 'Customer save error';
                }
  
            }else{
                $return['error'] = 'Customer with email exists';
            }
        }else{
            $return['error'] = 'Basic Validation Error';
        }

        return json_encode($return);
    }

    /**
      Edit Customer 
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function edit(Request $request){
        $return = $request->all();

        $customerRequest = false;
        $return['status'] = 0;   


        if (isset($return['id']) && intval($return['id']) > 0){
            $customerRequest = Customer::where('id' , intval($return['id']));
        }else if (isset($return['email']) && strlen($return['email']) > 0){
            $customerRequest = Customer::where('email' , $return['email']);
        } 

        if ($customerRequest && $customerRequest->count() > 0){
            $customer = $customerRequest->first();

            foreach ($customer->getAllColumnsNames() as $key => $columnName) {
                //critical - missing secure field and overall validation @todo @fix
                if (isset($return[$columnName])){
                    $customer->$columnName = $return[$columnName];
                }
            }

            if ($customer->save()){
                $return['status'] = 1;                  
            }else{
                $return['error'] = 'Customer save error';   
            }
        }else{
            $return['error'] = 'Missing Customer';        
        }

        return json_encode($return);      
    }

}
