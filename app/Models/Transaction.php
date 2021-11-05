<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'amount',
        'status',
        'from',
        'to',
    ];

    public function from_account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from', 'account');
    }

    public function to_account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to', 'account');
    }
}
