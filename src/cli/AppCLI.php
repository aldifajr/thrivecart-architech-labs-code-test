<?php
declare(strict_types=1);

namespace App\cli;

use App\controller\BasketController;

class AppCLI{
    public array $commands = [
        'add-item'      => 'Add item into basket',
        'remove-item'   => 'Remove item from the basket',
        'get-items'     => 'Get all items in the basket',
        'get-checkout-summary' => 'Get summary of costs, discounts and offers',
        'help'          => 'Show this help message',
    ];
    
    public function showHelp() : void
    {
        echo "Available commands:\n";
        foreach ($this->commands as $command => $description) {
            echo "  $command: $description\n";
        }
    }
    
    public function add(string $item) : void
    {
        $basket = new BasketController();
        $basket->add($item);
        
        echo "Item {$item} has been added to basket.\nHere's your current basket: \n";
        print_r($basket->getAllItems());
    }

    public function remove(string $item) : void
    {
        try{
            $basket = new BasketController();
            $basket->removeItem($item);
            
            echo "Item {$item} has been removed from the basket.\nHere's your current basket: \n";
            print_r($basket->getAllItems());
        }catch(\Exception $e){
            echo $e->getMessage();
        }        
    }

    public function getItems() : void
    {
        try{
            $basket = new BasketController();
            
            echo "Here's your current basket: \n";
            print_r($basket->getAllItems());
        }catch(\Exception $e){
            echo $e->getMessage();
        }        
    }

    public function getCheckoutSummary():void{
        try{
            $basket = new BasketController();
            
            echo "Here's your checkout summary: \n";
            print_r($basket->total());
        }catch(\Exception $e){
            echo $e->getMessage();
        } 
    }
}