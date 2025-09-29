<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('cod_usuario');
            $table->string('email_usuario')->unique();
            $table->string('senha_usuario');
            $table->integer('role_usuario')->default(0); // 0=user, 1=admin
            $table->string('nome_usuario');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
