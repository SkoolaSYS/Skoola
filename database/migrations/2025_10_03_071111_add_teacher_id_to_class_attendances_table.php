<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->after('student_id');

            // If you already have a teachers table:
            // $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('class_attendances', function (Blueprint $table) {
            $table->dropColumn('teacher_id');
        });
    }
};
