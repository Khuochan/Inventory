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
        Schema::create('request_spares', function (Blueprint $table) {
            $table->id();
            $table->string('request_name');
            $table->integer('stock_qty')->nullable();
            $table->integer('terminal_id');
            $table->integer('sparepart_id');
            $table->integer('spare_qty');
            $table->string('ticket_no');
            $table->integer('engineer_id');
            $table->string('remark')->nullable();
            $table->date('request_date');
            $table->integer('status_id')->default(1);
            $table->integer('request_status_id')->default(1);
            $table->timestamps();

            $table->foreign('terminal_id')
                ->references('id')
                ->on('terminals')
                ->onDelete('CASCADE');
            $table->foreign('sparepart_id')
                ->references('id')
                ->on('spareparts')
                ->onDelete('CASCADE');
            $table->foreign('engineer_id')
                ->references('id')
                ->on('engineers')
                ->onDelete('CASCADE');
            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('CASCADE');
            $table->foreign('request_status_id')
                ->references('id')
                ->on('request_statuses')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_spares');
    }
};
