<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poll_votes', function (Blueprint $table) {
            // Unique index for logged-in user votes
            $table->unique(['poll_id', 'user_id'], 'uq_poll_votes_login_user')
                ->where('user_id', '<>', null);
        });

        Schema::table('poll_votes', function (Blueprint $table) {
            // Unique index for guest votes (where user_id is null)
            $table->unique(['poll_id', 'ip_address'], 'uq_poll_votes_guest_user')
                ->where('user_id', null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poll_votes', function (Blueprint $table) {
            $table->dropUnique('uq_poll_votes_poll_user');
            $table->dropUnique('uq_poll_votes_poll_ip_guest');
        });
    }
};
