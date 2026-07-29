<?php

namespace App\Services;

use Aws\Ivs\IvsClient;
use Illuminate\Support\Facades\Log;

class IVSService
{
    /**
     * @var IvsClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = new IvsClient([
            'region' => config('ivs.region'),
            'version' => config('ivs.version'),
            'credentials' => [
                'key' => config('ivs.key'),
                'secret' => config('ivs.secret'),
            ],
        ]);
    }

    /**
     * Create a new IVS channel for a classroom.
     * Returns an array with 'stream_key' and 'playback_url'.
     */
    public function createChannel(string $name): array
    {
        try {
            $result = $this->client->createChannel([
                'name' => $name,
                'type' => 'STANDARD',
            ]);

            $channel = $result['channel'];
            $ingest = $channel['ingestEndpoints'][0] ?? null;
            $streamKey = $ingest['password'] ?? null;
            $playbackUrl = $channel['playbackUrl'] ?? null;

            return [
                'stream_key' => $streamKey,
                'playback_url' => $playbackUrl,
                'channel_arn' => $channel['arn'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('IVS createChannel failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Stop (delete) a channel.
     */
    public function deleteChannel(string $arn): void
    {
        try {
            $this->client->deleteChannel(['arn' => $arn]);
        } catch (\Exception $e) {
            Log::error('IVS deleteChannel failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
