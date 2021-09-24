<?php
declare(strict_types = 1);

namespace Repositories;

use Model\User_model;

/**
 * Interface UserRepositoryInterface
 */
interface UserRepositoryInterface
{
    /**
     * @param string $login
     * @param string $password
     *
     * @return User_model|null
     */
    public function findByLoginAndPassword(string $login, string $password): ?User_model;
}
