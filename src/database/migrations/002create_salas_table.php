<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sala', function (Blueprint $table) {
            $table->increments('cod_sala');
            $table->string('nome_sala', 45);
            $table->integer('capacidade_sala');
            $table->text('descricao_sala')->nullable();
            $table->text('localizacao_sala')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salas');
    }
};
