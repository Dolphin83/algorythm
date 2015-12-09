<?php

use Algorithm\Dijkstra;

class FormTest extends PHPUnit_Framework_TestCase
{
  public function testInvalidInputDateFormat()
  {
    $departure = 'A';
    $destination = 'C';
    $map = [
      'A' => 3,
      'B' => ['C' => 4]
    ];

    $res = Dijkstra::getCheapestRoute($departure, $destination, $map);
    $this->assertEquals($res, Dijkstra::FORMAT_ERROR, 'Вывод информации о неверном формате');
  }

  public function testOnlyPositiveCostAppears()
  {
    $departure = 'A';
    $destination = 'C';
    $map = [
        'A' => ['B' => 2,'C' => 4,'D' => -4],
        'B' => ['C' => 3, 'D' => -6],
        'C' => ['D' => 1],
    ];

    $res = Dijkstra::getCheapestRoute($departure, $destination, $map);
    $this->assertEquals($res, Dijkstra::FORMAT_ERROR, 'Вывод информации о неверном формате');
  }

  public function testDepartureIsUnknown()
  {
    $departure = 'UNKNOWN';
    $destination = 'C';
    $map = [
        'A' => ['B' => 2,'C' => 4,'D' => 4],
        'B' => ['C' => 3, 'D' => 6],
        'C' => ['D' => 1],
    ];

    $res = Dijkstra::getCheapestRoute($departure, $destination, $map);
    $this->assertEquals($res, sprintf(Dijkstra::FOUND_NO_WAY, $departure, $destination), 'Вывод информации о невозможности найти маршрут');
  }

  public function testDestinationIsUnknown()
  {
    $departure = 'A';
    $destination = 'UNKNOWN';
    $map = [
        'A' => ['B' => 2,'C' => 4,'D' => 4],
        'B' => ['C' => 3, 'D' => 6],
        'C' => ['D' => 1],
    ];

    $res = Dijkstra::getCheapestRoute($departure, $destination, $map);
    $this->assertEquals($res, sprintf(Dijkstra::FOUND_NO_WAY, $departure, $destination), 'Вывод информации о невозможности найти маршрут');
  }

  public function testDestinationIsNotReached()
  {
    $departure = 'A';
    $destination = 'E';
    $map = [
        'A' => ['B' => 2,'C' => 4,'D' => 4],
        'B' => ['C' => 3, 'D' => 6],
        'C' => ['D' => 1],
        'E' => ['K' => 1],
    ];

    $res = Dijkstra::getCheapestRoute($departure, $destination, $map);
    $this->assertEquals($res, sprintf(Dijkstra::FOUND_NO_WAY, $departure, $destination), 'Вывод информации о невозможности найти маршрут');
  }

  public function testFoundCheapestRouteLikeAviaTrip()
  {
    $departure = 'A';
    $destination = 'C';
    $map = [];
    $map['A']['B'] = 2;
    $map['A']['C'] = 7;
    $map['A']['D'] = 5;
    $map['B']['D'] = 2;
    $map['C']['B'] = 3;
    $map['D']['C'] = 1;

    $res = Dijkstra::getCheapestRoute($departure, $destination, $map);
    $this->assertEquals(
        $res,
        sprintf(Dijkstra::ROUTE, $departure, $destination, 'A-->B-->D-->C', 5),
        'Вывод информации о самом дешевом маршруте'
    );
  }

  public function testFoundCheapestRouteLikeCarTrip()
  {
    $departure = 'A';
    $destination = 'C';
    $map = [];
    $map['A']['B'] = 2;
    $map['A']['C'] = 7;
    $map['A']['D'] = 5;
    $map['B']['D'] = 3;
    $map['C']['B'] = 3;
    $map['D']['C'] = 1;

    $res = Dijkstra::getCheapestRoute($departure, $destination, $map, FALSE);
    $this->assertEquals(
        $res,
        sprintf(Dijkstra::ROUTE, $departure, $destination, 'A-->B-->C', 5),
        'Вывод информации о самом дешевом перелете'
    );
  }


}