<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__.'/examples', __DIR__.'/lib', __DIR__.'/test'])
    // include *.php files in the root directory
    ->withRootFiles()

    // add a single rule
    ->withRules([NoUnusedImportsFixer::class])

    // add sets - group of rules, from easiest to more complex ones
    // uncomment one, apply one, commit, PR, merge and repeat
    ->withPreparedSets(
        arrays: true,
        spaces: true,
        namespaces: true,
        docblocks: true,
        controlStructures: true,
        comments: true,
        casing: true,
        cleanup: true,
        psr12: true,
        laravel: true,
    );
