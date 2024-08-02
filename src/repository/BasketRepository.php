<?php
declare(strict_types=1);

namespace App\repository;

use App\AppConfig;
class BasketRepository{

    public static function updateItems(array $products) : void{
        $myfile = fopen(AppConfig::$path . "/data/basket.json", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($products));
        fclose($myfile);
    }

    public static function getAllCurrentItems() : array {
        $items = [];
        try{
            $dataFile = fopen(AppConfig::$path . "/data/basket.json", "r") or throw new \Exception("FILE_NOT_EXISTS");
            $data = fread($dataFile, filesize(AppConfig::$path . "/data/basket.json"));

            $items = json_decode($data);
        }catch(\Exception $e){
            if(str_contains($e->getMessage(), 'FILE_NOT_EXISTS')){
                $myfile = fopen(AppConfig::$path . "/data/basket.json", "w") or die("Unable to open file!");
                fwrite($myfile, json_encode([]));
                fclose($myfile);
            }            
        }
        return $items;
    }
}