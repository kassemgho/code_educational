<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemsTable extends Migration
{
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('teacher_code_solve');
            $table->float('time_limit_ms')->default(0.0) ;
            $table->boolean('active')->default(0);
            $table->text('hint1')->nullable();
            $table->text('hint2')->nullable();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('problems');
    }
}
