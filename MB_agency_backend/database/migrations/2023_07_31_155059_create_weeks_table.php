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
        Schema::create('weeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('influencer_id')->nullable()->constrained();
            $table->foreignId('file_id')->nullable()->constrained();
            $table->string('nivel')->nullable();
            $table->string('group_name')->nullable();
            $table->string('agent')->nullable();
            $table->string('country')->nullable();
            $table->string('gift_coins')->nullable();
            $table->string('non_friend_video_coins')->nullable();
            $table->string('friend_video_coins')->nullable();
            $table->string('task_coins')->nullable();
            $table->string('sign_coins')->nullable();
            $table->string('total_coins')->nullable();
            $table->string('leftover_first')->nullable();
            $table->string('adding_deduction_first')->nullable();
            $table->string('subtotal_first')->nullable();
            $table->string('leftover_second')->nullable();
            $table->string('adding_deduction_second')->nullable();
            $table->string('subtotal_second')->nullable();
            $table->string('leftover_third')->nullable();
            $table->string('adding_deduction_third')->nullable();
            $table->string('subtotal_third')->nullable();
            $table->string('calculation_week')->nullable();
            $table->string('final_coins')->nullable();
            $table->string('pay_before_infraction')->nullable();
            $table->string('infractions')->nullable();
            $table->string('weekly_reward_base')->nullable();
            $table->string('rank_mundial')->nullable();
            $table->string('rank_pais')->nullable();
            $table->string('rank_finde')->nullable();
            $table->string('pago_final')->nullable();
            $table->string('menor_edad')->nullable();
            $table->string('agent_bonus')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('payment_account_name_unique')->nullable();
            $table->string('config_pay')->nullable();
            $table->string('host_before')->nullable();
            $table->string('bonus_before')->nullable();
            $table->string('duracion_llamada')->nullable();
            $table->string('group_time')->nullable();
            $table->string('country_pay')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weeks');
    }
};
