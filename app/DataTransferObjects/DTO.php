<?php

namespace App\DataTransferObjects;

abstract class DTO
{
    public function toArray($flatten = false): array
    {
        $data = get_object_vars($this);
        $flattenedData = [];

        foreach ($data as $key => $value) {
            if ($value instanceof DTO) {
                if ($flatten) {
                    // Merge nested DTO properties into the top-level array
                    $flattenedData = array_merge($flattenedData, $value->toArray(true));
                } else {
                    $flattenedData[$key] = $value->toArray(false);
                }
            } elseif (is_array($value)) {
                if ($flatten) {
                    foreach ($value as $item) {
                        if ($item instanceof DTO) {
                            // Merge each DTO item properties into the top-level array
                            $flattenedData = array_merge($flattenedData, $item->toArray(true));
                        } else {
                            $flattenedData[$key][] = $item;
                        }
                    }
                } else {
                    // If not flattening, map through the array of DTOs
                    $flattenedData[$key] = array_map(function ($item) {
                        return $item instanceof DTO ? $item->toArray(false) : $item;
                    }, $value);
                }
            } else {
                $flattenedData[$key] = $value;
            }
        }

        return $flattenedData;
    }
}
