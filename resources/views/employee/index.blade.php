@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row mb-0">
            <div class="col-lg-9 col-xl-6">
                <h4 class="mb-3">{{ $pageTitle }}</h4>
            </div>
            <div class="col-lg-3 col-xl-6">
                <ul class="list-inline mb-0 float-end">
                    <li class="list-inline-item">
                        <a href="{{ route('employees.exportExcel') }}" class="btn btn-outline-success">
                            <i class="bi bi-download me-1"></i> to Excel
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('employees.exportPdf') }}" class="btn btn-outline-danger">
                            <i class="bi bi-download me-1"></i> to PDF
                        </a>
                    </li>
                    <li class="list-inline-item">|</li>
                    <button type="button" class="btn" style="background-color: #b11116; color: white;" data-bs-toggle="modal" data-bs-target="#createEmployee">
                        <i class="bi bi-plus-circle me-1"></i>Create Employee
                    </button>
                </ul>
            </div>
        </div>

        <hr>

        <!-- Modal Create Employee -->
        <div class="modal fade" id="createEmployee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Karyawan</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="firstName" class="form-label">First Name</label>
                                <input class="form-control @error('firstName') is-invalid @enderror" type="text" name="firstName" id="firstName" value="{{ old('firstName') }}" placeholder="Enter First Name">
                                @error('firstName')
                                    <div class="text-danger">
                                         <small>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input class="form-control @error('lastName') is-invalid @enderror" type="text" name="lastName" id="lastName" value="{{ old('lastName') }}" placeholder="Enter Last Name">
                                @error('lastName')
                                    <div class="text-danger">
                                         <small>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" id="email" value="{{ old('email') }}" placeholder="Enter Email">
                                @error('email')
                                    <div class="text-danger">
                                         <small>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input class="form-control @error('birth_date') is-invalid @enderror" type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" placeholder="Enter Birth Date">
                                @error('birth_date')
                                    <div class="text-danger">
                                        <small>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select name="position" id="position" class="form-select">
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}" {{ old('position') == $position->id ? 'selected' : '' }}>{{ $position->code.' - '.$position->name }}</option>
                                    @endforeach
                                </select>
                                @error('position')
                                    <div class="text-danger">
                                         <small>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="cv" class="form-label">
                                     Curriculum Vitae (CV)
                                </label>
                                <input type="file" class="form-control" name="cv" id="cv">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn" style="background-color: #b11116; color: white; border-color: #b11116;">
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
         </div>

        <!-- Data Table -->
        <div class="table-responsive border p-3 rounded-3 mb-5">
            <table class="table table-bordered table-hover table-striped mb-0 bg-white datatable" id="employeeTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Birth Date</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

        <style>
            /* Warna dasar untuk semua tombol pagination */
            .pagination .page-link {
                background-color: #b11116 !important; /* Warna background */
                color: white !important; /* Warna teks */
                border-color: #b11116 !important; /* Warna border */
            }

            /* Warna untuk halaman aktif */
            .pagination .page-item.active .page-link {
                background-color: #b11116 !important; /* Warna background aktif */
                border-color: #b11116 !important;
                color: white !important; /* Warna teks aktif */
            }

            /* Warna saat tombol di-hover */
            .pagination .page-link:hover {
                background-color: #a10f12 !important; /* Warna hover lebih gelap */
                border-color: #a10f12 !important;
                color: white !important; /* Warna teks saat hover */
            }
        </style>

    </div>
@endsection

@section('scripts')
<script>
    $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('employees.getData') }}',
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'no', name: 'no' },
            { data: 'firstName', name: 'firstName' },
            { data: 'lastName', name: 'lastName' },
            { data: 'email', name: 'email' },
            { data: 'birth_date', name: 'birth_date' }, // Menampilkan birth_date
            { data: 'position.name', name: 'position.name' }, // Menampilkan nama posisi
            { data: 'actions', name: 'actions', orderable: false, searchable: false } // Tombol aksi
        ]
    });
</script>
@endsection
