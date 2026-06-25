@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('pretitle', 'Sistem')
@section('page-title', 'Log Aktivitas')

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="action" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua aksi</option>
                        @foreach (\App\Models\ActivityLog::ACTION_META as $val => $meta)
                            <option value="{{ $val }}" @selected(request('action') === $val)>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari pelaku atau objek…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('action') || request('cari'))<a href="{{ route('admin.activity.index') }}" class="btn btn-link">Reset</a>@endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1">Waktu</th>
                        <th>Pelaku</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php($meta = $log->actionMeta())
                        <tr>
                            <td class="text-secondary text-nowrap" title="{{ $log->created_at }}">
                                {{ $log->created_at?->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td>{{ $log->user_name ?: 'Sistem' }}</td>
                            <td>
                                <span class="badge bg-{{ $meta['color'] }}-lt me-1"><i class="ti {{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}</span>
                                <span class="text-secondary">{{ $log->subjectLabelType() }}:</span>
                                <span class="fw-bold">{{ $log->subject_label }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-4">Belum ada aktivitas tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
