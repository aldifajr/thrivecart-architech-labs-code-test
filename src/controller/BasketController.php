<?php
declare(strict_types=1);

namespace App\controller;

use App\interface\BasketInterface;
use App\repository\ProductRepository;
use App\repository\BasketRepository;
use App\repository\Product;

class BasketController implements BasketInterface{
    private array $items = [];

    public function __construct(){
        $this->items = BasketRepository::getAllCurrentItems();
    }

    public function add(string $productCode):void{
        $this->items[] = ProductRepository::getProduct($productCode);

        BasketRepository::updateItems($this->items);
    }

    public function total(): array{
        return [
            "total-items"           => count($this->items),
            "price"                 => PriceCalculator::get($this->items),
            "items"                 => $this->items
        ];
    }

    public function removeItems():void{
        $this->items[] = [];

        BasketRepository::updateItems($this->items);
    }

    // This method will remove all items with the same item-id
    public function removeItem(string $itemId):void{
        $found = false;
        foreach($this->items as $key => $item){
            if($item->code === $itemId) {
                $found = true;
                array_splice($this->items, $key, 1);
            }
        }

        if($found === false) throw new \Exception("Item is not found in your basket");

        BasketRepository::updateItems($this->items);
    }

    public function getAllItems():array{
        return $this->items;
    }
}

class PriceCalculator{

    public static function get(array $items): array {

        $priceAllItems = 0;

        foreach($items as $key => $item){
            $priceAllItems += $item->price;
        }

        // Get delivery cost and offers
        $deliveryCost = self::getBasicDeliveryCost($priceAllItems);
        $offers = self::getOffers($items);

        return [
            "price-all-items"   => $priceAllItems,
            "delivery-cost"     => $deliveryCost,
            "offers"            => $offers,
            "total-price"       => ($priceAllItems + $deliveryCost - $offers['discount-from-offers'])
        ];
    }

    private static function getBasicDeliveryCost(float $priceAllItems): float{
        if($priceAllItems < 50){
            return 4.95;
        }else if($priceAllItems >= 50 && $priceAllItems < 90){
            return 2.95;
        }else if($priceAllItems >= 90){
            return 0;
        }else{
            throw new \Exception('DELIVERY_COST_UNDEFINED');
        }
    }

    // Currently the method only considerig there is only 1 active offer
    private static function getOffers(array $items):array{
        $R01IsInTheBasket = false;
        $discountFromOffers = null;
        $activeOffer = [
            "name"              => "Buy one red widget, get the second half price",
            "offered-item"   => null
        ];

        // Check if R01 is in the basket
        foreach($items as $key => $item){
            if($item->code === 'R01') $R01IsInTheBasket = true;
        }

        // Check if there are more than 1 items in the basket
        if(count($items) > 1 and $R01IsInTheBasket){
            $discountFromOffers = (bcmul("{$items[1]->price}", "0.5", 2));
            $activeOffer["offered-item"] = $items[1];
        }

        return [
            "offers"                => $activeOffer,
            "discount-from-offers"  => $discountFromOffers
        ];
    }
}