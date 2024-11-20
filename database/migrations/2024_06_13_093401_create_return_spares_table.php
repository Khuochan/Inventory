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
        Schema::create('return_spares', function (Blueprint $table) {
            $table->id();
            $table->string('return_id');
            $table->date('return_date');
            $table->integer('request_id');
            $table->integer('sparepart_id');
            $table->integer('spare_qty');
            $table->integer('user_id');
            $table->string('follow_up')->nullable();
            $table->string('remark')->nullable();
            $table->date('date_repaire')->nullable();
            $table->string('serial_part')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('status_id')->default(7);
            $table->integer('usage_id')->nullable();
            $table->integer('defect_id')->nullable();
            $table->integer('engineer_id')->nullable();
            $table->timestamps();
;
            $table->foreign('engineer_id')
                ->references('id')
                ->on('engineers')
                ->onDelete('CASCADE');
            $table->foreign('usage_id')
                ->references('id')
                ->on('usage')
                ->onDelete('CASCADE');
            $table->foreign('defect_id')
                ->references('id')
                ->on('defect_types')
                ->onDelete('CASCADE');
            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('CASCADE');
            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('CASCADE');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_spares');
    }
};
