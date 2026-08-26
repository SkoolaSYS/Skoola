<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // student reference
            $table->string('grade');       // example: Tingkatan 3, Darjah 4
            $table->string('class_name');             // example: 3A, 4B
            $table->string('subject');                // subject chosen
            $table->enum('status', [
                'Present',
                'Absent',
                'Late',
                'MC',
                'Unwell',
                'School Activity',
                'Others'
            ]);
            $table->timestamp('attendance_time')->useCurrent(); // time when saved
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')->on('students')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_attendances');
    }
};

