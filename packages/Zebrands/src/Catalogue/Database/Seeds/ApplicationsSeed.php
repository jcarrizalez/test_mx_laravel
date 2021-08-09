<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Seeds;

use DB;
use Illuminate\Database\Seeder;

class ApplicationsSeed extends Seeder
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

            DB::table('applications')->truncate();

            DB::table('applications')->insert($data);

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
    }

    private static function data()
    {   
        return [
            [
                'id'=> 1,
                'apk_id' => '9088-697E',
                'apk_secret' => '5fd2a7181825445eabd5edfdee1345baab942b2902c42',
                'name' => 'Api de prueba',
            ],
        ];
    }
}