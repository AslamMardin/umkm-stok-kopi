@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')

@section('content')
<div>
    <div class="page-actions" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <form action="{{ route('users.index') }}" method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama atau email">
                <select name="role" class="form-control" style="min-width:180px;">
                    <option value="">Semua Role</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
    </div>

    <div class="card">
        <div class="card-title">Daftar Pengguna</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Supplier</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $roles[$user->role] ?? ucfirst($user->role) }}</td>
                                <td>{{ $user->supplier->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-secondary btn-sm">Detail</a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user {{ addslashes($user->name) }}?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
            <div class="card-body pagination-wrap">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
