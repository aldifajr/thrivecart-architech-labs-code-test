<?php
declare(strict_types=1);

namespace App\repository;

class BasketRepository{

    public static function updateItems(array $products) : void{
        $myfile = fopen(\App\AppConfig::$path . "/data/basket.json", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($products));
        fclose($myfile);
    }

    public static function getAllCurrentItems() : array {
        $items = [];
        try{
            $dataFile = fopen(\App\AppConfig::$path . "/data/basket.json", "r") or throw new \Exception("FILE_NOT_EXISTS");
            $data = fread($dataFile, filesize(\App\AppConfig::$path . "/data/basket.json"));

            $items = json_decode($data);
        }catch(\Exception $e){
            if(str_contains($e->getMessage(), 'FILE_NOT_EXISTS')){
                $myfile = fopen(\App\AppConfig::$path . "/data/basket.json", "w") or die("Unable to open file!");
                fwrite($myfile, json_encode([]));
                fclose($myfile);
            }            
        }
        return $items;
    }
}