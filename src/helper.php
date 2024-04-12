<?php

use \Illuminate\Validation\Validator;
use \Illuminate\Contracts\Container\BindingResolutionException;

if (! function_exists('makeValidator'))
{
    /**
     * @param string $abstract
     * @param array $data
     * @param array $parameters
     * @return Validator
     * @throws BindingResolutionException
     */
    function makeValidator(string $abstract, array $data = [], array $parameters = []): \Illuminate\Validation\Validator
    {
        return app()->make($abstract, $parameters)->validateFails($data ?? request()->all());
    }
}
