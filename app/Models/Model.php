<?php

namespace App\Models;

abstract class Model
{
    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        if (property_exists($this, $name)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $columns
     */
    public function update(array $columns)
    {
        foreach ($columns as $column => $value) {
            $this->{$column} = $value;
        }
    }

    /**
     * @param array $columns
     */
    public function fill(array $columns)
    {
        $this->update($columns);
    }
}
