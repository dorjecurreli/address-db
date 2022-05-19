<?php

namespace App\Traits;

trait Filter
{


//    public function makeName($data)
//    {
//
//    }

    public function makeValues($data) : array
    {
        $values = [];

        foreach ($data as $entry) {

            if (is_object($entry)) {
                $key = strtolower($entry->getName());
                $value = $entry->getName();
                $values[$key] = $value;
            }

            if (is_string($entry)) {
                $key = strtolower($entry);
                $value = $entry;
                $values[$key] = $value;
            }
        }

        return $values;
    }







}