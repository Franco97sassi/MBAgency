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
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('influencer_id')->nullable()->constrained();
            $table->foreignId('file_id')->nullable()->constrained();
            $table->string('group_name')->nullable();
            $table->string('is_have_story')->nullable();
            $table->string('gift_coins')->nullable();
            $table->string('non_friend_video_coins')->nullable();
            $table->string('friend_video_coins')->nullable();
            $table->string('task_coins')->nullable();
            $table->string('total_coins')->nullable();
            $table->string('group_time')->nullable();
            $table->string('match_count')->nullable();
            $table->string('match_times_duration')->nullable();
            $table->string('kyc_pass')->nullable();
            $table->string('video_status')->nullable();
            $table->string('category')->nullable();
            $table->string('avg_friend_call_video_time')->nullable();
            $table->string('bank_country_ab')->nullable();
            $table->string('long_call_ratio')->nullable();
            $table->string('total_coins_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('days');
    }
};
