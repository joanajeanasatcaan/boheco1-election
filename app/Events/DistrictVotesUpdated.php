<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DistrictVotesUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $district;
    public $votes_count;

    public function __construct($district, $votes_count)
    {
        $this->district = $district;
        $this->votes_count = $votes_count;
    }

    public function broadcastWith(): array
    {
        return [
            'district'    => $this->district,
            'votes_count' => (int) $this->votes_count,
        ];
}
}