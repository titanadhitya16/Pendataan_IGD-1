@extends('layout.app')

@section('title','Formulir Pengantar Pasien')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <button id="darkModeToggle" class="btn btn-outline-secondary theme-toggle-button">
        <i class="fas fa-moon"></i>
    </button>

    <div class="logout-button-container">
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger logout-button" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
                <span class="logout-text">Keluar</span>
            </button>
        </form>
    </div>

    <div class="form-container">
        <div class="card shadow mb-4">
            <div class="card-header pt-2 pb-1 border-left-primary align-items-center">
                <div class="text-center mt-3">
                    <img src="{{ asset('Rumah_Sakit_Indriati.webp') }}" alt="Logo" class="logo">
                </div>

                <h2 class="mx-3 font-weight-bold text-black form-title pt-3">
                    <i class="fas fa-user-plus"></i> Form Data Pengantar Pasien
                </h2>
                <p class="form-subtitle mx-3">Silakan lengkapi data pengantar pasien di bawah ini</p>
                <br>
                @auth
                <div class="user-info-badge">
                    <i class="fas fa-user-circle"></i>
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->phone_number)
                    <span class="user-phone">• {{ auth()->user()->phone_number }}</span>
                    @endif
                </div>
                @endauth
            </div>
            <div class="card-body">
                @if(isset($successMessage) && $successMessage)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ $successMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(isset($errorMessage) && $errorMessage)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ $errorMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" id="escortForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="kategori_pengantar" class="form-label">
                            <i class="fas fa-tags"></i> Kategori Pengantar
                        </label>
                        <select class="form-select" id="kategori_pengantar" name="kategori_pengantar" required>
                            <option value="">Pilih kategori pengantar...</option>
                            <option value="Ambulans">Ambulans</option>
                            <option value="Karyawan">Karyawan</option>
                            <option value="Perorangan">Perorangan</option>
                            <option value="Satlantas">Satlantas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pengantar" class="form-label">
                            <i class="fas fa-user"></i> Nama Pengantar
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" class="form-control" id="nama_pengantar" name="nama_pengantar"
                                   placeholder="Masukkan nama lengkap"
                                   value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nomor_hp" class="form-label">
                            <i class="fas fa-phone"></i> Nomor HP
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </div>
                            <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp"
                                   placeholder="Contoh: 08123456789"
                                   value="{{ auth()->user()->phone_number ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group" id="kendaraan_group" style="display: none;">
                        <label for="nama_ambulan" class="form-label" id="kendaraan_label">
                            <i class="fas fa-ambulance" id="kendaraan_icon"></i> <span id="kendaraan_label_text">Nama Ambulan</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-text" id="kendaraan_input_icon">
                                <i class="fas fa-ambulance"></i>
                            </div>
                            <input type="text" class="form-control" id="nama_ambulan" name="nama_ambulan"
                                   placeholder="Masukkan nama ambulan">
                        </div>
                        <small class="text-muted mt-1 d-block" id="kendaraan_help_text">
                            Contoh: Ambulans Gawat Darurat 1
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="nama_pasien" class="form-label">
                            <i class="fas fa-user-injured"></i> Nama Pasien
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-user-injured"></i>
                            </div>
                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                   placeholder="Masukkan nama lengkap pasien" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jenis_kelamin_pasien" class="form-label">
                            <i class="fas fa-venus-mars"></i> Jenis Kelamin Pasien
                        </label>
                        <select class="form-select" id="jenis_kelamin_pasien" name="jenis_kelamin_pasien" required>
                            <option value="">Pilih jenis kelamin...</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="foto_pengantar" class="form-label">
                            <i class="fas fa-camera"></i> Foto Pengantar
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" class="file-input" id="foto_pengantar" name="foto_pengantar"
                                   accept="image/*" required>
                            <div class="file-input-display">
                                <i class="fas fa-cloud-upload-alt file-icon"></i>
                                <span class="file-text">Klik untuk memilih foto atau drag & drop</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100" id="submitBtn">
                            <span class="btn-text">
                                <i class="fas fa-paper-plane"></i> Kirim Data
                            </span>
                            <span class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        // File input handling
        $('#foto_pengantar').on('change', function() {
            const file = this.files[0];
            const display = $('.file-input-display');
            const text = $('.file-text');
            
            if (file) {
                display.addClass('has-file');
                text.text(`File dipilih: ${file.name}`);
            } else {
                display.removeClass('has-file');
                text.text('Klik untuk memilih foto atau drag & drop');
            }
        });

        // Drag and drop functionality
        $('.file-input-display').on('dragover', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#667eea');
        });

        $('.file-input-display').on('dragleave', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#e9ecef');
        });

        $('.file-input-display').on('drop', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#e9ecef');
            
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                $('#foto_pengantar')[0].files = files;
                $('#foto_pengantar').trigger('change');
            }
        });

        // Phone number formatting
        $('#nomor_hp').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value;
            } else if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            }
            $(this).val(value);
        });
        
        // Conditional field for 'Ambulans'
        $('#kategori_pengantar').on('change', function() {
            const category = $(this).val();
            const kendaraanGroup = $('#kendaraan_group');
            const kendaraanInput = $('#nama_ambulan');
            
            if (category === 'Ambulans') {
                kendaraanGroup.slideDown();
                kendaraanInput.prop('required', true);
            } else {
                kendaraanGroup.slideUp();
                kendaraanInput.prop('required', false);
                kendaraanInput.val(''); // Clear value when hidden
            }
            // Clear validation state when category changes
            kendaraanInput.removeClass('is-invalid is-valid');
        });

        // Form submission with full validation
        $('#escortForm').on('submit', function(e) {
            e.preventDefault();
            
            // --- VALIDATION BLOCK ---
            let isValid = true;
            let emptyFields = [];
            
            $('#escortForm').find('input[required]:visible, select[required]:visible').each(function() {
                const field = $(this);
                const fieldLabel = field.closest('.form-group').find('label').text().trim();
                
                field.removeClass('is-invalid is-valid');
                
                if (!field.val() || (typeof field.val() === 'string' && field.val().trim() === '')) {
                    isValid = false;
                    field.addClass('is-invalid');
                    emptyFields.push(fieldLabel);
                } else {
                    field.addClass('is-valid');
                }
            });

            if (!isValid) {
                const fieldList = emptyFields.map(field => `• ${field}`).join('<br>');
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    html: `<div class="text-start"><p class="mb-2">Mohon lengkapi semua field yang wajib diisi:</p>${fieldList}</div>`,
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'Mengerti'
                });
                return; // Stop submission if validation fails
            }
            // --- END OF VALIDATION BLOCK ---

            const submitBtn = $('#submitBtn');
            const form = this;
            
            const submissionAttempt = {
                timestamp: new Date().toISOString(),
                category: $('#kategori_pengantar').val(),
                name: $('#nama_pengantar').val(),
                patient: $('#nama_pasien').val()
            };
            localStorage.setItem('lastSubmissionAttempt', JSON.stringify(submissionAttempt));
            
            submitBtn.addClass('loading').prop('disabled', true);
            
            Swal.fire({
                title: 'Memproses Data',
                text: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            const fileInput = $('#foto_pengantar')[0];
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const formData = new FormData();
                    
                    formData.append('kategori_pengantar', $('#kategori_pengantar').val());
                    formData.append('nama_pengantar', $('#nama_pengantar').val());
                    formData.append('nomor_hp', $('#nomor_hp').val());
                    if ($('#kategori_pengantar').val() === 'Ambulans') {
                        formData.append('nama_ambulan', $('#nama_ambulan').val());
                    }
                    formData.append('nama_pasien', $('#nama_pasien').val());
                    formData.append('jenis_kelamin_pasien', $('#jenis_kelamin_pasien').val());
                    
                    formData.append('foto_pengantar_base64', e.target.result);
                    formData.append('foto_pengantar_info[name]', file.name);
                    formData.append('foto_pengantar_info[size]', file.size);
                    formData.append('foto_pengantar_info[type]', file.type);
                    
                    submitFormData(formData, submitBtn);
                };
                
                reader.onerror = function() {
                    submitBtn.removeClass('loading').prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal membaca file gambar. Silakan coba lagi.',
                        confirmButtonColor: '#dc3545'
                    });
                };
                
                reader.readAsDataURL(file);
            } else {
                submitBtn.removeClass('loading').prop('disabled', false);
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Silakan pilih foto pengantar terlebih dahulu.',
                    confirmButtonColor: '#ffc107'
                });
            }
        });

        function submitFormData(formData, submitBtn) {
            $.ajax({
                url: '/api/escort',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    submitBtn.removeClass('loading').prop('disabled', false);
                    
                    const submissionSuccess = {
                        timestamp: new Date().toISOString(),
                        submission_id: response.submission_id,
                        escort_id: response.data ? response.data.id : null,
                        session_id: response.session_id,
                        message: response.message
                    };
                    localStorage.setItem('lastSuccessfulSubmission', JSON.stringify(submissionSuccess));
                    
                    let submissionCount = parseInt(localStorage.getItem('submissionCount') || '0');
                    localStorage.setItem('submissionCount', (submissionCount + 1).toString());
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <div>
                                <p>Data berhasil disimpan</p>
                                <small class="text-muted">Data pengantar pasien telah berhasil disimpan dan akan diverifikasi oleh petugas.</small><br><small class="text-muted">Silahkan tunggu verifikasi dari petugas untuk proses selanjutnya.</small>
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#16aa40ff',
                        timer: 5000,
                        timerProgressBar: true,
                        showClass: { popup: 'animate__animated animate__fadeInDown' },
                        hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                    }).then(() => {
                        $('#escortForm')[0].reset();
                        $('.file-input-display').removeClass('has-file');
                        $('.file-text').text('Klik untuk memilih foto atau drag & drop');
                        $('.form-control, .form-select').removeClass('is-valid is-invalid');
                        $('#kendaraan_group').hide();
                        $('#kategori_pengantar').focus();
                    });
                    
                    console.log('Submission Statistics:', {
                        total_submissions: submissionCount + 1,
                        session_id: response.session_id,
                        api_submissions_count: response.meta ? response.meta.api_submissions_count : null
                    });
                },
                error: function(xhr) {
                    submitBtn.removeClass('loading').prop('disabled', false);
                    
                    const submissionError = {
                        timestamp: new Date().toISOString(),
                        status: xhr.status,
                        response: xhr.responseJSON,
                        submission_id: xhr.responseJSON ? xhr.responseJSON.submission_id : null
                    };
                    localStorage.setItem('lastSubmissionError', JSON.stringify(submissionError));
                    
                    let errorMessage = 'Terjadi kesalahan saat memproses data';
                    let errorDetails = '';
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorList = '';
                        for (let field in errors) {
                            errorList += `• ${errors[field].join(', ')}\n`;
                        }
                        errorMessage = 'Validasi data gagal';
                        errorDetails = errorList;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        html: `
                            <div>
                                <p>${errorMessage}</p>
                                ${errorDetails ? `<div class="mt-2"><small>${errorDetails.replace(/\n/g, '<br>')}</small></div>` : ''}
                                ${xhr.responseJSON && xhr.responseJSON.submission_id ? 
                                    `<div class="mt-2"><small class="text-muted">ID Error: ${xhr.responseJSON.submission_id}</small></div>` : ''}
                            </div>
                        `,
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#dc3545',
                        showClass: { popup: 'animate__animated animate__shakeX' }
                    });
                    
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(field => {
                            $(`[name="${field}"]`).addClass('is-invalid');
                        });
                    }
                }
            });
        }
        
        // Real-time validation
        $(document).on('input change', 'input[required], select[required], textarea[required]', function() {
            const field = $(this);
            field.removeClass('is-invalid is-valid');
            
            if (field.val() && (typeof field.val() !== 'string' || field.val().trim() !== '')) {
                field.addClass('is-valid');
            } else {
                field.addClass('is-invalid');
            }
        });
    });
</script>
@endpush