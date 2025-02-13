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

use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Website;
use Hyn\Tenancy\Database\Connection;
use Hyn\Tenancy\Exceptions\ModelValidationException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator as Native;

abstract class Validator
{
    /**
     * @var array
     */
    protected $create = [];
    /**
     * @var array
     */
    protected $update = [];
    /**
     * @var array
     */
    protected $delete = [];

    public function save(Model $model): bool
    {
        if ($model->exists) {
            return $this->update($model);
        }

        return $this->create($model);
    }

    /**
     * @return bool
     */
    public function delete(Model $model)
    {
        return $this->validate(
            $model,
            $this->delete
        );
    }

    /**
     * @return bool
     */
    protected function update(Model $model)
    {
        return $this->validate(
            $model,
            $this->update
        );
    }

    /**
     * @return bool
     */
    protected function create(Model $model)
    {
        return $this->validate(
            $model,
            $this->create
        );
    }

    /**
     * @return bool
     * @throws ModelValidationException
     */
    protected function validate(Model $model, array $rules)
    {
        /** @var Factory $validator */
        $factory = app(Factory::class);

        $rules = $this->replaceVariables($rules, $model);

        /** @var Native $validator */
        $validator = $factory->make(
            $model->getAttributes(),
            $rules
        );

        if ($validator->fails()) {
            throw new ModelValidationException($validator);
        }

        return $validator->passes();
    }

    /**
     * @return array
     */
    protected function replaceVariables(array $rules, Model $model)
    {
        /** @var Connection $connection */
        $connection = app(Connection::class);

        $hostname = app(Hostname::class);
        $website = app(Website::class);

        return collect($rules)->map(fn($ruleSet) => collect($ruleSet)->map(fn($rule) => str_replace([
            '%system%',
            '%tenant%',
            '%id%',
            '%websites%',
            '%hostnames%'
        ], [
            $connection->systemName(),
            $connection->tenantName(),
            $model->getKey(),
            $website->getTable(),
            $hostname->getTable()
        ], (string) $rule))->toArray())->toArray();
    }

    public function getRulesFor($model, $for = 'create'): array
    {
        $rules = $this->{$for} ?? [];

        return $this->replaceVariables($rules, $model);
    }
}
