<?php

namespace Modules\Elastic\Entities;

use Carbon\Carbon;

abstract class ElasticModel
{
    protected string $index;
    protected string $key = 'id';
    protected $fieldSuggest = null;
    protected $keySuggest = 'suggest';
    abstract public static function getProperties();

    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->{$name};
        }
        return null;
    }
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->properties)) {
            $this->{$name} = $value;
        }
    }
    public function __debugInfo()
    {
        return $this->toArray();
    }
    public function toArray()
    {
        $data = [];
        foreach ($this->properties as $property => $type) {
            if (isset($this->{$property})) {
                if ($type == "date") {
                    $data[$property] = new Carbon($this->{$property});
                } elseif ($type == 'integer') {
                    $data[$property] = (int) $this->{$property};
                } elseif ($type == 'double' || $type == 'float') {
                    $data[$property] = (float) $this->{$property};
                } else {
                    $data[$property] = $this->{$property};
                }
            } else {
                $data[$property] = null;
            }
        }
        return $data;
    }
}
