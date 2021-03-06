<?php

namespace App;

use DB;

use App\User;
use App\Quota;
use App\QuotaRequest;
use App\Earning;
use App\Withdrawal;

use Symfony\Component\HttpFoundation\ParameterBag;

class UserStatsManager
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getTotalReferences()
    {
        return User::where('referred_by', $this->user->username)->count();
    }

    public function getActiveReferences()
    {
        return User::where('referred_by', $this->user->username)->where('active', true)->count();
    }

    public function getTotalQuotas()
    {
        $quotas = $this->user->num_quotas;
        $pquotas = $this->user->quota_requests()->where('status', 0)->count();

        return $quotas + $pquotas;
    }

    public function getActiveQuotas()
    {
        return $this->user->num_quotas;
    }

    public function getTotalEarnings()
    {
        return $this->user->earnings->count();
    }

    public function getTotalWithdrawals()
    {
        return $this->user->withdrawals->count();
    }

    public function getTotalBalance()
    {
        return $this->user->balance + $this->user->e_funds;
    }

    public function getWithdrawableBalance()
    {
        return $this->user->balance;
    }

    public function getPendingWithdrawalsAmount()
    {
        return $this->user->withdrawal_requests()->where('status', 0)->sum('amount');
    }

    public function getBalanceEcommerce()
    {
        return $this->user->e_funds;
    }

    public function getStatsBag()
    {
        $bag = new ParameterBag();

        $bag->set('total.references', $this->getTotalReferences());
        $bag->set('total.quotas', $this->getTotalQuotas());
        $bag->set('total.earnings', $this->getTotalEarnings());
        $bag->set('total.withdrawals', $this->getTotalWithdrawals());
        $bag->set('total.pending.withdrawal_amount', $this->getPendingWithdrawalsAmount());

        $bag->set('active.references', $this->getActiveReferences());
        $bag->set('active.quotas', $this->getActiveQuotas());

        $bag->set('balance.total', $this->getTotalBalance());
        $bag->set('balance.withdrawable', $this->getWithdrawableBalance());
        $bag->set('balance.ecommerce', $this->getBalanceEcommerce());

        return $bag;
    }
}
