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

namespace Hyn\Tenancy\Contracts\Repositories;

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Website;
use Illuminate\Database\Eloquent\Builder;

interface HostnameRepository
{
    /**
     * @return Hostname|null
     */
    public function findByHostname(string $hostname);

    /**
     * @return Hostname|null
     */
    public function getDefault();

    public function create(Hostname &$hostname): Hostname;

    public function update(Hostname &$hostname): Hostname;

    /**
     * @param bool $hard
     */
    public function delete(Hostname &$hostname, $hard = false): Hostname;
    public function attach(Hostname &$hostname, Website &$website): Hostname;
    public function detach(Hostname &$hostname): Hostname;

    /**
     * @warn Only use for querying.
     */
    public function query(): Builder;
}
