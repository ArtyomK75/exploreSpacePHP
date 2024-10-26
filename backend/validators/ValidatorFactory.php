<?php

namespace Palmo\validators;

use Palmo\validators\impl\EmailValidator;
use Palmo\validators\impl\PasswordValidator;
use Palmo\validators\impl\UserNameValidator;

class ValidatorFactory
{
    private TypeValidator $caseOfTypeValidator;

    public function __construct(TypeValidator $case)
    {
        $this->caseOfTypeValidator = $case;
    }

    public function getValidator() {
        return match($this->caseOfTypeValidator){
            TypeValidator::Email => new EmailValidator(),
            TypeValidator::UserName => new UserNameValidator(),
            TypeValidator::Password => new PasswordValidator()
        };
    }
}