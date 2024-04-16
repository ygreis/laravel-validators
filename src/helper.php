<?php

use \Illuminate\Validation\Validator;
use \Illuminate\Contracts\Container\BindingResolutionException;
use \Ygreis\LaravelValidators\AbstractValidator;
use \Ygreis\LaravelValidators\Exceptions\ValidatorException;

if (! function_exists('makeValidator'))
{
    /**
     * @param string $abstract
     * @param array $data
     * @param array $parameters
     * @param bool $failsWithException
     * @return Validator
     * @throws BindingResolutionException
     * @throws ValidatorException
     */
    function makeValidator(string $abstract, array $data = [], array $parameters = [], bool $failsWithException = false): \Illuminate\Validation\Validator
    {
        /**
         * @var AbstractValidator $validatorInstance
         */
        $validatorInstance = app()->make($abstract, $parameters);

        return $validatorInstance->validateFails($data ?? request()->all(), $failsWithException);
    }
}

if (! function_exists('makeValidatorThrow'))
{
    /**
     * @param string $abstract
     * @param array $data
     * @param array $parameters
     * @return Validator
     * @throws BindingResolutionException
     * @throws ValidatorException
     */
    function makeValidatorThrow(string $abstract, array $data = [], array $parameters = []): \Illuminate\Validation\Validator
    {
        /**
         * @var AbstractValidator $validatorInstance
         */
        $validatorInstance = makeValidator($abstract, $data, $parameters, true);

        return $validatorInstance->validateFails($data ?? request()->all());
    }
}
