<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish - php.samdu.uz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-graduation-cap text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3">Tizimga kirish</h3>
                            <p class="text-muted">php.samdu.uz</p>
                        </div>

                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="loginPhone" class="form-label">Telefon raqam</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="loginPhone" maxlength="13" placeholder="+998 ** *** ** **" required>
                                </div>
                                <div class="invalid-feedback">Iltimos, telefon raqam kiriting</div>
                            </div>

                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Parol</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="loginPassword" placeholder="********" required>
                                </div>
                                <div class="invalid-feedback">Parol kiritilishi shart</div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Eslab qolish</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Kirish
                            </button>

                            <div class="text-center">
                                <a href="register.html" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-user-plus me-2"></i>Ro'yxatdan o'tish
                                </a>
                            </div>

                            <div class="text-center mt-3">
                                <a href="index.html" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-2"></i>Bosh sahifaga qaytish
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto +998 format
        $('#loginPhone').on('input', function() {
            let val = $(this).val();

            // Boshlanishi +998 bo'lmasa, o'rnatish
            if(!val.startsWith('+998')){
                val = '+998';
            }

            // Raqamlardan tashqari belgilarni olib tashlash
            val = val.replace(/[^0-9+]/g, '');

            // Maksimal uzunlik: +998 XX XXX XX XX (13 belgi)
            if(val.length > 13){
                val = val.slice(0, 13);
            }

            $(this).val(val);
        });

        $('#loginForm').submit(function(e) {
            e.preventDefault();

            let phone = $('#loginPhone').val().trim();
            let password = $('#loginPassword').val().trim();

            if(phone.length < 13 || !phone.startsWith('+998')){
                Swal.fire({
                    icon: 'warning',
                    title: "Telefon raqamni to'g'ri kiriting!"
                });
                return;
            }

            if(password === ""){
                Swal.fire({
                    icon: 'warning',
                    title: "Parol kiritilishi shart!"
                });
                return;
            }

            $.ajax({
                url: "login_check.php",
                method: "POST",
                data: {
                    phone: phone,
                    password: password,
                    remember: $('#rememberMe').is(':checked') ? 1 : 0
                },
                success: function(response){

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    if(response.error == 0){
                        Toast.fire({ icon: 'success', title: response.message });

                        setTimeout(() => {
                            window.location.href = "dashboard/index.php";
                        }, 2000);

                    } else {
                        $('#loginPassword').val('');
                        Toast.fire({ icon: 'error', title: response.message });
                    }
                },
                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: "Internet bilan muammo!",
                        text: "Qaytadan urinib ko'ring!"
                    });
                }
            });
        });


    </script>
</body>
</html>
