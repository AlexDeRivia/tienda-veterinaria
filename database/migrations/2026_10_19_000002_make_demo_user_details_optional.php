<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 8)->nullable()->change();
            $table->string('surnames')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('phone', 9)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 8)->nullable(false)->change();
            $table->string('surnames')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('phone', 9)->nullable(false)->change();
        });
    }
};
