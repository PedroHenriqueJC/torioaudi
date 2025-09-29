<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamento_evento', function (Blueprint $table) {
            $table->unsignedInteger('equipamento_cod_equipamento');
            $table->unsignedInteger('evento_cod_evento');
            $table->integer('quantidade_equipamento_emprestado')->default(0);

            $table->primary(['equipamento_cod_equipamento', 'evento_cod_evento']);

            $table->foreign('equipamento_cod_equipamento')->references('cod_equipamento')->on('equipamentos')->onDelete('cascade');
            $table->foreign('evento_cod_evento')->references('cod_evento')->on('eventos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamento_evento');
    }
};
