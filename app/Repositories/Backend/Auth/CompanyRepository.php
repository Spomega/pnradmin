<?php
/**
 * Created by PhpStorm.
 * User: spomega
 * Date: 12/3/19
 * Time: 7:17 PM
 */

namespace App\Repositories\Backend\Auth;
use App\Models\Auth\Company;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

class CompanyRepository extends BaseRepository
{

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Company::class;
    }


    /**
     * @param Permission  $permission
     * @param array $data
     *
     * @return mixed
     * @throws GeneralException
     */
    public function update(Company  $company, array $data)
    {


        // If the name is changing make sure it doesn't already exist
        if ($company->name !== $data['name']) {
            if ($this->companyExists($data['name'])) {
                throw new GeneralException('A Company already exists with the name '.$data['name']);
            }
        }


        return DB::transaction(function () use ($company, $data) {
            if ($company->update([
                'name' => $data['name'],
                'contact_person' => $data['contact_person'],
                'email' =>$data['email'],
                'company_code' => $data['company_code']
            ])) {

                // event(new RoleUpdated($permission));

                return $company;
            }

            throw new GeneralException(trans('exceptions.backend.access.permissions.update_error'));
        });
    }


    /**
     * @param $name
     *
     * @return bool
     */
    protected function companyExists($name) : bool
    {
        return $this->model
                ->where('name', $name)
                ->count() > 0;
    }
}
