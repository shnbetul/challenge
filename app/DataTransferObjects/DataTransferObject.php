<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Str;
use ReflectionClass;

abstract class DataTransferObject
{
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    /**
     * Makes non-accessible properties to readable.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    /**
     * Modifies empty strings to null.
     *
     * @return void
     */
    protected function modifyEmptyStringsToNull(): void
    {
        $properties = (new ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            $value = $property->getValue($this);

            if ($value === '') {
                $value = null;
            }

            $this->{$property->getName()} = $value;
        }
    }

    /**
     * Transforms data transfer object to array.
     *
     * @return array<string, mixed> $array
     */
    public function toArray(): array
    {
        $properties = (new ReflectionClass($this))->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $value = $property->getValue($this);

            if ($value instanceof DataTransferObject) {
                $value = $value->toArray();
            }

            $array[$property->getName()] = $value;
        }

        return $array;
    }

    /**
     * Transforms data transfer object to model array.
     *
     * @return array<string, mixed> $modelArray
     */
    public function toModelArray(): array
    {
        $array = $this->toArray();
        $modelArray = [];

        return $this->toModelArrayForeach($array);
    }

    private function toModelArrayForeach($array, $test = null): array
    {
        $modelArray = [];
        foreach ($array as $key => $value) {
            if (is_object($value)) {
                $value = $value->toModelArray();
            }
            if (is_array($value)) {
                $value = collect($value);
                $value = $this->toModelArrayForeach($value, 'test');
            }

            $modelArray[Str::snake($key)] = $value;
        }
        return $modelArray;
    }
}
