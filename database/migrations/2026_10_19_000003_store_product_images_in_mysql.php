<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_hash', 64)->nullable();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->foreignId('product_id')->primary()->constrained('products')->cascadeOnDelete();
            $table->string('mime_type', 32);
            $table->binary('content');
        });

        // The free database has limited space, so uploads are capped at 512 KB.
        // MEDIUMBLOB allows that size without encoding the bytes as base64.
        DB::statement('ALTER TABLE product_images MODIFY content MEDIUMBLOB NOT NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_hash');
        });
    }
};
