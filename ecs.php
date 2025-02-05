<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\ValueObject\Option;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
    ])
    ->withRules([
        ArraySyntaxFixer::class,
    ])
    ->withSpacing(indentation: Option::INDENTATION_SPACES, lineEnding: PHP_EOL)
    ->withPhpCsFixerSets(perCS20: true, doctrineAnnotation: true)
    ->withPreparedSets(psr12: true);