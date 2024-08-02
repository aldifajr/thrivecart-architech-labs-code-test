<?php
declare(strict_types=1);

namespace App\interface;

interface BasketInterface{
    public function add(string $productCode):void;

    public function total():array;
}