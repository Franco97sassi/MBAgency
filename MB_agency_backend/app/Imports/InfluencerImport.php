<?php

namespace App\Imports;

use App\Models\Influencer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InfluencerImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $influencers;

    public function __construct()
    {
        $this->influencers = Influencer::pluck('code')->toArray();
    }

    public function model(array $row)
    {
        if (!in_array($row['id'], $this->influencers)) {
            array_push($this->influencers, $row['id']);
            return new Influencer([
                'code' => $row['id'],
                'username' => $row['user_name']
            ]);
        }
        return null;
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
