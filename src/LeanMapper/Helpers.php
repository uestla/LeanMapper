<?php

/**
 * This file is part of the Lean Mapper library (http://www.leanmapper.com)
 *
 * Copyright (c) 2013 Vojtěch Kohout (aka Tharos)
 *
 * For the full copyright and license information, please view the file
 * license.md that was distributed with this source code.
 */

declare(strict_types=1);

namespace LeanMapper;

use Dibi\Row as DibiRow;

class Helpers
{

    public function __construct()
    {
        throw new Exception\UtilityClassException('Cannot instantiate utility class ' . get_called_class() . '.');
    }


    /**
     * @param  mixed $value
     */
    public static function getType($value): string
    {
        return is_object($value) ? ('instance of ' . get_class($value)) : gettype($value);
    }


    /**
     * Trims namespace part from fully qualified class name
     */
    public static function trimNamespace(string $class): string
    {
        $class = explode('\\', $class);
        return end($class);
    }


    /**
     * @param  array|DibiRow $row
     */
    public static function convertDbRow(string $table, $row, IMapper $mapper): array
    {
        if ($row instanceof DibiRow) {
            $row = $row->toArray();

        } elseif (!is_array($row)) {
            throw new Exception\InvalidArgumentException('DB row must be ' . DibiRow::class . '|array, ' . self::getType($row) . ' given.');
        }

        return $mapper->convertToRowData($table, $row);
    }


    /**
     * @param  array<array<string, mixed>|DibiRow<string, mixed>> $rows
     * @return array<array<string, mixed>>
     */
    public static function convertDbRows(string $table, array $rows, IMapper $mapper): array
    {
        $result = [];

        foreach ($rows as $k => $row) {
            $result[$k] = self::convertDbRow($table, $row, $mapper);
        }

        return $result;
    }


    /**
     * @param  array<string, mixed> $rowData
     * @return array<string, mixed>
     */
    public static function convertRowData(string $table, array $rowData, IMapper $mapper): array
    {
        return $mapper->convertFromRowData($table, $rowData);
    }

}
