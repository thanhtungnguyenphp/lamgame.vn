<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sharing_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_uid', 128)->unique();
            $table->string('public_id', 64)->unique();
            $table->string('display_name', 120);
            $table->string('email_hash', 64)->nullable()->index();
            $table->text('email_encrypted')->nullable();
            $table->boolean('email_discoverable')->default(true);
            $table->timestamp('email_verified_at');
            $table->timestamps();
        });

        Schema::create('sharing_recipient_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_hash', 64)->unique();
            $table->string('sender_uid', 128)->index();
            $table->string('recipient_uid', 128)->nullable()->index();
            $table->string('email_hash', 64)->nullable()->index();
            $table->text('email_encrypted')->nullable();
            $table->string('kind', 32);
            $table->string('display_label', 160);
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_shares', function (Blueprint $table) {
            $table->id();
            $table->string('share_id', 64)->unique();
            $table->string('owner_uid', 128)->index();
            $table->string('recipient_uid', 128)->nullable()->index();
            $table->string('recipient_email_hash', 64)->nullable()->index();
            $table->text('recipient_email_encrypted')->nullable();
            $table->string('recipient_label', 160);
            $table->string('client_ticket_id', 128);
            $table->string('number', 6);
            $table->string('province', 120);
            $table->string('province_code', 10);
            $table->string('region', 20);
            $table->date('draw_date');
            $table->string('image_path')->nullable();
            $table->string('image_sha256', 64)->nullable()->index();
            $table->unsignedTinyInteger('share_percent');
            $table->string('message', 300)->nullable();
            $table->string('terms_version', 40);
            $table->string('status', 32)->index();
            $table->string('result_status', 32)->default('pending');
            $table->boolean('result_final')->default(false);
            $table->json('matched_prizes')->nullable();
            $table->unsignedInteger('result_version')->default(0);
            $table->string('result_source_hash', 64)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('expires_at')->index();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->index(['owner_uid', 'client_ticket_id']);
            $table->index(['recipient_uid', 'created_at']);
            $table->index(['draw_date', 'region', 'status']);
        });

        // MySQL has no partial unique index. A row exists only while a share is active,
        // so this table makes the one-active-share invariant atomic.
        Schema::create('sharing_ticket_locks', function (Blueprint $table) {
            $table->id();
            $table->string('owner_uid', 128);
            $table->string('client_ticket_id', 128);
            $table->string('share_id', 64)->unique();
            $table->timestamps();
            $table->unique(['owner_uid', 'client_ticket_id']);
        });

        Schema::create('sharing_devices', function (Blueprint $table) {
            $table->id();
            $table->string('installation_id', 128)->unique();
            $table->string('firebase_uid', 128)->index();
            $table->string('fcm_token', 500)->unique();
            $table->string('platform', 16);
            $table->timestamps();
        });

        Schema::create('sharing_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('blocker_uid', 128);
            $table->string('blocked_uid', 128);
            $table->timestamps();
            $table->unique(['blocker_uid', 'blocked_uid']);
        });

        Schema::create('sharing_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reporter_uid', 128)->index();
            $table->string('share_id', 64)->index();
            $table->string('reason', 32);
            $table->json('moderation_snapshot');
            $table->string('status', 24)->default('pending');
            $table->timestamps();
            $table->unique(['reporter_uid', 'share_id']);
        });

        Schema::create('sharing_idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_uid', 128);
            $table->string('route', 160);
            $table->string('key_hash', 64);
            $table->string('payload_hash', 64);
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->json('response_body')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
            $table->unique(['firebase_uid', 'route', 'key_hash'], 'sharing_idempotency_scope_unique');
        });

        Schema::create('sharing_outbox_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 64)->unique();
            $table->string('share_id', 64)->nullable()->index();
            $table->string('recipient_uid', 128)->nullable()->index();
            $table->string('type', 40);
            $table->unsignedInteger('result_version')->default(0);
            $table->json('payload');
            $table->string('dedup_key', 191)->unique();
            $table->string('status', 20)->default('pending');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('available_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sharing_audit_events', function (Blueprint $table) {
            $table->id();
            $table->string('share_id', 64)->nullable()->index();
            $table->string('actor_uid', 128)->nullable()->index();
            $table->string('type', 50);
            $table->json('metadata')->nullable();
            $table->uuid('request_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sharing_audit_events');
        Schema::dropIfExists('sharing_outbox_events');
        Schema::dropIfExists('sharing_idempotency_keys');
        Schema::dropIfExists('sharing_reports');
        Schema::dropIfExists('sharing_blocks');
        Schema::dropIfExists('sharing_devices');
        Schema::dropIfExists('sharing_ticket_locks');
        Schema::dropIfExists('ticket_shares');
        Schema::dropIfExists('sharing_recipient_tokens');
        Schema::dropIfExists('sharing_profiles');
    }
};
