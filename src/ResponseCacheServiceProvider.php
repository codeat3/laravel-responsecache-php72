<?php

namespace Codeat3\ResponseCache;

use Illuminate\Cache\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Codeat3\ResponseCache\Hasher\RequestHasher;
use Codeat3\ResponseCache\Commands\ClearCommand;
use Codeat3\ResponseCache\Serializers\Serializer;
use Codeat3\ResponseCache\CacheProfiles\CacheProfile;

class ResponseCacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/responsecache.php' => config_path('responsecache.php'),
        ], 'config');

        $this->app->bind(CacheProfile::class, function (Container $app) {
            return $app->make(config('responsecache.cache_profile'));
        });

        $this->app->bind(RequestHasher::class, function (Container $app) {
            return $app->make(config('responsecache.hasher'));
        });

        $this->app->bind(Serializer::class, function (Container $app) {
            return $app->make(config('responsecache.serializer'));
        });

        $this->app->when(ResponseCacheRepository::class)
            ->needs(Repository::class)
            ->give(function (): Repository {
                $repository = $this->app['cache']->store(config('responsecache.cache_store'));
                if (! empty(config('responsecache.cache_tag'))) {
                    return $repository->tags(config('responsecache.cache_tag'));
                }

                return $repository;
            });

        $this->app->singleton('responsecache', ResponseCache::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/responsecache.php', 'responsecache');
    }
}
