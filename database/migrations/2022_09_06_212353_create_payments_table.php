<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->float('value');
            $table->unsignedBigInteger('table_id');
            $table->integer('client');
            $table->enum('payment', ['dinheiro', 'débito', 'crédito', 'pix']);
            $table->foreign('table_id')->references('id')->on('tables');
            $table->dateTime('time')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
