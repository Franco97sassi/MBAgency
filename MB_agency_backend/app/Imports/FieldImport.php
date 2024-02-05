<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Field;
use App\Models\Group;
use App\Models\Influencer;
use App\Models\StatusVideo;
use App\Models\Story;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FieldImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $groups, $statusVideos, $stories, $influencers, $categories, $file_id;


    public function __construct($file_id)
    {
        $this->groups = Group::pluck('id', 'name');
        $this->statusVideos = StatusVideo::pluck('id', 'name');
        $this->stories = Story::pluck('id', 'name');
        $this->influencers = Influencer::pluck('id', 'code');
        $this->categories = Category::pluck('id', 'name');
        $this->file_id = $file_id;
    }



    public function model(array $row)
    {
        return new Field([
            'influencer_id' => $this->influencers[$row['id']],
            'group_id' => $this->groups[$row['group_name']],
            'story_id' => $this->stories[$row['is_have_story']],
            'gift_coins' => $row['gift_coins'],
            'host_wall_coins' => $row['host_wall_coins'],
            'friend_video_coins' => $row['friend_video_coins'],
            'task_coins' => $row['task_coins'],
            'box_coins' => $row['box_coins'],
            'total_coins' => $row['total_coins'],
            'group_time' => $row['group_time'],
            'match_count' => $row['match_count'],
            'match_times_duration' => $row['match_times_duration'],
            'kyc_pass' => $row['kyc_pass'],
            'status_video_id' => $this->statusVideos[$row['video_status']],
            'category_id' => $this->categories[$row['category']],
            'avg_friend_call_video_time_30days' => $row['avg_friend_call_video_time_30days'],
            'bank_country_ab' => $row['bank_country_ab'],
            'long_call_ratio' => $row['long_call_ratio'],
            'total_coins_x' => $row['total_coins_apr'],
            'file_id' => $this->file_id
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
