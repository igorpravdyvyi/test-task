<?php
declare(strict_types = 1);

namespace Service;

use Exception;
use Model\Comment_model;
use Model\LikeableInterface;
use Model\Post_model;
use System\Emerald\Exception\EmeraldModelNoDataException;

/**
 * Class LikeModelFactory
 */
class LikeModelRetriever
{
    /**
     * @throws Exception
     */
    public function make(string $entity, int $entity_id): ?LikeableInterface
    {
        if ('comment' === $entity) {
            try {
                return new Comment_model($entity_id);
            } catch (EmeraldModelNoDataException $exception) {
                return null;
            }
        }
        
        if ('post' === $entity) {
            try {
                return new Post_model($entity_id);
            } catch (EmeraldModelNoDataException $exception) {
                return null;
            }
        }
        
        throw new Exception('Provided entity is not supported');
    }
}
