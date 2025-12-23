<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceGameWithdrawal extends Model
{
    protected $fillable = [
        'seller_id',
        'amount',
        'status',
        'bank_name',
        'bank_account',
        'bank_holder',
        'note',
        'admin_note',
        'transaction_id',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(SourceGameSeller::class, 'seller_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
