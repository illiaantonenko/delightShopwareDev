<?php


use DelightCallback\Models\Callback;

class Shopware_Controllers_Backend_DelightCallback extends Shopware_Controllers_Backend_Application
{
    protected $model = Callback::class;
    protected $alias = 'callback';
}