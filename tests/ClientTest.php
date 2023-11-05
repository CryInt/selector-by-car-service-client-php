<?php
declare(strict_types=1);

use CryCMS\SelectorByCarService\Client;
use CryCMS\SelectorByCarService\Exceptions\TransportException;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    protected $client;

    protected static $brand;
    protected static $brandId;

    protected static $year;
    protected static $yearId;

    protected static $model;
    protected static $modelId;

    protected static $modification;
    protected static $modificationId;

    protected static $tyre;
    protected static $tyreId;

    protected static $wheel;
    protected static $wheelId;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();
    }

    /**
     * @throws TransportException
     */
    public function testByIdBrands(): void
    {
        $brands = $this->client->getBrandsList();
        $this->assertNotEmpty($brands);

        self::$brand = $brands[0];
        self::$brandId = self::$brand->brand_id;
    }

    /**
     * @throws TransportException
     */
    public function testByIdBrand(): void
    {
        $brand = $this->client->getBrand(self::$brandId);
        $this->assertNotEmpty($brand);
        $this->assertEquals(self::$brand, $brand);

        $fields = get_object_vars($brand);
        foreach ($fields as $value) {
            $this->assertNotEmpty($value);
        }
    }

    /**
     * @throws TransportException
     */
    public function testByIdYears(): void
    {
        $years = $this->client->getYearsList(self::$brandId);
        $this->assertNotEmpty($years);

        self::$year = $years[0];
        self::$yearId = self::$year->year_id;
    }

    /**
     * @throws TransportException
     */
    public function testByIdYear(): void
    {
        $year = $this->client->getYear(self::$yearId);
        $this->assertNotEmpty($year);
        $this->assertEquals(self::$year, $year);

        $fields = get_object_vars($year);
        foreach ($fields as $value) {
            $this->assertNotEmpty($value);
        }
    }

    /**
     * @throws TransportException
     */
    public function testByIdModels(): void
    {
        $models = $this->client->getModelsList(self::$yearId);
        $this->assertNotEmpty($models);

        self::$model = $models[0];
        self::$modelId = self::$model->model_id;
    }

    /**
     * @throws TransportException
     */
    public function testByIdModel(): void
    {
        $model = $this->client->getModel(self::$modelId);
        $this->assertNotEmpty($model);
        $this->assertEquals(self::$model, $model);

        $fields = get_object_vars($model);
        foreach ($fields as $value) {
            $this->assertNotEmpty($value);
        }
    }

    /**
     * @throws TransportException
     */
    public function testByIdModifications(): void
    {
        $modifications = $this->client->getModificationsList(self::$modelId);
        $this->assertNotEmpty($modifications);

        self::$modification = $modifications[0];
        self::$modificationId = self::$modification->modification_id;
    }

    /**
     * @throws TransportException
     */
    public function testByIdModification(): void
    {
        $modification = $this->client->getModification(self::$modificationId);
        $this->assertNotEmpty($modification);

        $fields = get_object_vars($modification);
        foreach ($fields as $field => $value) {
            $this->assertNotEmpty($value);
            if (property_exists(self::$modification, $field)) {
                $this->assertEquals(self::$modification->$field, $modification->$field);
            }
        }
    }

    /**
     * @throws TransportException
     */
    public function testByIdTyres(): void
    {
        $tyres = $this->client->getTyres(self::$modificationId);
        $this->assertNotEmpty($tyres);

        self::$tyre = $tyres[0];
        self::$tyreId = self::$tyre->id;

        foreach ($tyres as $tyre) {
            $fields = get_object_vars($tyre);
            foreach ($fields as $value) {
                $this->assertNotEmpty($value);
            }
        }
    }

    /**
     * @throws TransportException
     */
    public function testByIdWheels(): void
    {
        $wheels = $this->client->getWheels(self::$modificationId);
        $this->assertNotEmpty($wheels);

        self::$wheel = $wheels[0];
        self::$wheelId = self::$wheel->id;

        foreach ($wheels as $wheel) {
            $fields = get_object_vars($wheel);
            foreach ($fields as $value) {
                $this->assertNotEmpty($value);
            }
        }
    }

    /**
     * @throws TransportException
     */
    public function testGetTyre(): void
    {
        $tyre = $this->client->getTyre(self::$tyreId);
        $this->assertNotEmpty($tyre);
        $this->assertEquals(self::$tyre, $tyre);

        $fields = get_object_vars($tyre);
        foreach ($fields as $value) {
            $this->assertNotEmpty($value);
        }
    }

    /**
     * @throws TransportException
     */
    public function testGetWheel(): void
    {
        $wheel = $this->client->getWheel(self::$wheelId);
        $this->assertNotEmpty($wheel);
        $this->assertEquals(self::$wheel, $wheel);

        $fields = get_object_vars($wheel);
        foreach ($fields as $value) {
            $this->assertNotEmpty($value);
        }
    }
}