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
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title')->nullable();
            $table->string('forename')->nullable();
            $table->string('surname')->nullable();
            $table->boolean('current');
        });

        Schema::create('class_employees', function (Blueprint $table) {
            $table->id();
            $table->string('class_id');
            $table->string('employee_id');
            $table->boolean('is_primary');

            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->unique(['class_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('class_employees');
    }
};
