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
        Schema::table('students', function (Blueprint $table) {
        $table->string('birth_cert_no')->nullable();
        $table->date('dob')->nullable();
        $table->string('grade')->nullable();
        $table->enum('gender', ['male', 'female'])->nullable();
        $table->string('race')->nullable();
        $table->string('religion')->nullable();
        $table->string('nationality')->nullable();
        $table->boolean('orphan')->nullable();
        $table->string('address')->nullable();
        $table->boolean('oku')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
        $table->dropColumn([
            'birth_cert_no',
            'dob',
            'grade',
            'gender',
            'race',
            'religion',
            'nationality',
            'orphan',
            'address',
            'oku',
        ]);
    });
    }
};
