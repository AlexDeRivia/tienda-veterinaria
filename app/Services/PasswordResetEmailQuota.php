<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PasswordResetEmailQuota
{
    private const MAX_PER_ACCOUNT_PER_DAY = 3;

    private const MAX_TOTAL_PER_DAY = 30;

    public function claim(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            // A fixed row lock keeps the global total correct across instances.
            Role::whereKey(2)->lockForUpdate()->firstOrFail();
            $day = now()->toDateString();
            $quotas = DB::table('password_reset_email_quotas');
            $perAccount = (int) $quotas->where('user_id', $user->id)->where('day', $day)->value('requests');
            $total = (int) DB::table('password_reset_email_quotas')->where('day', $day)->sum('requests');
            if ($perAccount >= self::MAX_PER_ACCOUNT_PER_DAY || $total >= self::MAX_TOTAL_PER_DAY) {
                return false;
            }

            DB::table('password_reset_email_quotas')->updateOrInsert(
                ['user_id' => $user->id, 'day' => $day],
                ['requests' => $perAccount + 1]
            );
            DB::table('password_reset_email_quotas')
                ->where('day', '<', now()->subDays(30)->toDateString())->delete();

            return true;
        });
    }
}
