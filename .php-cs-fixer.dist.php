<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('node_modules')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,

        // overwrite @Symfony
        'blank_line_after_opening_tag' => false, // to have declare strict types direct below
        'concat_space' => false,
        'linebreak_after_opening_tag' => false,
        'php_unit_method_casing' => false,
        'single_line_throw' => false,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
        // we don't want spaces in the phpdoc
        'phpdoc_align' => ['align' => 'left'],

        // custom additions
        'declare_strict_types' => true,

        // we want to allow type annotation using @var to help phpstan
        'phpdoc_to_comment' => ['ignored_tags' => ['var']],
    ])
    ->setFinder($finder)
;
