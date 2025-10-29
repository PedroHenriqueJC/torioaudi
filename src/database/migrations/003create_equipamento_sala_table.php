<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamento_sala', function (Blueprint $table) {
            $table->unsignedInteger('sala_cod_sala');
            $table->unsignedInteger('equipamento_cod_equipamento');
            $table->integer('quantidade_equipamento')->default(0);

            $table->primary(['sala_cod_sala', 'equipamento_cod_equipamento']);

            $table->foreign('sala_cod_sala')->references('cod_sala')->on('sala')->onDelete('cascade');
            $table->foreign('equipamento_cod_equipamento')->references('cod_equipamento')->on('equipamento')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamento_sala');
    }
};
