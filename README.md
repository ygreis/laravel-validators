# Laravel Validators
Custom validations for the laravel framework

## 📝 Summary

- [Getting Started](#getting-started)
  - [Installation](#installation)
- [How to use?](#how-to-use)
  - [Basic usage](#basic-usage)
  - [Function makeValidator](#function-makevalidator)
  - [Function makeValidatorThrow](#function-makevalidatorthrow)
  - [With Requests](#with-requests)
- [Example project](#example-project)

### ✨ <a name="getting-started">Getting Started</a>


### <a name="installation">Installation</a>

> **Requires:**
- **[PHP 7.3+](https://php.net/releases/)**
- **[Laravel 8.0+](https://github.com/laravel/laravel)**

**1**: First, you may use [Composer](https://getcomposer.org) to install Laravel Validators as a dependency into your Laravel project:

```bash
composer require ygreis/laravel-validators
```

### <a name="how-to-use">How to use?</a>

#### <a name="basic-usage">Basic usage</a>

The package provides two simple functions that can be used, one where errors are returned `makeValidator` and the other, if an error occurs, a custom exception is thrown that also returns errors `makeValidatorThrow`.

---

<details>

  <summary>Validator file example (click here)</summary>

> Create a file in a folder, example: `\App\Validators\TestValidator`

```php
<?php

namespace App\Validators;

use Ygreis\LaravelValidators\AbstractValidator;

class TestValidator extends AbstractValidator
{
    
    public function messages(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'age' => 'nullable|integer|min:18|max:120',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'age' => 'Age',
        ];
    }

    public function validators(): array
    {
        // You can import other validators Here
        return [
            // TestTwoValidator::class
        ];
    }

}

```

</details>

---

After creating a validation file, see how to use it in the examples below

#### <a name="function-makevalidator">Function makeValidator</a>

Example of use:

```php

public function TestValidatorException(Request $request)
{
    // Imagine that your Validator is in the path \App\Validators\TestValidator
    $validate = makeValidator(\App\Validators\TestValidator::class, $request->all());
    
    // Check has errors 
    if ($validate->errors()->count()) {
        dd('Get all errors', $validate->errors()->all());   
    }
}

```

#### <a name="function-makevalidatorthrow">Function makeValidatorThrow</a>

Example of use:

```php

public function TestValidatorException(Request $request)
{
    try {

        makeValidatorThrow(\App\Validators\TestValidator::class, $request->all());

        dd('All right!');

    // Exception from the library where it will be triggered when the Validator fails
    } catch (ValidatorException $exception) {
    
        // $exception->getMessage() Returns the first validation message that did not pass.
        // $exception->getErrors()->messages()->all() Returns all validation messages that did not pass.
        dd($exception->getMessage(), $exception->getErrors()->messages()->all());
    }
}

```

#### <a name="with-requests">With Requests</a>

You can use Laravel's Requests to validate the data and you can use Validators too.

---

<details>

  <summary>Request file example (click here)</summary>

> Create a file in a folder, example: `\App\Http\Requests\TestRequest`

```php
<?php

namespace App\Http\Requests;

use App\Validators\TestValidator;
use Ygreis\LaravelValidators\AbstractRequest;

class TestRequest extends AbstractRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user.age' => 'nullable|integer|min:18|max:120',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'user.age' => 'User Age',
        ];
    }

    public function validators()
    {
        return [TestValidator::class];
    }

}


```

</details>

---

---

<details>

  <summary>Usage Request in Controller</summary>

> Create a controller in a folder, example: `\App\Http\Controllers`

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;

class TestController extends Controller {

    public function TestValidatorException(TestRequest $request)
    {
        dd('All Right');
    }

}

```

</details>

---

> When the data is invalid, by default it will redirect to the previous page showing the errors that occurred, to catch the errors in the view you can do something like this:

<details>

  <summary>Get request errors in the view</summary>

```php

@if($errors->any())
    {!! implode('', $errors->all('<div>:message</div>')) !!}
@endif

```

</details>

---


#### <a name="example-project">Example project</a>

> We provide a package so you can test all the examples mentioned here, just access the link below :)

[Click Here](https://github.com/ygreis/test-validators-laravel)
