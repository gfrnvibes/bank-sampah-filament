<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'nik', 'email', 'password', 'phone', 'dusun', 'rt', 'rw', 'age', 'avatar', 'foto_ktp', 'is_active', 'balance', 'avatar_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

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

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Admin (ID 1) selalu punya akses tanpa cek verifikasi
        if ($this->id === 1) {
            return true;
        }

        // Nasabah harus cek domain gmail
        return str_ends_with($this->email, '@gmail.com');
    }

    public function hasVerifiedEmail(): bool
{
    // Jika user adalah admin, anggap sudah verifikasi (return true)
    if ($this->id === 1) {
        return true;
    }

    // Untuk user lain, gunakan pengecekan standar Laravel (cek kolom email_verified_at)
    return $this->getEmailForVerification() && $this->email_verified_at !== null;
}

    public function sendEmailVerificationNotification(): void
    {
        // Jika ID adalah 1 (admin), jangan kirim email verifikasi
        if ($this->id === 1) {
            return;
        }

        // Untuk nasabah saja
        $verifyUrl = URL::temporarySignedRoute(
            'filament.nasabah.auth.email-verification.verify',
            now()->addMinutes(60),
            ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
        );

        $notification = new \Illuminate\Auth\Notifications\VerifyEmail();
        $notification->createUrlUsing(fn() => $verifyUrl);

        $this->notify($notification);
    }
}
