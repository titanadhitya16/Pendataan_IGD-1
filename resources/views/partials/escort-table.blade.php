<div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    @php
        $activeFilter = null;
        $filterMessage = '';
        
        if(request('today_only')) {
            $activeFilter = 'today';
            $filterMessage = 'Menampilkan data hari ini saja (' . today()->format('d M Y') . ')';
        } elseif(request('week_only')) {
            $activeFilter = 'week';
            $filterMessage = 'Menampilkan data minggu ini (' . now()->startOfWeek()->format('d M') . ' - ' . now()->endOfWeek()->format('d M Y') . ')';
        } elseif(request('month_only')) {
            $activeFilter = 'month';
            $filterMessage = 'Menampilkan data bulan ini (' . now()->format('F Y') . ')';
        } elseif(request('date_specific')) {
            $activeFilter = 'specific';
            $filterMessage = 'Menampilkan data tanggal ' . \Carbon\Carbon::parse(request('date_specific'))->format('d M Y');
        }
    @endphp
    
    @if($activeFilter)
        <div class="alert alert-info border-0 rounded-0 mb-0" role="alert">
            <i class="fas fa-calendar-{{ $activeFilter == 'today' ? 'day' : ($activeFilter == 'week' ? 'week' : ($activeFilter == 'month' ? 'alt' : 'check')) }}"></i> 
            <strong>Filter Aktif:</strong> {{ $filterMessage }}
        </div>
    @endif
    <div class="card-body p-0">
        <!-- Navigation buttons for view all mode -->
        @if(request('view_all_mode') == 'true')
            <div class="view-all-navigation border-bottom">
                <div class="d-flex justify-content-between align-items-center py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-info d-flex align-items-center">
                            <i class="fas fa-list-ul me-2"></i> 
                            Mode Tampil Semua Data
                        </span>
                        @if(method_exists($escorts, 'total'))
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Halaman {{ $escorts->currentPage() }} dari {{ $escorts->lastPage() }} 
                                ({{ number_format($escorts->total()) }} total data)
                            </small>
                        @endif
                    </div>
                    <div class="btn-group" role="group" aria-label="Table Navigation">
                        @if(method_exists($escorts, 'currentPage'))
                            <button class="btn btn-sm btn-outline-primary table-nav-btn" 
                                    data-action="previous" 
                                    data-page="{{ $escorts->currentPage() - 1 }}"
                                    title="Halaman Sebelumnya"
                                    {{ $escorts->currentPage() <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-chevron-left me-1"></i> 
                                <span class="d-none d-md-inline">Sebelumnya</span>
                                <span class="d-md-none">Prev</span>
                            </button>
                            <span class="btn btn-sm btn-light disabled d-flex align-items-center">
                                <small>{{ $escorts->currentPage() }} / {{ $escorts->lastPage() }}</small>
                            </span>
                            <button class="btn btn-sm btn-outline-primary table-nav-btn" 
                                    data-action="next" 
                                    data-page="{{ $escorts->currentPage() + 1 }}"
                                    title="Halaman Selanjutnya"
                                    {{ $escorts->currentPage() >= $escorts->lastPage() ? 'disabled' : '' }}>
                                <span class="d-none d-md-inline">Selanjutnya</span>
                                <span class="d-md-none">Next</span>
                                <i class="fas fa-chevron-right ms-1"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-dark">
                        <th class="fw-bold px-4">No</th>
                        <th class="fw-bold">Kategori</th>
                        <th class="fw-bold">Nama Pengantar</th>
                        <th class="fw-bold">No. HP</th>
                        <th class="fw-bold">Nama Ambulan</th>
                        <th class="fw-bold">Nama Pasien</th>
                        <th class="fw-bold">Jenis Kelamin Pasien</th>
                <th scope="col">Status</th>
                <th scope="col">Tanggal Dibuat</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($escorts as $index => $escort)
                <tr>
                    <td class="px-4">
                        {{ 
                            method_exists($escorts, 'currentPage') 
                                ? ($escorts->currentPage() - 1) * $escorts->perPage() + $index + 1 
                                : $index + 1 
                        }}
                    </td>
                    <td>
                        <span class="badge kategori-badge
                            @if($escort->kategori_pengantar == 'Polisi') bg-primary
                            @elseif($escort->kategori_pengantar == 'Ambulans') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ $escort->kategori_pengantar }}
                        </span>
                    </td>
                    <td>{{ $escort->nama_pengantar }}</td>
                    <td>
                        <a href="tel:{{ $escort->nomor_hp }}" class="text-decoration-none">
                            {{ $escort->nomor_hp }}
                        </a>
                    </td>
                    <td>
                        <code>{{ $escort->nama_ambulan }}</code>
                    </td>
                    <td>{{ $escort->nama_pasien }}</td>
                    <td>{{ $escort->jenis_kelamin_pasien }}</td>
                    <td>
                        <span class="badge status-badge-{{ $escort->id }} {{ $escort->getStatusBadgeClass() }}">
                            {{ $escort->getStatusDisplayName() }}
                        </span>
                    </td>
                    <td>
                        <small>
                            {{ $escort->created_at->format('d/m/Y H:i') }}<br>
                            <span class="text-muted">{{ $escort->created_at->diffForHumans() }}</span>
                        </small>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <!-- Detail Button -->
                            <button type="button" 
                                    class="btn btn-sm btn-info detail-btn" 
                                    data-escort="{{ json_encode([
                                        'id' => $escort->id,
                                        'nama_pengantar' => $escort->nama_pengantar,
                                        'kategori_pengantar' => $escort->kategori_pengantar,
                                        'nomor_hp' => $escort->nomor_hp,
                                        'nama_ambulan' => $escort->nama_ambulan,
                                        'nama_pasien' => $escort->nama_pasien,
                                        'jenis_kelamin_pasien' => $escort->jenis_kelamin_pasien,
                                        'status' => $escort->getStatusDisplayName(),
                                        'status_badge_class' => $escort->getStatusBadgeClass(),
                                        'created_at' => $escort->created_at->format('d/m/Y H:i'),
                                        'created_at_diff' => $escort->created_at->diffForHumans(),
                                        'foto_pengantar' => $escort->foto_pengantar ? asset('storage/' . $escort->foto_pengantar) : null
                                    ]) }}"
                                    title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($escort->status === 'pending')
                                <button type="button" 
                                        class="btn btn-sm btn-outline-success btn-status-update" 
                                        data-escort-id="{{ $escort->id }}" 
                                        data-status="verified" 
                                        data-status-text="Terverifikasi"
                                        title="Verifikasi">
                                    <i class="fas fa-check"></i>
                                </button>

                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger btn-status-update" 
                                        data-escort-id="{{ $escort->id }}" 
                                        data-status="rejected" 
                                        data-status-text="Ditolak"
                                        title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p class="mb-0">Tidak ada data escort yang ditemukan</p>
                            <small>Coba ubah filter pencarian atau tunggu respon dari server</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

        <!-- Bottom navigation for view all mode -->
        @if(request('view_all_mode') == 'true')
            <div class="view-all-bottom-navigation border-top">
                <div class="d-flex justify-content-between align-items-center py-3 px-4">
                    <div class="text-muted small d-flex align-items-center">
                        <i class="fas fa-table me-2"></i>
                        @if(method_exists($escorts, 'total'))
                            Menampilkan 
                            <strong class="mx-1">{{ number_format($escorts->firstItem() ?? 0) }}</strong> 
                            sampai 
                            <strong class="mx-1">{{ number_format($escorts->lastItem() ?? 0) }}</strong> 
                            dari 
                            <strong class="mx-1">{{ number_format($escorts->total()) }}</strong> 
                            hasil
                        @endif
                    </div>
                    <div class="btn-group" role="group" aria-label="Bottom Table Navigation">
                        @if(method_exists($escorts, 'currentPage'))
                            <button class="btn btn-sm btn-outline-primary table-nav-btn" 
                                    data-action="previous" 
                                    data-page="{{ $escorts->currentPage() - 1 }}"
                                    title="Halaman Sebelumnya"
                                    {{ $escorts->currentPage() <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-chevron-left me-1"></i> 
                                <span class="d-none d-sm-inline">Sebelumnya</span>
                            </button>
                            <button class="btn btn-sm btn-outline-primary table-nav-btn" 
                                    data-action="next" 
                                    data-page="{{ $escorts->currentPage() + 1 }}"
                                    title="Halaman Selanjutnya"
                                    {{ $escorts->currentPage() >= $escorts->lastPage() ? 'disabled' : '' }}>
                                <span class="d-none d-sm-inline">Selanjutnya</span>
                                <i class="fas fa-chevron-right ms-1"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handler untuk tombol detail
    $(document).on('click', '.detail-btn', function() {
        const escort = JSON.parse($(this).attr('data-escort'));
        
        let photoHtml = '';
        if (escort.foto_pengantar) {
            photoHtml = `
                <div class="text-center mb-4">
                    <img src="${escort.foto_pengantar}" 
                         class="img-fluid rounded show-full-photo" 
                         alt="Foto ${escort.nama_pengantar}"
                         style="max-height: 300px; cursor: zoom-in;"
                         data-full-photo="${escort.foto_pengantar}">
                </div>`;
        } else {
            photoHtml = `
                <div class="alert alert-info text-center mb-4">
                    <i class="fas fa-image-slash fa-2x mb-2"></i>
                    <p class="mb-0">Tidak ada foto</p>
                </div>`;
        }

        const detailHtml = `
            <div class="row">
                <div class="col-12 text-start">
                    ${photoHtml}
                    <table class="table table-sm mb-0 text-start">
                        <tr>
                            <td width="35%" class="text-start"><strong>Nama Pengantar</strong></td>
                            <td class="text-start">: ${escort.nama_pengantar}</td>
                        </tr>
                        <tr>
                            <td><strong>Kategori</strong></td>
                            <td>: ${escort.kategori_pengantar}</td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>: <a href="tel:${escort.nomor_hp}">${escort.nomor_hp}</a></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Ambulanr</strong></td>
                            <td>: <code>${escort.nama_ambulan}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Pasien</strong></td>
                            <td>: ${escort.nama_pasien}</td>
                        </tr>
                         <tr>
                            <td><strong>Jenis Kelamin Pasien</strong></td>
                            <td>: ${escort.jenis_kelamin_pasien}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: <span class="badge ${escort.status_badge_class}">${escort.status}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Masuk</strong></td>
                            <td>: ${escort.created_at}
                                <small class="text-muted">${escort.created_at_diff}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>`;

        Swal.fire({
            title: `Detail Pengantar - ${escort.nama_pengantar}`,
            html: detailHtml,
            width: 800,
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                container: 'swal2-detail-modal'
            }
        });
    });

    // Handler untuk zoom foto
    $(document).on('click', '.show-full-photo', function() {
        const photoUrl = $(this).data('full-photo');
        
        Swal.fire({
            imageUrl: photoUrl,
            imageAlt: 'Foto Pengantar',
            width: 'auto',
            padding: '1rem',
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                image: 'img-fluid',
                container: 'swal2-photo-modal'
            }
        });
    });

    // Handler untuk navigasi tabel (view all mode)
    $(document).on('click', '.table-nav-btn:not([disabled])', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const action = button.data('action');
        const page = button.data('page');
        
        if (!page || page < 1) {
            return;
        }
        
        // Show loading state
        const originalHtml = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
        
        // Build navigation URL
        const formData = $('#filter-form').serialize();
        const url = `{{ route('dashboard') }}?${formData}&view_all_mode=true&per_page=50&page=${page}`;
        
        // Use the global loadDataFromUrl function from dashboard
        if (typeof window.loadDataFromUrl === 'function') {
            window.loadDataFromUrl(url, function(response) {
                // Success callback
                button.html(originalHtml).prop('disabled', false);
                
                // Show navigation feedback
                const actionText = action === 'next' ? 'Halaman Selanjutnya' : 'Halaman Sebelumnya';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: actionText,
                        text: `Berhasil pindah ke halaman ${page}`,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            }, function(xhr) {
                // Error callback
                button.html(originalHtml).prop('disabled', false);
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Navigasi Gagal',
                        text: 'Gagal memuat halaman. Silakan coba lagi.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } else {
            // Fallback if function not available
            window.location.href = url;
        }
    });
});
</script>

