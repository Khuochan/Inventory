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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('dept_id')->nullable();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('name');
            $table->integer('em_id')->unique();
            $table->string('family_status')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->string('password')->nullable();
            $table->string('address')->nullable();
            $table->date('start_date')->nullable();
            $table->string('family_phone')->nullable();
            $table->string('family_name')->nullable();
            $table->string('family_member')->nullable();
            $table->timestamps();
            $table->rememberToken();
            $table->boolean('active')->default(true);

            $table->foreign('dept_id')
            ->references('id')
            ->on('departments')
            ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
