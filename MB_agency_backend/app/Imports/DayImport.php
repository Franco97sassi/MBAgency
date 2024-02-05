<?php

namespace App\Imports;

use App\Models\Day;
use App\Models\Influencer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DayImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $influencers, $file_id;
    function __construct($file_id)
    {
        $this->influencers = Influencer::pluck('id', 'code');
        $this->file_id = $file_id;
    }
    public function model(array $row)
    {
        return new Day([
            'influencer_id' => $this->influencers[$row['id']],
            'file_id' => $this->file_id,
            'group_name' => $row['group_name'],
            'is_have_story' => $row['is_have_story'],
            'gift_coins' => $row['gift_coins'],
            'non_friend_video_coins' => $row['non_friend_video_coins'],
            'friend_video_coins' => $row['friend_video_coins'],
            'task_coins' => $row['task_coins'],
            'total_coins' => $row['total_coins'],
            'group_time' => $row['group_time'],
            'match_count' => $row['match_count'],
            'match_times_duration' => $row['match_times_duration'],
            'kyc_pass' => $row['kyc_pass'],
            'video_status' => $row['video_status'],
            'category' => $row['category'],
            'avg_friend_call_video_time' => $row['avg_friend_call_video_time'],
            'bank_country_ab' => $row['bank_country_ab'],
            'long_call_ratio' => $row['long_call_ratio'],
            'total_coins_end' => $row['total_coins_end'],
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
