<?php

namespace Palmo\validators;

interface Validator
{
    public function isValid($value): bool;
}