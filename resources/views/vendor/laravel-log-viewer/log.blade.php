@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <style>
    /* ── Page card (identical to access-logs) ── */
    .page-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2rem;
    }

    /* ── Table ── */
    .log-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 8px;
    }
    .log-table th {
      color: var(--muted);
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      padding: 0.75rem 1rem;
      text-transform: uppercase;
      font-size: 0.72rem;
      letter-spacing: 1px;
      border: none;
      background: transparent;
    }
    .log-table tr td {
      background: var(--surface2);
      padding: 0.85rem 1rem;
      color: #fff;
      transition: background 0.2s;
      vertical-align: middle;
      border: none;
    }
    .log-table tr:hover td {
      background: var(--surface3, #1e2a3a);
      cursor: pointer;
    }
    .log-table tr td:first-child { border-radius: 12px 0 0 12px; }
    .log-table tr td:last-child  { border-radius: 0 12px 12px 0; }

    /* ── Search / filter inputs ── */
    .log-search-input {
      background: var(--surface2);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 0.7rem 1rem;
      color: #fff;
      min-width: 220px;
      font-size: 0.85rem;
      transition: border-color 0.2s;
    }
    .log-search-input:focus {
      outline: none;
      border-color: var(--accent-red);
    }
    .log-search-input::placeholder { color: rgba(255,255,255,0.35); }

    /* ── File chip buttons ── */
    .log-file-chip {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.45rem 1rem;
      border-radius: 30px;
      font-size: 0.78rem;
      font-weight: 700;
      font-family: 'Syne', sans-serif;
      border: 1px solid var(--border);
      background: var(--surface2);
      color: rgba(255,255,255,0.7);
      text-decoration: none;
      transition: all 0.2s;
      cursor: pointer;
    }
    .log-file-chip:hover {
      background: var(--surface3, #1e2a3a);
      color: #fff;
      text-decoration: none;
    }
    .log-file-chip.active {
      background: var(--accent-red);
      border-color: var(--accent-red);
      color: #fff;
    }

    /* ── Level badges ── */
    .level-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      padding: 0.3rem 0.7rem;
      border-radius: 20px;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .level-error   { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
    .level-warning { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
    .level-info    { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
    .level-debug   { background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.3); }
    .level-notice  { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
    .level-critical,
    .level-alert,
    .level-emergency { background: rgba(239,68,68,0.25); color: #fca5a5; border: 1px solid rgba(239,68,68,0.5); }

    /* ── Stack trace panel ── */
    .log-stack {
      display: none;
      white-space: pre-wrap;
      font-family: 'Courier New', monospace;
      font-size: 0.72rem;
      background: rgba(0,0,0,0.35);
      color: #a5b4fc;
      padding: 1rem;
      border-radius: 8px;
      margin-top: 0.6rem;
      max-height: 300px;
      overflow-y: auto;
      border: 1px solid var(--border);
    }

    /* ── Action links footer ── */
    .log-actions a {
      font-size: 0.8rem;
      font-weight: 600;
      text-decoration: none;
      padding: 0.4rem 0.9rem;
      border-radius: 8px;
      border: 1px solid var(--border);
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      transition: all 0.2s;
    }
    .log-actions a:hover { opacity: 0.8; }
    .log-action-download { background: rgba(59,130,246,0.15); color: #60a5fa; border-color: rgba(59,130,246,0.3) !important; }
    .log-action-clean    { background: rgba(245,158,11,0.15); color: #fbbf24; border-color: rgba(245,158,11,0.3) !important; }
    .log-action-delete   { background: rgba(239,68,68,0.15);  color: #f87171; border-color: rgba(239,68,68,0.3) !important; }

    /* ── DataTables overrides ── */
    div.dataTables_wrapper div.dataTables_filter input,
    div.dataTables_wrapper div.dataTables_length select {
      background: var(--surface2) !important;
      border: 1px solid var(--border) !important;
      color: #fff !important;
      border-radius: 8px;
      padding: 4px 8px;
    }
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_length label {
      color: rgba(255,255,255,0.6) !important;
      font-size: 0.8rem;
    }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button {
      background: var(--surface2) !important;
      border: 1px solid var(--border) !important;
      color: rgba(255,255,255,0.7) !important;
      border-radius: 8px;
      margin: 0 2px;
    }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
      background: var(--accent-red) !important;
      border-color: var(--accent-red) !important;
      color: #fff !important;
    }
  </style>

  {{-- ─────────────── Header ─────────────── --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 text-white fw-bold mb-1" style="font-family:'Syne',sans-serif;">
        <i class="fas fa-bug text-danger me-2"></i> Errores del Sistema
      </h1>
      <p class="text-white small mb-0 opacity-75">Registro técnico de errores y eventos de la aplicación.</p>
    </div>
  </div>

  <div class="page-card">

    {{-- ─── Row 1: file chips + date input ─── --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">

      {{-- File chips --}}
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <span class="text-white opacity-75 small me-1"><i class="fas fa-calendar-alt me-1"></i> Archivo:</span>
        @forelse($files as $file)
          <a href="?l={{ \Illuminate\Support\Facades\Crypt::encryptString($file) }}"
             class="log-file-chip {{ $current_file == $file ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            {{ $file }}
          </a>
        @empty
          <span class="text-white opacity-50 small">No hay archivos de log.</span>
        @endforelse
      </div>

      {{-- DataTables search override (hidden, replaced below) + filter --}}
      <div class="d-flex align-items-center gap-2">
        <i class="fas fa-search text-white opacity-50"></i>
        <input type="text" id="log-search" class="log-search-input" placeholder="Buscar en los logs...">
      </div>
    </div>

    {{-- ─── Table ─── --}}
    @if($logs === null)
      <div class="text-center py-5">
        <i class="fas fa-file-alt fs-2 text-white opacity-25 d-block mb-3"></i>
        <p class="text-white opacity-50">El archivo es demasiado grande (+50MB). Descárgalo o límpialo.</p>
      </div>
    @elseif(empty($logs))
      <div class="text-center py-5">
        <i class="fas fa-check-circle fs-2 text-success opacity-50 d-block mb-3"></i>
        <p class="text-white opacity-50">El archivo de log está vacío. ¡No hay errores registrados!</p>
      </div>
    @else
      <div class="table-responsive">
        <table id="table-log" class="log-table" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
          <thead>
            <tr>
              @if($standardFormat)
                <th>Nivel</th>
                <th>Contexto</th>
                <th>Fecha / Hora</th>
              @else
                <th>#</th>
              @endif
              <th>Contenido</th>
            </tr>
          </thead>
          <tbody>
            @foreach($logs as $key => $log)
              <tr data-display="stack{{ $key }}">
                @if($standardFormat)
                  <td>
                    <span class="level-badge level-{{ strtolower($log['level']) }}">
                      <i class="fa fa-{{ $log['level_img'] }}"></i>
                      {{ $log['level'] }}
                    </span>
                  </td>
                  <td>
                    <span class="text-white small opacity-75">{{ $log['context'] }}</span>
                  </td>
                @endif
                <td class="date text-white small" style="white-space:nowrap;">
                  {{{ $log['date'] }}}
                </td>
                <td class="text">
                  @if($log['stack'])
                    <button type="button"
                            class="btn btn-sm float-end ms-2 mb-1"
                            style="background:rgba(255,255,255,0.08); border:1px solid var(--border); color:#fff; border-radius:6px; font-size:0.7rem;"
                            data-display="stack{{ $key }}">
                      <i class="fa fa-code"></i> Stack
                    </button>
                  @endif
                  <span class="text-white">{{{ $log['text'] }}}</span>
                  @if(isset($log['in_file']))
                    <br><span class="text-white opacity-50 small" style="font-size:0.72rem;">{{{ $log['in_file'] }}}</span>
                  @endif
                  @if($log['stack'])
                    <div class="log-stack" id="stack{{ $key }}">{{ trim($log['stack']) }}</div>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif

    {{-- ─── Footer actions ─── --}}
    @if($current_file)
      <div class="log-actions d-flex flex-wrap gap-2 mt-4 pt-3" style="border-top: 1px solid var(--border);">
        <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ $current_folder ? '&f='.\Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}"
           class="log-action-download">
          <i class="fa fa-download"></i> Descargar archivo
        </a>
        <a id="clean-log"
           href="?clean={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ $current_folder ? '&f='.\Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}"
           class="log-action-clean">
          <i class="fa fa-sync"></i> Limpiar archivo
        </a>
        <a id="delete-log"
           href="?del={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ $current_folder ? '&f='.\Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}"
           class="log-action-delete">
          <i class="fa fa-trash"></i> Eliminar archivo
        </a>
        @if(count($files) > 1)
          <a id="delete-all-log"
             href="?delall=true{{ $current_folder ? '&f='.\Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}"
             class="log-action-delete">
            <i class="fa fa-trash-alt"></i> Eliminar todos
          </a>
        @endif
      </div>
    @endif

  </div>{{-- /.page-card --}}
</div>{{-- /.container-fluid --}}

{{-- DataTables (only JS needed, CSS handled by our custom styles) --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
  // Toggle stack trace on row click
  $('.log-table tbody tr').on('click', function () {
    var id = $(this).data('display');
    if (id) $('#' + id).toggle();
  });
  // Stack button
  $('.log-table').on('click', 'button[data-display]', function (e) {
    e.stopPropagation();
    var id = $(this).data('display');
    $('#' + id).toggle();
  });

  // Init DataTable
  var table = $('#table-log').DataTable({
    "order": [$('#table-log').data('orderingIndex') || 0, 'desc'],
    "pageLength": 25,
    "searching": true,
    "dom": 'lrtip', // hide default search box (we use our own)
    "language": {
      "lengthMenu": "Mostrar _MENU_ registros",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
      "infoEmpty": "Sin resultados",
      "paginate": { "previous": "‹", "next": "›" },
      "emptyTable": "No hay registros"
    }
  });

  // Connect our custom search input to DataTable
  $('#log-search').on('keyup', function () {
    table.search($(this).val()).draw();
  });

  // Confirm destructive actions
  $('#delete-log, #clean-log, #delete-all-log').on('click', function () {
    return confirm('¿Estás seguro? Esta acción no se puede deshacer.');
  });
});
</script>
@endsection
