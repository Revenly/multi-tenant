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

use Hyn\Tenancy\Contracts\Website;
use Illuminate\Database\Eloquent\Builder;

interface WebsiteRepository
{
    /**
     * @return Website|null
     */
    public function findByUuid(string $uuid);

    /**
     * @return Website|null
     */
    public function findById(string|int $id);
    public function create(Website &$website): Website;
    public function update(Website &$website): Website;
    /**
     * @param bool $hard
     */
    public function delete(Website &$website, $hard = false): Website;

    /**
     * @warn Only use for querying.
     */
    public function query(): Builder;
}
