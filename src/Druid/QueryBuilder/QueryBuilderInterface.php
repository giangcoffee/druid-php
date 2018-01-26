<?php


namespace Druid\QueryBuilder;


use Druid\Query\QueryInterface;

interface QueryBuilderInterface
{
    /**
     * @return null|QueryInterface
     */
    public function getQuery();

    /**
     * @return mixed|string
     */
    public function getJSONQuery();
}