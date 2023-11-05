<?php
namespace CryCMS\SelectorByCarService;

use CryCMS\SelectorByCarService\DTO\TitleDTO;
use CryCMS\SelectorByCarService\DTO\BrandDTO;
use CryCMS\SelectorByCarService\DTO\YearDTO;
use CryCMS\SelectorByCarService\DTO\ModelDTO;
use CryCMS\SelectorByCarService\DTO\ModificationDTO;
use CryCMS\SelectorByCarService\DTO\ModificationSimpleDTO;
use CryCMS\SelectorByCarService\DTO\TyreDTO;
use CryCMS\SelectorByCarService\DTO\WheelDTO;
use CryCMS\SelectorByCarService\Exceptions\TransportException;
use JsonException;

class Client
{
    public const SERVICE_HOST = 'https://selector.x03.ru';

    protected $error;

    /**
     * @throws TransportException
     */
    public function getBrandsList(): array
    {
        $result = [];

        $brands = $this->cUrl(self::SERVICE_HOST . '/car/brands');

        foreach ($brands as $brand) {
            $result[] = $this->buildBrand($brand);
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getBrand(int $brandId): ?BrandDTO
    {
        $brand = $this->cUrl(self::SERVICE_HOST . '/car/brand/' . $brandId);
        return $this->buildBrand($brand);
    }

    /**
     * @throws TransportException
     */
    public function getYearsList(int $brandId): array
    {
        $result = [];

        $years = $this->cUrl(self::SERVICE_HOST . '/car/brand/' . $brandId . '/years');

        foreach ($years as $year) {
            $result[] = $this->buildYear($year);
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getYear(int $yearId): ?YearDTO
    {
        $year = $this->cUrl(self::SERVICE_HOST . '/car/year/' . $yearId);
        return $this->buildYear($year);
    }

    /**
     * @throws TransportException
     */
    public function getModelsList(int $yearId): array
    {
        $result = [];

        $models = $this->cUrl(self::SERVICE_HOST . '/car/year/' . $yearId . '/models');

        foreach ($models as $model) {
            $result[] = $this->buildModel($model);
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getModel(int $modelId): ?ModelDTO
    {
        $model = $this->cUrl(self::SERVICE_HOST . '/car/model/' . $modelId);
        return $this->buildModel($model);
    }

    /**
     * @throws TransportException
     */
    public function getModificationsList(int $modificationId): array
    {
        $result = [];

        $modifications = $this->cUrl(self::SERVICE_HOST . '/car/model/' . $modificationId . '/modifications');

        foreach ($modifications as $modification) {
            $result[] = $this->buildModificationSimple($modification);
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getModification(int $modification): ?ModificationDTO
    {
        $modification = $this->cUrl(self::SERVICE_HOST . '/car/modification/' . $modification);
        return $this->buildModification($modification);
    }

    /**
     * @throws TransportException
     */
    public function getTyres(int $modificationId): array
    {
        $result = [];

        $tyres = $this->cUrl(self::SERVICE_HOST . '/car/modification/' . $modificationId . '/tyres');
        foreach ($tyres as $tyre) {
            $result[] = $this->buildTyre($tyre);
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getWheels(int $modificationId): array
    {
        $result = [];

        $wheels = $this->cUrl(self::SERVICE_HOST . '/car/modification/' . $modificationId . '/wheels');
        foreach ($wheels as $wheel) {
            $result[] = $this->buildWheel($wheel);
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getTyre(int $sizeId): ?TyreDTO
    {
        $size = $this->cUrl(self::SERVICE_HOST . '/car/tyre/' . $sizeId);
        return $this->buildTyre($size);
    }

    /**
     * @throws TransportException
     */
    public function getWheel(int $sizeId): ?WheelDTO
    {
        $size = $this->cUrl(self::SERVICE_HOST . '/car/wheel/' . $sizeId);
        return $this->buildWheel($size);
    }

    protected function buildBrand($brandObject): BrandDTO
    {
        $brandDTO = new BrandDTO();
        $brandDTO->brand_id = $brandObject->brand_id;
        $brandDTO->name = $brandObject->name;
        $brandDTO->url = $brandObject->url;
        $brandDTO->title = $this->buildTitle($brandObject->title);

        return $brandDTO;
    }

    protected function buildYear($yearObject): YearDTO
    {
        $yearDTO = new YearDTO();
        $yearDTO->year_id = $yearObject->year_id;
        $yearDTO->brand_id = $yearObject->brand_id;
        $yearDTO->name = $yearObject->name;
        $yearDTO->url = $yearObject->url;
        $yearDTO->title = $this->buildTitle($yearObject->title);

        return $yearDTO;
    }

    protected function buildModel($modelObject): ModelDTO
    {
        $modelDTO = new ModelDTO();
        $modelDTO->model_id = $modelObject->model_id;
        $modelDTO->year_id = $modelObject->year_id;
        $modelDTO->name = $modelObject->name;
        $modelDTO->url = $modelObject->url;
        $modelDTO->title = $this->buildTitle($modelObject->title);

        return $modelDTO;
    }

    protected function buildModificationSimple($modificationObject): ModificationSimpleDTO
    {
        $modificationSimpleDTO = new ModificationSimpleDTO();
        $modificationSimpleDTO->modification_id = $modificationObject->modification_id;
        $modificationSimpleDTO->model_id = $modificationObject->model_id;
        $modificationSimpleDTO->name = $modificationObject->name;
        $modificationSimpleDTO->url = $modificationObject->url;

        return $modificationSimpleDTO;
    }

    protected function buildModification($modificationObject): ModificationDTO
    {
        $modificationSimpleDTO = new ModificationDTO();
        $modificationSimpleDTO->modification_id = $modificationObject->modification_id;
        $modificationSimpleDTO->model_id = $modificationObject->model_id;
        $modificationSimpleDTO->name = $modificationObject->name;
        $modificationSimpleDTO->url = $modificationObject->url;

        $modificationSimpleDTO->pcd = $modificationObject->pcd;
        $modificationSimpleDTO->dia = $modificationObject->dia;
        $modificationSimpleDTO->k_type = $modificationObject->k_type;
        $modificationSimpleDTO->k_size = $modificationObject->k_size;
        $modificationSimpleDTO->title = $this->buildTitle($modificationObject->title);

        return $modificationSimpleDTO;
    }

    protected function buildTyre($tyreObject): TyreDTO
    {
        $tyreDTO = new TyreDTO();
        $tyreDTO->id = $tyreObject->id;
        $tyreDTO->width = $tyreObject->width;
        $tyreDTO->height = $tyreObject->height;
        $tyreDTO->diameter = $tyreObject->diameter;
        $tyreDTO->type = $tyreObject->type;
        $tyreDTO->axis = $tyreObject->axis;

        return $tyreDTO;
    }

    protected function buildWheel($wheelObject): WheelDTO
    {
        $wheelDTO = new WheelDTO();
        $wheelDTO->id = $wheelObject->id;
        $wheelDTO->width = $wheelObject->width;
        $wheelDTO->diameter = $wheelObject->diameter;
        $wheelDTO->et = $wheelObject->et;
        $wheelDTO->type = $wheelObject->type;
        $wheelDTO->axis = $wheelObject->axis;

        return $wheelDTO;
    }

    protected function buildTitle($titleObject): TitleDTO
    {
        $titleDTO = new TitleDTO();
        $titleDTO->brand = $titleObject->brand;
        $titleDTO->year = $titleObject->year;
        $titleDTO->model = $titleObject->model;
        $titleDTO->modification = $titleObject->modification;

        return $titleDTO;
    }

    /** @noinspection PhpUnused */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @throws TransportException
     */
    protected function cUrl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 360);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);

        curl_setopt($ch, CURLOPT_POST, 0);

        $data = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new TransportException($data, $httpCode);
        }

        try {
            return json_decode($data, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new TransportException($e, $httpCode);
        }
    }
}