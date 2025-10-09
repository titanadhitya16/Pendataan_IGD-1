@extends('layout.app')

@section('title', 'Dashboard IGD')

@push('styles')
<link href="https://cdnjs.com/cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0 text-gray-800">Dashboard Pendataan IGD</h1>
    <button id="darkModeToggle" class="btn btn-outline-secondary">
        <i class="fas fa-moon"></i>
    </button>
    </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-primary text-white shadow-sm rounded-4 border-0 h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h6 class="card-title mb-1 text-white-50 fw-normal">Total Pengantar</h6>
                            <h2 class="mb-0 fw-bold" id="total-count">{{ $stats['total'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-info text-white shadow-sm rounded-4 border-0 h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-calendar-day fa-2x text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h6 class="card-title mb-1 text-white-50 fw-normal">Hari Ini</h6>
                            <h2 class="mb-0 fw-bold" id="today-count">{{ $stats['today'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-warning text-white shadow-sm rounded-4 border-0 h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-clock fa-2x text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h6 class="card-title mb-1 text-white-50 fw-normal">Menunggu</h6>
                            <h2 class="mb-0 fw-bold" id="pending-count">{{ $stats['pending'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-success text-white shadow-sm rounded-4 border-0 h-100 stats-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h6 class="card-title mb-1 text-white-50 fw-normal">Terverifikasi</h6>
                            <h2 class="mb-0 fw-bold" id="verified-count">{{ $stats['verified'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-filter me-2"></i>Filter & Pencarian
            </h6>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
                <i class="fas fa-sliders-h me-1"></i> Filter Lanjutan
            </button>
        </div>
        <div class="card-body">
            <form id="filter-form">
                <div class="row g-3 mb-3">
                    <div class="col-lg-8">
                        <label for="search" class="form-label">Pencarian Cepat</label>
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Cari nama pengantar, nama pasien, nomor HP, atau nama ambulan..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-4 d-flex align-items-end">
                        <div class="d-grid gap-2 d-md-flex w-100">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary flex-fill">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>

                <div class="collapse" id="advancedFilters">
                    <hr>
                    <div class="row g-3">
                         <div class="col-lg-3 col-md-6">
                            <label for="kategori" class="form-label">Kategori Pengantar</label>
                            <select class="form-select" id="kategori" name="kategori">
                                <option value="">Semua Kategori</option>
                                <option value="Satlantas" {{ request('kategori') == 'Satlantas' ? 'selected' : '' }}>Satlantas</option>
                                <option value="Ambulans" {{ request('kategori') == 'Ambulans' ? 'selected' : '' }}>Ambulans</option>
                                <option value="Perorangan" {{ request('kategori') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                                <option value="Karyawan" {{ request('kategori') == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="status" class="form-label">Status Verifikasi</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua</option>
                                <option value="pending" @if(request('status') == 'pending') selected @endif>Menunggu</option>
                                <option value="verified" @if(request('status') == 'verified') selected @endif>Terverifikasi</option>
                                <option value="rejected" @if(request('status') == 'rejected') selected @endif>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="jenis_kelamin_pasien" class="form-label">Jenis Kelamin Pasien</label>
                            <select class="form-select" id="jenis_kelamin_pasien" name="jenis_kelamin_pasien">
                                <option value="">Semua</option>
                                <option value="Laki-laki" @if(request('jenis_kelamin_pasien') == 'Laki-laki') selected @endif>Laki-laki</option>
                                <option value="Perempuan" @if(request('jenis_kelamin_pasien') == 'Perempuan') selected @endif>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Filter Tanggal Cepat</label>
                            <div class="d-flex flex-wrap gap-2">
                                <input type="checkbox" class="btn-check" id="today_only" name="today_only" value="1" autocomplete="off" @if(request('today_only')) checked @endif>
                                <label class="btn btn-sm btn-outline-secondary" for="today_only">Hari Ini</label>

                                <input type="checkbox" class="btn-check" id="week_only" name="week_only" value="1" autocomplete="off" @if(request('week_only')) checked @endif>
                                <label class="btn btn-sm btn-outline-secondary" for="week_only">Minggu Ini</label>

                                <input type="checkbox" class="btn-check" id="month_only" name="month_only" value="1" autocomplete="off" @if(request('month_only')) checked @endif>
                                <label class="btn btn-sm btn-outline-secondary" for="month_only">Bulan Ini</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-lg-3">
                            <label for="date_specific" class="form-label">Tanggal Spesifik</label>
                            <input type="date" class="form-control" id="date_specific" name="date_specific" value="{{ request('date_specific') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 ms-auto d-flex align-items-end">
                             <button type="button" id="view-all-data" class="btn btn-info w-100">
                                <i class="fas fa-list me-1"></i> Lihat Semua Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-download me-2"></i>Unduh Data
            </h6>
             <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#downloadSection" aria-expanded="false" aria-controls="downloadSection">
                <i class="fas fa-chevron-down me-1"></i> Tampilkan Opsi
            </button>
        </div>
        <div class="collapse" id="downloadSection">
            <div class="card-body">
                 <div class="alert alert-info border-left-info">
                    <strong>Petunjuk:</strong> Pilih rentang tanggal dan filter opsional untuk mengunduh data dalam format Excel atau CSV.
                </div>
                <form id="download-form">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-lg-4">
                            <label for="download_start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="download_start_date" name="start_date" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="download_end_date" class="form-label fw-semibold">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="download_end_date" name="end_date" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-info quick-date" data-days="0">Hari Ini</button>
                                <button type="button" class="btn btn-sm btn-outline-info quick-date" data-days="7">7 Hari</button>
                                <button type="button" class="btn btn-sm btn-outline-info quick-date" data-period="current-month">Bulan Ini</button>
                                <button type="button" class="btn btn-sm btn-outline-info quick-date" data-period="last-month">Bulan Lalu</button>
                            </div>
                        </div>
                    </div>
                     <div class="row g-3 mb-3">
                        <div class="col-lg-3">
                            <label for="download_kategori" class="form-label">Filter Kategori</label>
                            <select class="form-select" id="download_kategori" name="kategori">
                                <option value="">Semua</option>
                                <option value="Satlantas">Satlantas</option>
                                <option value="Ambulans">Ambulans</option>
                                <option value="Perorangan">Perorangan</option>
                                <option value="Karyawan">Karyawan</option>
                            </select>
                        </div>
                         <div class="col-lg-3">
                            <label for="download_status" class="form-label">Filter Status</label>
                            <select class="form-select" id="download_status" name="status">
                                <option value="">Semua</option>
                                <option value="pending">Menunggu</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="download_jenis_kelamin" class="form-label">Filter Gender</label>
                            <select class="form-select" id="download_jenis_kelamin_pasien" name="jenis_kelamin_pasien">
                                <option value="">Semua</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                         <div class="col-lg-3">
                            <label for="download_search" class="form-label">Filter Pencarian</label>
                            <input type="text" class="form-control" id="download_search" name="search" placeholder="Nama, HP, dll...">
                        </div>
                     </div>
                     <div class="d-flex gap-2 mb-3">
                        <button type="button" id="copy-current-filters" class="btn btn-secondary">
                            <i class="fas fa-copy me-1"></i> Salin Filter Aktif
                        </button>
                        <button type="button" id="preview-download-data" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i> Preview Data
                        </button>
                     </div>
                     <div class="border rounded p-3" id="download-preview" style="display: none;">
                        <h6 class="text-info mb-3">Preview Data yang Akan Diunduh</h6>
                        <div class="row g-3 text-center mb-2">
                            <div class="col-md-3 col-6"><span class="h4 d-block" id="preview-total">-</span><small>Total</small></div>
                            <div class="col-md-3 col-6"><span class="h4 d-block" id="preview-verified">-</span><small>Terverifikasi</small></div>
                            <div class="col-md-3 col-6"><span class="h4 d-block" id="preview-pending">-</span><small>Menunggu</small></div>
                            <div class="col-md-3 col-6"><span class="h4 d-block" id="preview-rejected">-</span><small>Ditolak</small></div>
                        </div>
                        <div class="small text-muted text-center">Periode: <span id="preview-period">-</span></div>
                     </div>
                    <hr>
                    <div class="text-center">
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <button type="submit" name="format" value="excel" class="btn btn-primary">
                                <i class="fas fa-file-excel me-2"></i>Unduh Excel
                            </button>
                            <button type="submit" name="format" value="csv" class="btn btn-success">
                                <i class="fas fa-file-csv me-2"></i>Unduh CSV
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">Data Pengantar Pasien</h6>
            <div class="d-flex gap-2">
                <button id="back-to-pagination" class="btn btn-sm btn-outline-info d-none">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Pagination
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i> Refresh Tabel
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="table-container">
                @include('partials.escort-table', ['escorts' => $escorts])
            </div>
            <div id="pagination-container" class="mt-3">
                {{ $escorts->links() }}
            </div>
        </div>
    </div>
</div>

<div id="loading" class="d-none">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.45/moment-timezone-with-data.min.js"></script>
<script>
$(document).ready(function() {
    // Note: The JavaScript logic from the original file remains largely unchanged 
    // as it controls functionality tied to element IDs and classes which have been preserved.
    // The visual changes are handled by the new CSS.
    
    // Make loadDataFromUrl globally available for table navigation
    window.loadDataFromUrl = loadDataFromUrl;
    
    // Load initial data
    loadData();
    
    // Show advanced filters if any advanced filter has a value on page load
    const advancedFilters = ['#kategori', '#status', '#jenis_kelamin_pasien', '#today_only', '#week_only', '#month_only', '#date_specific'];
    const hasAdvancedFilter = advancedFilters.some(selector => 
        $(selector).val() || (['#today_only', '#week_only', '#month_only'].includes(selector) && $(selector).is(':checked'))
    );
    
    if (hasAdvancedFilter) {
        $('#advancedFilters').collapse('show');
    }
    
    // AJAX form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        loadData();
    });
    
    // Auto search on input change with delay
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadData();
        }, 500);
    });
    
    // Auto filter on select change and checkbox/date input change
    $('#kategori, #jenis_kelamin, #status, #today_only, #week_only, #month_only, #date_specific').on('change', function() {
        const dateFilters = ['#today_only', '#week_only', '#month_only', '#date_specific'];
        const changedFilter = $(this).attr('id');
        if (dateFilters.includes('#' + changedFilter)) {
            dateFilters.forEach(filter => {
                if (filter !== '#' + changedFilter) {
                    if (filter === '#date_specific') {
                        $(filter).val('');
                    } else {
                        $(filter).prop('checked', false);
                    }
                }
            });
        }
        loadData();
    });

    // View All Data button functionality
    $('#view-all-data').on('click', function() {
        const button = $(this);
        const originalHtml = button.html();
        
        // Show confirmation dialog
        Swal.fire({
            title: 'Mode Tampil Semua Data?',
            html: 'Anda akan masuk ke mode tampil semua data dengan navigasi halaman yang disederhanakan. Filter yang aktif akan tetap berlaku.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-eye me-1"></i> Ya, Aktifkan Mode',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
            customClass: {
                popup: 'swal-wide'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                button.html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...').prop('disabled', true);
                
                // Set view mode to 'all' and load first page
                window.viewAllMode = true;
                
                // Get current filters and add view_all_mode parameter
                const formData = $('#filter-form').serialize();
                const url = '{{ route("dashboard") }}?' + formData + '&view_all_mode=true&per_page=50';
                
                // Load data with enhanced pagination
                loadDataFromUrl(url, function(response) {
                    // Success callback
                    button.html(originalHtml).prop('disabled', false);
                    
                    // Show back to pagination button
                    $('#back-to-pagination').removeClass('d-none');
                    
                    // Update pagination container with custom navigation
                    updateViewAllPagination(response);
                    
                    Swal.fire({
                        title: 'Mode Tampil Semua Aktif!',
                        text: 'Sekarang Anda dapat melihat semua data dengan navigasi yang disederhanakan.',
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }, function(xhr) {
                    // Error callback
                    button.html(originalHtml).prop('disabled', false);
                    window.viewAllMode = false;
                    
                    // Show detailed error information
                    let errorMsg = 'Terjadi kesalahan saat mengaktifkan mode tampil semua.';
                    if (xhr.responseText) {
                        errorMsg += '\\n\\nDetail: ' + xhr.responseText.substring(0, 200);
                    }
                    if (xhr.status) {
                        errorMsg += '\\n\\nStatus: ' + xhr.status;
                    }
                    
                    Swal.fire({
                        title: 'Gagal Mengaktifkan Mode',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    });

    // Back to pagination button functionality
    $('#back-to-pagination').on('click', function() {
        // Hide the back button
        $(this).addClass('d-none');
        
        // Exit view all mode
        window.viewAllMode = false;
        
        // Reload data with normal pagination
        loadData();
        
        // Show success message
        Swal.fire({
            title: 'Kembali ke Mode Normal',
            text: 'Data telah dikembalikan ke mode pagination normal.',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
    
    // Download Section Functionality
    
    // Quick date selection
    $('.quick-date').on('click', function() {
        const button = $(this);
        const days = button.data('days');
        const period = button.data('period');
        
        let startDate, endDate;
        const today = new Date();
        
        if (period) {
            if (period === 'current-month') {
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = today;
            } else if (period === 'last-month') {
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                startDate = lastMonth;
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
            }
        } else {
            if (days === 0) {
                startDate = today;
                endDate = today;
            } else {
                startDate = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000));
                endDate = today;
            }
        }
        
        $('#download_start_date').val(formatDateForInput(startDate));
        $('#download_end_date').val(formatDateForInput(endDate));
    });
    
    // Date validation
    $('#download_start_date, #download_end_date').on('change', function() {
        const startDate = $('#download_start_date').val();
        const endDate = $('#download_end_date').val();
        
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            Swal.fire('Tanggal Tidak Valid', 'Tanggal akhir harus sama atau lebih besar dari tanggal mulai', 'warning');
            $(this).val('');
        }
    });

    // Copy current filters to download form
    $('#copy-current-filters').on('click', function() {
        $('#download_kategori').val($('#kategori').val());
        $('#download_status').val($('#status').val());
        $('#download_jenis_kelamin_pasien').val($('#jenis_kelamin_pasien').val());
        $('#download_search').val($('#search').val());
        
        const button = $(this);
        const originalHtml = button.html();
        button.html('<i class="fas fa-check"></i> Disalin!').addClass('btn-success').removeClass('btn-secondary');
        setTimeout(() => {
            button.html(originalHtml).removeClass('btn-success').addClass('btn-secondary');
        }, 2000);
    });

    // Preview download data
    $('#preview-download-data').on('click', function() {
        const startDate = $('#download_start_date').val();
        const endDate = $('#download_end_date').val();
        
        if (!startDate || !endDate) {
            Swal.fire('Tanggal Belum Dipilih', 'Harap pilih tanggal mulai dan tanggal akhir', 'warning');
            Swal.fire({
                title: 'Tanggal Belum Dipilih',
                html: 'Harap pilih tanggal mulai dan tanggal akhir',
                icon: 'warning',
                confirmButtonColor: '#ff0000ff'
            });
            return;
        }

        const button = $(this);
        const originalHtml = button.html();
        button.html('<i class="fas fa-spinner fa-spin me-1"></i> Memuat...').prop('disabled', true);
        
        const filters = {
            start_date: startDate,
            end_date: endDate,
            kategori: $('#download_kategori').val(),
            status: $('#download_status').val(),
            jenis_kelamin_pasien: $('#download_jenis_kelamin_pasien').val(),
            search: $('#download_search').val()
        };

        $.ajax({
            url: '{{ route("dashboard") }}',
            method: 'GET',
            data: { ...filters, preview: true, ajax: true },
            success: function(response) {
                if (response.status === 'success') {
                    $('#preview-total').text(response.stats?.total || 0);
                    $('#preview-verified').text(response.stats?.verified || 0);
                    $('#preview-pending').text(response.stats?.pending || 0);
                    $('#preview-rejected').text(response.stats?.rejected || 0);
                    $('#preview-period').text(`${formatDateIndonesian(startDate)} - ${formatDateIndonesian(endDate)}`);
                    $('#download-preview').slideDown();
                }
                button.html(originalHtml).prop('disabled', false);
            },
            error: function() {
                Swal.fire('Gagal', 'Terjadi kesalahan saat memuat preview data.', 'error');
                button.html(originalHtml).prop('disabled', false);
            }
        });
    });
    
    // Handle download form submission
    $('#download-form').on('submit', function(e) {
        e.preventDefault();
        const startDate = $('#download_start_date').val();
        const endDate = $('#download_end_date').val();
        if (!startDate || !endDate) {
            Swal.fire('Data Tidak Lengkap', 'Harap pilih tanggal mulai dan tanggal akhir', 'warning');
            return;
        }
        
        const format = e.originalEvent.submitter.value;
        initiateDownload(format);
    });

    function initiateDownload(format = 'csv') {
        Swal.fire({
            title: 'Memproses Download...',
            text: 'Mohon tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => Swal.showLoading()
        });

        const formData = new FormData($('#download-form')[0]);
        const url = format === 'excel' ? '{{ route("dashboard.download.excel") }}' : '{{ route("dashboard.download.csv") }}';
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.blob();
        })
        .then(blob => {
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = downloadUrl;
            const fileExtension = format === 'excel' ? 'xlsx' : 'csv';
            a.download = `Data_Escort_IGD_${$('#download_start_date').val()}_to_${$('#download_end_date').val()}.${fileExtension}`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            Swal.fire({
                title: 'Download Berhasil!',
                html: `File ${format.toUpperCase()} telah diunduh.`,
                icon: 'success',
                confirmButtonColor: '#28a745',});
        })
        .catch(error => {
            console.error('Download failed:', error);
            Swal.fire('Download Gagal', 'Terjadi kesalahan saat mengunduh file.', 'error');
        });
    }

    // Pagination click handler
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (url) {
            loadDataFromUrl(url);
        }
    });

    function loadData() {
        let formData = $('#filter-form').serialize();
        let url = '{{ route("dashboard") }}?' + formData;
        
        // Add view_all_mode parameter if in view all mode
        if (window.viewAllMode) {
            url += '&view_all_mode=true&per_page=50';
        }
        
        loadDataFromUrl(url);
    }
    
    function loadDataFromUrl(url, successCallback, errorCallback) {
        $('#loading').removeClass('d-none');
        
        $.ajax({
            url: url,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                try {
                    $('#table-container').html(response.html);
                    
                    // Handle pagination based on mode
                    if (window.viewAllMode && response.view_all_mode) {
                        updateViewAllPagination(response);
                    } else {
                        $('#pagination-container').html(response.pagination);
                    }
                    
                    $('#total-count').text(response.stats.total);
                    $('#today-count').text(response.stats.today);
                    $('#pending-count').text(response.stats.pending);
                    $('#verified-count').text(response.stats.verified);
                    $('#loading').addClass('d-none');
                    
                    // Handle view_all mode UI changes
                    if (response.view_all_mode || window.viewAllMode) {
                        $('#back-to-pagination').removeClass('d-none');
                    } else {
                        $('#back-to-pagination').addClass('d-none');
                    }
                    
                    // Call success callback if provided
                    if (typeof successCallback === 'function') {
                        successCallback(response);
                    }
                } catch (error) {
                    if (typeof errorCallback === 'function') {
                        errorCallback({ responseText: 'Error processing response: ' + error.message });
                    } else {
                        alert('Terjadi kesalahan saat memproses data: ' + error.message);
                    }
                }
            },
            error: function(xhr) {
                $('#loading').addClass('d-none');
                
                // Call error callback if provided, otherwise show default alert
                if (typeof errorCallback === 'function') {
                    errorCallback(xhr);
                } else {
                    let errorMessage = 'Terjadi kesalahan saat memuat data';
                    if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMessage += ': ' + (errorResponse.message || errorResponse.error || 'Unknown error');
                        } catch (e) {
                            errorMessage += ': ' + xhr.responseText.substring(0, 100);
                        }
                    }
                    alert(errorMessage);
                }
            }
        });
    }
    
    // Status update functionality
    $(document).on('click', '.btn-status-update', function(e) {
        e.preventDefault();
        const escortId = $(this).data('escort-id');
        const newStatus = $(this).data('status');
        const statusText = $(this).data('status-text');
        
        const row = $(this).closest('tr');
        
        // --- PERUBAHAN DIMULAI DI SINI ---
        // Mengambil data yang lebih lengkap dari setiap kolom (td) di baris tabel.
        // Catatan: Angka di 'td:nth-child()' mungkin perlu disesuaikan jika struktur tabel Anda berubah.
        const escortData = {
            kategori: row.find('td:nth-child(2)').text().trim(),
            nama_pengantar: row.find('td:nth-child(3)').text().trim(),
            no_pengantar: row.find('td:nth-child(4)').text().trim(),
            nama_ambulance: row.find('td:nth-child(5)').text().trim(),
            nama_pasien: row.find('td:nth-child(6)').text().trim(),
            jenis_kelamin_pasien: row.find('td:nth-child(7)').text().trim()
        };
        // --- AKHIR DARI PERUBAHAN PENGAMBILAN DATA ---

        const swalConfigMap = {
            'Terverifikasi': { title: 'Verifikasi Pengantar?', icon: 'success', confirmButtonColor: '#28a745', confirmButtonText: 'Ya, Verifikasi' },
            'Ditolak': { title: 'Tolak Pengantar?', icon: 'error', confirmButtonColor: '#dc3545', confirmButtonText: 'Ya, Tolak' },
            'Menunggu': { title: 'Ubah ke "Menunggu"?', icon: 'warning', confirmButtonColor: '#ffc107', confirmButtonText: 'Ya, Ubah' },
            'default': { title: 'Ubah Status?', icon: 'question', confirmButtonColor: '#6c757d', confirmButtonText: 'Ya, Lanjutkan' }
        };

        const config = swalConfigMap[statusText] || swalConfigMap['default'];

        // --- PERUBAHAN DIMULAI DI SINI ---
        // Membuat konten HTML yang lebih detail untuk ditampilkan di dalam SweetAlert.
        let detailHtml = `
            <div style="text-align: left; margin: 0 1.5rem;">
                <p class="mb-2">Anda akan mengubah status untuk data berikut:</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><strong>Kategori:</strong> <span>${escortData.kategori}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><strong>Nama Pengantar:</strong> <span>${escortData.nama_pengantar}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><strong>No. HP Pengantar:</strong> <span>${escortData.no_pengantar}</span></li>
        `;

        // Hanya tampilkan nama ambulan jika ada isinya (bukan '-' atau kosong)
        if (escortData.nama_ambulance && escortData.nama_ambulance !== '-') {
            detailHtml += `<li class="list-group-item d-flex justify-content-between"><strong>Ambulans:</strong> <span>${escortData.nama_ambulance}</span></li>`;
        }

        detailHtml += `
                    <li class="list-group-item d-flex justify-content-between bg-light"><strong>Nama Pasien:</strong> <span>${escortData.nama_pasien}</span></li>
                    <li class="list-group-item d-flex justify-content-between bg-light"><strong>Jenis Kelamin:</strong> <span>${escortData.jenis_kelamin_pasien}</span></li>
                </ul>
                <p class="mt-3">Apakah Anda yakin ingin melanjutkan?</p>
            </div>
        `;

        Swal.fire({
            title: config.title,
            // Menggunakan variabel detailHtml yang baru dibuat
            html: detailHtml, 
            icon: config.icon,
            showCancelButton: true,
            confirmButtonColor: config.confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: config.confirmButtonText,
            cancelButtonText: 'Batal',
            customClass: { 
                popup: 'swal-wide',
                htmlContainer: 'swal-html-container' // Class untuk styling tambahan jika perlu
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateEscortStatus(escortId, newStatus);
            }
        });
    });

    function updateEscortStatus(escortId, status) {
        Swal.fire({
            title: 'Memproses...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => Swal.showLoading()
        });
        
        $.ajax({
            url: `/escorts/${escortId}/status`,
            type: 'PATCH',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message, // Pesan dari server Anda
                        icon: 'success',
                        confirmButtonColor: '#28a745' // <-- KUNCI-nya di sini
                    });
                    loadData(); // Reload data to reflect changes
                } else {
                    Swal.fire('Gagal', 'Gagal memperbarui status.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        });
    }

    // View All Mode Pagination Functions
    function updateViewAllPagination(response) {
        if (!response.pagination_info) {
            console.warn('No pagination_info in response');
            $('#pagination-container').html('<div class="text-muted text-center">Tidak ada informasi pagination</div>');
            return;
        }
        
        try {
            const info = response.pagination_info;
            const currentPage = info.current_page || 1;
            const lastPage = info.last_page || 1;
            const total = info.total || 0;
            const from = info.from || 0;
            const to = info.to || 0;
            
            let paginationHtml = `
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan ${from} sampai ${to} dari ${total} hasil
                        <span class="badge bg-info ms-2">Mode Tampil Semua</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary view-all-nav" 
                                data-page="${currentPage - 1}" 
                                ${currentPage <= 1 ? 'disabled' : ''}>
                            <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                        </button>
                        <span class="btn btn-sm btn-light disabled">
                            Halaman ${currentPage} dari ${lastPage}
                        </span>
                        <button class="btn btn-sm btn-outline-primary view-all-nav" 
                                data-page="${currentPage + 1}" 
                                ${currentPage >= lastPage ? 'disabled' : ''}>
                            Selanjutnya <i class="fas fa-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#pagination-container').html(paginationHtml);
        } catch (error) {
            $('#pagination-container').html('<div class="text-danger text-center">Error loading pagination</div>');
        }
    }
    
    // Handle view-all navigation clicks
    $(document).on('click', '.view-all-nav:not([disabled])', function() {
        const page = $(this).data('page');
        if (page && page > 0) {
            const formData = $('#filter-form').serialize();
            const url = '{{ route("dashboard") }}?' + formData + '&view_all_mode=true&per_page=50&page=' + page;
            loadDataFromUrl(url);
        }
    });

    // Utility functions
    function formatDateForInput(date) {
        return date.toISOString().split('T')[0];
    }

    function formatDateIndonesian(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }
});
</script>
@endpush
@endsection