<?php
namespace CryCMS\SelectorByCarService\DTO;

/**
 * @property $modification_id
 * @property $model_id
 * @property $name
 * @property $url
 *
 * @property $pcd
 * @property $dia
 * @property $k_type
 * @property $k_size
 * @property TitleDTO $title
 */
class ModificationDTO extends ModificationSimpleDTO
{
    public $pcd;
    public $dia;
    public $k_type;
    public $k_size;
    public $title;
}