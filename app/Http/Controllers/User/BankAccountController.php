<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreBankAccountRequest;
use App\Http\Requests\User\UpdateBankAccountRequest;
use App\Models\User\BankAccount;
use App\Services\User\BankAccountService;

use Illuminate\Http\JsonResponse;

class BankAccountController extends Controller
{
    public function __construct(protected BankAccountService $bankAccountService) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', BankAccount::class);

        $bankAccounts = $this->bankAccountService->getAllUserBankAccounts();

        return $this->success(
            'Bank accounts retrieved successfully.',
            $bankAccounts->map(fn($account) => [
                'id' => $account->id,
                'bank_name' => $account->bank_name,
                'account_holder_name' => $account->account_holder_name,
                'account_number' => $account->masked_account_number,
                'iban' => $account->masked_iban,
                'swift_code' => $account->swift_code,
                'country_code' => $account->country_code,
                'currency_code' => $account->currency_code,
                'status' => $account->is_verified ? 'approved' : 'pending',
                'created_at' => $account->created_at,
            ])
        );
    }

    public function show(BankAccount $bankAccount): JsonResponse
    {
        $this->authorize('view', $bankAccount);

        $bankAccount = $this->bankAccountService->getUserBankAccount($bankAccount);

        return $this->success(
            'Bank account retrieved successfully.',
            [
                'id' => $bankAccount->id,
                'bank_name' => $bankAccount->bank_name,
                'account_holder_name' => $bankAccount->account_holder_name,
                'account_number' => $bankAccount->masked_account_number,
                'iban' => $bankAccount->masked_iban,
                'swift_code' => $bankAccount->swift_code,
                'country_code' => $bankAccount->country_code,
                'currency_code' => $bankAccount->currency_code,
                'status' => $bankAccount->is_verified ? 'approved' : 'pending',
                'created_at' => $bankAccount->created_at,
            ]
        );
    }

    public function store(StoreBankAccountRequest $request): JsonResponse
    {
        $this->authorize('create', BankAccount::class);

        $bankAccount = $this->bankAccountService->createBankAccount($request->validated());

        return $this->success(
            'Bank account added successfully. Pending verification.',
            [
                'id' => $bankAccount->id,
                'bank_name' => $bankAccount->bank_name,
                'currency_code' => $bankAccount->currency_code,
                'status' => $bankAccount->is_verified ? 'approved' : 'pending'
            ]
        );
    }

    public function update(UpdateBankAccountRequest $request, BankAccount $bankAccount): JsonResponse
    {
        $this->authorize('update', $bankAccount);

        $updated = $this->bankAccountService->updateBankAccount($bankAccount, $request->validated());

        return $this->success(
            'Bank account updated successfully.',
            [
                'id' => $updated->id,
                'bank_name' => $updated->bank_name,
                'account_holder_name' => $updated->account_holder_name,
                'account_number' => $updated->masked_account_number,
                'iban' => $updated->masked_iban,
                'swift_code' => $updated->swift_code,
                'country_code' => $updated->country_code,
                'currency_code' => $updated->currency_code,
                'status' => $updated->is_verified ? 'approved' : 'pending',
                'created_at' => $updated->created_at,
            ]
        );
    }

    public function destroy(BankAccount $bankAccount): JsonResponse
    {
        $this->authorize('delete', $bankAccount);

        $this->bankAccountService->deleteBankAccount($bankAccount);

        return $this->success('Bank account deleted successfully.');
    }
}
