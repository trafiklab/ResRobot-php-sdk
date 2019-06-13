<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Contract\WebResponse;
use Trafiklab\ResRobot\Model\ResRobotStopLocationLookupRequest;

class ResRobotStopLocationLookupResponseTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validStopLocationLookupJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validStopLocationLookupReply.json"), true);
        $webResponse = self::createMock(WebResponse::class);
        $response = new ResRobotStopLocationLookupResponse($webResponse, $jsonArray);

        self::assertEquals($webResponse, $response->getOriginalApiResponse());
        self::assertEquals(10, count($response->getFoundStopLocations()));
        self::assertEquals("740098000", $response->getFoundStopLocations()[0]->getId());
    }

}