<?php
session_start();
require_once 'config.php';
$db = new Database();

$oquv_rejalar = $db->get_oquv_rejalar();

function process_data_for_template(array $data): array
{
    $semesters = [];

    foreach ($data as $row) {

        $semestrNum = (int)$row['semestr'];
        $fanCode    = $row['fan_code'];
        $soat       = (int)$row['jami_soat'];
        $turName    = mb_strtolower($row['dars_tur_name'], 'UTF-8');

        /* ===== SEMESTR INIT ===== */
        if (!isset($semesters[$semestrNum])) {
            $semesters[$semestrNum] = [
                'id' => $row['semestr_id'],
                'name' => $semestrNum . '-SEMESTR',
                'subjects' => [],
                'totals' => [
                    'credit' => 0,
                    'totalHours' => 0,
                    'auditoriya' => [
                        'total' => 0,
                        'lecture' => 0,
                        'practical' => 0,
                        'lab' => 0,
                        'seminar' => 0
                    ],
                    'malakaAmaliyot' => 0,
                    'kursIshi' => 0,
                    'mustaqilTalim' => 0
                ]
            ];
        }

        /* ===== FAN INIT (ASSOCIATIVE) ===== */
        if (!isset($semesters[$semestrNum]['subjects'][$fanCode])) {
            $semesters[$semestrNum]['subjects'][$fanCode] = [
                'code' => $fanCode,
                'name' => $row['fan_name'],
                'examType' => 'I',
                'credit' => 0,
                'totalHours' => 0,
                'auditoriya' => [
                    'total' => 0,
                    'lecture' => 0,
                    'practical' => 0,
                    'lab' => 0,
                    'seminar' => 0
                ],
                'malakaAmaliyot' => 0,
                'kursIshi' => 0,
                'mustaqilTalim' => 0,
                'department' => $row['kafedra_name']
            ];
        }

        $subject =& $semesters[$semestrNum]['subjects'][$fanCode];

        /* ===== TOTAL HOURS ===== */
        $subject['totalHours'] += $soat;
        $semesters[$semestrNum]['totals']['totalHours'] += $soat;

        /* ===== DARS TURI TAQSIMOT ===== */
        if (str_contains($turName, 'ma')) {
            $subject['auditoriya']['lecture'] += $soat;
            $subject['auditoriya']['total']   += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['lecture'] += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['total']   += $soat;

        } elseif (str_contains($turName, 'amaliy')) {
            $subject['auditoriya']['practical'] += $soat;
            $subject['auditoriya']['total']     += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['practical'] += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['total']     += $soat;

        } elseif (str_contains($turName, 'lab')) {
            $subject['auditoriya']['lab'] += $soat;
            $subject['auditoriya']['total'] += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['lab'] += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['total'] += $soat;

        } elseif (str_contains($turName, 'seminar')) {
            $subject['auditoriya']['seminar'] += $soat;
            $subject['auditoriya']['total'] += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['seminar'] += $soat;
            $semesters[$semestrNum]['totals']['auditoriya']['total'] += $soat;

        } elseif (str_contains($turName, 'mustaqil')) {
            $subject['mustaqilTalim'] += $soat;
            $semesters[$semestrNum]['totals']['mustaqilTalim'] += $soat;

        } elseif (str_contains($turName, 'malaka')) {
            $subject['malakaAmaliyot'] += $soat;
            $semesters[$semestrNum]['totals']['malakaAmaliyot'] += $soat;

        } elseif (str_contains($turName, 'kurs')) {
            $subject['kursIshi'] += $soat;
            $semesters[$semestrNum]['totals']['kursIshi'] += $soat;
        }
    }

    /* ===== SORT SEMESTERS ===== */
    ksort($semesters);

    /* ===== CREDIT + SUBJECT SORT ===== */
    foreach ($semesters as &$semester) {
        foreach ($semester['subjects'] as &$subject) {
            $subject['credit'] = round($subject['totalHours'] / 30);
            $semester['totals']['credit'] += $subject['credit'];
        }

        ksort($semester['subjects']);
        $semester['subjects'] = array_values($semester['subjects']);
    }

    /* ===== YEARLY TOTAL ===== */
    $yearlyTotal = [
        'credit' => 0,
        'totalHours' => 0,
        'auditoriya' => ['total'=>0,'lecture'=>0,'practical'=>0,'lab'=>0,'seminar'=>0],
        'malakaAmaliyot' => 0,
        'kursIshi' => 0,
        'mustaqilTalim' => 0
    ];

    foreach ($semesters as $s) {
        foreach ($yearlyTotal as $k => &$v) {
            if (is_array($v)) {
                foreach ($v as $kk => &$vv) {
                    $vv += $s['totals'][$k][$kk];
                }
            } else {
                $v += $s['totals'][$k];
            }
        }
    }

    return [
        'academicYear' => '2024-2025',
        'semesters' => array_values($semesters),
        'yearlyTotal' => $yearlyTotal
    ];
}

$data = process_data_for_template($oquv_rejalar);

