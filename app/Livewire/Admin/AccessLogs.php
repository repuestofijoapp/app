<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class AccessLogs extends Component
{
    use WithPagination;

    public $ipFilter     = '';
    public $dateFilter   = '';
    public $userFilter   = '';   // nuevo: filtrar por nombre o email
    public $tab          = 'accesos'; // 'accesos' | 'sesiones'

    public $failedThreshold = 10;
    public $perPage = 25;

    protected $paginationTheme = 'bootstrap';

    public function updatingIpFilter()   { $this->resetPage(); }
    public function updatingDateFilter() { $this->resetPage(); }
    public function updatingUserFilter() { $this->resetPage(); }
    public function updatingPerPage()    { $this->resetPage(); }
    public function updatingTab()        { $this->resetPage(); }

    public function render()
    {
        // ── Tab: Registro de accesos HTTP ─────────────────────────────────────
        $query = DB::table('access_logs as al')
            ->leftJoin('users as u', 'al.user_id', '=', 'u.id')
            ->select(
                'al.*',
                'u.name  as user_name',
                'u.email as user_email',
                'u.role  as user_role'
            )
            ->orderBy('al.created_at', 'desc');

        if (!empty($this->ipFilter)) {
            $query->where('al.ip', 'like', '%' . $this->ipFilter . '%');
        }
        if (!empty($this->dateFilter)) {
            $query->whereDate('al.created_at', $this->dateFilter);
        }
        if (!empty($this->userFilter)) {
            $query->where(function ($q) {
                $q->where('u.name',  'like', '%' . $this->userFilter . '%')
                  ->orWhere('u.email', 'like', '%' . $this->userFilter . '%');
            });
        }

        // ── Tab: Sesiones de usuario (login/logout) ───────────────────────────
        $sessionsQuery = DB::table('user_sessions as us')
            ->join('users as u', 'us.user_id', '=', 'u.id')
            ->select(
                'us.*',
                'u.name  as user_name',
                'u.email as user_email',
                'u.role  as user_role'
            )
            ->orderBy('us.logged_in_at', 'desc');

        if (!empty($this->dateFilter)) {
            $sessionsQuery->whereDate('us.logged_in_at', $this->dateFilter);
        }
        if (!empty($this->userFilter)) {
            $sessionsQuery->where(function ($q) {
                $q->where('u.name',  'like', '%' . $this->userFilter . '%')
                  ->orWhere('u.email', 'like', '%' . $this->userFilter . '%');
            });
        }

        // IPs con tráfico inusual hoy
        $suspiciousIps = DB::table('access_logs')
            ->select('ip', DB::raw('count(*) as total'))
            ->whereDate('created_at', $this->dateFilter ?: now()->toDateString())
            ->groupBy('ip')
            ->having('total', '>', $this->failedThreshold)
            ->pluck('total', 'ip')
            ->toArray();

        return view('livewire.admin.access-logs', [
            'logs'          => $query->paginate($this->perPage),
            'sessions'      => $sessionsQuery->paginate($this->perPage),
            'suspiciousIps' => $suspiciousIps,
        ])->layout('layouts.app');
    }
}
