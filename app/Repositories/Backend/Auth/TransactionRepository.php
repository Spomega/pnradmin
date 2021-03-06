<?php
/**
 * Created by PhpStorm.
 * User: spomega
 * Date: 12/8/19
 * Time: 6:46 PM
 */

namespace App\Repositories\Backend\Auth;
use App\Models\Auth\Transaction;

use App\Repositories\BaseRepository;

class TransactionRepository extends  BaseRepository
{

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        // TODO: Implement model() method.
        return Transaction::class;
    }


    public  function getTransactionByDate($startDate,$endDate){

        return Transaction::whereBetween('date_paid',[$startDate,$endDate])->get();
    }

    public function getTransactionByCompanyDate($company,$startDate,$endDate){
        return Transaction::where('company_id',$company)
            ->whereBetween('date_paid',[$startDate,$endDate])
            ->get();
    }

    public function getTransactionByCompany($company){

        return Transaction::where('company_id',$company)->get();
    }


}
