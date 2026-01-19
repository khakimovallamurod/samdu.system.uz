<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php.samdu.uz - Samarqand Davlat Universiteti PhD Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>
                <span class="brand-text">php.samdu.uz</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Bosh sahifa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Universitet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#process">Jarayon</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">Savollar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-nav-login" href="login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Kirish
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section-modern">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 hero-content">
                    <div class="hero-badge mb-3">
                        <i class="fas fa-certificate me-2"></i>
                        <span>Rasmiy onlayn portal</span>
                    </div>
                    <h1 class="display-3 fw-bold mb-4 hero-title">
                        Samarqand Davlat Universiteti
                    </h1>
                    <p class="lead mb-4 text-muted">
                        PhD dasturiga hujjat topshirish uchun zamonaviy onlayn platforma. 
                        Vaqtingizni tejang, jarayonni soddalashtiramiz!
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="login.php" class="btn btn-primary btn-lg px-4 shadow-sm">
                            <i class="fas fa-sign-in-alt me-2"></i>Shaxsiy kabinetga kirish
                        </a>
                        <a href="register.php" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-user-plus me-2"></i>Ro'yxatdan o'tish
                        </a>
                    </div>
                    <div class="hero-stats d-flex gap-4 flex-wrap">
                        <div class="stat-item">
                            <h4 class="mb-0 text-primary fw-bold">2500+</h4>
                            <small class="text-muted">PhD talabalari</small>
                        </div>
                        <div class="stat-item">
                            <h4 class="mb-0 text-primary fw-bold">50+</h4>
                            <small class="text-muted">Mutaxassisliklar</small>
                        </div>
                        <div class="stat-item">
                            <h4 class="mb-0 text-primary fw-bold">24/7</h4>
                            <small class="text-muted">Onlayn xizmat</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center hero-illustration">
                    <div class="hero-image-wrapper">
                        <div class="floating-card card-1">
                            <i class="fas fa-file-alt"></i>
                            <span>Hujjatlar</span>
                        </div>
                        <div class="floating-card card-2">
                            <i class="fas fa-upload"></i>
                            <span>Yuklash</span>
                        </div>
                        <div class="floating-card card-3">
                            <i class="fas fa-check-circle"></i>
                            <span>Tasdiqlash</span>
                        </div>
                        <i class="fas fa-university hero-icon-large"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About University Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Universitet haqida</span>
                <h2 class="display-5 fw-bold mt-3">Samarqand Davlat Universiteti</h2>
                <p class="lead text-muted">O'zbekistonning eng qadimiy va nufuzli oliy ta'lim muassasalaridan biri</p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="info-card text-center">
                        <div class="info-icon bg-primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="h4 mt-3">1927 yil</h3>
                        <p class="text-muted mb-0">Ta'sis etilgan</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="info-card text-center">
                        <div class="info-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="h4 mt-3">25,000+</h3>
                        <p class="text-muted mb-0">Talabalar</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="info-card text-center">
                        <div class="info-icon bg-warning">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="h4 mt-3">1,500+</h3>
                        <p class="text-muted mb-0">Professor va o'qituvchilar</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="info-card text-center">
                        <div class="info-icon bg-info">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="h4 mt-3">14</h3>
                        <p class="text-muted mb-0">Fakultetlar</p>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-image-wrapper">
                        <div class="about-icon-bg">
                            <i class="fas fa-book-reader"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3 class="h2 mb-4">PhD dasturi - ilmiy muvaffaqiyat yo'li</h3>
                    <p class="mb-3">
                        Samarqand Davlat Universiteti O'zbekiston va Markaziy Osiyo mintaqasidagi yetakchi ilmiy-tadqiqot markazlaridan biri hisoblanadi.
                    </p>
                    <ul class="custom-list">
                        <li><i class="fas fa-check-circle text-success me-2"></i>50 dan ortiq yo'nalishlarda PhD dasturlari</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Xalqaro standartlarga mos ta'lim</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Zamonaviy ilmiy laboratoriyalar</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Tajribali ilmiy rahbarlar</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Xorijiy universitetlar bilan hamkorlik</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Afzalliklar</span>
                <h2 class="display-5 fw-bold mt-3">Nima uchun bizni tanlaysiz?</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-primary">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h5 class="mt-3">Onlayn hujjat topshirish</h5>
                        <p class="text-muted mb-0">Hujjatlarni universitetga kelmasdan onlayn tarzda yuklang va topshiring</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-success">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="mt-3">Vaqtni tejash</h5>
                        <p class="text-muted mb-0">Navbatda kutmasdan, istalgan vaqtda hujjatlaringizni yuborishingiz mumkin</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-warning">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="mt-3">Xavfsiz saqlash</h5>
                        <p class="text-muted mb-0">Barcha ma'lumotlaringiz shifrlangan va xavfsiz tizimda saqlanadi</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-info">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h5 class="mt-3">Bildirishnomalar</h5>
                        <p class="text-muted mb-0">Hujjatlar holati haqida email orqali xabardor bo'lib borasiz</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-danger">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="mt-3">Texnik yordam</h5>
                        <p class="text-muted mb-0">Har qanday savol yuzaga kelsa, yordam xizmati doim yoningizda</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper bg-secondary">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="mt-3">Mobil qulay</h5>
                        <p class="text-muted mb-0">Telefon yoki planshetdan ham qulay foydalanish mumkin</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Qanday ishlaydi?</span>
                <h2 class="display-5 fw-bold mt-3">Hujjat topshirish jarayoni</h2>
                <p class="lead text-muted">4 ta oddiy qadam orqali hujjatlaringizni topshiring</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <div class="step-icon bg-primary">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h5 class="mt-3">Ro'yxatdan o'tish</h5>
                        <p class="text-muted small">Email va parol orqali shaxsiy kabinetingizni yarating</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <div class="step-icon bg-success">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h5 class="mt-3">Tizimga kirish</h5>
                        <p class="text-muted small">Login va parol bilan shaxsiy kabinetingizga kiring</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <div class="step-icon bg-warning">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <h5 class="mt-3">Hujjatlarni yuklash</h5>
                        <p class="text-muted small">Forma to'ldiring va zarur fayllarni yuklang</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <div class="step-icon bg-info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="mt-3">Tasdiqlash</h5>
                        <p class="text-muted small">Hujjatlaringiz ko'rib chiqiladi va natija xabar qilinadi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Required Documents Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Kerakli hujjatlar</span>
                <h2 class="display-5 fw-bold mt-3">PhD uchun zarur hujjatlar ro'yxati</h2>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="document-item">
                        <div class="doc-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="doc-content">
                            <h6 class="mb-1">Passport nusxasi</h6>
                            <p class="text-muted small mb-0">Birinchi sahifaning rangli skanerlangan nusxasi (PDF yoki JPG)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="document-item">
                        <div class="doc-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="doc-content">
                            <h6 class="mb-1">Yo'llanma xat</h6>
                            <p class="text-muted small mb-0">Ish joyidan yoki universitet tomonidan berilgan rasmiy xat</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="document-item">
                        <div class="doc-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="doc-content">
                            <h6 class="mb-1">Ariza</h6>
                            <p class="text-muted small mb-0">PhD dasturiga qabul qilish uchun shaxsiy ariza</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="document-item">
                        <div class="doc-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="doc-content">
                            <h6 class="mb-1">OAK Bulletin nusxasi</h6>
                            <p class="text-muted small mb-0">Oliy attestatsiya komissiyasi byulleteni</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="document-item">
                        <div class="doc-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="doc-content">
                            <h6 class="mb-1">PhD Status xati</h6>
                            <p class="text-muted small mb-0">PhD talabaligingizni tasdiqlovchi rasmiy hujjat</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="document-item">
                        <div class="doc-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="doc-content">
                            <h6 class="mb-1">Imtihon ma'lumoti</h6>
                            <p class="text-muted small mb-0">Kirish imtihonlari yoki test natijalari</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Savollar</span>
                <h2 class="display-5 fw-bold mt-3">Ko'p beriladigan savollar</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Ro'yxatdan o'tish bepulmi?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ha, platformadan foydalanish va hujjat topshirish to'liq bepul. Hech qanday to'lov talab qilinmaydi.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Qanday formatdagi fayllarni yuklash mumkin?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    PDF, JPG, PNG va DOCX formatdagi fayllarni yuklashingiz mumkin. Har bir fayl hajmi 5MB dan oshmasligi kerak.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Hujjatlar qachon ko'rib chiqiladi?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Hujjatlaringiz 3-5 ish kuni ichida ko'rib chiqiladi. Natija email orqali sizga xabar qilinadi.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Yuklangan hujjatni o'zgartirish mumkinmi?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ha, hujjatlaringiz ko'rib chiqilishidan oldin shaxsiy kabinetingizdan o'zgartirish va qayta yuklashingiz mumkin.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Texnik yordam qanday olaman?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Savollaringiz bo'lsa, support@samdu.uz email manziliga yoki +998 66 239-11-40 raqamiga murojaat qilishingiz mumkin.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-3">
                        <i class="fas fa-graduation-cap me-2"></i>
                        php.samdu.uz
                    </h5>
                    <p class="text-white-50">
                        Samarqand Davlat Universiteti PhD hujjatlarini topshirish uchun rasmiy onlayn portal.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-telegram fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="mb-3">Tezkor havolalar</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#home">Bosh sahifa</a></li>
                        <li><a href="#about">Universitet</a></li>
                        <li><a href="#process">Jarayon</a></li>
                        <li><a href="#faq">Savollar</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="mb-3">Foydali havolalar</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="login.php">Kirish</a></li>
                        <li><a href="register.php">Ro'yxatdan o'tish</a></li>
                        <li><a href="#">Yo'riqnoma</a></li>
                        <li><a href="#">Texnik yordam</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="mb-3">Aloqa</h6>
                    <ul class="list-unstyled footer-contact">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Samarqand, Universitet ko'chasi</li>
                        <li><i class="fas fa-phone me-2"></i> +998 66 239-11-40</li>
                        <li><i class="fas fa-envelope me-2"></i> info@samdu.uz</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50">&copy; 2026 Samarqand Davlat Universiteti. Barcha huquqlar himoyalangan.</p>
                </div>
                
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn" class="scroll-top-btn">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
