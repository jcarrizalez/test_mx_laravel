<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() :void
    {
        Schema::create('products', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('code', 13);
            $table->string('name', 50);
            $table->string('description', 200)->nullable();
            $table->string('color', 20)->nullable();
            $table->integer('stock')->default(0);
            $table->float('price', 8, 2)->default(0);
            $table->integer('country_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->softDeletes();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->unique(['code']);
            $table->unique(['name','country_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() :void
    {
        Schema::dropIfExists('products');
    }
}