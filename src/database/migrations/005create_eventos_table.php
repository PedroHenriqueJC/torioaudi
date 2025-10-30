<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {   
        Schema::create('evento', function (Blueprint $table) {
            $table->increments('cod_evento');
            $table->timestamp('evento_inicio');
            $table->timestamp('evento_fim');
            $table->string('nome_evento');
            $table->text('descricao_evento')->nullable();
            $table->softDeletes();
            $table->boolean('pre_agenda_evento')->default(false);

            // FKs
            $table->unsignedInteger('usuario_cod_usuario');
            $table->unsignedInteger('sala_cod_sala');

            $table->foreign('usuario_cod_usuario')->references('cod_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('sala_cod_sala')->references('cod_sala')->on('sala')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento');
    }
};
