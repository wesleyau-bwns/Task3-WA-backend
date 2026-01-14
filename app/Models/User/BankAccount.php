<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'account_holder_name',
        'account_number',
        'iban',
        'swift_code',
        'country_code',
        'currency_code',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'account_number' => 'encrypted',
        'iban' => 'encrypted',
    ];

    protected $appends = [
        'masked_account_number',
        'masked_iban',
    ];

    public function getMaskedAccountNumberAttribute(): ?string
    {
        if (!$this->account_number) {
            return null;
        }

        return str_repeat('*', max(strlen($this->account_number) - 4, 0))
            . substr($this->account_number, -4);
    }

    public function getMaskedIbanAttribute(): ?string
    {
        if (!$this->iban) {
            return null;
        }

        return substr($this->iban, 0, 4)
            . str_repeat('*', max(strlen($this->iban) - 8, 0))
            . substr($this->iban, -4);
    }
}
