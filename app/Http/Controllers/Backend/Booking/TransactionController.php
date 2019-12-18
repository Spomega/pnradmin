<?php

namespace App\Http\Controllers\Backend\Booking;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Auth\TransactionRepository;
use App\Repositories\Backend\Auth\CompanyRepository;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    /**
     * @param TransactionRepository transactionRepository
     *
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    //

    function  index(Request $request){

        return view('backend.auth.transaction.index')
            ->withTransactions($this->transactionRepository
                ->orderBy('id','asc')
                ->paginate());
    }

    function  adminIndex(Request $request,CompanyRepository $companyRepository){
        $company_data = $companyRepository->get(['id','name']);

        $companies = array();

        foreach ($company_data as $data)
        {

            $companies[$data->id] = $data->name;
        }



        return view('backend.auth.transaction.adminindex')
            ->withTransactions($this->transactionRepository
                ->orderBy('id','asc')
                ->paginate())
            ->withCompanies($companies);

    }

    function filterByDate(Request $request){
        $date = $request->input('daterange');
        list($startdate,$enddate) = explode("-",$date);

        $startdate = date_format(date_create($startdate),'Y-m-d');
        $enddate = date_format(date_create($enddate),'Y-m-d');

        //$this->transactionRepository->getTransactionByDate($startdate,$enddate);

        return view('backend.auth.transaction.index')
            ->withTransactions($this->transactionRepository->getTransactionByDate($startdate,$enddate));

    }

    function filterAdminByDateCompany(Request $request){
        $date = $request->input('daterange');
        $company = $request->input('company');

        dd(User::where('company',$company));
    }
}
