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

namespace Hyn\Tenancy\Database;

use Illuminate\Database\ConnectionResolverInterface;

class Resolver implements ConnectionResolverInterface
{
    public function __construct(private readonly string $connection, private readonly ConnectionResolverInterface $connectionResolver)
    {
    }

    public function connection($name = null)
    {
        return $this->connectionResolver->connection($this->connection);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->connectionResolver, $name], $arguments);
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->connection;
    }

    /**
     * Set the default connection name.
     *
     * @param  string $name
     * @return void
     */
    public function setDefaultConnection($name)
    {
        throw new \InvalidArgumentException("Resetting the default connection of a forced model is impossible.");
    }
}
