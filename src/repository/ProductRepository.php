<?php
declare(strict_types=1);

namespace App\repository;

class ProductRepository{

    public static function getProduct(string $productCode): Product{
        $desiredProduct = new Product();
        $file = \App\AppConfig::$path . "/data/products.json";

        $dataFile = fopen($file, "r") or die("Unable to open file!");
        $data = fread($dataFile, filesize($file));

        $products = json_decode($data);

        foreach($products as $key => $product){
            if($product->code === $productCode){
                $desiredProduct->name   = $product->name;
                $desiredProduct->code   = $product->code;
                $desiredProduct->price  = $product->price;
                $desiredProduct->currency = $product->currency;
            }
        }

        fclose($dataFile);

        return $desiredProduct;
    }
}