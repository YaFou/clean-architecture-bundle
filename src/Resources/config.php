<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use YaFou\CleanArchitectureBundle\AbstractEncoder;
use YaFou\CleanArchitectureBundle\Validator\Validator;
use YaFou\CleanArchitectureUtilities\EncoderInterface;
use YaFou\CleanArchitectureUtilities\Validator\ValidatorInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('clean_architecture.encoder', AbstractEncoder::class)
            ->abstract()

        ->alias(EncoderInterface::class, 'clean_architecture.encoder')

        ->set('clean_architecture.validator', Validator::class)

        ->alias(ValidatorInterface::class, 'clean_architecture.validator');
};