?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'quv Rejalari - O'quv Qo'lanma</title>
    <link rel="stylesheet" href="../assets/css/dashboard_style.css">
    <link rel="stylesheet" href="../assets/css/oquv_reja_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php include_once 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-navbar">
                <div class="navbar-left">
                    <h1>O'quv Rejalari</h1>
                    <p class="navbar-subtitle">Excel formatida ko'rinish va boshqarish</p>
                </div>
                <div class="navbar-right">
                    <div class="current-date">
                        <i class="fas fa-calendar-day"></i>
                        <span id="currentDate"></span>
                    </div>
                </div>
            </header>

            <div class="content-container">
                <!-- Controls Panel -->
                <div class="controls-panel">
                    <div class="semester-switcher" id="semesterSwitcher">
                        <!-- JavaScript tomonidan yaratiladi -->
                    </div>

                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="filterYonalish">
                                <i class="fas fa-compass"></i> Kafedra
                            </label>
                            <select id="filterYonalish" class="filter-select">
                                <option value="">Barcha kafedralar</option>
                                <?php
                                // Kafedralar ro'yxati
                                $kafedralar = [];
                                foreach ($data['semesters'] as $semester) {
                                    foreach ($semester['subjects'] as $subject) {
                                        if (!in_array($subject['department'], $kafedralar)) {
                                            $kafedralar[] = $subject['department'];
                                        }
                                    }
                                }
                                sort($kafedralar);
                                foreach ($kafedralar as $kafedra): ?>
                                <option value="<?php echo htmlspecialchars($kafedra); ?>">
                                    <?php echo htmlspecialchars($kafedra); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="filterKurs">
                                <i class="fas fa-layer-group"></i> Semestr
                            </label>
                            <select id="filterKurs" class="filter-select">
                                <option value="">Barcha semestrlar</option>
                                <?php foreach ($data['semesters'] as $semester): ?>
                                <option value="<?php echo explode('-', $semester['name'])[0]; ?>">
                                    <?php echo $semester['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="search-actions">
                        <div class="search-box" style="max-width: 400px;">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchSubject" placeholder="Fan kodi yoki nomi bo'yicha qidirish...">
                        </div>
                        
                        <div class="action-buttons" style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="btn btn-secondary" id="clearFilters">
                                <i class="fas fa-filter-circle-xmark"></i> Filtrlarni tozalash
                            </button>
                            <button class="btn btn-primary" id="applyFilters">
                                <i class="fas fa-filter"></i> Filtrlash
                            </button>
                            <button class="btn btn-success" id="exportExcel">
                                <i class="fas fa-file-excel"></i> Excel ga eksport
                            </button>
                            <button class="btn btn-info" id="printTable">
                                <i class="fas fa-print"></i> Chop etish
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-cards">
                    <div class="stat-card-excel">
                        <h4>Jami Fanlar</h4>
                        <div class="value" id="totalSubjects"><?php echo array_sum(array_map(function($sem) { return count($sem['subjects']); }, $data['semesters'])); ?></div>
                        <div class="label">Barcha semestrlar bo'yicha</div>
                    </div>
                    
                    <div class="stat-card-excel">
                        <h4>Umumiy Kreditlar</h4>
                        <div class="value" id="totalCredits"><?php echo $data['yearlyTotal']['credit']; ?></div>
                        <div class="label">Jami kredit soatlari</div>
                    </div>
                    
                    <div class="stat-card-excel">
                        <h4>Jami Soatlar</h4>
                        <div class="value" id="totalHours"><?php echo $data['yearlyTotal']['totalHours']; ?></div>
                        <div class="label">Barcha fanlar uchun</div>
                    </div>
                    
                    <div class="stat-card-excel">
                        <h4>Semestrlar</h4>
                        <div class="value" id="totalSemesters"><?php echo count($data['semesters']); ?></div>
                        <div class="label">Umumiy semestrlar soni</div>
                    </div>
                </div>

                <!-- Excel View Table -->
                <div class="excel-view-container">
                    <div class="excel-header">
                        <div>
                            <h2>O'QUV REJA JADVALI</h2>
                            <div class="academic-year" id="academicYear"><?php echo $data['academicYear']; ?> O'quv yili</div>
                        </div>
                        <div class="excel-actions">
                            <button class="btn btn-sm btn-light" id="zoomIn">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-light" id="zoomOut">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <button class="btn btn-sm btn-light" id="fullscreen">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>

                    <div class="excel-table-container" id="excelTableContainer">
                    
                        <table class="excel-table" id="excelTable">
                            <!-- Table will be generated by JavaScript -->
                        </table>
                        
                        <div class="cell-tooltip" id="cellTooltip"></div>
                    </div>

                    <div class="pagination-controls">
                        <button class="btn btn-sm btn-outline" id="prevPage">
                            <i class="fas fa-chevron-left"></i> Oldingi
                        </button>
                        <span class="page-info">
                            Sahifa: <span id="currentPage">1</span> / <span id="totalPages">1</span>
                        </span>
                        <button class="btn btn-sm btn-outline" id="nextPage">
                            Keyingi <i class="fas fa-chevron-right"></i>
                        </button>
                        <select id="pageSize" class="filter-select" style="width: auto;">
                            <option value="10">10 qator</option>
                            <option value="25">25 qator</option>
                            <option value="50">50 qator</option>
                            <option value="100">100 qator</option>
                        </select>
                    </div>
                </div>

                <!-- Legend -->
                <div class="legend mt-4">
                    <h6><i class="fas fa-info-circle me-2"></i>Belgilar:</h6>
                    <div class="legend-items">
                        <div class="legend-item">
                            <span class="exam-type exam-s">S</span>
                            <span>Sinov (Test/Quiz)</span>
                        </div>
                        <div class="legend-item">
                            <span class="exam-type exam-i">I</span>
                            <span>Imtihon (Exam)</span>
                        </div>
                        <div class="legend-item">
                            <span style="display: inline-block; width: 20px; height: 20px; background-color: #e8f4fc; border: 1px solid #3498db;"></span>
                            <span>Auditoriya soatlari</span>
                        </div>
                        <div class="legend-item">
                            <span style="display: inline-block; width: 20px; height: 20px; background-color: #d5f5e3; border: 1px solid #27ae60;"></span>
                            <span>Semestr jami</span>
                        </div>
                        <div class="legend-item">
                            <span style="display: inline-block; width: 20px; height: 20px; background-color: #2ecc71; color: white; text-align: center; line-height: 20px; font-weight: bold;">Y</span>
                            <span>Yillik jami</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Subject Details Modal -->
    <div class="modal" id="subjectModal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h3 id="subjectModalTitle">Fan ma'lumotlari</h3>
                <button class="modal-close" id="closeSubjectModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="subject-detail" id="subjectDetailContent">
                    <!-- JavaScript orqali to'ldiriladi -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeSubjectBtn">
                    Yopish
                </button>
                <button type="button" class="btn btn-primary" id="editSubjectBtn">
                    <i class="fas fa-edit"></i> Tahrirlash
                </button>
            </div>
        </div>
    </div>

    <script>
        const sampleData = <?php echo json_encode($data, JSON_UNESCAPED_UNICODE); ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Dastlabki yuklash
            initializeTable();
            setupEventListeners();
            loadData();
            updateStats();
            updateCurrentDate();
            
            // Boshlang'ich filtrni qo'llash
            applyFilters();
        });

        // Global o'zgaruvchilar
        let currentPage = 1;
        let pageSize = 25;
        let filteredData = [];
        let currentSemester = 'all';
        let zoomLevel = 100;

        function initializeTable() {
            const academicYearElement = document.getElementById('academicYear');
            if (academicYearElement) {
                academicYearElement.textContent = `${sampleData.academicYear} O'quv yili`;
            }
            
            // Semestr switcher yaratish
            createSemesterSwitcher();
        }

        function createSemesterSwitcher() {
            const switcher = document.getElementById('semesterSwitcher');
            if (!switcher) return;
            
            let html = `
                <button class="semester-btn active" data-semester="all">
                    <i class="fas fa-layer-group"></i> Barcha semestrlar
                </button>
            `;
            
            sampleData.semesters.forEach(semester => {
                const semNum = semester.name.split('-')[0];
                html += `
                    <button class="semester-btn" data-semester="${semNum}">
                        ${semester.name}
                    </button>
                `;
            });
            
            switcher.innerHTML = html;
            
            // Event listener qo'shish
            switcher.querySelectorAll('.semester-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    switcher.querySelectorAll('.semester-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentSemester = this.dataset.semester;
                    applyFilters();
                });
            });
        }

        function loadData() {
            // Ma'lumotlarni localStorage dan yoki API dan olish
            // Hozircha PHP dan kelgan ma'lumotlardan foydalanamiz
            filteredData = getAllSubjects();
            renderTable();
        }

        function getAllSubjects() {
            let allSubjects = [];
            sampleData.semesters.forEach(semester => {
                const semNum = semester.name.split('-')[0];
                semester.subjects.forEach(subject => {
                    allSubjects.push({
                        ...subject,
                        semester: semNum,
                        semesterName: semester.name,
                        semesterId: semester.id
                    });
                });
            });
            return allSubjects;
        }

        function setupEventListeners() {
            // Filtrlar
            document.getElementById('applyFilters')?.addEventListener('click', applyFilters);
            document.getElementById('clearFilters')?.addEventListener('click', clearFilters);
            
            // Search
            document.getElementById('searchSubject')?.addEventListener('input', debounce(applyFilters, 300));
            
            // Pagination
            document.getElementById('prevPage')?.addEventListener('click', goToPrevPage);
            document.getElementById('nextPage')?.addEventListener('click', goToNextPage);
            document.getElementById('pageSize')?.addEventListener('change', function() {
                pageSize = parseInt(this.value);
                currentPage = 1;
                applyFilters();
            });
            
            // Eksport va chop etish
            document.getElementById('exportExcel')?.addEventListener('click', exportToExcel);
            document.getElementById('printTable')?.addEventListener('click', printTable);
            
            // Zoom va fullscreen
            document.getElementById('zoomIn')?.addEventListener('click', () => changeZoom(10));
            document.getElementById('zoomOut')?.addEventListener('click', () => changeZoom(-10));
            document.getElementById('fullscreen')?.addEventListener('click', toggleFullscreen);
            
            // Subject modal
            document.getElementById('closeSubjectModal')?.addEventListener('click', function() {
                document.getElementById('subjectModal').classList.remove('show');
            });
            
            document.getElementById('closeSubjectBtn')?.addEventListener('click', function() {
                document.getElementById('subjectModal').classList.remove('show');
            });
        }

        function applyFilters() {
            
            // Filtr qiymatlarini olish
            const yonalishFilter = document.getElementById('filterYonalish').value.toLowerCase();
            const kursFilter = document.getElementById('filterKurs').value;
            const imtihonFilter = document.getElementById('filterImtihon').value;
            const searchTerm = document.getElementById('searchSubject')?.value.toLowerCase() || '';
            
            // Barcha fanlarni olish
            let subjects = getAllSubjects();
            // Filtrlash
            subjects = subjects.filter(subject => {
                // Semestr filtr
                if (currentSemester !== 'all' && subject.semester.toString() !== currentSemester) {
                    return false;
                }
                
                // Kafedra filtr
                if (yonalishFilter && !subject.department.toLowerCase().includes(yonalishFilter)) {
                    return false;
                }
                
                // Kurs (semestr) filtr
                if (kursFilter && subject.semester.toString() !== kursFilter) {
                    return false;
                }
                
                // Imtihon turi filtr
                if (imtihonFilter && subject.examType !== imtihonFilter) {
                    return false;
                }
                
                // Qidiruv
                if (searchTerm) {
                    const searchIn = (subject.code + subject.name + subject.department).toLowerCase();
                    if (!searchIn.includes(searchTerm)) {
                        return false;
                    }
                }
                
                return true;
            });
            
            filteredData = subjects;
            
            // Pagination
            currentPage = 1;
            renderTable();
            updateStats();
            updatePagination();
            
        }

        function clearFilters() {
            document.getElementById('filterYonalish').value = '';
            document.getElementById('filterKurs').value = '';
            document.getElementById('filterImtihon').value = '';
            document.getElementById('searchSubject').value = '';
            
            // Semestr switcherni qayta o'rnatish
            const allBtn = document.querySelector('.semester-btn[data-semester="all"]');
            if (allBtn) {
                document.querySelectorAll('.semester-btn').forEach(btn => btn.classList.remove('active'));
                allBtn.classList.add('active');
                currentSemester = 'all';
            }
            
            applyFilters();
        }

        function renderTable() {
            const table = document.getElementById('excelTable');
            const container = document.getElementById('excelTableContainer');
            
            if (!table || !container) return;
            
            // Jadvalni tozalash
            table.innerHTML = '';
            
            // Jadval sarlavhasini yaratish
            const thead = document.createElement('thead');
            thead.innerHTML = createTableHeader();
            table.appendChild(thead);
            
            // Jadval tana qismini yaratish
            const tbody = document.createElement('tbody');
            
            // Semestrlar sonini tekshirish
            const semestersCount = sampleData.semesters.length;
            
            if (semestersCount === 0) {
                // Ma'lumot yo'q
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `
                    <td colspan="32" style="text-align: center; padding: 50px; color: #999;">
                        <i class="fas fa-database" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <h3>Ma'lumot topilmadi</h3>
                        <p>O'quv rejasi ma'lumotlari mavjud emas</p>
                    </td>
                `;
                tbody.appendChild(noDataRow);
            } else if (semestersCount === 1) {
                // Faqat 1 semestr
                const semester = sampleData.semesters[0];
                renderSingleSemester(tbody, semester);
            } else {
                // Ko'p semestrlar
                // Har 2 semestr uchun juftlik hosil qilish
                for (let i = 0; i < semestersCount; i += 2) {
                    const leftSemester = sampleData.semesters[i];
                    const rightSemester = i + 1 < semestersCount ? sampleData.semesters[i + 1] : null;
                    
                    renderSemesterPair(tbody, leftSemester, rightSemester);
                }
            }
            
            // Yillik jami qatori (faqat barcha semestrlar ko'rinishida)
            if (currentSemester === 'all') {
                const yearlyTotalRow = document.createElement('tr');
                yearlyTotalRow.className = 'year-total-row';
                yearlyTotalRow.innerHTML = createYearlyTotalRow();
                tbody.appendChild(yearlyTotalRow);
            }
            
            table.appendChild(tbody);
            
            // Tooltip va click eventlarini qo'shish
            setupTableInteractions();
        }

        function createTableHeader() {
            return `
                <tr>
                    <th colspan="32" style="text-align: center; font-size: 1.2rem; background-color: #f8f9fa;">
                        O'QUV REJA JADVALI - ${sampleData.academicYear} O'QUV YILI
                    </th>
                </tr>
            `;
        }

        function renderSingleSemester(tbody, semester) {
            // Semestr sarlavhasi
            const semesterHeader = document.createElement('tr');
            semesterHeader.className = 'semester-header-row';
            semesterHeader.innerHTML = `
                <td colspan="16" class="semester-header">${semester.name}</td>
            `;
            tbody.appendChild(semesterHeader);
            
            // Ustun sarlavhalari
            const columnHeader = document.createElement('tr');
            columnHeader.innerHTML = createColumnHeaders();
            tbody.appendChild(columnHeader);
            
            // Fanlar ro'yxati
            const subjects = semester.subjects;
            for (let i = 0; i < subjects.length; i++) {
                const subject = subjects[i];
                const row = document.createElement('tr');
                row.className = 'subject-row';
                row.innerHTML = createSubjectCells(subject, 'left');
                tbody.appendChild(row);
            }
            
            // Semestr jami qatori
            const semesterTotals = document.createElement('tr');
            semesterTotals.className = 'total-row';
            semesterTotals.innerHTML = createSemesterTotalRow(semester);
            tbody.appendChild(semesterTotals);
        }

        function renderSemesterPair(tbody, leftSemester, rightSemester) {
            // Juft semestr sarlavhasi
            const semesterHeader = document.createElement('tr');
            semesterHeader.className = 'semester-header-row';
            
            if (rightSemester) {
                semesterHeader.innerHTML = `
                    <td colspan="16" class="semester-header">${leftSemester.name}</td>
                    <td colspan="16" class="semester-header">${rightSemester.name}</td>
                `;
            } else {
                semesterHeader.innerHTML = `
                    <td colspan="16" class="semester-header">${leftSemester.name}</td>
                    <td colspan="16" class="semester-header">-</td>
                `;
            }
            tbody.appendChild(semesterHeader);
            
            // Ustun sarlavhalari
            const columnHeader = document.createElement('tr');
            columnHeader.innerHTML = createColumnHeaders();
            tbody.appendChild(columnHeader);
            
            // Fanlar ro'yxati
            const leftSubjects = leftSemester.subjects;
            const rightSubjects = rightSemester ? rightSemester.subjects : [];
            const maxRows = Math.max(leftSubjects.length, rightSubjects.length);
            
            for (let i = 0; i < maxRows; i++) {
                const row = document.createElement('tr');
                row.className = 'subject-row';
                
                // Chap semestr
                const leftSubject = i < leftSubjects.length ? leftSubjects[i] : null;
                row.innerHTML += createSubjectCells(leftSubject, 'left');
                
                // O'ng semestr
                const rightSubject = i < rightSubjects.length ? rightSubjects[i] : null;
                row.innerHTML += createSubjectCells(rightSubject, 'right');
                
                tbody.appendChild(row);
            }
            
            // Semestr jami qatorlari
            const semesterTotals = document.createElement('tr');
            semesterTotals.className = 'total-row';
            semesterTotals.innerHTML = createSemesterPairTotalRow(leftSemester, rightSemester);
            tbody.appendChild(semesterTotals);
        }

        function createColumnHeaders() {
            return `
                <!-- Chap semestr ustunlari -->
                <th class="fixed-column">Kod</th>
                <th>Fan nomi</th>
                <th>S/I</th>
                <th>Kredit</th>
                <th>Soat</th>
                <th colspan="5" class="section-header">Auditoriya soatlari</th>
                <th>Malaka amaliyot</th>
                <th>Kurs ishi</th>
                <th>Mustaqil taʼlim</th>
                <th>Kafedra</th>
                
                <!-- O'ng semestr ustunlari -->
                <th class="fixed-column">Kod</th>
                <th>Fan nomi</th>
                <th>S/I</th>
                <th>Kredit</th>
                <th>Soat</th>
                <th colspan="5" class="section-header">Auditoriya soatlari</th>
                <th>Malaka amaliyot</th>
                <th>Kurs ishi</th>
                <th>Mustaqil taʼlim</th>
                <th>Kafedra</th>
            `;
        }

        function createSubjectCells(subject, side) {
            if (!subject) {
                // Bo'sh qator
                return `
                    <td class="fixed-column ${side === 'left' ? '' : ''}"><div class="subject-code">-</div></td>
                    <td><div class="subject-name">-</div></td>
                    <td><span class="exam-type">-</span></td>
                    <td class="credit-cell">-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                `;
            }
            
            // Kreditni hisoblash (1 kredit = 30 soat)
            const calculatedCredit = Math.round(subject.totalHours / 30);
            
            return `
                <td class="fixed-column ${side === 'left' ? '' : ''}">
                    <div class="subject-code" title="${subject.code}">${subject.code}</div>
                </td>
                <td>
                    <div class="subject-name" title="${subject.name}">
                        ${truncateText(subject.name, 50)}
                    </div>
                </td>
                <td>
                    <span class="exam-type exam-${subject.examType.toLowerCase()}" title="${subject.examType === 'I' ? 'Imtihon' : 'Sinov'}">
                        ${subject.examType}
                    </span>
                </td>
                <td class="credit-cell">${calculatedCredit}</td>
                <td>${subject.totalHours}</td>
                <td>${subject.auditoriya.total}</td>
                <td>${subject.auditoriya.lecture}</td>
                <td>${subject.auditoriya.practical}</td>
                <td>${subject.auditoriya.lab}</td>
                <td>${subject.auditoriya.seminar}</td>
                <td>${subject.malakaAmaliyot || 0}</td>
                <td>${subject.kursIshi || 0}</td>
                <td>${subject.mustaqilTalim}</td>
                <td title="${subject.department}">${truncateText(subject.department, 20)}</td>
            `;
        }

        function createSemesterTotalRow(semester) {
            // Kreditni qayta hisoblash (1 kredit = 30 soat)
            const totalCredit = Math.round(semester.totals.totalHours / 30);
            
            return `
                <td colspan="2" class="total-cell"><strong>Jami semestrda</strong></td>
                <td class="total-cell"></td>
                <td class="total-cell semester-total">${totalCredit}</td>
                <td class="total-cell semester-total">${semester.totals.totalHours}</td>
                <td class="total-cell semester-total">${semester.totals.auditoriya.total}</td>
                <td class="total-cell semester-total">${semester.totals.auditoriya.lecture}</td>
                <td class="total-cell semester-total">${semester.totals.auditoriya.practical}</td>
                <td class="total-cell semester-total">${semester.totals.auditoriya.lab}</td>
                <td class="total-cell semester-total">${semester.totals.auditoriya.seminar}</td>
                <td class="total-cell semester-total">${semester.totals.malakaAmaliyot || 0}</td>
                <td class="total-cell semester-total">${semester.totals.kursIshi || 0}</td>
                <td class="total-cell semester-total">${semester.totals.mustaqilTalim}</td>
                <td class="total-cell"></td>
                <td colspan="16" class="total-cell"></td>
            `;
        }

        function createSemesterPairTotalRow(leftSemester, rightSemester) {
            // Chap semestr kreditini hisoblash
            const leftCredit = Math.round(leftSemester.totals.totalHours / 30);
            
            let rightHtml = '';
            if (rightSemester) {
                const rightCredit = Math.round(rightSemester.totals.totalHours / 30);
                rightHtml = `
                    <td colspan="2" class="total-cell"><strong>Jami semestrda</strong></td>
                    <td class="total-cell"></td>
                    <td class="total-cell semester-total">${rightCredit}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.totalHours}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.auditoriya.total}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.auditoriya.lecture}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.auditoriya.practical}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.auditoriya.lab}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.auditoriya.seminar}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.malakaAmaliyot || 0}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.kursIshi || 0}</td>
                    <td class="total-cell semester-total">${rightSemester.totals.mustaqilTalim}</td>
                    <td class="total-cell"></td>
                `;
            } else {
                rightHtml = `<td colspan="14" class="total-cell"></td>`;
            }
            
            return `
                <td colspan="2" class="total-cell"><strong>Jami semestrda</strong></td>
                <td class="total-cell"></td>
                <td class="total-cell semester-total">${leftCredit}</td>
                <td class="total-cell semester-total">${leftSemester.totals.totalHours}</td>
                <td class="total-cell semester-total">${leftSemester.totals.auditoriya.total}</td>
                <td class="total-cell semester-total">${leftSemester.totals.auditoriya.lecture}</td>
                <td class="total-cell semester-total">${leftSemester.totals.auditoriya.practical}</td>
                <td class="total-cell semester-total">${leftSemester.totals.auditoriya.lab}</td>
                <td class="total-cell semester-total">${leftSemester.totals.auditoriya.seminar}</td>
                <td class="total-cell semester-total">${leftSemester.totals.malakaAmaliyot || 0}</td>
                <td class="total-cell semester-total">${leftSemester.totals.kursIshi || 0}</td>
                <td class="total-cell semester-total">${leftSemester.totals.mustaqilTalim}</td>
                <td class="total-cell"></td>
                ${rightHtml}
            `;
        }

        function createYearlyTotalRow() {
            // Yillik kreditni qayta hisoblash (1 kredit = 30 soat)
            const yearlyCredit = Math.round(sampleData.yearlyTotal.totalHours / 30);
            
            return `
                <td colspan="2" class="total-cell"><strong>Jami yillik</strong></td>
                <td class="total-cell"></td>
                <td class="total-cell year-total">${yearlyCredit}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.totalHours}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.auditoriya.total}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.auditoriya.lecture}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.auditoriya.practical}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.auditoriya.lab}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.auditoriya.seminar}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.malakaAmaliyot}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.kursIshi}</td>
                <td class="total-cell year-total">${sampleData.yearlyTotal.mustaqilTalim}</td>
                <td class="total-cell"></td>
                <td colspan="16" class="total-cell"></td>
            `;
        }

        // Qolgan JavaScript funksiyalari o'zgarmaydi (setupTableInteractions, showSubjectDetails, updateStats, updatePagination, exportToExcel, printTable, va hokazo)
        // Faqat updateStats funksiyasini yangilash kerak:
        function updateStats() {
            const totalSubjects = filteredData.length;
            const totalHours = filteredData.reduce((sum, subject) => sum + subject.totalHours, 0);
            const totalCredits = Math.round(totalHours / 30);
            const averageCredits = totalSubjects > 0 ? (totalCredits / totalSubjects).toFixed(1) : 0;
            const examSubjects = filteredData.filter(s => s.examType === 'I').length;
            
            document.getElementById('totalSubjects').textContent = totalSubjects;
            document.getElementById('totalCredits').textContent = totalCredits;
            document.getElementById('totalHours').textContent = totalHours;
        }

        // Qolgan yordamchi funksiyalar o'zgarmaydi (debounce, truncateText, openModal, showLoading, hideLoading, changeZoom, toggleFullscreen, updateCurrentDate)

        // Yordamchi funksiyalar
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function truncateText(text, maxLength) {
            if (!text) return '';
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }

        function openModal(modalId, title = '') {
            const modal = document.getElementById(modalId);
            const modalTitle = document.getElementById(modalId + 'Title') || 
                            document.getElementById('subjectModalTitle');
            
            if (modal) {
                if (modalTitle && title) {
                    modalTitle.textContent = title;
                }
                modal.classList.add('show');
            }
        }

        function changeZoom(delta) {
            zoomLevel = Math.min(Math.max(70, zoomLevel + delta), 150);
            const tableContainer = document.getElementById('excelTableContainer');
            if (tableContainer) {
                tableContainer.style.transform = `scale(${zoomLevel / 100})`;
                tableContainer.style.transformOrigin = 'top left';
            }
        }

        function toggleFullscreen() {
            const container = document.getElementById('excelTableContainer').parentElement;
            if (!document.fullscreenElement) {
                container.requestFullscreen().catch(err => {
                    console.error(`Fullscreen error: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        }

        function updateCurrentDate() {
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const now = new Date();
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                dateElement.textContent = now.toLocaleDateString('uz-UZ', options);
            }
        }

        function showSubjectDetails(code) {
            // Fan ma'lumotlarini topish
            let subject = null;
            let semesterName = '';
            
            for (const semester of sampleData.semesters) {
                const found = semester.subjects.find(s => s.code === code);
                if (found) {
                    subject = found;
                    semesterName = semester.name;
                    break;
                }
            }
            
            if (!subject) return;
            
            // Modal kontentini to'ldirish
            const detailContent = document.getElementById('subjectDetailContent');
            const calculatedCredit = Math.round(subject.totalHours / 30);
            
            detailContent.innerHTML = `
                <div class="subject-detail-header">
                    <h4>${subject.name}</h4>
                    <div class="subject-meta">
                        <span class="badge badge-primary">${subject.code}</span>
                        <span class="badge">${semesterName}</span>
                        <span class="exam-type exam-${subject.examType.toLowerCase()}">
                            ${subject.examType === 'I' ? 'Imtihon' : 'Sinov'}
                        </span>
                    </div>
                </div>
                
                <div class="subject-info-grid mt-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-weight-hanging"></i>
                        </div>
                        <div class="info-content">
                            <h6>Kredit</h6>
                            <p>${calculatedCredit} kredit</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h6>Umumiy soatlar</h6>
                            <p>${subject.totalHours} soat</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="info-content">
                            <h6>Auditoriya soatlari</h6>
                            <p>${subject.auditoriya.total} soat</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="info-content">
                            <h6>Mustaqil ta'lim</h6>
                            <p>${subject.mustaqilTalim} soat</p>
                        </div>
                    </div>
                </div>
                
                <div class="subject-details mt-4">
                    <h5><i class="fas fa-info-circle me-2"></i>Batafsil ma'lumotlar</h5>
                    <div class="details-grid">
                        <div class="detail-item">
                            <strong>Ma'ruza soatlari:</strong>
                            <span>${subject.auditoriya.lecture} soat</span>
                        </div>
                        <div class="detail-item">
                            <strong>Amaliy soatlar:</strong>
                            <span>${subject.auditoriya.practical} soat</span>
                        </div>
                        <div class="detail-item">
                            <strong>Laboratoriya soatlari:</strong>
                            <span>${subject.auditoriya.lab} soat</span>
                        </div>
                        <div class="detail-item">
                            <strong>Seminar soatlari:</strong>
                            <span>${subject.auditoriya.seminar} soat</span>
                        </div>
                        ${subject.malakaAmaliyot ? `
                        <div class="detail-item">
                            <strong>Malakaviy amaliyot:</strong>
                            <span>${subject.malakaAmaliyot} soat</span>
                        </div>
                        ` : ''}
                        ${subject.kursIshi ? `
                        <div class="detail-item">
                            <strong>Kurs ishi:</strong>
                            <span>${subject.kursIshi} soat</span>
                        </div>
                        ` : ''}
                        <div class="detail-item">
                            <strong>Kafedra:</strong>
                            <span>${subject.department}</span>
                        </div>
                    </div>
                </div>
            `;
            
            // Modalni ochish
            document.getElementById('subjectModalTitle').textContent = subject.name;
            openModal('subjectModal', 'Fan ma\'lumotlari');
        }

        function setupTableInteractions() {
            const table = document.getElementById('excelTable');
            const tooltip = document.getElementById('cellTooltip');
            
            if (!table || !tooltip) return;
            
            // Hover effekti uchun
            const cells = table.querySelectorAll('td, th');
            cells.forEach(cell => {
                // Tooltip
                cell.addEventListener('mouseenter', function(e) {
                    const title = this.getAttribute('title');
                    if (title) {
                        tooltip.textContent = title;
                        tooltip.style.opacity = '1';
                        
                        const rect = this.getBoundingClientRect();
                        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        
                        tooltip.style.left = (rect.left + scrollLeft + rect.width / 2) + 'px';
                        tooltip.style.top = (rect.top + scrollTop - 40) + 'px';
                    }
                });
                
                cell.addEventListener('mouseleave', function() {
                    tooltip.style.opacity = '0';
                });
                
                // Click event - fan ma'lumotlarini ko'rsatish
                if (cell.classList.contains('subject-code') || cell.classList.contains('subject-name')) {
                    cell.style.cursor = 'pointer';
                    cell.addEventListener('click', function() {
                        const code = this.closest('tr').querySelector('.subject-code')?.textContent;
                        if (code && code !== '-') {
                            showSubjectDetails(code);
                        }
                    });
                }
            });
            
            // Satr hover effekti
            const rows = table.querySelectorAll('.subject-row');
            rows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.classList.add('highlight-row');
                });
                
                row.addEventListener('mouseleave', function() {
                    this.classList.remove('highlight-row');
                });
            });
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / pageSize);
            document.getElementById('currentPage').textContent = currentPage;
            document.getElementById('totalPages').textContent = totalPages;
            
            // Previous/next tugmalarini faollashtirish/faolsizlashtirish
            document.getElementById('prevPage').disabled = currentPage <= 1;
            document.getElementById('nextPage').disabled = currentPage >= totalPages;
        }

        function goToPrevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
                updatePagination();
            }
        }

        function goToNextPage() {
            const totalPages = Math.ceil(filteredData.length / pageSize);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
                updatePagination();
            }
        }

        function exportToExcel() {
            
            // CSV formatga o'tkazish
            let csvContent = "Kod,Fan nomi,Sinov/Imtihon,Kredit,Soat,Jami auditoriya,Ma'ruza,Amaliy,Laboratoriya,Seminar,Malakaviy amaliyot,Kurs ishi,Mustaqil ta'lim,Kafedra,Semestr\n";
            
            filteredData.forEach(subject => {
                const row = [
                    `"${subject.code}"`,
                    `"${subject.name.replace(/"/g, '""')}"`,
                    subject.examType,
                    Math.round(subject.totalHours / 30),
                    subject.totalHours,
                    subject.auditoriya.total,
                    subject.auditoriya.lecture,
                    subject.auditoriya.practical,
                    subject.auditoriya.lab,
                    subject.auditoriya.seminar,
                    subject.malakaAmaliyot || 0,
                    subject.kursIshi || 0,
                    subject.mustaqilTalim,
                    `"${subject.department}"`,
                    subject.semesterName
                ];
                
                csvContent += row.join(',') + "\n";
            });
            
            // CSV faylni yuklab olish
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            
            link.setAttribute('href', url);
            link.setAttribute('download', `oquv_reja_${sampleData.academicYear}_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            alert('O\'quv reja CSV formatida yuklab olindi!');
            hideLoading();
        }

        function printTable() {
            const printContent = document.querySelector('.excel-view-container').cloneNode(true);
            
            // Chop etish uchun kerak bo'lmagan elementlarni olib tashlash
            const elementsToRemove = printContent.querySelectorAll('.excel-actions, .pagination-controls, .loading-overlay');
            elementsToRemove.forEach(el => el.remove());
            
            // Yangi oynada ochish
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="uz">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>O'quv Reja - ${sampleData.academicYear}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { border-collapse: collapse; width: 100%; font-size: 12px; }
                        th, td { border: 1px solid #ddd; padding: 6px; text-align: center; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        .semester-header { background-color: #3498db; color: white; }
                        .total-cell { background-color: #e8f6f3; font-weight: bold; }
                        .year-total { background-color: #2ecc71; color: white; }
                        .subject-code { font-weight: bold; }
                        @media print {
                            @page { size: landscape; }
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    <h2 style="text-align: center; margin-bottom: 20px;">
                        O'QUV REJA JADVALI - ${sampleData.academicYear} O'QUV YILI
                    </h2>
                    ${printContent.innerHTML}
                    <div style="margin-top: 30px; text-align: center; font-size: 11px; color: #666;">
                        ${new Date().toLocaleDateString('uz-UZ')} sanada chop etildi
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            
            // Chop etish
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }
    </script>
</body>
</html>