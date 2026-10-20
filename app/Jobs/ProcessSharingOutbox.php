<?php

namespace App\Jobs;

use App\Models\SharingBlock;
use App\Models\SharingDevice;
use App\Models\SharingOutboxEvent;
use App\Models\TicketShare;
use App\Services\FcmNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class ProcessSharingOutbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public array $backoff = [30, 120, 600, 1800];

    public function __construct(private readonly string $eventId) {}

    public function handle(FcmNotificationService $fcm): void
    {
        $event = SharingOutboxEvent::where('event_id', $this->eventId)->first();
        if (!$event || $event->status === 'sent') return;
        $share = $event->share_id ? TicketShare::where('share_id', $event->share_id)->first() : null;

        if ($share && $share->recipient_uid && SharingBlock::where(fn ($q) => $q->where(['blocker_uid' => $share->owner_uid, 'blocked_uid' => $share->recipient_uid])->orWhere(['blocker_uid' => $share->recipient_uid, 'blocked_uid' => $share->owner_uid]))->exists()) {
            $event->update(['status' => 'suppressed']);
            return;
        }

        if ($event->type === 'share_invitation_email') {
            if (!$share?->recipient_email_encrypted) { $event->update(['status' => 'suppressed']); return; }
            Mail::raw('Bạn có lời mời chia sẻ mới trên Lotto Live. Mở ứng dụng sau khi đăng ký hoặc đăng nhập để xem chi tiết.', function ($message) use ($share) {
                $message->to(Crypt::decryptString($share->recipient_email_encrypted))->subject('Lời mời mới từ Lotto Live');
            });
            $event->update(['status' => 'sent', 'sent_at' => now(), 'attempts' => $event->attempts + 1]);
            return;
        }

        $type = $event->type === 'share_result' ? 'share_result' : ($event->type === 'share_invitation' ? 'share_invitation' : 'share_updated');
        $title = $type === 'share_invitation' ? 'Bạn có lời mời chia sẻ mới' : ($type === 'share_result' ? 'Kết quả vé chia sẻ đã cập nhật' : 'Lời mời chia sẻ đã cập nhật');
        $body = 'Mở Lotto Live để xem chi tiết.';
        $success = true;
        foreach (SharingDevice::where('firebase_uid', $event->recipient_uid)->get() as $device) {
            $delivery = $fcm->sendToTokenDetailed($device->fcm_token, ['title' => $title, 'body' => $body], [
                'type' => $type, 'share_id' => (string) $event->share_id,
                'event_id' => (string) $event->event_id, 'result_version' => (string) $event->result_version,
            ]);
            if ($delivery === 'invalid') $device->delete();
            $success = $delivery !== 'failed' && $success;
        }
        $event->update(['status' => $success ? 'sent' : 'pending', 'sent_at' => $success ? now() : null, 'attempts' => $event->attempts + 1]);
        if (!$success) $this->release(60);
    }

    public function failed(\Throwable $exception): void
    {
        SharingOutboxEvent::where('event_id', $this->eventId)->where('status', 'pending')->update(['status' => 'failed']);
    }
}
