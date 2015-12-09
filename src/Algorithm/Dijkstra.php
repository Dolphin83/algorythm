<?php

namespace Algorithm;

/**
 * Class Dijkstra
 * @package Algorithm
 */
class Dijkstra
{
    const FORMAT_ERROR = 'Invalid input data format';
    const FOUND_NO_WAY = 'Between %s and %s can not build a route';
    const ROUTE = 'The cheapest route between %s and %s is %s. The fare is %g';

    /**
     * @param $departure <p>Название города отправления</p>
     * @param $destination <p>Название города прибытия</p>
     * @param $map <p>Матрица городов.
     * На пересечении столбцов и строк - стоимость
     * проезда</p>
     * @param bool $strict <p>Режим работы: если true, то матрица городов
     * используется как есть.
     * Если false - матрица городов дополняется обратными
     * маршрутами со такой же стоимостью как прямой</p>
     * @return string Сообещние о выполнение данной функции
     */
    public static function getCheapestRoute($departure, $destination, $map, $strict=true)
    {
        if (! Dijkstra::checkFormat($map)) {
            return Dijkstra::FORMAT_ERROR;
        }

        if (! $strict) {
            $map = Dijkstra::extendMap($map);
        }

        $vertices = Dijkstra::getAllVertices($map);

        if (!in_array($departure, $vertices) || !in_array($destination, $vertices)) {
            return sprintf(Dijkstra::FOUND_NO_WAY, $departure, $destination);
        }

        $wayPoint = [];
        $weight = array_fill_keys($vertices, PHP_INT_MAX);
        $weight[$departure] = 0;

        while ($weight) {
            $current = array_search(min($weight), $weight);
            if ($current == $destination) break;

            if (!empty($map[$current])) {
                foreach ($map[$current] as $related => $cost) {
                    if (!empty($weight[$related]) && $weight[$current] + $cost < $weight[$related]) {
                        $weight[$related] = $weight[$current] + $cost;
                        $wayPoint[$related] = array(
                            'neighbor' => $current,
                            'cost' => $weight[$related]
                        );
                    }
                }
            }

            unset($weight[$current]);
        }

        if (!array_key_exists($destination, $wayPoint)) {
            return sprintf(Dijkstra::FOUND_NO_WAY, $departure, $destination);
        }

        $path = [];
        $current = $destination;
        while ($current != $departure) {
            $path[] = $current;
            $current = $wayPoint[$current]['neighbor'];
        }
        $path[] = $departure;
        $path = array_reverse($path);

        return sprintf(Dijkstra::ROUTE, $departure, $destination, implode('-->', $path), $wayPoint[$destination]['cost']);
    }

    /**
     * Проверяет входные данные на соответствию формату, с которым работает основная функция
     * @param mixed $array <p>Матрица городов.
     * На пересечении столбцов и строк - стоимость
     * проезда</p>
     *
     * @return bool true если проверка пройдена, false если проверка не пройдена.
     */
    private static function checkFormat($array)
    {
        foreach($array as $item) {
            if (! is_array($item)) return false;
            foreach($item as $cost) {
                if (!is_numeric($cost) || $cost < 0) return false;
            }
        }

        return true;
    }

    /**
     * Создает зеркальное отражение матрицы
     *
     * @param mixed $array <p>Матрица городов.
     * На пересечении столбцов и строк - стоимость
     * проезда</p>
     *
     * @return array Обновленная матрица городов.
     */
    private static function extendMap(array $array)
    {
        foreach (array_keys($array) as $k1) {
            foreach (array_keys($array[$k1]) as $k2) {
                if (empty($array[$k2][$k1])) {
                    $array[$k2][$k1] = $array[$k1][$k2];
                }
            }
        }

        return $array;
    }

    /**
     * Возвращает уникальный список городов
     *
     * @param mixed $array <p>Матрица городов.
     * На пересечении столбцов и строк - стоимость
     * проезда</p>
     * @return array Уникальный список городов.
     */
    private static function getAllVertices(array $array)
    {
        $buf = [];
        array_walk($array, function($item) use (&$buf) {
            $buf = array_merge($buf, array_keys($item));
        });

        return array_values(array_unique(array_merge($buf, array_keys($array))));
    }
}