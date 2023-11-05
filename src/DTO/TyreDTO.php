<?php
namespace CryCMS\SelectorByCarService\DTO;

/**
 * @property $id
 * @property $width
 * @property $height
 * @property $diameter
 * @property $type
 * @property $axis
 */
class TyreDTO
{
    public const AXIS_FRONT = 1;
    public const AXIS_REAR = 2;

    public $id;
    public $width;
    public $height;
    public $diameter;
    public $type;
    public $axis;
}