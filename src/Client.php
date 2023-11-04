<?php
namespace CryCMS\SelectorByCarService;

use CryCMS\SelectorByCarService\DTO\BrandDTO;
use CryCMS\SelectorByCarService\DTO\TitleDTO;
use CryCMS\SelectorByCarService\DTO\YearDTO;
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
            $brandDTO = new BrandDTO();
            $brandDTO->brand_id = $brand->brand_id;
            $brandDTO->name = $brand->name;
            $brandDTO->url = $brand->url;

            $titleDTO = new TitleDTO();
            $titleDTO->brand = $brand->title->brand;
            $titleDTO->year = $brand->title->year;
            $titleDTO->model = $brand->title->model;
            $titleDTO->modification = $brand->title->modification;

            $brandDTO->title = $titleDTO;

            $result[] = $brandDTO;
        }

        return $result;
    }

    /**
     * @throws TransportException
     */
    public function getYearsList(int $brandId): array
    {
        $result = [];

        $years = $this->cUrl(self::SERVICE_HOST . '/car/brand/' . $brandId . '/years');

        foreach ($years as $year) {
            $yearDTO = new YearDTO();
            $yearDTO->year_id = $year->year_id;
            $yearDTO->brand_id = $year->brand_id;
            $yearDTO->name = $year->name;
            $yearDTO->url = $year->url;

            $titleDTO = new TitleDTO();
            $titleDTO->brand = $year->title->brand;
            $titleDTO->year = $year->title->year;
            $titleDTO->model = $year->title->model;
            $titleDTO->modification = $year->title->modification;

            $yearDTO->title = $titleDTO;

            $result[] = $yearDTO;
        }

        return $result;
    }

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