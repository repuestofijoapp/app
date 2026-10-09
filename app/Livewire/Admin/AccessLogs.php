<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\AccessLogHelper;
use Carbon\Carbon;

class AccessLogs extends Component
{
    use WithPagination;

    public $ipFilter       = '';
    public $dateFilter     = '';
    public $userFilter     = '';
    public $categoryFilter = ''; // 'catalogo' | 'admin' | 'auth' | 'b2b' | 'legal' | 'interaccion'
    public $tab            = 'accesos'; // 'accesos' | 'sesiones'

    public $failedThreshold = 10;
    public $perPage         = 25;

    // Modal de inspección de usuario (Timeline)
    public $inspectingUserId = null;
    public $inspectingUser   = null;
    public $userTimeline     = [];
    public $userStats        = [];

    protected $paginationTheme = 'bootstrap';

    public function updatingIpFilter()       { $this->resetPage(); }
    public function updatingDateFilter()     { $this->resetPage(); }
    public function updatingUserFilter()     { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingPerPage()        { $this->resetPage(); }
    public function updatingTab()            { $this->resetPage(); }

    /**
     * Abre el modal e inspecciona la línea de tiempo completa de un usuario.
     */
    public function inspectUser($userId)
    {
        $this->inspectingUserId = $userId;
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            $this->inspectingUserId = null;
            return;
        }

        $this->inspectingUser = (array) $user;

        // Estadísticas del usuario
        $totalSessions = DB::table('user_sessions')->where('user_id', $userId)->count();
        $totalRequests = DB::table('access_logs')->where('user_id', $userId)->count();
        $lastSession   = DB::table('user_sessions')->where('user_id', $userId)->orderByDesc('logged_in_at')->first();
        $lastLog       = DB::table('access_logs')->where('user_id', $userId)->orderByDesc('created_at')->first();

        $this->userStats = [
            'total_sessions' => $totalSessions,
            'total_requests' => $totalRequests,
            'last_ip'        => $lastSession->ip ?? ($lastLog->ip ?? 'N/A'),
            'last_active_at' => $lastSession->logged_in_at ?? ($lastLog->created_at ?? null),
        ];

        // Construir Timeline cronológico unificado (Sesiones + Consultas)
        $timelineEvents = [];

        // 1. Eventos de sesiones (Login y Logout)
        $sessions = DB::table('user_sessions')
            ->where('user_id', $userId)
            ->orderByDesc('logged_in_at')
            ->limit(20)
            ->get();

        foreach ($sessions as $s) {
            $timelineEvents[] = [
                'type'        => 'login',
                'title'       => 'Inicio de sesión',
                'description' => "Accedió a la plataforma desde IP {$s->ip}",
                'icon'        => 'fas fa-sign-in-alt',
                'color'       => '#10b981',
                'bg'          => 'rgba(16,185,129,0.15)',
                'time'        => Carbon::parse($s->logged_in_at),
                'ip'          => $s->ip,
                'user_agent'  => $s->user_agent,
            ];

            if ($s->logged_out_at) {
                $loginTime  = Carbon::parse($s->logged_in_at);
                $logoutTime = Carbon::parse($s->logged_out_at);
                $diff       = $loginTime->diff($logoutTime);
                $durText    = ($diff->h > 0 ? $diff->h.'h ' : '') . $diff->i.'m ' . $diff->s.'s';

                $timelineEvents[] = [
                    'type'        => 'logout',
                    'title'       => 'Cierre de sesión',
                    'description' => "Sesión finalizada · Tiempo conectado: {$durText}",
                    'icon'        => 'fas fa-sign-out-alt',
                    'color'       => '#ef4444',
                    'bg'          => 'rgba(239,68,68,0.15)',
                    'time'        => $logoutTime,
                    'ip'          => $s->ip,
                    'user_agent'  => $s->user_agent,
                ];
            }
        }

        // 2. Eventos de accesos/consultas (access_logs)
        $logs = DB::table('access_logs')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(40)
            ->get();

        foreach ($logs as $l) {
            $formatted = AccessLogHelper::formatDisplay($l->action_name ?? null, $l->route, $l->method, $l->category ?? null);

            $timelineEvents[] = [
                'type'        => 'action',
                'title'       => $formatted['action_name'],
                'description' => "{$l->method} /{$l->route}",
                'icon'        => $formatted['icon'],
                'color'       => $formatted['badge_color'],
                'bg'          => $formatted['badge_bg'],
                'category'    => $formatted['badge_label'],
                'time'        => Carbon::parse($l->created_at),
                'ip'          => $l->ip,
                'user_agent'  => $l->user_agent,
            ];
        }

        // Ordenar cronológicamente descendente
        usort($timelineEvents, function ($a, $b) {
            return $b['time']->timestamp <=> $a['time']->timestamp;
        });

        // Limitar a los 50 más relevantes
        $this->userTimeline = array_slice($timelineEvents, 0, 50);
    }

    /**
     * Cierra el modal de inspección.
     */
    public function closeInspectModal()
    {
        $this->inspectingUserId = null;
        $this->inspectingUser   = null;
        $this->userTimeline     = [];
        $this->userStats        = [];
    }

    public function render()
    {
        $today = now()->toDateString();
        $targetDate = $this->dateFilter ?: $today;

        // ── Tarjetas de estadísticas resumidas (Hoy) ──────────────────────────
        $activeUsersToday = DB::table('user_sessions')
            ->whereDate('logged_in_at', $today)
            ->distinct('user_id')
            ->count('user_id');

        $sessionsToday = DB::table('user_sessions')
            ->whereDate('logged_in_at', $today)
            ->count();

        $requestsToday = DB::table('access_logs')
            ->whereDate('created_at', $today)
            ->count();

        $activeSessionsNow = DB::table('user_sessions')
            ->whereNull('logged_out_at')
            ->where('logged_in_at', '>=', now()->subHours(2))
            ->count();

        $topStats = [
            'active_users'   => $activeUsersToday,
            'sessions_today' => $sessionsToday,
            'requests_today' => $requestsToday,
            'active_now'     => $activeSessionsNow,
        ];

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
        if (!empty($this->categoryFilter)) {
            $query->where('al.category', $this->categoryFilter);
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

        // IPs con tráfico inusual
        $suspiciousIps = DB::table('access_logs')
            ->select('ip', DB::raw('count(*) as total'))
            ->whereDate('created_at', $targetDate)
            ->groupBy('ip')
            ->having('total', '>', $this->failedThreshold)
            ->pluck('total', 'ip')
            ->toArray();

        return view('livewire.admin.access-logs', [
            'logs'          => $query->paginate($this->perPage),
            'sessions'      => $sessionsQuery->paginate($this->perPage),
            'suspiciousIps' => $suspiciousIps,
            'topStats'      => $topStats,
        ])->layout('layouts.app');
    }
}
