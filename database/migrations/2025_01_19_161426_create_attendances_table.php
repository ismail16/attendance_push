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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key')->nullable();
            $table->foreignId('deviceId')->nullable();
            $table->ipAddress('device_ip');
            $table->string('userId');
            $table->string('punchTime');
            $table->string('punchType')->comment('0=in, 4=out');
            $table->string('punchMode');
            $table->boolean('status')->default(false)->comment('1=synced, 0=not_synced');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
