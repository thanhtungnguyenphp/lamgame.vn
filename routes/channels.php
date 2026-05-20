<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('forum.post.{postId}', fn ($user, $postId) => (bool) $user);
Broadcast::channel('user.{userId}', fn ($user, $userId) => (int) $user->id === (int) $userId);

// Sport channels are public (no auth needed) — mobile subscribes directly
