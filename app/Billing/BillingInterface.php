<?php namespace Billing;

interface BillingInterface {
    public function charge(array $data);
}