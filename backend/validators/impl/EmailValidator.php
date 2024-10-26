<?php

namespace Palmo\validators\impl;

use Palmo\validators\Validator;

class EmailValidator implements Validator
{

    public function isValid($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}