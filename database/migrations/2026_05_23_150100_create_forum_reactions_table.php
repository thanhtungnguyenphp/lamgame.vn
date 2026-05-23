<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_reactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('reactable'); // reactable_type, reactable_id
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('voter_identifier', 100);
            $table->string('type', 20); // like, love, fire, think, game
            $table->timestamps();

            $table->unique(['reactable_type', 'reactable_id', 'voter_identifier'], 'unique_reaction');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_reactions');
    }
};
