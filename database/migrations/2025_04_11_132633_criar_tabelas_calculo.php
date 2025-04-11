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
        Schema::create('processos', function (Blueprint $table){
            $table->id();
            $table->string('nup', length:20);
            $table->string('interessado', length:255);
            $table->string('matricula', length:10);
            $table->string('cargo', length:255);
            $table->text('assunto');
            $table->timestamp('criado_em');

        });

        Schema::create('calculos', function(Blueprint $table){
            $table->id();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->boolean('tem_ipcae')->default(false);
            $table->double(column: 'total')->nullable();
            
            $table->foreignId('processo_id');
        });

        Schema::create('registros', function(Blueprint $table){
            $table->id();
            $table->date('periodo');
            $table->date('periodo_fim')->nullable();
            $table->enum('tipo_periodo', ['normal', 'ferias', 'decimo_terceiro', 'outro']);
            $table->string('descricao', length:255);
            $table->string('proporcional', length:20)->default('1/1');
            
            $table->foreignId('calculo_id');
            $table->foreignId('secao_pago_id');
            $table->foreignId('secao_devido_id');

        });

        Schema::create('secoes', function(Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['pago', 'devido']);
            
            $table->foreignId('vencimento_id');
        });

        Schema::create('eventos_calculo', function(Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->date('data_direito')->nullable();
            $table->date('data_fim')->nullable();
            $table->enum('tipo_secao', ['pago', 'devido']);
            $table->boolean('previdenciario')->default(true);
            
            $table->foreignId('calculo_id');
            $table->foreignId('evento_id');

        });

        Schema::create('eventos_secoes', function(Blueprint $table){
            $table->foreignId('evento_calculo_id');
            $table->foreignId('secao_id');
            
            $table->string('proporcional', length:20)->default('1/1');
        });

        Schema::create('formulas_adicionais', function(Blueprint $table){
            $table->id();
            $table->enum('tipo', ['noturno', 'hora_extra']);
            $table->enum('tipo_periodo', ['normal', 'ferias', 'decimo_terceiro', 'outro']);
            $table->date('data_inicio')->nullable();
            $table->integer('horas_mensais')->default(180);
            
            $table->foreignId('calculo_id');

        });

        Schema::create('adicionais_eventos', function(Blueprint $table){
            $table->foreignId('adicional_id');
            $table->foreignId('evento_id');
            
        });

        Schema::create('adicionais_registros', function(Blueprint $table){
            $table->id();
            $table->double('horas');
            $table->date('data_inicio');

            $table->foreignId('registro_id')->nullable();
            $table->foreignId('adicional_id');
        });

        Schema::create('progressoes_calculo', function(Blueprint $table){
            $table->id();
            $table->date('data_direito')->nullable();
            $table->enum('tipo_secao', ['pago', 'devido']);
            
            $table->foreignId('calculo_id');
            $table->foreignId('tabela_id');
            $table->foreignId('progressao_id');

        });

        Schema::create('ferias', function(Blueprint $table){
            $table->id();
            $table->date('data_direito');
            
            $table->foreignId('registro_id')->nullable();
            $table->foreignId("calculo_id");
        });


        Schema::create('descontos', function(Blueprint $table){
            $table->id();
            $table->text('descricao');
            $table->double('valor');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('processos');
        Schema::drop('calculos');
        Schema::drop('registros');
        Schema::drop('secoes');
        Schema::drop('eventos_calculo');
        Schema::drop('eventos_secoes');
        Schema::drop('formulas_adicionais');
        Schema::drop('adicionais_eventos');
        Schema::drop('adicionais_registros');
        Schema::drop('progressoes_calculo');
        Schema::drop('ferias');
        Schema::drop('descontos');
    }
};
