<?php
declare(strict_types = 1);

namespace Repositories;

use Model\User_model;

/**
 * Class UserRepository
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    
    /**
     * @param string $login
     * @param string $password
     *
     * @return User_model|null
     */
    public function findByLoginAndPassword(string $login, string $password): ?User_model
    {
        $result = $this->queryBuilder
            ->get_where($this->getTableName(), ['email' => $login, 'password' => $password]);
        
        if (!$result) {
            return null;
        }
        
        return new User_model($result->row_array()['id']);
    }
    
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return User_model::CLASS_TABLE;
    }
}
