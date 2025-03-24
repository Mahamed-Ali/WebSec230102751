<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('code');
        $table->string('name');
        $table->string('model');
        $table->string('photo')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}


    public function down() {
        Schema::dropIfExists('products');
    }
}
