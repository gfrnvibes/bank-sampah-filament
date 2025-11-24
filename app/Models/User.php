<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'nik', 'email', 'password', 'phone', 'dusun', 'rt', 'rw', 'age', 'avatar', 'no_rek', 'is_active', 'balance'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'balance' => 'decimal:2',
        ];
    }

    protected $casts = [
        'balance' => 'decimal:2',
    ];


    public function wasteDeposits()
    {
        return $this->hasMany(WasteDeposit::class);
    }

    public function balanceWithdrawals()
    {
        return $this->hasMany(BalanceWithdrawal::class);
    }

    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class);
    }
}