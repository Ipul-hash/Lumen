@extends('admin.layouts.app')

@section('page-title', 'Manajemen Pengguna & Administrator')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-500 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-muted">Akses & Keamanan</li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-500 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-gray-900">Kelola User</li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column gap-6">

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 rounded-3 shadow-sm">
        <i class="ki-duotone ki-shield-tick fs-2 text-success me-3"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-7 fw-semibold text-gray-800">{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center p-4 rounded-3 shadow-sm">
        <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-7 fw-semibold text-gray-800">{{ session('error') }}</div>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-flush h-100 border">
                <div class="card-body d-flex flex-column justify-content-center p-5">
                    <span class="fs-7 fw-bold text-gray-500 text-uppercase">Total Pengguna</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="fs-2hx fw-bolder text-gray-900 me-2">{{ $stats['total'] }}</span>
                        <span class="badge badge-light-primary fw-bold fs-8">Semua Akun</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-flush h-100 border">
                <div class="card-body d-flex flex-column justify-content-center p-5">
                    <span class="fs-7 fw-bold text-gray-500 text-uppercase">Superadmin</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="fs-2hx fw-bolder text-danger me-2">{{ $stats['superadmin'] }}</span>
                        <span class="badge badge-light-danger fw-bold fs-8">Full Privilege</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-flush h-100 border">
                <div class="card-body d-flex flex-column justify-content-center p-5">
                    <span class="fs-7 fw-bold text-gray-500 text-uppercase">Admin Operasional</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="fs-2hx fw-bolder text-primary me-2">{{ $stats['admin'] }}</span>
                        <span class="badge badge-light-primary fw-bold fs-8">Admin Biasa</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-flush h-100 border">
                <div class="card-body d-flex flex-column justify-content-center p-5">
                    <span class="fs-7 fw-bold text-gray-500 text-uppercase">Pelanggan Toko</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="fs-2hx fw-bolder text-gray-700 me-2">{{ $stats['customer'] }}</span>
                        <span class="badge badge-light-secondary fw-bold fs-8">Customer Store</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex align-items-center position-relative my-1">
                    <input type="hidden" name="role" value="{{ $currentRole }}">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4 text-gray-500">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" 
                           name="q" 
                           value="{{ $searchQuery }}" 
                           class="form-control form-control-solid w-250px w-md-300px ps-12 fs-7" 
                           placeholder="Cari nama, email, no HP..." />
                    @if(!empty($searchQuery))
                    <a href="{{ route('admin.users.index', ['role' => $currentRole]) }}" class="btn btn-icon btn-sm btn-color-gray-400 btn-active-color-primary ms-n8">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    @endif
                </form>
            </div>

            <div class="card-toolbar d-flex flex-wrap gap-2">
                <div class="d-flex gap-1 bg-light p-1 rounded-2">
                    <a href="{{ route('admin.users.index', ['role' => 'all', 'q' => $searchQuery]) }}" 
                       class="btn btn-sm {{ $currentRole === 'all' ? 'btn-primary' : 'btn-color-gray-600 btn-active-white' }} px-3 py-1 fs-8">
                       Semua ({{ $stats['total'] }})
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'superadmin', 'q' => $searchQuery]) }}" 
                       class="btn btn-sm {{ $currentRole === 'superadmin' ? 'btn-danger' : 'btn-color-gray-600 btn-active-white' }} px-3 py-1 fs-8">
                       Superadmin ({{ $stats['superadmin'] }})
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'admin', 'q' => $searchQuery]) }}" 
                       class="btn btn-sm {{ $currentRole === 'admin' ? 'btn-primary' : 'btn-color-gray-600 btn-active-white' }} px-3 py-1 fs-8">
                       Admin Biasa ({{ $stats['admin'] }})
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'customer', 'q' => $searchQuery]) }}" 
                       class="btn btn-sm {{ $currentRole === 'customer' ? 'btn-dark' : 'btn-color-gray-600 btn-active-white' }} px-3 py-1 fs-8">
                       Pelanggan ({{ $stats['customer'] }})
                    </a>
                </div>

                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-plus fs-3 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Tambah User Baru
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-7 gy-4">
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-8 text-uppercase gs-0">
                            <th class="min-w-200px">Pengguna</th>
                            <th class="min-w-125px">Role Hak Akses</th>
                            <th class="min-w-125px">Nomor Telepon</th>
                            <th class="min-w-150px">Terdaftar Pada</th>
                            <th class="text-end min-w-100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        @forelse($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label {{ $u->role === 'superadmin' ? 'bg-light-danger text-danger' : ($u->role === 'admin' ? 'bg-light-primary text-primary' : 'bg-light-secondary text-gray-700') }} fw-bolder fs-5">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="text-gray-900 fw-bold fs-7 text-hover-primary">
                                            {{ $u->name }}
                                            @if($u->id === auth()->id())
                                                <span class="badge badge-light-info fw-bold fs-9 ms-1">Anda</span>
                                            @endif
                                        </div>
                                        <span class="text-muted fs-8">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $u->role_badge_class }} fw-bolder fs-8 px-3 py-1">
                                    {{ $u->role_label }}
                                </span>
                            </td>
                            <td>
                                <span class="text-gray-700 fs-8">{{ $u->phone ?: '-' }}</span>
                            </td>
                            <td>
                                <span class="text-gray-700 fs-8">{{ $u->created_at ? $u->created_at->translatedFormat('d M Y, H:i') : '-' }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-icon btn-light btn-active-light-primary btn-sm" title="Edit Pengguna">
                                        <i class="ki-duotone ki-pencil fs-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>

                                    @if($u->id !== auth()->id())
                                    <button type="button" 
                                            class="btn btn-icon btn-light btn-active-light-danger btn-sm" 
                                            title="Hapus Pengguna"
                                            onclick="confirmDeleteUser('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ route('admin.users.destroy', $u) }}')">
                                        <i class="ki-duotone ki-trash fs-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-muted">
                                <i class="ki-duotone ki-profile-user fs-3x text-muted mb-3 d-block">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                                Belum ada data pengguna yang sesuai dengan filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-stack flex-wrap pt-4">
                <div class="fs-7 text-gray-700">
                    Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} total data
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>
            <div class="modal-body px-8 pb-8 pt-0 text-center">
                <div class="symbol symbol-60px bg-light-danger rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center">
                    <i class="ki-duotone ki-trash text-danger fs-2hx">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                </div>
                <h3 class="text-gray-950 fw-bolder mb-2">Hapus Pengguna</h3>
                <p class="text-gray-600 fs-7 mb-6">
                    Apakah Anda yakin ingin menghapus akun <span class="fw-bold text-gray-900" id="deleteUserName"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteUserForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light fs-7 fw-semibold px-6" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fs-7 fw-bold px-6">Ya, Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDeleteUser(userId, userName, deleteUrl) {
    document.getElementById('deleteUserName').innerText = userName;
    document.getElementById('deleteUserForm').action = deleteUrl;
    var modalElement = document.getElementById('deleteUserModal');
    var modal = new bootstrap.Modal(modalElement);
    modal.show();
}
</script>
@endpush
@endsection
