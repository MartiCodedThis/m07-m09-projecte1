<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 255);
            $table->bigInteger('file_id')->unsigned();
            $table->float('latitude');
            $table->float('longitude');
            $table->unsignedBigInteger('visibility_id');
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('visibility_id')->references('id')->on('visibilities');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
