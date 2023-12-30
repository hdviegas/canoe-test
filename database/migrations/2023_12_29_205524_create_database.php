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
        Schema::create('fund_managers', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->timestamps();
        });

        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fund_manager_id');
            $table->text('name');
            $table->integer('start_year');
            $table->json('aliases');
            $table->timestamps();
            $table->foreign('fund_manager_id')->references('id')->on('fund_managers');

        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->timestamps();
        });

        Schema::create('fund_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fund_id');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();
            $table->foreign('fund_id')->references('id')->on('funds');
            $table->foreign('company_id')->references('id')->on('companies');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_companies');
        Schema::dropIfExists('funds');
        Schema::dropIfExists('fund_managers');
        Schema::dropIfExists('companies');
    }
};
