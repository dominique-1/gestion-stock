<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('smart_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert_id')->unique(); // ID unique pour l'alerte
            $table->enum('type', ['stock_min', 'overstock', 'expiry', 'prediction_risk', 'movement_anomaly']);
            $table->enum('level', ['critical', 'warning', 'info']);
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->json('data')->nullable(); // Données techniques structurées
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->boolean('dismissed')->default(false);
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->index(['type', 'level']);
            $table->index(['is_read']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('smart_alerts');
    }
};
