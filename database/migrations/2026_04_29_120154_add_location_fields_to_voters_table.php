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
        Schema::table('voters', function (Blueprint $table) {
            // foreignId কলাম তৈরি এবং কনস্ট্রেইন্ট একসাথে যোগ করা
            $table->foreignId('division_id')->nullable()->after('id')->constrained('divisions')->onDelete('set null');
            $table->foreignId('district_id')->nullable()->after('division_id')->constrained('districts')->onDelete('set null');
            $table->foreignId('upazila_id')->nullable()->after('district_id')->constrained('upazilas')->onDelete('set null');
            $table->foreignId('union_id')->nullable()->after('upazila_id')->constrained('unions')->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->after('union_id')->constrained('wards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            // ড্রপ করার সময় ফরেন কি ড্রপ করা আগে প্রয়োজন (লারাভেল অটোমেটিক ইনডেক্স নেম হ্যান্ডেল করে)
            $table->dropForeign(['division_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['upazila_id']);
            $table->dropForeign(['union_id']);
            $table->dropForeign(['ward_id']);

            // কলামগুলো ড্রপ করা
            $table->dropColumn(['division_id', 'district_id', 'upazila_id', 'union_id', 'ward_id']);
        });
    }
};
