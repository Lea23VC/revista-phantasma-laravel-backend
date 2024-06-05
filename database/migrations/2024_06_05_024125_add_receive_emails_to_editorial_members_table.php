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
        Schema::table('editorial_members', function (Blueprint $table) {
            $table->boolean('receive_emails')->default(false)->after('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('editorial_members', function (Blueprint $table) {
            //
            $table->dropColumn('receive_emails');
        });
    }
};
