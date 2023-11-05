<?php
namespace CryCMS\SelectorByCarService\DTO;

/**
 * @property $id
 * @property $width
 * @property $diameter
 * @property $et
 * @property $type
 * @property $axis
 */
class WheelDTO
{
    public const AXIS_FRONT = 1;
    public const AXIS_REAR = 2;

    public $id;
    public $width;
    public $diameter;
    public $et;
    public $type;
    public $axis;
}