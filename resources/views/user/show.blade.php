@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div style="max-width:700px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Detail: {{ $user->name }}</div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <div class="detail-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div class="detail-label">Nama</div>
                    <div class="detail-value">{{ $user->name }}</div>
                </div>
                <div>
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $user->email }}</div>
                </div>
                <div>
                    <div class="detail-label">Role</div>
                    <div class="detail-value">{{ $roles[$user->role] ?? ucfirst($user->role) }}</div>
                </div>
                <div>
                    <div class="detail-label">Supplier</div>
                    <div class="detail-value">{{ $user->supplier->name ?? '-' }}</div>
                </div>
                <div style="grid-column:1 / -1;">
                    <div class="detail-label">Dibuat</div>
                    <div class="detail-value">{{ $user->created_at->locale('id')->isoFormat('D MMMM Y HH:mm') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
