<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SecurityLog;
use App\Models\BlacklistIp;
use Illuminate\Support\Carbon;

class SecurityAlerts extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $eventFilter = '';
    public string $dateFilter  = '';
    public int    $perPage     = 25;

    // Modal detail
    public bool  $showDetail = false;
    public ?array $detailLog = null;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingEventFilter() { $this->resetPage(); }
    public function updatingDateFilter()  { $this->resetPage(); }
    public function updatingPerPage()     { $this->resetPage(); }

    public function openDetail(int $id): void
    {
        $log = SecurityLog::with('user')->find($id);
        if (!$log) return;

        $this->detailLog = [
            'id'         => $log->id,
            'ip'         => $log->ip_address,
            'event'      => $log->event_type,
            'user'       => $log->user?->name ?? 'Anónimo',
            'user_email' => $log->user?->email,
            'details'    => $log->details ?? [],
            'date'       => $log->created_at->format('d/m/Y H:i:s'),
            'is_blocked' => BlacklistIp::where('ip_address', $log->ip_address)->active()->exists(),
        ];
        $this->showDetail = true;
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->detailLog  = null;
    }

    public function blockIp(string $ip): void
    {
        BlacklistIp::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason'     => 'Bloqueado manualmente desde el Centro de Alertas',
                'expires_at' => now()->addHours(72), // 72h manual block
                'blocked_by' => auth()->id(),
            ]
        );

        if ($this->detailLog && $this->detailLog['ip'] === $ip) {
            $this->detailLog['is_blocked'] = true;
        }

        session()->flash('success', "IP {$ip} bloqueada por 72 horas.");
    }

    public function unblockIp(string $ip): void
    {
        BlacklistIp::where('ip_address', $ip)->delete();

        if ($this->detailLog && $this->detailLog['ip'] === $ip) {
            $this->detailLog['is_blocked'] = false;
        }

        session()->flash('success', "IP {$ip} desbloqueada.");
    }

    public function render()
    {
        $query = SecurityLog::with('user')->latest();

        if ($this->search) {
            $query->where('ip_address', 'like', "%{$this->search}%");
        }
        if ($this->eventFilter) {
            $query->where('event_type', $this->eventFilter);
        }
        if ($this->dateFilter) {
            $query->whereDate('created_at', $this->dateFilter);
        }

        $blockedIps = BlacklistIp::active()->pluck('ip_address')->toArray();

        return view('livewire.admin.security-alerts', [
            'logs'       => $query->paginate($this->perPage),
            'blockedIps' => $blockedIps,
            'eventTypes' => SecurityLog::distinct()->pluck('event_type'),
            'totalBlocked' => count($blockedIps),
        ])->layout('layouts.app');
    }
}
