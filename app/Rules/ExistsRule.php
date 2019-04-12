<?php

namespace App\Rules;

use Doctrine\ORM\EntityManager;

class ExistsRule
{
    protected $database;

    public function __construct(EntityManager $database)
    {
        $this->database = $database;
    }

    public function validate($field, $value, $params, $fields)
    {
        $result = $this->database->getRepository($params[0])
            ->findOneBy([
                $field => $value
            ]);

        return $result === null;
    }
}
