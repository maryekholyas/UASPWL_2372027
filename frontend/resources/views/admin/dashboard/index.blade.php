@extends('admin.adminNavigation')

@section('title', 'Manage Staff & Member')
@section('nav-color', 'bg-primary')
@section('dashboard-name', 'Admin Dashboard - Staff & Member Management')

@section('content')
<!-- Navigation Button untuk membuka masing-masing grup -->
<div class="d-flex justify-content-center mb-4">
    <button class="btn btn-outline-primary mx-2" id="btnPanitia">Panitia</button>
    <button class="btn btn-outline-primary mx-2" id="btnTimKeuangan">Tim Keuangan</button>
    <button class="btn btn-outline-primary mx-2" id="btnMember">Member</button>
</div>

<!-- Bagian Tambah Panitia (hanya untuk grup Panitia) -->
<div id="addPanitiaSection" class="mb-4" style="display: none;">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPanitiaModal">
        Tambah Panitia
    </button>
</div>

<!-- Modal Add Panitia -->
<div class="modal fade" id="addPanitiaModal" tabindex="-1" aria-labelledby="addPanitiaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.staff.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addPanitiaModalLabel">Tambah Panitia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Field Field Tambah Panitia -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="panitia" selected>Panitia</option>
                            <option value="tim_keuangan">Tim Keuangan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Container untuk Grup -->
