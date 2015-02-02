<?php
class Package extends Eloquent{
    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }
}