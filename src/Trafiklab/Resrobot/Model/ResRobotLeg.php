<?php


namespace Trafiklab\ResRobot\Model;

use Trafiklab\Common\Model\Contract\RoutePlanningLeg;
use Trafiklab\Common\Model\Contract\Stop;
use Trafiklab\Common\Model\Contract\Vehicle;

/**
 * A leg is one part of a journey, made with a single vehicle or on foot. A journey can consist of one or more legs. In
 * the case of multiple legs, a transfer is required between two legs.
 * @package Trafiklab\ResRobot\Model
 */
class ResRobotLeg implements RoutePlanningLeg
{

    private $_destination;
    private $_direction;
    private $_intermediaryStops;
    private $_notes;
    private $_origin;
    private $_type;
    private $_vehicle;

    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * The origin of this leg.
     * @return Stop The stoplocation at which this leg starts.
     */
    public function getOrigin(): Stop
    {
        return $this->_origin;
    }

    /**
     * The destination of this leg.
     * @return Stop The stoplocation at which this leg ends.
     */
    public function getDestination(): Stop
    {
        return $this->_destination;
    }

    /**
     * Remarks about this leg, for example describing facilities on board of a train, or possible disturbances on the
     * route.
     * @return string[]
     */
    public function getNotes(): array
    {
        return $this->_notes;
    }

    /**
     * The vehicle which is used to travel from the origin to the destination of this leg, if any. Can be null in case
     * of a walk between two stop locations.
     * @return Vehicle|null The vehicle used on this leg, or null in case of a walking transfer.
     */
    public function getVehicle(): ?Vehicle
    {
        return $this->_vehicle;
    }

    /**
     * Intermediary stops made by the vehicle on this leg.
     * @return ResRobotStop[] Stops between the origin and destination, excluding the origin and destination.
     */
    public function getIntermediaryStops(): array
    {
        return $this->_intermediaryStops;
    }

    /**
     * The direction of the vehicle on this leg. Can be null in case
     * of a walk between two stop locations.
     * @return string|null The direction of the vehicle used on this leg, or null in case of a walking transfer.
     */
    public function getDirection(): ?string
    {
        return $this->_direction;
    }

    /**
     * JNY: journey, WALK: walking.
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    private function parseApiResponse(array $json)
    {
        $this->_notes = [];
        if (key_exists('Notes', $json)) {
            foreach ($json['Notes']['Note'] as $note) {
                $this->_notes[] = $note['value'];
            }
        }

        $this->_type = $json['type'];

        if ($this->_type == "JNY") {
            $this->_vehicle = new ResRobotVehicle($json['Product']);

            $this->_intermediaryStops = [];
            foreach ($json['Stops']['Stop'] as $stop) {
                $this->_intermediaryStops[] = new ResRobotStop($stop);
            }
            // The stops array also includes the departure and arrival. Pop them of and use them instead of the
            // origin and destination data delivered by the API. This approach uses only one data type instead
            // of 2, making it easier to handle for end users.
            $this->_origin = array_shift($this->_intermediaryStops);
            $this->_destination = array_pop($this->_intermediaryStops);

            $this->_direction = $json['direction'];
        } else {
            // Should Dist (distance) and Duration (time) be parsed in case of a walk?

            $this->_origin = new ResRobotStop($json['Origin']);
            $this->_destination = new ResRobotStop($json['Destination']);
        }

    }
}