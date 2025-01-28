@section('title', 'Pendaftaran Mahasiswa Baru | INISMA')
@extends('landingpage.start')
@section('content')

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="dark-background">
        </section><!-- /Hero Section -->

        <!-- About Section -->
        <section id="about-daftar" class="about section">
            <div class="container">
                <div class="row gy-3">
                    <h1 class="text-center"
                        style="font-size: 3rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    background: linear-gradient(45deg, #b00c20, #5d0519); 
                    -webkit-background-clip: text; color: transparent; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);">
                        Pendaftaran Mahasiswa Baru INISMA
                    </h1>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon bg-primary text-white">
                                    <i class="fas fa-info"></i>
                                </div>
                                <div class="timeline-content">
                                    <h4 class="timeline-title">Informasi Pendaftaran</h4>
                                    <p>Silakan lengkapi formulir pendaftaran dengan data yang benar dan lengkap. Pastikan
                                        semua kolom yang wajib diisi telah terisi dengan benar sebelum mengirimkan formulir.
                                    </p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>1. Identitas Pribadi</h5>
                                    <p>Pastikan Nama Lengkap, Tanggal Lahir, dan Alamat sesuai dengan KTP Anda.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon bg-warning text-white">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>2. Nomor HP Aktif</h5>
                                    <p>Pastikan nomor HP Anda aktif dan dapat menerima pesan WhatsApp.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon bg-info text-white">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>3. Email Aktif</h5>
                                    <p>Sebelum mendaftar, pastikan email Anda aktif. Setelah mendaftar, akun Anda akan
                                        dikirimkan ke email tersebut.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success text-white">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>4. Pilih Kelas</h5>
                                    <p>Silakan pilih Kelas/Jalur Masuk Anda.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon bg-danger text-white">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>5. Pilih Program Studi</h5>
                                    <p>Silakan pilih program studi sesuai dengan keinginan Anda.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon bg-secondary text-white">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Bantuan</h5>
                                    <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi
                                        kami melalui email atau nomor telepon berikut : <a href="https://wa.me/082279710272" target="_blank">0822-7971-0272</a></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">

                        {{-- @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Terjadi kesalahan!</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif --}}
                        <br>
                        <form id="registerForm" action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="namalengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="namalengkap" name="namalengkap"
                                    autocomplete="namalengkap" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggallahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggallahir" name="tanggallahir"
                                    autocomplete="tanggallahir" placeholder="Tanggal Lahir" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomorhp" class="form-label">Nomor HP (Wajib ada Whatsapp)</label>
                                <input type="text" class="form-control" id="nomorhp" name="nomorhp"
                                    autocomplete="nomorhp" placeholder="Nomor HP Aktif" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Aktif</label>
                                <input type="email" class="form-control" id="emailAktif" name="email"
                                    autocomplete="emailAktif" required placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap (Sesuai KTP)</label>
                                <textarea class="form-control" id="alamat" name="alamat" autocomplete="alamat" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="jalurmasuk_id">Kelas yang Anda Pilih</label>
                                <select class="form-control" id="jalurmasuk_id" name="jalurmasuk_id" required>
                                    <option value="">Pilih Jalur Masuk</option>
                                    @foreach ($jalurMasuk as $jalur)
                                        <option value="{{ $jalur->id }}" data-biaya="{{ $jalur->biaya_pendaftaran }}">
                                            {{ $jalur->nm_jalur }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Hanya di tampilkan sebagai Informasi --}}
                            <div class="mb-3" id="biaya-container">
                                <label for="biaya_pendaftaran">Biaya Pendaftaran</label>
                                <input type="text" class="form-control" disabled id="biaya_pendaftaran" readonly>
                            </div>
                            
                            {{-- Inputan lain jika memilih kelas Tahfiz --}}
                            <div class="mb-3" id="input-tahfiz-container" style="display: none;">
                                {{-- <label for="input_tahfiz">Input Kelas Tahfiz</label> --}}
                                <div class="mb-3">
                                    <label for="jumlah_juz">Jumlah Hafalan</label>
                                <select class="form-control" id="jumlah_juz" name="jumlah_juz" required>
                                    <option value="">Pilih Jumlah Juz</option>
                                    <option value="1">1 Juz</option>
                                    <option value="5">5 Juz</option>
                                    <option value="10">10 Juz</option>
                                    <option value="15">15 Juz</option>
                                    <option value="20">20 Juz</option>
                                    <option value="25">25 Juz</option>
                                    <option value="30">30 Juz</option>
                                </select>
                                </div>
                                <!-- Dropdown untuk jumlah juz -->
                                
                                <div class="mb-3">
                                    <label for="sertifikat">Upload Bukti Sertifikat</label>
                                <input type="file" class="form-control" id="sertifikat" name="sertifikat" accept=".pdf" required>
                                </div>
                                <!-- Input untuk upload file bukti sertifikat -->
                                
                            </div>
                            <div class="mb-3">
                                <label for="prodiyangdipilih" class="form-label">Prodi yang Dipilih</label>
                                <select class="form-select" id="prodiyangdipilih" name="prodiyangdipilih" required>
                                    <option value="" disabled selected>Pilih Program Studi</option>
                                    @foreach ($programStudi as $prodi)
                                        <option value="{{ $prodi->nm_prodi }}">{{ $prodi->nm_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </form>
                    </div>

                </div>

            </div>

        </section><!-- /About Section -->

    </main>

@endsection
@push('scripts')
    <script>
        //         $(document).ready(function() {
        //     $('#prodiyangdipilih').select2({
        //         placeholder: "Pilih Program Studi",
        //         allowClear: true
        //     });
        // });
        document.getElementById('jalurmasuk_id').addEventListener('change', function() {
        var jalurMasukId = this.value;
        var biayaContainer = document.getElementById('biaya-container');
        var tahfizContainer = document.getElementById('input-tahfiz-container');
        var biayaInput = document.getElementById('biaya_pendaftaran');
        
        // Menyembunyikan biaya pendaftaran dan menampilkan inputan lain jika kelas Tahfiz dipilih
        if (jalurMasukId) {
            var selectedOption = this.options[this.selectedIndex];
            var biaya = selectedOption.getAttribute('data-biaya');
            var selectedClass = selectedOption.text.toLowerCase();

            if (selectedClass.includes('tahfiz')) {
                biayaContainer.style.display = 'none'; // Menyembunyikan biaya pendaftaran
                tahfizContainer.style.display = 'block'; // Menampilkan inputan lain untuk Tahfiz
            } else {
                biayaContainer.style.display = 'block'; // Menampilkan biaya pendaftaran
                tahfizContainer.style.display = 'none'; // Menyembunyikan inputan lain
                biayaInput.value = biaya; // Menampilkan biaya pendaftaran jika bukan Tahfiz
            }
        }
    });
        $(document).ready(function() {
            $('#jalurmasuk_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                var biaya = selectedOption.data('biaya');

                // Set nilai biaya pendaftaran ke input
                $('#biaya_pendaftaran').val(biaya ? 'Rp. ' + parseInt(biaya).toString().replace(
                    /\B(?=(\d{3})+(?!\d))/g, '.') : '');
            });
        });
        @if (session('success'))
            Swal.fire({
                title: 'Pendaftaran Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke halaman login
                    window.location.href = '/login';
                }
            });
        @endif
        //Set Biaya
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('pendaftaran.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: new FormData(document.getElementById('registerForm'))
                })
                .then(response => {
                    if (!response.ok) {
                        // Jika status bukan 2xx, misalnya 422, maka parsing JSON untuk error
                        return response.json().then(data => {
                            throw data; // Lempar error jika ada
                        });
                    }
                    return response.json(); // Jika berhasil, lanjutkan dengan data JSON
                })
                .then(data => {
                    // Jika tidak ada error, tampilkan pesan sukses
                    Swal.fire({
                        title: 'Pendaftaran Berhasil!',
                        text: 'Silakan cek email Anda untuk login dan Melakukan Proses Berikutnya.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/login'; // Redirect ke halaman login
                    });
                })
                .catch(error => {
                    // Menampilkan error jika ada
                    let errorMessage = '';
                    if (error.errors) {
                        // Gabungkan semua pesan error dalam satu string
                        for (const [field, messages] of Object.entries(error.errors)) {
                            errorMessage += messages.join(', ') + '\n';
                        }
                    } else {
                        errorMessage = error.message || 'Terjadi kesalahan yang tidak diketahui.';
                    }

                    // Tampilkan error di SweetAlert
                    Swal.fire({
                        title: 'Terjadi Kesalahan!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });





            //batas selector
        });
    </script>
@endpush
