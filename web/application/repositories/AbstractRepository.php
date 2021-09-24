<?php
declare(strict_types = 1);

namespace Repositories;

use CI_DB_query_builder;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository
{
    /**
     * @var CI_DB_query_builder
     */
    protected $queryBuilder;
    
    public function __construct(CI_DB_query_builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
    
    public function findById(int $id): array
    {
        return $this->queryBuilder->get_where($this->getTableName(), ['id' => $id])->first_row();
    }
    
    abstract function getTableName(): string;
}
