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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('sparepart_id')->nullable();
            $table->integer('quantity');
            $table->string('serial_part')->nullable();
            $table->integer('used_qty')->nullable();
            $table->integer('using_qty')->nullable();
            $table->integer('broken_qty')->nullable();
            $table->string('remark')->nullable();
            $table->integer('condition_id');
            $table->integer('warehouse_id')->nullable();
            $table->integer('defect_id')->nullable();
            $table->date('return_date')->nullable();
            $table->integer('usage_id')->nullable();

            $table->timestamps();

            $table->foreign('sparepart_id')
                ->references('id')
                ->on('spareparts')
                ->onDelete('CASCADE');
            $table->foreign('condition_id')
                ->references('id')
                ->on('conditions')
                ->onDelete('CASCADE');
            $table->foreign('defect_id')
                ->references('id')
                ->on('defect_types')
                ->onDelete('CASCADE');
            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('CASCADE');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
