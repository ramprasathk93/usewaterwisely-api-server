<?php
/**
 * Created by PhpStorm.
 * User: iRam
 * Date: 26/3/17
 * Time: 7:39 PM
 */

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\Validator;

class InvalidRequestException extends AppException
{
    public function __construct(Validator $validator)
    {
        $this->message = implode(', ', $validator->errors()->all());
        $this->code = 400;
    }
}