<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haftalik Reja - O'quv Qo'lanma</title>
    <link rel="stylesheet" href="../assets/css/dashboard_style.css">
    <link rel="stylesheet" href="../assets/css/haftalik-reja.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        <!-- Main Content -->
        <main class="main-content">
            <header class="top-navbar">
                <div class="navbar-left">
                    <h1>Haftalik O'quv Rejasi</h1>
                    <p class="navbar-subtitle">Haftalik dars rejalarini boshqarish</p>
                </div>
                <div class="navbar-right">
                    <div class="current-week">
                        <i class="fas fa-calendar-alt"></i>
                        <span id="currentWeek">Hozirgi hafta: 1</span>
                    </div>
                    <button class="btn btn-primary" id="addRejaBtn">
                        <i class="fas fa-plus"></i> Reja qo'shish
                    </button>
                </div>
            </header>

            <div class="content-container">
                <!-- Filtrlash paneli -->
                <div class="filter-panel">
                    <div class="filter-controls">
                        <div class="filter-group">
                            <label for="filterYonalish">
                                <i class="fas fa-compass"></i> Yo'nalish
                            </label>
                            <select id="filterYonalish" class="filter-select">
                                <option value="">Barcha yo'nalishlar</option>
                                <!-- Yo'nalishlar JavaScript orqali to'ldiriladi -->
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="filterKurs">
                                <i class="fas fa-layer-group"></i> Kurs
                            </label>
                            <select id="filterKurs" class="filter-select">
                                <option value="">Barcha kurslar</option>
                                <option value="1">1-kurs</option>
                                <option value="2">2-kurs</option>
                                <option value="3">3-kurs</option>
                                <option value="4">4-kurs</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="filterHafta">
                                <i class="fas fa-calendar-week"></i> Hafta
                            </label>
                            <select id="filterHafta" class="filter-select">
                                <option value="">Barcha haftalar</option>
                                <!-- Haftalar JavaScript orqali to'ldiriladi -->
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="filterDarsTuri">
                                <i class="fas fa-chalkboard-teacher"></i> Dars turi
                            </label>
                            <select id="filterDarsTuri" class="filter-select">
                                <option value="">Barcha dars turlari</option>
                                <option value="A">Amaliyot (A)</option>
                                <option value="T">Teoriya (T)</option>
                                <option value="M">Mustaqil ish (M)</option>
                                <option value="B">Bajarilishi (B)</option>
                                <option value="D">Darslik (D)</option>
                                <option value="TT">Test/Tekshiruv (T/TY)</option>
                                <option value="G">Guruh ishi (G)</option>
                            </select>
                        </div>
                    </div>
                    
                    <button class="btn btn-secondary" id="clearFilters">
                        <i class="fas fa-filter-circle-xmark"></i> Filtrlarni tozalash
                    </button>
                </div>

                <!-- Haftalik reja jadvali -->
                <div class="table-container mt-4">
                    <div class="table-header">
                        <div class="table-title">
                            <h3>Haftalik Rejalar Jadvali</h3>
                            <span class="badge" id="totalRejalar">0 ta reja</span>
                        </div>
                        <div class="table-actions">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchReja" placeholder="Mavzu bo'yicha qidirish...">
                            </div>
                            <button class="btn btn-success" id="exportBtn">
                                <i class="fas fa-file-export"></i> Eksport qilish
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Yo'nalish</th>
                                    <th>Hafta</th>
                                    <th>Mavzu</th>
                                    <th>Dars turi</th>
                                    <th>Vaqt</th>
                                    <th>Holati</th>
                                    <th>Harakatlar</th>
                                </tr>
                            </thead>
                            <tbody id="rejalarTable">
                                <!-- JavaScript orqali to'ldiriladi -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="table-footer">
                        <div class="table-info">
                            <p>Jami: <span id="rejaCount">0</span> ta reja</p>
                            <div class="filter-info" id="rejaFilterInfo"></div>
                        </div>
                    </div>
                </div>

                <!-- Haftalik reja grid ko'rinishi -->
                <div class="section mt-4">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-grid me-2"></i>Haftalik reja grid ko'rinishi
                    </h2>
                    
                    <div class="week-selector">
                        <button class="week-nav" id="prevWeek">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div class="week-display">
                            <h4 id="currentWeekDisplay">1-hafta</h4>
                            <div class="week-dates" id="weekDates"></div>
                        </div>
                        
                        <button class="week-nav" id="nextWeek">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="grid-container" id="weekGrid">
                        <!-- Grid ko'rinishi JavaScript orqali to'ldiriladi -->
                    </div>
                    
                    <div class="legend mt-4">
                        <h6>Dars turlari legendasi:</h6>
                        <div class="legend-items">
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #2ecc71;"></span>
                                <span>A - Amaliyot</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #3498db;"></span>
                                <span>T - Teoriya</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #9b59b6;"></span>
                                <span>M - Mustaqil ish</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #f39c12;"></span>
                                <span>B - Bajarilishi</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #e74c3c;"></span>
                                <span>D - Darslik</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #1abc9c;"></span>
                                <span>T/TY - Test/Tekshiruv</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #34495e;"></span>
                                <span>G - Guruh ishi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Reja qo'shish modal oynasi -->
    <div class="modal" id="rejaModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="rejaModalTitle">Yangi reja qo'shish</h3>
                <button class="modal-close" id="closeRejaModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejaForm">
                    <input type="hidden" id="rejaId">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="rejaYonalish">
                                <i class="fas fa-compass"></i> Yo'nalish
                            </label>
                            <select id="rejaYonalish" required>
                                <option value="">Yo'nalishni tanlang</option>
                                <!-- Yo'nalishlar JavaScript orqali to'ldiriladi -->
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="rejaKurs">
                                <i class="fas fa-layer-group"></i> Kurs
                            </label>
                            <select id="rejaKurs" required>
                                <option value="">Kursni tanlang</option>
                                <option value="1">1-kurs</option>
                                <option value="2">2-kurs</option>
                                <option value="3">3-kurs</option>
                                <option value="4">4-kurs</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="rejaHafta">
                                <i class="fas fa-calendar-week"></i> Hafta
                            </label>
                            <select id="rejaHafta" required>
                                <option value="">Haftani tanlang</option>
                                <!-- 1-16 hafta gacha -->
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="rejaDarsTuri">
                                <i class="fas fa-chalkboard-teacher"></i> Dars turi
                            </label>
                            <select id="rejaDarsTuri" required>
                                <option value="">Dars turini tanlang</option>
                                <option value="A">Amaliyot (A)</option>
                                <option value="T">Teoriya (T)</option>
                                <option value="M">Mustaqil ish (M)</option>
                                <option value="B">Bajarilishi (B)</option>
                                <option value="D">Darslik (D)</option>
                                <option value="TT">Test/Tekshiruv (T/TY)</option>
                                <option value="G">Guruh ishi (G)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="rejaMavzu">
                            <i class="fas fa-book-open"></i> Mavzu
                        </label>
                        <input type="text" id="rejaMavzu" placeholder="Masalan: Algoritmlar asoslari" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="rejaVaqt">
                                <i class="fas fa-clock"></i> Davomiylik (soat)
                            </label>
                            <select id="rejaVaqt">
                                <option value="2">2 soat</option>
                                <option value="3">3 soat</option>
                                <option value="4">4 soat</option>
                                <option value="6">6 soat</option>
                                <option value="8">8 soat</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="rejaHolati">
                                <i class="fas fa-check-circle"></i> Holati
                            </label>
                            <select id="rejaHolati">
                                <option value="rejalashtirilgan">Rejalashtirilgan</option>
                                <option value="bajarilgan">Bajarilgan</option>
                                <option value="kechiktirilgan">Kechiktirilgan</option>
                                <option value="bekor_qilingan">Bekor qilingan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="rejaTavsif">
                            <i class="fas fa-file-alt"></i> Qo'shimcha ma'lumotlar
                        </label>
                        <textarea id="rejaTavsif" rows="3" placeholder="Qo'shimcha izohlar, materiallar, manbalar..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="rejaOgituvchi">
                            <i class="fas fa-user-tie"></i> O'qituvchi (ixtiyoriy)
                        </label>
                        <input type="text" id="rejaOgituvchi" placeholder="Masalan: Alisher Navoiy">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelRejaBtn">
                    Bekor qilish
                </button>
                <button type="button" class="btn btn-primary" id="saveRejaBtn">
                    <i class="fas fa-save"></i> Saqlash
                </button>
            </div>
        </div>
    </div>

    <!-- Reja ma'lumotlari modal oynasi -->
    <div class="modal" id="viewRejaModal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h3 id="viewRejaTitle">Reja ma'lumotlari</h3>
                <button class="modal-close" id="closeViewRejaModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="reja-detail" id="rejaDetailContent">
                    <!-- JavaScript orqali to'ldiriladi -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeViewRejaBtn">
                    Yopish
                </button>
                <button type="button" class="btn btn-primary" id="editRejaFromViewBtn">
                    <i class="fas fa-edit"></i> Tahrirlash
                </button>
            </div>
        </div>
    </div>

    <script src="../assets/js/app.js"></script>
    <script src="../assets/js/haftalik-reja.js"></script>
</body>
</html>