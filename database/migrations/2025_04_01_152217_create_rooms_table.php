<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name');
            $table->string('room_type'); // Standard, Deluxe, Suite, etc.
            $table->string('main_photo'); // Main room photo
            $table->integer('max_adults');
            $table->integer('max_children');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('featured')->default(false);  // Add 'featured' column

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
