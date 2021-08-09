<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Seeds;

use DB;
use Illuminate\Database\Seeder;

class BrandsSeed extends Seeder
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

            DB::table('brands')->truncate();

            DB::table('brands')->insert($data);

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
    }

    private static function data()
    {   
        return [
            [
                'id'=> 1,
                'name' => 'Addidas',
                'description' => 'Marca Addidas',
            ],
            [
                'id'=> 2,
                'name' => 'Nike',
                'description' => 'Marca Nike',
            ],
            [
                'id'=> 3,
                'name' => 'Fila',
                'description' => 'Marca Fila',
            ],
        ];
    }
}