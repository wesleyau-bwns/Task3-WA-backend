<?php

namespace App\Services\User;

use App\Models\User\BankAccount;
use Illuminate\Support\Facades\Auth;

class BankAccountService
{
    public function getAllUserBankAccounts()
    {
        return BankAccount::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
    }

    public function getUserBankAccount(BankAccount $bankAccount): BankAccount
    {
        return $bankAccount;
    }

    public function createBankAccount(array $data): BankAccount
    {
        $user = Auth::user();

        return BankAccount::create([
            'user_id' => $user->id,
            'bank_name' => $data['bank_name'],
            'account_holder_name' => $data['account_holder_name'],
            'account_number' => $data['account_number'], // auto-encrypted by cast
            'iban' => $data['iban'] ?? null,             // auto-encrypted by cast
            'swift_code' => $data['swift_code'] ?? null,
            'country_code' => strtoupper($data['country_code']),
            'currency_code' => strtoupper($data['currency_code']),
            'is_verified' => false,  // default false, admin must approve
        ]);
    }

    public function updateBankAccount(BankAccount $bankAccount, array $data): BankAccount
    {
        $bankAccount->update([
            'bank_name' => $data['bank_name'],
            'account_holder_name' => $data['account_holder_name'],
            'account_number' => $data['account_number'],
            'iban' => $data['iban'] ?? null,
            'swift_code' => $data['swift_code'] ?? null,
            'country_code' => strtoupper($data['country_code']),
            'currency_code' => strtoupper($data['currency_code']),
        ]);

        return $bankAccount;
    }

    public function deleteBankAccount(BankAccount $bankAccount): void
    {
        $bankAccount->delete();
    }
}
