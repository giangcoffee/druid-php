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

use Druid\Query\Aggregation\GroupBy;
use Druid\Query\Component\DimensionSpec\DefaultDimensionSpec;
use Druid\Query\Component\HavingInterface;

/**
 * Class GroupByQueryBuilder.
 */
class GroupByQueryBuilder extends AbstractAggregationQueryBuilder implements QueryBuilderInterface
{
    protected $components = [
        'dataSource' => null,
        'dimensions' => [],
        'limitSpec' => null,
        'having' => null,
        'granularity' => null,
        'filter' => null,
        'aggregations' => [],
        'postAggregations' => [],
        'intervals' => [],
    ];

    /**
     * @param string $dimension
     * @param string $outputName
     *
     * @return $this
     */
    public function addDimension($dimension, $outputName)
    {
        return $this->addComponent('dimensions', new DefaultDimensionSpec($dimension, $outputName));
    }

    /**
     * @param HavingInterface $having
     *
     * @return $this
     */
    public function setHaving(HavingInterface $having)
    {
        return $this->addComponent('having', $having);
    }

    /**
     * @return GroupBy
     */
    public function getQuery()
    {
        $this->query = new GroupBy();
        foreach ($this->components as $componentName => $component) {
            if (!empty($component)) {
                $method = 'set'.ucfirst($componentName);
                $this->query->$method($component);
            }
        }

        return $this->query;
    }
}
