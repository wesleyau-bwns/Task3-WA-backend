<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

use Illuminate\Support\Facades\Log;

class OrderPolicy
{
    /**
     * Admin can view all orders.
     * User can view their own orders.
     * Merchant can view orders belonging to their merchant.
     */
    public function view(User $authUser, Order $order): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }

        if ($authUser->hasRole('user')) {
            return $order->user_id === $authUser->id;
        }

        if ($authUser->hasRole('merchant')) {
            return $order->merchant_id === $authUser->merchant_id;
        }

        return false;
    }

    /**
     * Only merchant (own orders) or admin can update order status.
     */
    public function update(User $authUser, Order $order): bool
    {
        return $authUser->hasRole('admin')
            || ($authUser->hasRole('merchant')
                && $order->merchant_id === $authUser->merchant_id);
    }

    /**
     * Only users can create orders for themselves.
     */
    public function create(User $authUser): bool
    {
        // Log::info("OrderPolicy create", ["roles" => $authUser->getRoleNames()]);
        return $authUser->hasRole('user');
    }
}
