<?php
declare(strict_types=1);

use CryCMS\SelectorByCarService\Client;
use CryCMS\SelectorByCarService\Exceptions\TransportException;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    protected $client;

    protected static $brands;
    protected static $years;

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
        self::$brands = $this->client->getBrandsList();
        $this->assertNotEmpty(self::$brands);
    }

    /**
     * @throws TransportException
     */
    public function testByIdYears(): void
    {
        foreach (self::$brands as $brand) {
            self::$years[$brand->brand_id] = $this->client->getYearsList($brand->brand_id);
            $this->assertNotEmpty(self::$years[$brand->brand_id]);
        }
    }
}