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
        Schema::create('banklocations', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_name_id');
            $table->integer('site_name_id');
            $table->string('siteID')->unique();
            $table->text('address', 1000);
            $table->timestamps();
            $table->foreign('bank_name_id')
                ->references('id')
                ->on('customers')
                ->onDelete('CASCADE');
            $table->foreign('site_name_id')
                ->references('id')
                ->on('sites')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banklocations');
    }
};
