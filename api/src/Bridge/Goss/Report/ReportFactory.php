<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Report;

use Dungap\Bridge\Goss\Contracts\GossReportFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossReportInterface;
use Dungap\Util\Serializer\HypenNameConverter;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReportFactory implements GossReportFactoryInterface
{
    public function create(string $output): GossReportInterface
    {
        $classMetadataFactory = new ClassMetadataFactory(
            new AttributeLoader(),
        );
        $normalizer = new ObjectNormalizer(
            classMetadataFactory: $classMetadataFactory,
            nameConverter: new HypenNameConverter(),
            propertyAccessor: new PropertyAccessor(),
            propertyTypeExtractor: new ReflectionExtractor(),
        );
        $normalizers = [
            $normalizer,
            new GetSetMethodNormalizer(),
            new ArrayDenormalizer(),
        ];
        $serializer = new Serializer($normalizers,
            [new JsonEncoder()]
        );

        return $serializer->deserialize($output, Report::class, 'json');
    }
}
