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
        Schema::create('coop_programs', function (Blueprint $table) {
            $table->id();
            $table->string('coop_id');
            $table->foreign('coop_id')->references('id')->on('cooperatives')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('program_status', ['Finished','Ongoing']);
            $table->string('number')->nullable();
            $table->string('email');
            $table->integer('loan_ammount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coop_programs');
    }
};