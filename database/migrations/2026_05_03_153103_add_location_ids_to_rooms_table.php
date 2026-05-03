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
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('division_id')->nullable()->after('id');
            $table->unsignedBigInteger('district_id')->nullable()->after('division_id');
            $table->unsignedBigInteger('upazila_id')->nullable()->after('district_id');
            $table->unsignedBigInteger('union_id')->nullable()->after('upazila_id');
            $table->unsignedBigInteger('ward_id')->nullable()->after('union_id');
            $table->unsignedBigInteger('village_id')->nullable()->after('ward_id');

            // Foreign key constraints
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('upazila_id')->references('id')->on('upazilas')->onDelete('set null');
            $table->foreign('union_id')->references('id')->on('unions')->onDelete('set null');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('set null');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['division_id', 'district_id', 'upazila_id', 'union_id', 'ward_id', 'village_id']);
            $table->dropColumn(['division_id', 'district_id', 'upazila_id', 'union_id', 'ward_id', 'village_id']);
        });
    }
};
