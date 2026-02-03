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
        Schema::create('delivery_services', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('user_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('transport_type', [
                                'air',    // самолет
                                'road',   // суша / авто
                                'sea',    // корабль
                                'rail'    // поезд (если понадобится)
                            ])->nullable();
            $table->string('wechat')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->integer('price')->nullable();
            $table->longText('info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_services');
    }
};
