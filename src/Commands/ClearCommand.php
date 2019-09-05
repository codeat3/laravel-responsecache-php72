<?php

namespace Codeat3\ResponseCache\Commands;

use Illuminate\Console\Command;
use Codeat3\ResponseCache\ResponseCacheRepository;
use Codeat3\ResponseCache\Events\ClearedResponseCache;
use Codeat3\ResponseCache\Events\ClearingResponseCache;

class ClearCommand extends Command
{
    protected $signature = 'responsecache:clear';

    protected $description = 'Clear the response cache';

    public function handle(ResponseCacheRepository $cache)
    {
        event(new ClearingResponseCache());

        $cache->clear();

        event(new ClearedResponseCache());

        $this->info('Response cache cleared!');
    }
}
