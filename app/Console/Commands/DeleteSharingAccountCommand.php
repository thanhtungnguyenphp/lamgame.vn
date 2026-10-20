<?php

namespace App\Console\Commands;

use App\Models\SharingDevice;
use App\Models\SharingProfile;
use App\Models\TicketShare;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteSharingAccountCommand extends Command
{
    protected $signature = 'sharing:delete-account {firebase_uid : Firebase UID from the verified account-deletion webhook}';
    protected $description = 'Revoke Lotto Live sharing access and private media after a Firebase account deletion.';

    public function handle(): int
    {
        $uid = $this->argument('firebase_uid');
        DB::transaction(function () use ($uid) {
            SharingDevice::where('firebase_uid', $uid)->delete();
            TicketShare::where(fn ($q) => $q->where('owner_uid', $uid)->orWhere('recipient_uid', $uid))
                ->lockForUpdate()->get()->each(function (TicketShare $share) use ($uid) {
                    // Never delete accepted audit rows. Revoke image/message access instead;
                    // the audit record remains for the documented retention period.
                    if ($share->image_path) Storage::disk(config('sharing.image_disk'))->delete($share->image_path);
                    $share->update([
                        'image_path' => null, 'message' => null,
                        'status' => in_array($share->status, ['awaiting_registration', 'pending'], true) ? 'cancelled' : $share->status,
                        'cancelled_at' => in_array($share->status, ['awaiting_registration', 'pending'], true) ? now() : $share->cancelled_at,
                        'recipient_uid' => $share->recipient_uid === $uid ? null : $share->recipient_uid,
                    ]);
                    DB::table('sharing_ticket_locks')->where('share_id', $share->share_id)->delete();
                });
            SharingProfile::where('firebase_uid', $uid)->delete();
            DB::table('sharing_blocks')->where('blocker_uid', $uid)->orWhere('blocked_uid', $uid)->delete();
        });
        $this->info('Sharing account access revoked.');
        return self::SUCCESS;
    }
}
