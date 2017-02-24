<?php

$finder = PhpCsFixer\Finder::create()->in([__DIR__.'/app', __DIR__.'/tests']);

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules([
        '@PSR2' => true,
        'phpdoc_align' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
