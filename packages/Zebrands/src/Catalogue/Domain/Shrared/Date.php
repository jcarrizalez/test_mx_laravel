<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain\Shrared;

use DateTime;
use Base\Carbon;

class Date
{
    /*
        DATE_TEST_SPPEDY=false
        8640 segundos son 12meses, esperas 2:24 hora-minutos
        4320 segundos son  6meses, esperas 1:12 hora-minutos
        720  segundos son  1mes,   esperas 12 minutos
        24 segundos un dia
        1 segundo una hora
        DATE_TEST_SPPEDY=true
        4320 segundos son 12meses, esperas 1:12 hora-minutos
        4320 segundos son  6meses, esperas 36 minutos
        360  segundos son  1mes,   esperas 6 minutos
        12 segundos un dia
        1 segundo una hora
    */
    public static function intervals($fn, $cant=null) :?string
    {   
        if(env('APP_ENV')!='production' && env('DATE_TEST')==true){

            $sppedy = env('DATE_TEST_SPPEDY', false);
            switch ($fn) {
                case 'addDays':
                    return ($cant*($sppedy?12:24))." seconds";
                case 'removeDays':
                    return '- '.($cant*($sppedy?12:24))." seconds";
                case 'addHours':
                    return ((int) ($cant/2)).' seconds';
                case 'addMonths':
                    return ($cant*($sppedy?360:720)).' seconds';
                case 'addOneMonth':
                    return ($sppedy?360:720).' seconds';
                case 'addSevenDays':
                    return ($sppedy?72:144).' seconds + '.($sppedy?6:12).' seconds';
                case 'addOneMonthSevenDays':
                    return ($sppedy?360:720).' seconds + '.($sppedy?72:144).' seconds + 6 seconds';
                case 'removeSeconds':
                    return '- '.$cant.' seconds';
                default:
                    return null;
            }

        }
        else{
            switch ($fn) {
                case 'addDays':
                    return "$cant days";
                case 'removeDays':
                    return "- $cant days";
                case 'addHours':
                    return "$cant hour";
                case 'addMonths':
                    return "$cant months";
                case 'addOneMonth':
                    return '1 months';
                case 'addSevenDays':
                    return '6 days + 12 hour';
                case 'addOneMonthSevenDays':
                    return '1 months + 6 days + 12 hour';
                case 'removeSeconds':
                    return "- $cant seconds";
                default:
                    return null;
            }
        }
    }

    public static function now() :string
    {   
        return Carbon::now()->format('Y-m-d H:i:s');
    }
    
    public static function create(string $date) :DateTime
    {
        return new DateTime($date);
    }

    public static function interval(string $date, string $interval) :DateTime
    {
        return date_add(
            self::create($date), 
            date_interval_create_from_date_string($interval)
        );
    }

    public static function addOneMonthSevenDays(string $date) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__) );
    }

    public static function addSevenDays(string $date) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__) );
    }
    
    public static function addOneMonth(string $date) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__) );
    }

    public static function addMonths(string $date, $cant) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__, $cant) );
    }

    public static function addDays(string $date, $cant) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__, $cant) );
    }

    public static function removeDays(string $date, $cant) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__, $cant) );
    }

    public static function addHours(string $date, $cant) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__, $cant) );
    }
    
    public static function removeSeconds(string $date, $cant) :DateTime
    {
        return self::interval($date, self::intervals(__FUNCTION__, $cant) );
    }

    public static function format(DateTime $date) :string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function parse(string $date) :DateTime
    {
        $array = explode(' ', $date);

        if(!isset($array[1])){
            $date = $array[0].' 00:00:00';
        }

        if(env('APP_ENV')!='production' && env('DATE_TEST')==true){
            $now  = self::now();
            $from = Carbon::parse($now);
            $date = Carbon::parse($date);
            $diff = $date->diffInDays($from);
            #Mayor o igual que
            if(true===$date->gte($from)){
                return self::addDays($now, $diff);
            }
            else{
                return self::removeDays($now, $diff);
            }
        }
        else{
            return Carbon::parse($date);
        }
    }
}