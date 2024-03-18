<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('subject_teacher_id')->constrained('subject_teacher')->onDelete('cascade');
            $table->integer('number_of_lessons');
            $table->integer('number_of_ratings');
            $table->float('mark_of_commings');
            $table->float('mark_of_ratings');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
