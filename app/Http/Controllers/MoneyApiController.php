<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transactions;
use App\Models\Customer;

class MoneyApiController extends Controller
{
    
    /**
      Balance check
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function balance(Request $request)
    {
        $return = $request->all();

        $return['status'] = 0;
        //get customer for full list

        if (isset($return['customer_id'])){
          $customerRequest = Customer::where('id' , intval($return['customer_id']));

          if ($customerRequest->count() > 0){
            //will not do double check - just request all transactions
            $return['balance'] = Transactions::where('customer_id' , intval($return['customer_id']))->sum('amouth');

          }else{
            $return['error'] = 'Customer not found';
          }
        }else{
            $return['error'] = 'Customer not requested';
        }

        return json_encode($return);
    }

    /**
      Deposit money
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function deposit(Request $request)
    {
        $return = $request->all();

        //@todo - @fix smells like a dublicated code !!!
        if (isset($return['customer_id'])){
          $customerRequest = Customer::where('id' , intval($return['customer_id']));

          if ($customerRequest->count() > 0){
            if (isset($return['amouth']) && intval($return['amouth']) > 0){

              //create depozit payment
              $deposit = new Transactions();
              $deposit->customer_id = intval($return['customer_id']);
              $deposit->type = Transactions::DEPOSIT;
              $deposit->amouth = intval($return['amouth']);

              $deposit->description = 'deposit';

              if ($deposit->save()){

                $return['deposited'] = $deposit->amouth;

                $return['status'] = 1;

                $depositCount = Transactions::where('customer_id' , intval($return['customer_id']))->where('type' , Transactions::DEPOSIT)->count();

                //Every 3rd deposit of the customer should be awarded with bonus on the deposit amount according to his bonus parameter

                //really [smelly] workaround , but everyt 3rd must divide with 3 :P
                if ($depositCount > 0 && ($depositCount / Transactions::BONUS_STEP) == intval($depositCount / Transactions::BONUS_STEP)){
                  //now we need a customer
                  $customer = $customerRequest->first();
                  $bonusDeposit = new Transactions();
                  $bonusDeposit->customer_id = intval($return['customer_id']);
                  $bonusDeposit->type = Transactions::BONUS_DEPOSIT;
                  $bonusDeposit->amouth = intval(($return['amouth'] / 100) * $customer->bonus);

                  $bonusDeposit->description = 'bonus deposit';

                  if ($bonusDeposit->save()){
                    $return['bonus_deposited'] = $bonusDeposit->amouth;
                  }
                }

              }

            }else{
              $return['error'] = 'Deposit needs to be possitive';
            }
          }else{
            $return['error'] = 'Customer not found';
          }
        }else{
            $return['error'] = 'Customer not requested';
        }

        return json_encode($return);
    }

    /**
      Widraw money
      @param  Illuminate\Http\Request http request 
      @return string json response
    **/
    public function widraw(Request $request)
    {
        $return = $request->all();

        //@todo - @fix smells like a dublicated code !!!
        if (isset($return['customer_id'])){
          $customerRequest = Customer::where('id' , intval($return['customer_id']));

          if ($customerRequest->count() > 0){
            //will not do double check - just request all transactions
            $return['full_balance'] = Transactions::where('customer_id' , intval($return['customer_id']))->whereIn('type', [Transactions::DEPOSIT, Transactions::WIDRAW])->sum('amouth');

            if (intval($return['full_balance']) > 0 && isset($return['amouth']) && intval($return['amouth']) > 0){

              $widraw = new Transactions();
              $widraw->customer_id = intval($return['customer_id']);
              $widraw->type = Transactions::WIDRAW;
              $widraw->amouth = (0 - intval($return['amouth']));

              $widraw->description = 'widraw';

              if ($widraw->save()){
                $return['widrawed'] = $return['amouth'];
              }

            }else{
              $return['error'] = 'Widraw summ must be possitive';
            }

          }else{
            $return['error'] = 'Customer not found';
          }
        }else{
            $return['error'] = 'Customer not requested';
        }


        return json_encode($return);
    }
}
