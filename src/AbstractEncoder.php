<?php

namespace YaFou\CleanArchitectureBundle;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use YaFou\CleanArchitectureUtilities\EncoderInterface;

abstract class AbstractEncoder implements EncoderInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    abstract protected function getUser(string $password = null): UserInterface;

    public function encode(string $plainPassword): string
    {
        return $this->encoder->encodePassword($this->getUser(), $plainPassword);
    }

    public function isValid(string $password, string $plainPassword): bool
    {
        return $this->encoder->isPasswordValid($this->getUser($password), $plainPassword);
    }
}
