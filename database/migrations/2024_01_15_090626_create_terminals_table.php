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
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->string('atm_id')->unique();
            $table->string('serial_number')->unique();
            $table->string('alias_id')->nullable();
            $table->date('install_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('takeover_date');
            $table->string('android_version');
            $table->integer('model_id');
            $table->integer('type_id');
            $table->integer('banklocation_id');
            $table->integer('category_id');
            $table->integer('status_id');
            $table->integer('warrenty');
            $table->timestamps();
            $table->foreign('model_id')
                ->references('id')
                ->on('terminalmodels')
                ->onDelete('CASCADE');
            $table->foreign('type_id')
                ->references('id')
                ->on('terminaltypes')
                ->onDelete('CASCADE');
            $table->foreign('banklocation_id')
                ->references('id')
                ->on('banklocations')
                ->onDelete('CASCADE');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('CASCADE');
            $table->foreign('status_id')
                ->references('id')
                ->on('terminalstatuses')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminals');
    }
};
