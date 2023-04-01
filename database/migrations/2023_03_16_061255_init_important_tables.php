<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Mime', function(Blueprint $table){
            $table->id();
            $table->string('name', 75);
        });

        Schema::create('Extension', function(Blueprint $table){
            $table->id();
            $table->string('name', 50);
            $table->unsignedBigInteger('mime_id');
            $table->foreign('mime_id')->references('id')->on('Mime')->onDelete('cascade');
        });

        Schema::create('Signature', function(Blueprint $table){
            $table->id();
            $table->unsignedSmallInteger('offset');
            $table->string('hex');
            $table->unsignedBigInteger('extension_id');
            $table->foreign('extension_id')->references('id')->on('Extension')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('Signature', function(Blueprint $table){
            $table->dropForeign(['extension_id']);
        });
        Schema::table('Extension', function(Blueprint $table){
            $table->dropForeign(['mime_id']);
        });
        Schema::dropIfExists('Signature');
        Schema::dropIfExists('Extension');
        Schema::dropIfExists('Mime');
    }
};
