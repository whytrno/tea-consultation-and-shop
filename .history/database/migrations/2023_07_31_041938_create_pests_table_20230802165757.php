<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pests', function (Blueprint $table) {
            $table->id();
            $table->string('pest')->unique();
            $table->string('image');
            $table->text('pest_detail');
            $table->text('description');
            $table->text('how_to_controll');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pests');
    }
};