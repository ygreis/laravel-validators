<?php

namespace Ygreis\LaravelValidators;

use Illuminate\Validation\Validator;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Container\Container;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Ygreis\LaravelValidators\Exceptions\ValidatorException;
use Ygreis\LaravelValidators\Interfaces\AbstractValidatorInterface;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
abstract class AbstractValidator implements AbstractValidatorInterface
{

    use ValidatesWhenResolvedTrait;

    /**
     * The container instance.
     *
     * @var Container
     */
    public $container;

    /**
     * Data that will be validated
     *
     * @var array
     */
    protected $data = [];

    private $rules = [];

    private $attributes = [];

    private $messages = [];

    /**
     * Validator constructor.
     *
     * @param Container $container
     * @param bool $throwWithException
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Get the validators rules that apply to the request.
     *
     * @return array
     */
    public function validators(): array
    {
        return [];
    }

    /**
     * @param array $data
     * @return Validator
     * @throws ValidationException
     */
    public function validate(array $data): Validator
    {
        $this->data = $data;
        $instance = $this->getValidatorInstance();
        if (! $instance->passes()) {
            $this->failedValidation($instance);
        }
        return $instance;
    }

    /**
     * @param array $data
     * @param bool $failsWithException
     * @return Validator
     * @throws ValidatorException
     */
    public function validateFails(array $data, bool $failsWithException = false): Validator
    {
        try {
            return $this->validate($data);
        } catch (ValidationException $exception) {
            if ($failsWithException) {
                throw new ValidatorException($exception, $exception->validator->getMessageBag()->first());
            }
        } catch (\Exception $exception){

            if ($failsWithException) {
                throw new ValidatorException($exception->getMessage(), $exception->getMessage(), 501);
            }
        }

        return $exception->validator;
    }

    /**
     * Get the validator instance for the request.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getValidatorInstance(): ValidatorInterface
    {
        $factory = $this->container->make(ValidationFactory::class);
        $validator = $this->createDefaultValidator($factory);

        if (method_exists($this, 'withValidator')) {
            $this->withValidator($validator);
        }

        return $validator;
    }

    /**
     * Create the default validator instance.
     *
     */
    private function createDefaultValidator(ValidationFactory $factory): ValidatorInterface
    {

        $this->prepareValidator();

        return $factory->make(
            $this->data,
            $this->rules,
            $this->messages,
            $this->attributes
        );
    }

    private function prepareValidator(): void
    {
        foreach($this->validators() as $validator) {
            /**
             * @var self $makeValidator
             */
            $makeValidator = app()->make($validator);

            $this->rules = array_merge($this->rules, $makeValidator->rules());
            $this->messages = array_merge($this->messages, $makeValidator->messages());
            $this->attributes = array_merge($this->attributes, $makeValidator->attributes());
        }

        $this->rules = array_merge($this->rules, $this->container->call([$this, 'rules']));
        $this->messages = array_merge($this->messages, $this->messages());
        $this->attributes = array_merge($this->attributes, $this->attributes());
    }

}
