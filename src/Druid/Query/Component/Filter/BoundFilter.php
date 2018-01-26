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

namespace Druid\Query\Component\Filter;

use Druid\Query\Component\AbstractTypedComponent;
use Druid\Query\Component\FilterInterface;

/**
 * Class BoundFilter.
 */
class BoundFilter extends AbstractTypedComponent implements FilterInterface
{
    /** @var string */
    private $dimension;

    /** @var mixed */
    private $lower;

    /** @var mixed */
    private $upper;

    /** @var boolean */
    private $lowerStrict;

    /** @var  boolean */
    private $upperStrict;

    /** @var  string */
    private $ordering;

    /** @var  mixed */
    private $extractionFn;

    /**
     * BoundFilter constructor.
     *
     * @param string $dimension
     * @param $lower
     * @param $upper
     * @param $lowerStrict
     * @param $upperStrict
     * @param null $ordering
     * @param null $extractionFn
     * @internal param string $values
     */
    public function __construct($dimension, $lower = null, $upper = null, $lowerStrict = null, $upperStrict = null, $ordering = null, $extractionFn = null)
    {
        parent::__construct(FilterInterface::TYPE_BOUND);

        $this->dimension = $dimension;
        $this->lower = $lower;
        $this->upper = $upper;
        $this->lowerStrict = $lowerStrict;
        $this->upperStrict = $upperStrict;
        $this->ordering = $ordering;
        $this->extractionFn = $extractionFn;
    }

    /**
     * @return string
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * @return mixed
     */
    public function getLower()
    {
        return $this->lower;
    }

    /**
     * @return mixed
     */
    public function getUpper()
    {
        return $this->upper;
    }

    /**
     * @return boolean
     */
    public function isLowerStrict()
    {
        return $this->lowerStrict;
    }

    /**
     * @return boolean
     */
    public function isUpperStrict()
    {
        return $this->upperStrict;
    }

    /**
     * @return string
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @return mixed
     */
    public function getExtractionFn()
    {
        return $this->extractionFn;
    }
}
