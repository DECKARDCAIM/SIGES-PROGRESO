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
            $table->string('avatar')->nullable()->after('estado');
            $table->string('avatar_url')->nullable()->after('avatar');
            $table->string('banner')->nullable()->after('avatar_url');
            $table->string('banner_url')->nullable()->after('banner');
            $table->string('phone')->nullable()->after('banner_url');
            $table->string('department')->nullable()->after('phone');
            $table->string('company')->nullable()->after('department');
            $table->string('location')->nullable()->after('company');
            $table->text('about')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'avatar_url', 'banner', 'banner_url', 'phone', 'department', 'company', 'location', 'about']);
        });
    }
};

