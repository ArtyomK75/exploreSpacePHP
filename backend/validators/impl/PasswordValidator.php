<?php

namespace Palmo\validators\impl;

use Palmo\validators\Validator;

class PasswordValidator implements Validator
{

    public function isValid($value): bool
    {
        return strlen($value) >= 6;
    }
}