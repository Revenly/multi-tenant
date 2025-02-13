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

namespace Hyn\Tenancy\Listeners\Database;

use Hyn\Tenancy\Contracts\Hostname;
use Illuminate\Contracts\Cache\Factory;

class FlushHostnameCache
{
    public function __construct(private readonly Factory $cache)
    {
    }

    public function saved(Hostname $hostname)
    {
        if ($hostname->isDirty([
            'under_maintenance_since', 'website_id', 'force_https',
            'redirect_to', 'fqdn'
        ])) {
            $this->flush($hostname);
        }
    }

    protected function flush(Hostname $hostname)
    {
        $fqdn = $hostname->getOriginal('fqdn') ?? $hostname->fqdn;

        $this->cache->forget("tenancy.hostname.$fqdn");
    }
}
