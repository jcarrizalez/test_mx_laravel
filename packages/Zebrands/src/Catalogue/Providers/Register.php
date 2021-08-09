<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Providers;

use Zebrands\Catalogue\Contracts\BrandContract;
use Zebrands\Catalogue\Contracts\UserContract;
use Zebrands\Catalogue\Contracts\ProductContract;

use Zebrands\Catalogue\Repositories\BrandRepository;
use Zebrands\Catalogue\Repositories\UserRepository;
use Zebrands\Catalogue\Repositories\ProductRepository;

class Register
{
    const ALIAS      = 'Catalogue';

    public $commands  = [
    ];

    public $contracts = [
        BrandContract::class             => BrandRepository::class,
        UserContract::class              => UserRepository::class,
        ProductContract::class           =>ProductRepository::class
    ];

    public $alias     = [
    ];
}