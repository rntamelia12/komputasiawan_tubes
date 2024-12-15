@extends('layouts.app')

@section('content')
    <div class="py-4 container-fluid align-center" style="height: 100vh; background-color: #b11116;">
        <br><br><br><br>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <div class="mt-3 mb-4 text-center">
                            <h1><i class="bi-hexagon-fill me" style="color: #b11116;"></i></h1>
                            <h4><b>Ones Group</b></h4>
                            <hr>
                            <form id="reset-password-form">
                                @csrf

                                <!-- Email Field -->
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Your Email">
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <hr>
                                <div class="row mb-0">
                                    <div class="col-md">
                                        <button type="submit" class="btn col-md-11" style="background-color: #b11116; border-color: #b11116; color: #fff;">
                                            <i class="bi bi-box-arrow-in-right"></i> {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Menangani submit formulir
        document.querySelector('#reset-password-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah form melakukan submit biasa

            const form = e.target;
            const formData = new FormData(form);

            // Mengirimkan permintaan ke server menggunakan fetch (AJAX)
            fetch('{{ route('password.email') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json()) // Mengambil data JSON dari server
            .then(data => {
                if (data.success) {
                    // Menampilkan SweetAlert jika berhasil
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,  // Pesan dari server
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Menampilkan SweetAlert jika ada kesalahan
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Terjadi kesalahan, coba lagi.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error(error);
                // Menampilkan SweetAlert jika ada error
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan server.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>

    @section('styles')
    <style>
        /* Styling for Forgot Password Page */
        .container-fluid {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #b11116;
            height: 100vh;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            text-align: center;
            background-color: white;
            padding: 20px 0;
            border-radius: 10px 10px 0 0;
        }

        .card-header h1 {
            font-size: 50px;
            color: #b11116;
        }

        .card-header h4 {
            font-size: 22px;
            color: #333;
        }

        .input-group-text {
            background-color: #f7f7f7;
            border-right: 1px solid #ddd;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 15px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #b11116;
            box-shadow: 0 0 0 0.2rem rgba(177, 17, 22, 0.25);
        }

        button[type="submit"] {
            background-color: #b11116;
            border-color: #b11116;
            color: white;
            font-size: 16px;
            padding: 12px 20px;
            border-radius: 5px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #a10f12;
            border-color: #a10f12;
        }

        .invalid-feedback {
            font-size: 14px;
            color: red;
        }

        /* Responsiveness for smaller screens */
        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 0;
                padding-right: 0;
            }

            .card {
                width: 90%;
                margin: 0 auto;
            }

            .card-header h1 {
                font-size: 40px;
            }

            .card-header h4 {
                font-size: 18px;
            }

            .form-control {
                padding: 12px;
            }

            button[type="submit"] {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
    @endsection
@endsection
