<?php
class Cart extends Eloquent{
    public $timestamps = false;

    public function products()
    {
        return $this->belongsTo('Product', 'product_id');
    }
}