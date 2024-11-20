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
        Schema::create('addreplaces', function (Blueprint $table) {
            $table->id();
            $table->integer('terminal_id');
            $table->integer('sparepart_id');
            $table->date('replace_date');
            $table->integer('engineer_id');
            $table->integer('quantity');
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addreplaces');
    }
};
