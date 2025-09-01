<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'address', 'user_id', 'type', 'category', 'ledger_number', 'is_active', 'billing_date'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Method to get the latest transaction
    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany('datetime');
    }

    public function getBalanceAttribute()
    {
        $debits = $this->transactions->where('type', 'debit')->sum('amount');
        $credits = $this->transactions->where('type', 'credit')->sum('amount');

        return $debits - $credits;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