<div id="groupContent">
    <!-- Grup Panitia -->
    <div id="groupPanitia" style="display: none;">
        <h4>Daftar Panitia</h4>
        <table class="table table-bordered" id="panitiaTable">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $s)
                    @if($s['role'] == 'panitia')
                        <tr>
                            <td>{{ $s['nama'] }}</td>
                            <td>{{ $s['email'] }}</td>
                            <td><span class="badge bg-primary">Panitia</span></td>
                            <td>
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPanitiaModal-{{ $s['_id'] }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <!-- Tombol Delete -->
                                <form action="{{ route('admin.staff.delete', $s['_id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus panitia ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal Edit Panitia -->
                        <div class="modal fade" id="editPanitiaModal-{{ $s['_id'] }}" tabindex="-1" aria-labelledby="editPanitiaModalLabel-{{ $s['_id'] }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.staff.update', $s['_id']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPanitiaModalLabel-{{ $s['_id'] }}">Edit Panitia</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="nama-{{ $s['_id'] }}" class="form-label">Nama</label>
                                                    <input type="text" name="nama" id="nama-{{ $s['_id'] }}" class="form-control" value="{{ $s['nama'] }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email-{{ $s['_id'] }}" class="form-label">Email</label>
                                                    <input type="email" name="email" id="email-{{ $s['_id'] }}" class="form-control" value="{{ $s['email'] }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="password-{{ $s['_id'] }}" class="form-label">Password</label>
                                                    <input type="password" name="password" id="password-{{ $s['_id'] }}" class="form-control" placeholder="Kosongkan jika tidak diubah">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="role-{{ $s['_id'] }}" class="form-label">Role</label>
                                                    <select name="role" id="role-{{ $s['_id'] }}" class="form-select" required>
                                                        <option value="panitia" {{ $s['role'] == 'panitia' ? 'selected' : '' }}>Panitia</option>
                                                        <option value="tim_keuangan" {{ $s['role'] == 'tim_keuangan' ? 'selected' : '' }}>Tim Keuangan</option>
                                                        <option value="member" {{ $s['role'] == 'member' ? 'selected' : '' }}>Member</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Edit Panitia -->
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Grup Tim Keuangan -->
    <div id="groupTimKeuangan" style="display: none;">
        <h4>Daftar Tim Keuangan</h4>
        <table class="table table-bordered" id="financeTable">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $s)
                    @if($s['role'] == 'tim_keuangan')
                        <tr>
                            <td>{{ $s['nama'] }}</td>
                            <td>{{ $s['email'] }}</td>
                            <td><span class="badge bg-success">Tim Keuangan</span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFinanceModal-{{ $s['_id'] }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.staff.delete', $s['_id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus tim keuangan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal Edit Tim Keuangan -->
                        <div class="modal fade" id="editFinanceModal-{{ $s['_id'] }}" tabindex="-1" aria-labelledby="editFinanceModalLabel-{{ $s['_id'] }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.staff.update', $s['_id']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editFinanceModalLabel-{{ $s['_id'] }}">Edit Tim Keuangan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="nama-{{ $s['_id'] }}" class="form-label">Nama</label>
                                                    <input type="text" name="nama" id="nama-{{ $s['_id'] }}" class="form-control" value="{{ $s['nama'] }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email-{{ $s['_id'] }}" class="form-label">Email</label>
                                                    <input type="email" name="email" id="email-{{ $s['_id'] }}" class="form-control" value="{{ $s['email'] }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="password-{{ $s['_id'] }}" class="form-label">Password</label>
                                                    <input type="password" name="password" id="password-{{ $s['_id'] }}" class="form-control" placeholder="Kosongkan jika tidak diubah">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="role-{{ $s['_id'] }}" class="form-label">Role</label>
                                                    <select name="role" id="role-{{ $s['_id'] }}" class="form-select" required>
                                                        <option value="tim_keuangan" {{ $s['role'] == 'tim_keuangan' ? 'selected' : '' }}>Tim Keuangan</option>
                                                        <option value="panitia" {{ $s['role'] == 'panitia' ? 'selected' : '' }}>Panitia</option>
                                                        <option value="member" {{ $s['role'] == 'member' ? 'selected' : '' }}>Member</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Edit Tim Keuangan -->
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Grup Member -->
    <div id="groupMember" style="display: none;">
        <h4>Daftar Member</h4>
        <table class="table table-bordered" id="memberTable">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $s)
                    @if($s['role'] == 'member')
                        <tr>
                            <td>{{ $s['nama'] }}</td>
                            <td>{{ $s['email'] }}</td>
                            <td><span class="badge bg-secondary">Member</span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMemberModal-{{ $s['_id'] }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.staff.delete', $s['_id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus member ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal Edit Member -->
                        <div class="modal fade" id="editMemberModal-{{ $s['_id'] }}" tabindex="-1" aria-labelledby="editMemberModalLabel-{{ $s['_id'] }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.staff.update', $s['_id']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editMemberModalLabel-{{ $s['_id'] }}">Edit Member</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="nama-{{ $s['_id'] }}" class="form-label">Nama</label>
                                                    <input type="text" name="nama" id="nama-{{ $s['_id'] }}" class="form-control" value="{{ $s['nama'] }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email-{{ $s['_id'] }}" class="form-label">Email</label>
                                                    <input type="email" name="email" id="email-{{ $s['_id'] }}" class="form-control" value="{{ $s['email'] }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="password-{{ $s['_id'] }}" class="form-label">Password</label>
                                                    <input type="password" name="password" id="password-{{ $s['_id'] }}" class="form-control" placeholder="Kosongkan jika tidak diubah">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="role-{{ $s['_id'] }}" class="form-label">Role</label>
                                                    <select name="role" id="role-{{ $s['_id'] }}" class="form-select" required>
                                                        <option value="member" {{ $s['role'] == 'member' ? 'selected' : '' }}>Member</option>
                                                        <option value="panitia" {{ $s['role'] == 'panitia' ? 'selected' : '' }}>Panitia</option>
                                                        <option value="tim_keuangan" {{ $s['role'] == 'tim_keuangan' ? 'selected' : '' }}>Tim Keuangan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Edit Member -->
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<script>
  // Tampilkan grup Panitia secara default
  document.getElementById('groupPanitia').style.display = 'block';
  document.getElementById('addPanitiaSection').style.display = 'block';

  document.getElementById('btnPanitia').addEventListener('click', function() {
      document.getElementById('groupPanitia').style.display = 'block';
      document.getElementById('groupTimKeuangan').style.display = 'none';
      document.getElementById('groupMember').style.display = 'none';
      document.getElementById('addPanitiaSection').style.display = 'block';
  });

  document.getElementById('btnTimKeuangan').addEventListener('click', function() {
      document.getElementById('groupPanitia').style.display = 'none';
      document.getElementById('groupTimKeuangan').style.display = 'block';
      document.getElementById('groupMember').style.display = 'none';
      document.getElementById('addPanitiaSection').style.display = 'none';
  });

  document.getElementById('btnMember').addEventListener('click', function() {
      document.getElementById('groupPanitia').style.display = 'none';
      document.getElementById('groupTimKeuangan').style.display = 'none';
      document.getElementById('groupMember').style.display = 'block';
      document.getElementById('addPanitiaSection').style.display = 'none';
  });
</script>
@endsection