<style>
/* Custom styling untuk modal SweetAlert2 */
.swal2-detail-modal .swal2-popup {
    padding: 2rem;
    text-align: left;
}

.swal2-detail-modal .swal2-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    text-align: left;
}

.swal2-detail-modal .table td {
    padding: 0.5rem;
    text-align: left !important;
}

.swal2-detail-modal .swal2-html-container {
    text-align: left;
}

.swal2-detail-modal .table {
    text-align: left;
    margin-left: 0;
}

.swal2-photo-modal {
    background: rgba(0, 0, 0, 0.9);
}

.swal2-photo-modal .swal2-popup {
    background: transparent;
    box-shadow: none;
}

.swal2-photo-modal .swal2-image {
    max-height: 85vh !important;
    border-radius: 0.5rem;
}

/* View All Mode Navigation Styling */
.view-all-navigation,
.view-all-bottom-navigation {
    background-color: var(--card-header-bg, #f8f9fc);
    color: var(--bs-body-color, #5a5c69);
    transition: all 0.3s ease;
}

[data-bs-theme="dark"] .view-all-navigation,
[data-bs-theme="dark"] .view-all-bottom-navigation {
    background-color: var(--card-header-bg, #1a1a1a);
    color: var(--bs-body-color, #e0e0e0);
}

.view-all-navigation {
    border-bottom-color: var(--card-border-color, #e3e6f0);
}

.view-all-bottom-navigation {
    border-top-color: var(--card-border-color, #e3e6f0);
}

[data-bs-theme="dark"] .view-all-navigation {
    border-bottom-color: var(--card-border-color, #333333);
}

[data-bs-theme="dark"] .view-all-bottom-navigation {
    border-top-color: var(--card-border-color, #333333);
}

.table-nav-btn {
    transition: all 0.2s ease;
    min-width: 100px;
    border-color: var(--input-border, #d1d3e2);
    color: var(--bs-body-color, #5a5c69);
    background-color: transparent;
}

.table-nav-btn:not([disabled]):hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background-color: var(--table-row-hover-bg, #f2f3f7);
    border-color: #4e73df;
    color: #4e73df;
}

[data-bs-theme="dark"] .table-nav-btn {
    border-color: var(--input-border, #333333);
    color: var(--bs-body-color, #e0e0e0);
    background-color: transparent;
}

[data-bs-theme="dark"] .table-nav-btn:not([disabled]):hover {
    background-color: var(--table-row-hover-bg, #1e1e1e);
    border-color: #1cc88a;
    color: #1cc88a;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.table-nav-btn[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: var(--input-bg, #ffffff);
    color: var(--text-muted-custom, #858796);
}

[data-bs-theme="dark"] .table-nav-btn[disabled] {
    background-color: var(--input-bg, #1a1a1a);
    color: var(--text-muted-custom, #b0b0b0);
}

.table-nav-btn[disabled]:hover {
    transform: none;
    box-shadow: none;
}

.view-all-navigation .badge {
    font-size: 0.75em;
    padding: 0.5em 0.75em;
    background-color: #36b9cc !important;
    border: none;
}

[data-bs-theme="dark"] .view-all-navigation .badge {
    background-color: #1cc88a !important;
}

.view-all-navigation .text-muted,
.view-all-bottom-navigation .text-muted {
    color: var(--text-muted-custom, #858796) !important;
    font-size: 0.85em;
}

[data-bs-theme="dark"] .view-all-navigation .text-muted,
[data-bs-theme="dark"] .view-all-bottom-navigation .text-muted {
    color: var(--text-muted-custom, #b0b0b0) !important;
}

.view-all-bottom-navigation .text-muted strong {
    color: var(--bs-body-color, #5a5c69) !important;
    font-weight: 600;
}

[data-bs-theme="dark"] .view-all-bottom-navigation .text-muted strong {
    color: var(--bs-body-color, #e0e0e0) !important;
}

/* Animation for navigation actions */
@keyframes tableNavigation {
    0% { opacity: 0.7; transform: scale(0.98); }
    50% { opacity: 1; transform: scale(1.02); }
    100% { opacity: 1; transform: scale(1); }
}

.table-responsive {
    animation: tableNavigation 0.3s ease-out;
}

/* Page counter styling */
.btn-light.disabled {
    background-color: var(--input-bg, #ffffff);
    color: var(--text-muted-custom, #858796);
    border-color: var(--input-border, #d1d3e2);
    opacity: 1;
}

[data-bs-theme="dark"] .btn-light.disabled {
    background-color: var(--input-bg, #1a1a1a);
    color: var(--text-muted-custom, #b0b0b0);
    border-color: var(--input-border, #333333);
}

/* Icon styling */
.view-all-navigation i,
.view-all-bottom-navigation i {
    color: inherit;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .view-all-navigation .d-flex,
    .view-all-bottom-navigation .d-flex {
        flex-direction: column;
        gap: 0.75rem;
        align-items: stretch !important;
    }
    
    .table-nav-btn {
        min-width: auto;
        flex: 1;
    }
    
    .view-all-navigation .btn-group,
    .view-all-bottom-navigation .btn-group {
        width: 100%;
        display: flex;
    }
    
    .view-all-navigation .gap-3 {
        gap: 0.5rem !important;
    }
}

@media (max-width: 576px) {
    .view-all-navigation,
    .view-all-bottom-navigation {
        padding: 0.75rem !important;
    }
    
    .view-all-navigation .badge {
        font-size: 0.7em;
        padding: 0.4em 0.6em;
    }
    
    .view-all-navigation .text-muted,
    .view-all-bottom-navigation .text-muted {
        font-size: 0.8em;
    }
}

/* Loading state for navigation buttons */
.table-nav-btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
</script>

@if(method_exists($escorts, 'hasPages') && $escorts->hasPages())
    <div class="d-flex justify-content-center align-items-center mt-3">
        <div class="text-muted small">
            Menampilkan {{ $escorts->firstItem() ?? 0 }} sampai {{ $escorts->lastItem() ?? 0 }} 
            dari {{ $escorts->total() }} hasil
        </div>
    </div>
@elseif(is_countable($escorts) && count($escorts) > 0)
    <div class="d-flex justify-content-center align-items-center mt-3">
        <div class="text-muted small">
            Menampilkan {{ count($escorts) }} dari {{ count($escorts) }} hasil
            @if(request('view_all') === 'true')
                <span class="badge bg-info ms-2">Semua Data</span>
            @endif
        </div>
    </div>
@endif