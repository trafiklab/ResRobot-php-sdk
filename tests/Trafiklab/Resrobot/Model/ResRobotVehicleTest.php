<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Enum\TransportType;

class ResRobotVehicleTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validVehicleJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validRoutePlanningVehicle.json"), true);
        $vehicle = new ResRobotVehicle($jsonArray);

        self::assertEquals("Regional Tåg 11154", $vehicle->getName());
        self::assertEquals(TransportType::TRAIN, $vehicle->getType());
        self::assertEquals(11154, $vehicle->getNumber());
        self::assertEquals(300, $vehicle->getOperatorCode());
        self::assertEquals("Öresundståg", $vehicle->getOperatorName());
        self::assertEquals("http://www.oresundstag.se/", $vehicle->getOperatorUrl());
    }

}