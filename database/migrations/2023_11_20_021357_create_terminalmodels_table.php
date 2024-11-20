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
        Schema::create('terminalmodels', function (Blueprint $table) {
            $table->id();
            $table->integer('terminaltype_id');
            $table->string('terminal_model')->unique();
            $table->timestamps();
            $table->foreign('terminaltype_id')
            ->references('id')
            ->on('terminaltypes')
            ->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminalmodels');
    }
};
