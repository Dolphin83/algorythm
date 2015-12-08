<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Algorithm\Dijkstra;

$map = [];
$map['A']['B'] = 1.2;
$map['A']['C'] = 5.45;
$map['A']['D'] = 7.87;
$map['B']['C'] = 3.12;
$map['B']['D'] = 6.55;
$map['C']['D'] = 1.83;

echo Dijkstra::calculate('A','C',$map);

