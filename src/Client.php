<?php
namespace CryCMS\SelectorByCarService;

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

    public const ENTITY_BRANDS = 'brands';
    public const ENTITY_YEARS = 'years';
    public const ENTITY_MODELS = 'models';
    public const ENTITY_MODIFICATIONS = 'modifications';
    public const ENTITY_TYRES = 'tyres';
    public const ENTITY_WHEELS = 'wheels';

    protected const ENTITY_BRANDS_BUILDER = 'buildBrand';
    protected const ENTITY_YEARS_BUILDER = 'buildYear';
    protected const ENTITY_MODELS_BUILDER = 'buildModel';
    protected const ENTITY_MODIFICATIONS_BUILDER = 'buildModification';
    protected const ENTITY_MODIFICATIONS_SIMPLE_BUILDER = 'buildModificationSimple';
    protected const ENTITY_TYRES_BUILDER = 'buildTyre';
    protected const ENTITY_WHEELS_BUILDER = 'buildWheel';

    protected const ENTITY_BUILDERS = [
        self::ENTITY_BRANDS => self::ENTITY_BRANDS_BUILDER,
        self::ENTITY_YEARS => self::ENTITY_YEARS_BUILDER,
        self::ENTITY_MODELS => self::ENTITY_MODELS_BUILDER,
        self::ENTITY_MODIFICATIONS => self::ENTITY_MODIFICATIONS_SIMPLE_BUILDER,
        self::ENTITY_TYRES => self::ENTITY_TYRES_BUILDER,
        self::ENTITY_WHEELS => self::ENTITY_WHEELS_BUILDER,
    ];

    protected $error;

    protected $host;
    protected $token;

    public function __construct(?string $token = null, ?string $host = null)
    {
        if ($token !== null) {
            $this->token = $token;
        }

        if ($host !== null) {
            $this->host = $host;
        }
        else {
            $this->host = self::SERVICE_HOST;
        }
    }

    /**
     * @throws TransportException
     */
    public function getBrandsList(): array
    {
        $result = [];

        $brands = $this->cUrl($this->host . '/car/brands');

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
        $brand = $this->cUrl($this->host . '/car/brand/' . $brandId);
        return $this->buildBrand($brand);
    }

    /**
     * @throws TransportException
     */
    public function getYearsList(int $brandId): array
    {
        $result = [];

        $years = $this->cUrl($this->host . '/car/brand/' . $brandId . '/years');

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
        $year = $this->cUrl($this->host . '/car/year/' . $yearId);
        return $this->buildYear($year);
    }

    /**
     * @throws TransportException
     */
    public function getModelsList(int $yearId): array
    {
        $result = [];

        $models = $this->cUrl($this->host . '/car/year/' . $yearId . '/models');

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
        $model = $this->cUrl($this->host . '/car/model/' . $modelId);
        return $this->buildModel($model);
    }

    /**
     * @throws TransportException
     */
    public function getModificationsList(int $modificationId): array
    {
        $result = [];

        $modifications = $this->cUrl($this->host . '/car/model/' . $modificationId . '/modifications');

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
        $modification = $this->cUrl($this->host . '/car/modification/' . $modification);
        return $this->buildModification($modification);
    }

    /**
     * @throws TransportException
     */
    public function getTyresList(int $modificationId): array
    {
        $result = [];

        $tyres = $this->cUrl($this->host . '/car/modification/' . $modificationId . '/tyres');
        foreach ($tyres as $tyre) {
            $result[] = $this->buildTyre($tyre);
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getWheelsList(int $modificationId): array
    {
        $result = [];

        $wheels = $this->cUrl($this->host . '/car/modification/' . $modificationId . '/wheels');
        foreach ($wheels as $wheel) {
            $result[] = $this->buildWheel($wheel);
        }

        return $result;
    }

    /**
     * @throws TransportException
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function getByUrl(
        string $entity,
        ?string $brandUrl = null,
        ?string $yearUrl = null,
        ?string $modelUrl = null,
        ?string $modificationUrl = null
    )
    {
        $buildMethod = null;

        $url = [];
        $url[] = 'car';
        $url[] = 'url';

        if ($brandUrl !== null) {
            $buildMethod = self::ENTITY_BRANDS_BUILDER;
            $url[] = $brandUrl;
        }

        if ($yearUrl !== null) {
            $buildMethod = self::ENTITY_YEARS_BUILDER;
            $url[] = $yearUrl;
        }

        if ($modelUrl !== null) {
            $buildMethod = self::ENTITY_MODELS_BUILDER;
            $url[] = $modelUrl;
        }

        if ($modificationUrl !== null) {
            $buildMethod = self::ENTITY_MODIFICATIONS_BUILDER;
            $url[] = $modificationUrl;
        }

        if (array_key_exists($entity, self::ENTITY_BUILDERS)) {
            $buildMethod = self::ENTITY_BUILDERS[$entity];
            $url[] = $entity;
        }

        $query = $this->host . '/' . implode('/', $url);

        $data = $this->cUrl($query);
        if (empty($data)) {
            return null;
        }

        if (is_array($data)) {
            $result = [];

            foreach ($data as $once) {
                $result[] = $this->$buildMethod($once);
            }

            return $result;
        }

        return $this->$buildMethod($data);
    }

    /**
     * @throws TransportException
     */
    public function getTyre(int $sizeId): ?TyreDTO
    {
        $size = $this->cUrl($this->host . '/car/tyre/' . $sizeId);
        return $this->buildTyre($size);
    }

    /**
     * @throws TransportException
     */
    public function getWheel(int $sizeId): ?WheelDTO
    {
        $size = $this->cUrl($this->host . '/car/wheel/' . $sizeId);
        return $this->buildWheel($size);
    }

    protected function buildBrand($brandObject): BrandDTO
    {
        $brandDTO = new BrandDTO();
        $brandDTO->brand_id = $brandObject->brand_id;
        $brandDTO->name = $brandObject->name;
        $brandDTO->url = $brandObject->url;

        return $brandDTO;
    }

    protected function buildYear($yearObject): YearDTO
    {
        $yearDTO = new YearDTO();
        $yearDTO->year_id = $yearObject->year_id;
        $yearDTO->brand_id = $yearObject->brand_id;
        $yearDTO->name = $yearObject->name;
        $yearDTO->url = $yearObject->url;

        return $yearDTO;
    }

    protected function buildModel($modelObject): ModelDTO
    {
        $modelDTO = new ModelDTO();
        $modelDTO->model_id = $modelObject->model_id;
        $modelDTO->year_id = $modelObject->year_id;
        $modelDTO->name = $modelObject->name;
        $modelDTO->url = $modelObject->url;

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
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        curl_setopt($ch, CURLOPT_POST, 0);

        $headers = [];

        if ($this->token !== null) {
            $headers[] = "Authorization: Bearer " . $this->token;
        }

        if (count($headers) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

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