<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class AccessLogs extends Component
{
    use WithPagination;

    public $ipFilter = '';
    public $dateFilter = '';
    public $failedThreshold = 10; // Highlight IPs with more than X accesses in the period

    public $perPage = 25;

    protected $paginationTheme = 'bootstrap';

    public function updatingIpFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DB::table('access_logs')->orderBy('created_at', 'desc');

        if (!empty($this->ipFilter)) {
            $query->where('ip', 'like', '%' . $this->ipFilter . '%');
        }

        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', $this->dateFilter);
        }

        // Find suspicious IPs (high traffic today)
        $suspiciousIps = DB::table('access_logs')
            ->select('ip', DB::raw('count(*) as total'))
            ->whereDate('created_at', $this->dateFilter ?: now()->toDateString())
            ->groupBy('ip')
            ->having('total', '>', $this->failedThreshold)
            ->pluck('total', 'ip')
            ->toArray();

        return view('livewire.admin.access-logs', [
            'logs' => $query->paginate($this->perPage),
            'suspiciousIps' => $suspiciousIps
        ])->layout('layouts.app');
    }
}
