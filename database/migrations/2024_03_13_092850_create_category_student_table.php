<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryStudentTable extends Migration
{
    public function up()
    {
        Schema::create('category_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('mark');
            $table->float('attendance_marks');
            $table->float('assessment_marks');
            $table->integer('number_of_assessment');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_student');
    }
}
