<?php
declare(strict_types = 1);

namespace Model;

use System\Emerald\Emerald_model;

/**
 * Interface LikeableInterface
 *
 * @package Model
 */
interface LikeableInterface
{
    /**
     * @return void
     */
    public function like(): void;
}
