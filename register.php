<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ro'yxatdan o'tish - php.samdu.uz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3">Ro'yxatdan o'tish</h3>
                            <p class="text-muted">php.samdu.uz</p>
                        </div>

                        <div id="successMessage" class="alert alert-success d-none" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Muvaffaqiyatli ro'yxatdan o'tdingiz! <a href="login.php" class="alert-link">Kirish</a>
                        </div>

                        <form id="registerForm">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">To'liq ism</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="fullName" placeholder="Ism Familiya" required>
                                </div>
                                <div class="invalid-feedback">To'liq ismingizni kiriting</div>
                            </div>

                            <div class="mb-3">
                                <label for="registerPhone" class="form-label">Telefon raqam</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="registerPhone" maxlength="13"
                                        placeholder="+998 ** *** ** **" required>
                                </div>
                                <div class="invalid-feedback">Telefon raqamni kiriting</div>
                            </div>

                            <div class="mb-3">
                                <label for="registerPassword" class="form-label">Parol</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="registerPassword" placeholder="********" required minlength="6">
                                </div>
                                <div class="invalid-feedback">Parol kamida 6 ta belgidan iborat bo'lishi kerak</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Parolni tasdiqlash</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirmPassword" placeholder="********" required>
                                </div>
                                <div class="invalid-feedback">Parollar mos kelmayapti</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-user-plus me-2"></i>Ro'yxatdan o'tish
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SENING main.js -->
    <script src="assets/js/main.js"></script>

    <script>
        // +998 prefiksni avtomatik saqlash
        $('#registerPhone').on('input', function() {
            let val = $(this).val();

            if (!val.startsWith('+998')) {
                val = '+998';
            }

            val = val.replace(/[^0-9+]/g, '');

            if (val.length > 13) {
                val = val.slice(0, 13);
            }

            $(this).val(val);
        });

        $('#registerForm').submit(function(e) {
            e.preventDefault();

            let fullname = $('#fullName').val().trim();
            let phone = $('#registerPhone').val().trim();
            let password = $('#registerPassword').val().trim();
            let confirm = $('#confirmPassword').val().trim();

            if (fullname === "" || phone.length < 13){
                toastr.warning("Ism va telefonni to‘g‘ri kiriting!");
                return;
            }
            if (password.length < 6) {
                toastr.warning("Parol kamida 6 ta belgidan iborat bo‘lishi kerak!");
                return;
            }
            if (password !== confirm) {
                toastr.error("Parollar mos kelmayapti!");
                return;
            }
            $.ajax({
                url: "register_check.php",
                method: "POST",
                data: {
                    fullname: fullname,
                    phone: phone,
                    password: password
                },
                success: function(resp) {
                    
                    if (resp.error == 0) {
                        toastr.success(resp.message ?? "Ro‘yxatdan o‘tish muvaffaqiyatli!");
                        $('#registerForm')[0].reset();
                        $('#registerPhone').val('+998');
                        $('#successMessage').removeClass('d-none');
                    } else {
                        toastr.error(resp.message ?? "Xatolik yuz berdi!");
                    }
                },
                error: function() {
                    toastr.error("Internet bilan muammo, qaytadan urinib ko‘ring!");
                }
            });
        });
    </script>
</body>
</html>
