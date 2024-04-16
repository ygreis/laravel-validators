<?php

namespace Ygreis\LaravelValidators\Exceptions;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class ValidatorException extends \Exception {

    /**
     * @rvar mixed
     */
    protected $errors = null;

    public function __construct( $errors = null, $message = null, $code = 422, \Exception $previous = null ) {
        parent::__construct( $message, $code, $previous );

        $this->setErrors( $errors );
    }

    protected function setErrors( mixed $errors ): void {

        if ($errors instanceof ValidationException) {
            $errors = $errors->validator;
        }

        if ( is_array( $errors ) ) {
            $errors = new MessageBag( $errors );
        }

        $this->errors = $errors ?? $this->message;
    }

    /**
     * @return string|Validator|MessageBag
     */
    public function getErrors(): mixed {
        return $this->errors;
    }

}
