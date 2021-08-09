<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Seeds;

use DB;
use Illuminate\Database\Seeder;

class CountriesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = self::data();

        DB::transaction(function () use($data) {

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table('countries')->truncate();

            DB::table('countries')->insert($data);

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
    }

    private static function data()
    {   
        return [
            [
                'id'=> 1,
                'name' => 'Mexico'
            ],
            [
                'id'=> 2,
                'name' => 'Chile'
            ],
            [
                'id'=> 3,
                'name' => 'Uruguay'
            ],
            [
                'id'=> 4,
                'name' => 'Peru'
            ],
            [
                'id'=> 5,
                'name' => 'Argentina'
            ],
        ];
    }
}