<?php

namespace Ygreis\Validator;

use Illuminate\Foundation\Http\FormRequest;

class AbstractRequest extends FormRequest
{

    /**
     * Get the validators rules that apply to the request.
     *
     * @return array
     */
    public function validators()
    {
        return [];
    }

    public function validateResolved()
    {

        foreach($this->validators() as $validator){
            app()->make($validator)->validate($this->all());
        }

        parent::validateResolved();

    }

}
