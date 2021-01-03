<?php

namespace YaFou\CleanArchitectureBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use YaFou\CleanArchitectureUtilities\Validator\AssertionInterface;
use YaFou\CleanArchitectureUtilities\Validator\Error;

class Assertion implements AssertionInterface
{
    /**
     * @var Error[]
     */
    private $errors;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    private $value;
    /**
     * @var string|null
     */
    private $propertyPath;

    public function __construct(ValidatorInterface $validator, $value, ?string $propertyPath = null)
    {
        $this->validator = $validator;
        $this->value = $value;
        $this->propertyPath = $propertyPath;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function null(): AssertionInterface
    {
        $this->assert(new IsNull());

        return $this;
    }

    private function assert(Constraint $constraint): void
    {
        $violations = $this->validator->validate($this->value, [$constraint]);
        $this->errors = array_merge($this->errors, $this->convertViolationsToErrors($violations));
    }

    private function convertViolationsToErrors(ConstraintViolationListInterface $violations): array
    {
        return array_map(
            function (ConstraintViolationInterface $violation) {
                return new Error($this->value, $violation->getMessage(), [], $this->propertyPath);
            },
            iterator_to_array($violations)
        );
    }

    public function notNull(): AssertionInterface
    {
        $this->assert(new NotNull());

        return $this;
    }

    public function empty(): AssertionInterface
    {
        $this->assert(new Blank());

        return $this;
    }

    public function notEmpty(): AssertionInterface
    {
        $this->assert(new NotBlank());

        return $this;
    }

    public function boolean(): AssertionInterface
    {
        $this->assertType('bool');

        return $this;
    }

    public function integer(): AssertionInterface
    {
        $this->assertType('int');

        return $this;
    }

    public function float(): AssertionInterface
    {
        $this->assertType('float');

        return $this;
    }

    public function string(): AssertionInterface
    {
        $this->assertType('string');

        return $this;
    }

    public function digit(): AssertionInterface
    {
        $this->assertType('digit');

        return $this;
    }

    public function array(): AssertionInterface
    {
        $this->assertType('array');

        return $this;
    }

    public function min(int $min): AssertionInterface
    {
        $this->assert(new Range(['min' => $min]));

        return $this;
    }

    public function max(int $max): AssertionInterface
    {
        $this->assert(new Range(['max' => $max]));

        return $this;
    }

    public function range(int $min = null, int $max = null): AssertionInterface
    {
        $this->assert(new Range(['min' => $min, 'max' => $max]));

        return $this;
    }

    public function length(int $length): AssertionInterface
    {
        $this->assert(new Length($length));

        return $this;
    }

    public function minLength(int $minLength): AssertionInterface
    {
        $this->assert(new Length(null, $minLength));

        return $this;
    }

    public function maxLength(int $maxLength): AssertionInterface
    {
        $this->assert(new Length(null, null, $maxLength));

        return $this;
    }

    public function lengthRange(int $minLength = null, int $maxLength = null): AssertionInterface
    {
        $this->assert(new Length(null, $minLength, $maxLength));

        return $this;
    }

    public function email(): AssertionInterface
    {
        $this->assert(new Email());

        return $this;
    }

    private function assertType(string $type): void
    {
        $this->assert(new Type($type));
    }
}
