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
        $offers = self::getOffers($items);
        $deliveryCost = self::getBasicDeliveryCost($priceAllItems - $offers['discount-from-offers']);

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

    private static function getOffers(array $items):array{
        $activeOffer = [];

        $discountFromOffers = self::getDiscountFromOffers($items);
        
        if($discountFromOffers > 0 ) $activeOffer[] = ["name" => "Buy one red widget, get the second half price"];

        return [
            "offers"                => $activeOffer,
            "discount-from-offers"  => $discountFromOffers
        ];
    }

    private static function getDiscountFromOffers(array $items): float{
        $R01Items = [];

        foreach($items as $key => $item){
            if($item->code === 'R01') $R01Items[] = $item;
        }

        if(count($R01Items) >= 2){
            return $R01Items[0]->price * 0.5;
        }

        return 0;
    }
}