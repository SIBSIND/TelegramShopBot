<?php

namespace TelegramShopBot\controlPanel;

use TelegramShopBot\Database\Manager\CityManager;
use TelegramShopBot\Database\Manager\DistrictManager;
use TelegramShopBot\Database\Manager\ProductManager;

class HandlerAPI
{
    private $entity;
    private $method;

    /**
     * HandlerAPI constructor.
     *
     * @param $entity
     * @param $method
     */
    public function __construct($entity, $method)
    {
        $this->entity = $entity;
        $this->method = $method;
    }


    public function handle($data)
    {
        $callFunc = $this->entity . '_' . $this->method;
        if (method_exists($this, $callFunc)) {
            if (empty($data)) {
                call_user_func([$this, $callFunc]);
            } else {
                call_user_func([$this, $callFunc], $data);
            }
        }
    }

    private function city_getAll()
    {
        $allCity = CityManager::getAll();

        $result = [];
        foreach ($allCity as $city) {
            $result[] = [
                'id' => $city->getId(),
                'name' => $city->getName()
            ];
        }

        echo json_encode($result);
    }

    private function city_update($data)
    {
        $city = CityManager::getById(intval($data['id']));
        CityManager::update($city->setName($data['name']));

        echo json_encode(['result' => 'success']);
    }

    private function city_create($data)
    {
        $city = CityManager::create($data['name']);

        echo json_encode([
            'id' => $city->getId(),
            'name' => $city->getName()
        ]);
    }

    private function city_delete($data)
    {
        CityManager::delete($data['id']);

        echo json_encode(['result' => 'success']);
    }

    private function product_getAll()
    {
        $allProducts = ProductManager::getAll();

        $result = [];
        foreach ($allProducts as $product) {
            $result[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice()
            ];
        }

        echo json_encode($result);
    }

    private function product_update($data)
    {
        ProductManager::update(
            ProductManager::getOne(intval($data['id']))
                ->setName($data['name'])
                ->setPrice($data['price'])
        );

        echo json_encode(['result' => 'success']);
    }

    private function product_delete($data)
    {
        ProductManager::delete($data['id']);

        echo json_encode(['result' => 'success']);
    }

    private function product_create($data)
    {
        $product = ProductManager::create($data['name'], $data['price']);

        echo json_encode([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice()
        ]);
    }

    private function district_getAll($data)
    {
        $districts = DistrictManager::getAllByCityId($data['city_id']);

        if (!empty($districts)) {
            $result = [];
            foreach ($districts as $district) {
                $result[] = [
                    'id' => $district->getId(),
                    'name' => $district->getName()
                ];
            }
            $result['city_name'] = $districts[0]->getCity()->getName();

            echo json_encode($result);
        } else {
            echo json_encode(['city_name' => CityManager::getById($data['city_id'])->getName()]);
        }
    }

    private function district_update($data)
    {
        DistrictManager::update(
            DistrictManager::getById($data['id'])
                ->setName($data['name'])
        );

        echo json_encode(['result' => 'success']);
    }

    private function district_delete($data)
    {
        DistrictManager::delete($data['id']);

        echo json_encode(['result' => 'success']);
    }

    private function district_create($data)
    {
        $district = DistrictManager::create($data['city_id'], $data['name']);

        echo json_encode([
            'id' => $district->getId(),
            'name' => $district->getName()
        ]);
    }
}