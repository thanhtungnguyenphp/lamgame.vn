<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumConversation extends Model
{
    protected $fillable = ['participant_1', 'participant_2', 'last_message_at'];
    protected $casts = ['last_message_at' => 'datetime'];

    public function messages() { return $this->hasMany(ForumMessage::class, 'conversation_id'); }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('participant_1', $userId)->orWhere('participant_2', $userId);
    }

    public function getOtherParticipant(int $userId): int
    {
        return $this->participant_1 === $userId ? $this->participant_2 : $this->participant_1;
    }
}
