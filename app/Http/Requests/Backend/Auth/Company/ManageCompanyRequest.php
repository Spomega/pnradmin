<?php
/**
 * Created by PhpStorm.
 * User: spomega
 * Date: 12/4/19
 * Time: 4:10 AM
 */

namespace App\Http\Requests\Backend\Auth\Company;


class ManageCompanyRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //

        ];
    }
}
