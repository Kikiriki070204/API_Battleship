<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MyEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $message;
  public $partida_id;

  public function __construct($message, $partida_id)
  {
      $this->message = $message;
      $this->partida_id = $partida_id;
  }

  public function broadcastOn()
  {
      return ["my-channel-{$this->partida_id}"];
  }

  public function broadcastAs()
  {
      return 'my-event';
  }
}
