<?php
/**
 * Created by PhpStorm.
 * User: spomega
 * Date: 12/3/19
 * Time: 7:17 PM
 */

namespace App\Repositories\Backend\Auth;
use App\Models\Auth\Company;

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
}
