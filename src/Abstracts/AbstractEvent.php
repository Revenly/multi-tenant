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

namespace Hyn\Tenancy\Abstracts;

abstract class AbstractEvent
{
    public $reason;

    /**
     * @return $this
     */
    public function setReason(string $reason)
    {
        $this->reason = $reason;

        return $this;
    }
}
