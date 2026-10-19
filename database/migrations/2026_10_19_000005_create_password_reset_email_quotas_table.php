<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_email_quotas', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('day');
            $table->unsignedSmallInteger('requests');
            $table->primary(['user_id', 'day']);
            $table->index('day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_email_quotas');
    }
};
