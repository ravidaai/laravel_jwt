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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->date('dob')->after('last_name');
            $table->enum('gender', ['male', 'female', 'other'])->after('dob');
            $table->string('username')->unique()->after('gender');
            $table->boolean('privacy_policy_agreement')->default(false)->after('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'dob', 'gender', 'username', 'privacy_policy_agreement']);
        });
    }
};
