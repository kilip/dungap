<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Factory;

use Dungap\Device\Entity\Category;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Category>
 *
 * @method        Category|Proxy     create(array|callable $attributes = [])
 * @method static Category|Proxy     createOne(array $attributes = [])
 * @method static Category[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Category[]|Proxy[] createSequence(iterable|callable $sequence)
 *
 * @phpstan-method        Proxy<Category> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Category> createOne(array $attributes = [])
 * @phpstan-method static list<Proxy<Category>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Category>> createSequence(iterable|callable $sequence)
 */
final class CategoryFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->sentence(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->withoutPersisting()
            // ->afterInstantiate(function(Category $category): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Category::class;
    }
}
