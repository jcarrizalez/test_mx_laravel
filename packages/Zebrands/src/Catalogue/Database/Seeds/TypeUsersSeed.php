<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Seeds;

use DB;
use Illuminate\Database\Seeder;

class TypeUsersSeed extends Seeder
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

            DB::table('type_users')->truncate();

            DB::table('type_users')->insert($data);

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
    }

    private static function data()
    {   
        return [
            [
                'id'=> 1,
                'name' => 'Administrador',
            ],
            [
                'id'=> 2,
                'name' => 'Anónimo'
            ],
        ];
    }
}