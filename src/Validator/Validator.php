<?php

namespace YaFou\CleanArchitectureBundle\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;
use YaFou\CleanArchitectureUtilities\Validator\AssertionInterface;
use YaFou\CleanArchitectureUtilities\Validator\ErrorList;
use YaFou\CleanArchitectureUtilities\Validator\ValidatorInterface;

class Validator implements ValidatorInterface
{
    /**
     * @var SymfonyValidatorInterface
     */
    private $validator;
    /**
     * @var Assertion[]
     */
    private $assertions = [];

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function that($value, string $propertyPath = null): AssertionInterface
    {
        return $this->assertions[] = new Assertion($this->validator, $value, $propertyPath);
    }

    public function getErrors(): ErrorList
    {
        $errors = array_map(function (Assertion $assertion) {
            return $assertion->getErrors();
        }, $this->assertions);

        return new ErrorList(array_merge(...$errors));
    }
}
