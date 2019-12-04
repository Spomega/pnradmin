<?php

namespace App\Http\Controllers\Backend\Auth\Company;
use App\Repositories\Backend\Auth\CompanyRepository;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * @var PermissionRepository
     *
     */
    protected  $companyRepository;

    /**
     * @param PermissionRepository permissionRepository
     *
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function  index(){

        return view('backend.auth.company.index')
            ->withCompanies($this->companyRepository
                ->orderBy('id','asc')
                ->paginate('25'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return view('backend.auth.company.create');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        //
        $this->companyRepository->create($request->only('name','contact','email','iata'));
        return redirect()->route('admin.auth.company.index')->withFlashSuccess(__('alerts.backend.company.created'));
    }



}
