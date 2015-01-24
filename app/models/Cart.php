<?php
class Cart extends Eloquent{

    public function products()
    {
        return $this->belongsTo('Product', 'product_id');
    }
}