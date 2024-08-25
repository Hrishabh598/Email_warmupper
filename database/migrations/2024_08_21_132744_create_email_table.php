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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('mailer');
            $table->string('host');
            $table->integer('port_no')->default(587);
            $table->string('username');
            $table->string("password");
            $table->string('encryption')->default('tls');
            $table->integer('sent')->default(0);
            $table->integer('next_in')->default(0);
            $table->integer('landed_in_spam')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
