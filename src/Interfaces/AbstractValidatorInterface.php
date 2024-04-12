<?php

namespace Ygreis\Validator\Interfaces;

use Illuminate\Validation\Validator;
interface AbstractValidatorInterface
{
    public function messages(): array;
    public function attributes(): array;
    public function rules(): array;
    public function validators(): array;
    public function validate(array $data): Validator;
    public function validateFails(array $data): Validator;

}
