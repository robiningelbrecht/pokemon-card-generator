<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('docker')
    ->exclude('bin');

return (new PhpCsFixer\Config)
    ->setRules([
        '@Symfony' => true
    ])
    ->setFinder($finder);
