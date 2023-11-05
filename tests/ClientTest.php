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

    protected static $brandUrl;
    protected static $yearUrl;
    protected static $modelUrl;
    protected static $modificationUrl;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();
    }

    // by id

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
        $tyres = $this->client->getTyresList(self::$modificationId);
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
        $wheels = $this->client->getWheelsList(self::$modificationId);
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

    // by href

    /**
     * @throws TransportException
     */
    public function testGetByUrlBrands(): void
    {
        $brands = $this->client->getByUrl('brands');
        $this->assertNotEmpty($brands);
        $this->assertEquals(self::$brand, $brands[0]);

        self::$brandUrl = $brands[0]->url;

        $brand = $this->client->getByUrl('brand', self::$brandUrl);
        $this->assertNotEmpty($brand);
        $this->assertEquals(
            self::removeTitleFromTestTemporary(self::$brand),
            self::removeTitleFromTestTemporary($brand)
        );
    }

    /**
     * @throws TransportException
     */
    public function testGetByUrlYears(): void
    {
        $years = $this->client->getByUrl('years', self::$brandUrl);
        $this->assertNotEmpty($years);
        $this->assertEquals(self::$year, $years[0]);

        self::$yearUrl = $years[0]->url;

        $year = $this->client->getByUrl('year', self::$brandUrl, self::$yearUrl);
        $this->assertNotEmpty($year);
        $this->assertEquals(
            self::removeTitleFromTestTemporary(self::$year),
            self::removeTitleFromTestTemporary($year)
        );
    }

    /**
     * @throws TransportException
     */
    public function testGetByUrlModels(): void
    {
        $models = $this->client->getByUrl('models', self::$brandUrl, self::$yearUrl);
        $this->assertNotEmpty($models);
        $this->assertEquals(self::$model, $models[0]);

        self::$modelUrl = $models[0]->url;

        $model = $this->client->getByUrl('model', self::$brandUrl, self::$yearUrl, self::$modelUrl);
        $this->assertNotEmpty($model);
        $this->assertEquals(
            self::removeTitleFromTestTemporary(self::$model),
            self::removeTitleFromTestTemporary($model)
        );
    }

    /**
     * @throws TransportException
     */
    public function testGetByUrlModifications(): void
    {
        $modifications = $this->client->getByUrl(
            'modifications',
            self::$brandUrl,
            self::$yearUrl,
            self::$modelUrl
        );

        $this->assertNotEmpty($modifications);
        $this->assertEquals(self::$modification, $modifications[0]);

        self::$modificationUrl = $modifications[0]->url;

        $modification = $this->client->getByUrl(
            'modification',
            self::$brandUrl,
            self::$yearUrl,
            self::$modelUrl,
            self::$modificationUrl
        );

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
    public function testGetByUrlTyres(): void
    {
        $tyres = $this->client->getByUrl(
            'tyres',
            self::$brandUrl,
            self::$yearUrl,
            self::$modelUrl,
            self::$modificationUrl
        );

        $this->assertNotEmpty($tyres);
        $this->assertEquals(self::$tyre, $tyres[0]);
    }

    /**
     * @throws TransportException
     */
    public function testGetByUrlWheels(): void
    {
        $wheels = $this->client->getByUrl(
            'wheels',
            self::$brandUrl,
            self::$yearUrl,
            self::$modelUrl,
            self::$modificationUrl
        );

        $this->assertNotEmpty($wheels);
        $this->assertEquals(self::$wheel, $wheels[0]);
    }

    /**
     * @TODO - after adding correct title in methods by id - remove this
     *
     * @param $entity
     *
     * @return mixed
     */
    protected static function removeTitleFromTestTemporary($entity)
    {
        if (property_exists($entity, 'title')) {
            $entity->title = null;
        }

        return $entity;
    }
}