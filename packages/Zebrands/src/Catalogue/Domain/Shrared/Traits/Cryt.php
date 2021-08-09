<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain\Shrared\Traits;

trait Cryt
{
	private $cryt_type1 = 8888;
	private $cryt_type2 = 9999;

	private function decrypt($cvv) :string
	{
		$cvv_array = explode('-', "$cvv");

		if(!isset($cvv_array[1])) return "123456789";
		if(!isset($cvv_array[2])) return "123456789";
		$type_encrypt = $cvv_array[0];
		$cvv_length = $cvv_array[1];

		unset($cvv_array[0]);
		unset($cvv_array[1]);

		$type_encrypt = $this->{'cryt_type'.$type_encrypt}??121212;

		$cvv = implode('-', $cvv_array);

		$cvv = (base64_decode($cvv)/$type_encrypt); // Este dato es igual al escrito en frontend
	    
	    if(strlen("$cvv")===1) $cvv = "00{$cvv}";
	    else if(strlen("$cvv")===2) $cvv = "0{$cvv}";
	    if(strlen("$cvv")===3 && $cvv_length==4) $cvv = "0{$cvv}";
	    return "$cvv";
	}

	private function encrypt($cvv) :string
	{
		$cvv_length = strlen("$cvv");

		$cvv = base64_encode(''.(( (int) $cvv)*$this->cryt_type2 ) );
	
		return '2-'.$cvv_length.'-'.$cvv;
	}
}