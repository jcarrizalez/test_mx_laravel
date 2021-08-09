<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain\Shrared\Traits;

use Zebrands\Catalogue\Domain\Shrared\Date;
use Zebrands\Catalogue\Domain\Shrared\Exception;

trait CardCustomer
{
	public function getCardCustomer($customer_id)
    {	
        $cache_key = self::$CACHE_CUSTOMER_CARD.$customer_id;

        if(null === $card = $this->cache->get($cache_key) ){

            $cards = $this->customers->cardsByCustomer($customer_id);

            if(count($cards)===0){
                throw new Exception(4045);
            }
            $card  = (object) reset($cards);
        }
        return $card;
    }
}