<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\TestServiceProvider;
use App\Providers\TypeScriptTransformerServiceProvider;
use SocialiteProviders\Manager\ServiceProvider;

return [
    AppServiceProvider::class,
    TestServiceProvider::class,
    TypeScriptTransformerServiceProvider::class,
    ServiceProvider::class,
];
