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
        Schema::create('ordermachines', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id');
            $table->integer('type_id');
            $table->integer('model_id');
            $table->integer('quantity');
            $table->integer('warehouse_id');
            $table->integer('status_id');
            $table->timestamps();

            $table->foreign('bank_id')
                ->references('id')
                ->on('customers')
                ->onDelete('CASCADE');
            $table->foreign('type_id')
                ->references('id')
                ->on('terminaltypes')
                ->onDelete('CASCADE');
            $table->foreign('model_id')
                ->references('id')
                ->on('terminalmodels')
                ->onDelete('CASCADE');
            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('CASCADE');
            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordermachines');
    }
};
