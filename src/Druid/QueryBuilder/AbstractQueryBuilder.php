<?php

/*
 * Copyright (c) 2016 PIXEL FEDERATION, s.r.o.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the PIXEL FEDERATION, s.r.o. nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL PIXEL FEDERATION, s.r.o. BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Druid\QueryBuilder;

use Druid\Query\Component\ComponentInterface;
use Druid\Query\Component\DataSource\TableDataSource;
use Druid\Query\Component\DataSourceInterface;
use Druid\Query\Component\Factory\FilterFactory;
use Druid\Query\Component\FilterInterface;
use Druid\Query\Component\Granularity\SimpleGranularity;
use Druid\Query\Component\GranularityInterface;
use Druid\Query\Component\Interval\Interval;
use Druid\Query\QueryInterface;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

/**
 * Class AbstractQueryBuilder.
 */
abstract class AbstractQueryBuilder implements QueryBuilderInterface
{
    const EMPTY_JSON_QUERY = "{}";

    /**
     * @var array
     */
    protected $components = [];

    /**
     * @var QueryInterface
     */
    protected $query;

    /**
     * @var SerializerInterface
     */
    protected static $serializer;

    /**
     * @return SerializerInterface
     */
    public static function getSerializer()
    {
        if (!self::$serializer instanceof SerializerInterface) {
            self::$serializer = SerializerBuilder::create()
                ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
                ->build();
        }

        return self::$serializer;
    }

    /**
     * @param string|DataSourceInterface $dataSource
     *
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        if ($dataSource instanceof DataSourceInterface) {
            return $this->addComponent('dataSource', $dataSource);
        }
        // the default
        return $this->addComponent('dataSource', new TableDataSource($dataSource));
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @param bool $useZuluTime
     *
     * @return $this
     */
    public function addInterval(\DateTime $start, \DateTime $end, $useZuluTime = false)
    {
        return $this->addComponent('intervals', new Interval($start, $end, $useZuluTime));
    }

    /**
     * @param string|GranularityInterface $granularity
     *
     * @return $this
     */
    public function setGranularity($granularity)
    {
        if ($granularity instanceof GranularityInterface) {
            return $this->addComponent('granularity', $granularity);
        } elseif (is_string($granularity)) {
            return $this->addComponent('granularity', new SimpleGranularity($granularity));
        }
        return $this;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function setFilter(FilterInterface $filter)
    {
        return $this->addComponent('filter', $filter);
    }

    /**
     * @param string             $componentName
     * @param ComponentInterface $component
     *
     * @return $this
     */
    public function addComponent($componentName, ComponentInterface $component)
    {
        $componentExists = array_key_exists($componentName, $this->components);
        if (!$componentExists) {
            throw new \InvalidArgumentException(
                sprintf('Undefined component name %s', $componentName)
            );
        }

        $isMultipleComponent = is_array($this->components[$componentName]);
        if ($isMultipleComponent) {
            $this->components[$componentName] = \array_merge($this->components[$componentName], [$component]);

            return $this;
        }

        $this->components[$componentName] = $component;

        return $this;
    }


    /**
     * @return mixed|string
     */
    public function getJSONQuery()
    {
        if (!$this->query instanceof QueryInterface) {
            return self::EMPTY_JSON_QUERY;
        }

        return self::getSerializer()->serialize($this->query, 'json');
    }
}
