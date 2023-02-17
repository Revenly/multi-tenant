<?php

/*
 * This file is part of the hyn/multi-tenant package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/hyn/multi-tenant
 */

namespace Hyn\Tenancy\Listeners;

use Hyn\Tenancy\Contracts\Website\UuidGenerator;
use Hyn\Tenancy\Events\Websites\Creating;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;

class WebsiteUuidGeneration
{
    /**
     * WebsiteUuidGeneration constructor.
     */
    public function __construct(private readonly Repository $config, private readonly UuidGenerator $generator)
    {
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(Creating::class, [$this, 'addUuid']);
    }

    public function addUuid(Creating $event)
    {
        if (! $event->website->uuid && $this->config->get('tenancy.website.disable-random-id') !== true) {
            $event->website->uuid = $this->generator->generate($event->website);
        }
    }
}
