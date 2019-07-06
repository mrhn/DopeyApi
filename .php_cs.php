<?php

$excludeDirs = [
    'bootstrap/',
    'config/',
    'public/',
    'resources/',
    'storage/',
    'vendor/',
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude($excludeDirs)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,
            '@PSR1' => true,
            '@PSR2' => true,
            'align_multiline_comment' => true,
            'blank_line_before_return' => true,
            'php_unit_test_annotation' => false,
            'php_unit_method_casing' => ['case' => 'snake_case'],
            // for larastan
            'return_assignment' => false,
        ]
    )
    ->setFinder($finder)
    ->setUsingCache(false);
