<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body style="background-image: url('{{ asset('aurora.jpg') }}'); 
             background-size: cover; 
             background-position: center; 
             background-repeat: no-repeat; 
             background-attachment: fixed; 
             background-color: #06141d; /* TRICK: Pengganti warna putih saat loading */
             min-height: 100vh; 
             margin: 0;" class="position-relative text-dark font-sans antialiased">

    <div class="position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50" style="z-index: 1; pointer-events: none;">
    </div>

    <div class="container py-5" style="position: relative; z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card shadow border-0 bg-white bg-opacity-75"
                    style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="text-dark fw-bold m-0">
                                <i class="bi bi-clipboard-check text-primary"></i> To-Do List
                            </h2>

                            @if(Auth::check())
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if(session('success'))
                            <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('todo.store') }}" method="POST" class="row g-3 mb-4">
                            @csrf
                            <div class="col-md-6">
                                <input type="text" name="task_name" class="form-control"
                                    placeholder="Tuliskan nama tugas baru..." required>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="deadline" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i>
                                    Tambah</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="8%">No.</th>
                                        <th width="42%">Nama Tugas</th>
                                        <th width="15%">Deadline</th>
                                        <th width="15%" class="text-center">Checklist</th>
                                        <th width="20%" class="text-center">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todos as $index => $todo)
                                        @php
                                            $isPast = \Carbon\Carbon::parse($todo->deadline)->isPast();
                                            $isOverdue = !$todo->is_completed && $isPast;
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if($todo->is_completed)
                                                    <del class="text-muted italic">{{ $todo->task_name }}</del>
                                                @else
                                                    <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                                        {{ $todo->task_name }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $isOverdue ? 'bg-danger' : ($todo->is_completed ? 'bg-secondary' : 'bg-success') }}">
                                                    {{ date('d-m-Y', strtotime($todo->deadline)) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('todo.check', $todo->id) }}" method="POST"
                                                    class="form-checklist">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-checklist {{ $todo->is_completed ? 'btn-success' : 'btn-outline-success' }}">
                                                        @if($todo->is_completed)
                                                            <i class="bi bi-check-circle-fill"></i> Selesai
                                                        @else
                                                            <i class="bi bi-circle"></i> Belum
                                                        @endif
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $todo->id }}">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </button>

                                                <form action="{{ route('todo.destroy', $todo->id) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editModal{{ $todo->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('todo.update', $todo->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Tugas</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Tugas</label>
                                                                <input type="text" name="task_name" class="form-control"
                                                                    value="{{ $todo->task_name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Deadline</label>
                                                                <input type="date" name="deadline" class="form-control"
                                                                    value="{{ $todo->deadline }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada tugas. Silakan
                                                tambah tugas baru di atas!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(function () {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 2000);
            }
        });
    </script>
    <script>
        document.querySelectorAll('.form-checklist').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Kunci halaman agar tidak refresh

                let formElement = this;
                let url = formElement.getAttribute('action');
                let token = formElement.querySelector('input[name="_token"]').value;
                let button = formElement.querySelector('.btn-checklist');
                let row = formElement.closest('tr');
                let tdTask = row.querySelector('td:nth-child(2)');
                let badge = row.querySelector('td:nth-child(3) .badge');

                // Mengambil teks tugas secara bersih (tanpa tag <del> atau <span>)
                let taskText = tdTask.textContent.trim();

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(new FormData(formElement))
                })
                    .then(response => {
                        if (response.ok) {
                            // JIKA SEBELUMNYA "BELUM" -> BERUBAH MENJADI "SELESAI"
                            if (button.classList.contains('btn-outline-success')) {
                                button.className = 'btn btn-sm btn-checklist btn-success';
                                button.innerHTML = '<i class="bi bi-check-circle-fill"></i> Selesai';
                                tdTask.innerHTML = `<del class="text-muted italic">${taskText}</del>`;

                                if (badge) badge.className = 'badge bg-secondary';
                            }
                            // JIKA SEBELUMNYA "SELESAI" -> BERUBAH MENJADI "BELUM" (UNCHECKLIST)
                            else {
                                button.className = 'btn btn-sm btn-checklist btn-outline-success';
                                button.innerHTML = '<i class="bi bi-circle"></i> Belum';

                                // Kembalikan teks biasa tanpa coretan
                                tdTask.innerHTML = `<span>${taskText}</span>`;

                                // Hitung ulang warna badge berdasarkan tanggal tenggat secara real-time
                                if (badge) {
                                    // Ambil tanggal dari badge (format d-m-Y)
                                    let parts = badge.textContent.trim().split('-');
                                    let deadlineDate = new Date(parts[2], parts[1] - 1, parts[0]);
                                    let today = new Date();
                                    today.setHours(0, 0, 0, 0); // Reset jam agar adil

                                    if (deadlineDate < today) {
                                        badge.className = 'badge bg-danger'; // Merah jika telat
                                        tdTask.querySelector('span').className = 'text-danger fw-bold';
                                    } else {
                                        badge.className = 'badge bg-success'; // Hijau jika aman
                                    }
                                }
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</body>

</html>