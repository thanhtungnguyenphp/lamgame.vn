<?php

namespace App\Services\Forum;

use App\Models\ForumBlock;
use App\Models\ForumConversation;
use App\Models\ForumMessage;
use Illuminate\Support\Facades\RateLimiter;

class ForumMessageService
{
    public function getConversations(int $userId, int $perPage = 20)
    {
        return ForumConversation::forUser($userId)
            ->orderByDesc('last_message_at')
            ->paginate($perPage);
    }

    public function getMessages(int $conversationId, int $userId, int $perPage = 30)
    {
        $conv = ForumConversation::findOrFail($conversationId);
        if ($conv->participant_1 !== $userId && $conv->participant_2 !== $userId) {
            abort(403);
        }

        // Mark as read
        ForumMessage::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return ForumMessage::where('conversation_id', $conversationId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findOrCreateConversation(int $userId, int $targetId): array
    {
        if ($userId === $targetId) return ['error' => 'Không thể nhắn cho chính mình'];
        if (ForumBlock::isBlocked($userId, $targetId)) return ['error' => 'Bạn đã bị chặn'];

        $ids = [min($userId, $targetId), max($userId, $targetId)];
        $conv = ForumConversation::firstOrCreate(
            ['participant_1' => $ids[0], 'participant_2' => $ids[1]]
        );

        return ['conversation' => $conv];
    }

    public function sendMessage(int $conversationId, int $senderId, string $content): array
    {
        $key = "forum_pm:{$senderId}";
        if (RateLimiter::tooManyAttempts($key, 20)) {
            return ['error' => 'Quá giới hạn 20 tin nhắn/giờ'];
        }
        RateLimiter::hit($key, 3600);

        $conv = ForumConversation::findOrFail($conversationId);
        if ($conv->participant_1 !== $senderId && $conv->participant_2 !== $senderId) {
            abort(403);
        }

        $targetId = $conv->getOtherParticipant($senderId);
        if (ForumBlock::isBlocked($senderId, $targetId)) {
            return ['error' => 'Bạn đã bị chặn'];
        }

        $message = ForumMessage::create([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'content' => $content,
            'created_at' => now(),
        ]);

        $conv->update(['last_message_at' => now()]);

        return ['message' => $message];
    }

    public function markRead(int $conversationId, int $userId): void
    {
        ForumMessage::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function block(int $blockerId, int $blockedId): void
    {
        ForumBlock::firstOrCreate(['blocker_id' => $blockerId, 'blocked_id' => $blockedId, 'created_at' => now()]);
    }

    public function unblock(int $blockerId, int $blockedId): void
    {
        ForumBlock::where('blocker_id', $blockerId)->where('blocked_id', $blockedId)->delete();
    }
}
