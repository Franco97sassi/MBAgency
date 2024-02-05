<?php

namespace App\Imports;

use App\Models\Influencer;
use App\Models\Week;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WeekImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $influencers, $file_id;
    function __construct($file_id)
    {
        $this->influencers = Influencer::pluck('id', 'code');
        $this->file_id = $file_id;
    }
    public function model(array $row)
    {
        return new Week([
            'influencer_id' => $this->influencers[$row['id']],
            'file_id' => $this->file_id,
            'nivel' => $row['nivel'],
            'group_name' => $row['group_name'],
            'agent' => $row['agent'],
            'country' => $row['country'],
            'gift_coins' => $row['gift_coins'],
            'non_friend_video_coins' => $row['non_friend_video_coins'],
            'friend_video_coins' => $row['friend_video_coins'],
            'task_coins' => $row['task_coins'],
            'sign_coins' => $row['sign_coins'],
            'total_coins' => $row['total_coins'],
            'leftover_first' => $row['leftover_first'],
            'adding_deduction_first' => $row['adding_deduction_first'],
            'subtotal_first' => $row['subtotal_first'],
            'leftover_second' => $row['leftover_second'],
            'adding_deduction_second' => $row['adding_deduction_second'],
            'subtotal_second' => $row['subtotal_second'],
            'leftover_third' => $row['leftover_third'],
            'adding_deduction_third' => $row['adding_deduction_third'],
            'subtotal_third' => $row['subtotal_third'],
            'calculation_week' => $row['calculation_week'],
            'final_coins' => $row['final_coins'],
            'pay_before_infraction' => $row['pay_before_infraction'],
            'infractions' => $row['infractions'],
            'weekly_reward_base' => $row['weekly_reward_base'],
            'rank_mundial' => $row['rank_mundial'],
            'rank_pais' => $row['rank_pais'],
            'rank_finde' => $row['rank_finde'],
            'pago_final' => $row['pago_final'],
            'menor_edad' => $row['menor_edad'],
            'agent_bonus' => $row['agent_bonus'],
            'metodo_pago' => $row['metodo_pago'],
            'payment_account_name_unique' => $row['payment_account_name_unique'],
            'config_pay' => $row['config_pay'],
            'host_before' => $row['host_before'],
            'bonus_before' => $row['bonus_before'],
            'duracion_llamada' => $row['duracion_llamada'],
            'group_time' => $row['group_time'],
            'country_pay' => $row['country_pay'],
        ]);
    }

     // Inserciones por lotes
     public function batchSize(): int
     {
         return 1000;
     }
 
     // lectura de fragmentos
     public function chunkSize(): int
     {
         return 1000;
     }
}
