<?php

namespace Codeat3\ResponseCache\Hasher;

use Illuminate\Http\Request;
use Codeat3\ResponseCache\CacheProfiles\CacheProfile;

class DefaultHasher implements RequestHasher
{
    /** @var \Codeat3\ResponseCache\CacheProfiles\CacheProfile */
    protected $cacheProfile;

    public function __construct(CacheProfile $cacheProfile)
    {
        $this->cacheProfile = $cacheProfile;
    }

    public function getHashFor(Request $request): string
    {
        return 'responsecache-'.md5(
            "{$request->getHost()}-{$request->getRequestUri()}-{$request->getMethod()}/".$this->cacheProfile->useCacheNameSuffix($request)
        );
    }
}
