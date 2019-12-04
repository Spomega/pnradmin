<?php
/**
 * Created by PhpStorm.
 * User: spomega
 * Date: 12/4/19
 * Time: 3:30 AM
 */

namespace App\Http\Requests\Backend\Auth\Company;


use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
            'name' => 'required|unique:roles|max:191',
            'contact' => 'required|max:191',
        ];
    }

}
