<?php

use App\Broadcasting\OrderChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('order.{id}', OrderChannel::class);
