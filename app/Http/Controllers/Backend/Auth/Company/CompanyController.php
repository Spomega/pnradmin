<?php

namespace App\Http\Controllers\Backend\Auth\Company;
use App\Repositories\Backend\Auth\CompanyRepository;
use App\Http\Requests\Backend\Auth\Company\StoreCompanyRequest;
use App\Http\Requests\Backend\Auth\Company\ManageCompanyRequest;
use App\Http\Controllers\Controller;
use App\Models\Auth\Company;

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
        $this->companyRepository->create($request->only('name','contact_person','email','company_code'));
        return redirect()->route('admin.auth.company.index')->withFlashSuccess(__('alerts.backend.company.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param   ManageCompanyRequest  $request
     * @param   Company $company
     * @return  Company $company
     */
    public function edit(ManageCompanyRequest $request,Company $company)
    {
        return view('backend.auth.company.edit')
            ->withCompany($company);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ManageCompanyRequest $request
     * @param  Company $company
     * @return mixed
     */
    public function update(StoreCompanyRequest $request, Company $company)
    {
        //
        $this->companyRepository->update($company, $request->only('name','contact_person','email','company_code'));
        return redirect()->route('admin.auth.company.index')->withFlashSuccess(__('alerts.backend.company.updated'));

    }



}
