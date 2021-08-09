<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Persistence;

use Base\Cache AS BaseCache;
#use Cache AS BaseCache;
class Cache
{
    public function get($key)
    {
    	return BaseCache::get($key);
    }

    public function put($key, $data, $minutes)
    {
    	BaseCache::put($key, $data, $minutes);
    }
}