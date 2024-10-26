<?php

namespace Palmo\validators\impl;

use Palmo\validators\Validator;

class UserNameValidator implements Validator
{

    public function isValid($value): bool
    {
        return preg_match("/^[A-Za-z0-9_.]+$/", $value);
    }
}