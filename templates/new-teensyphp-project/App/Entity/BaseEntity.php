<?php

namespace App\Entity;
use TeensyPHP\Traits\Crud;
use TeensyPHP\Traits\ArrayAccessImplementation;

/**
 * Base Entity shares the database connection, ArrayAccessImplementation, Crud across all entities.
 * Unless you have a specific reason to do otherwise, e.g. connecting to a different database,
 * you should extend this when creating your own entities.
 */
class BaseEntity
{
    use ArrayAccessImplementation, Crud;
}
