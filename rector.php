<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Rector\Assign\CallOnAppArrayAccessToStandaloneAssignRector;
use RectorLaravel\Rector\Class_\AddExtendsAnnotationToModelFactoriesRector;
use RectorLaravel\Rector\ClassMethod\AddGenericReturnTypeToRelationsRector;
use RectorLaravel\Rector\Expr\AppEnvironmentComparisonToParameterRector;
use RectorLaravel\Rector\FuncCall\ArgumentFuncCallToMethodCallRector;
use RectorLaravel\Rector\If_\AbortIfRector;
use RectorLaravel\Rector\MethodCall\AssertStatusToAssertMethodRector;
use RectorLaravel\Rector\StaticCall\DispatchToHelperFunctionsRector;
use RectorLaravel\Set\LaravelSetProvider;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap/app.php',
        __DIR__.'/bootstrap/providers.php',
        __DIR__.'/config',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withPhpSets()
    ->withPreparedSets(
        codeQuality: true,
        deadCode: true,
        earlyReturn: true,
        typeDeclarations: true,
        privatization: true,
    )
    ->withSetProviders(LaravelSetProvider::class)
    ->withComposerBased(laravel: true/** other options */)
    ->withRules([
        AbortIfRector::class,
        AddExtendsAnnotationToModelFactoriesRector::class,
        AddGenericReturnTypeToRelationsRector::class,
        AppEnvironmentComparisonToParameterRector::class,
        ArgumentFuncCallToMethodCallRector::class,
        CallOnAppArrayAccessToStandaloneAssignRector::class,
        DispatchToHelperFunctionsRector::class,
        AssertStatusToAssertMethodRector::class,
    ]);
