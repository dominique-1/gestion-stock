<?php

namespace App\Models;

class DemoProduct
{
    public $id;
    public $name;
    public $barcode;
    public $category;
    public $supplier;
    public $price;
    public $current_stock;
    public $stock_min;
    public $stock_optimal;
    public $expires_at;
    public $description;
    public $documents;
    public $stockMovements;
    
    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
    
    public function stockValue()
    {
        return $this->price * $this->current_stock;
    }
}
