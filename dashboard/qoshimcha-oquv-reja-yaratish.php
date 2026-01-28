<?php

    include_once 'config.php';
    $db = new Database();
    $semestrlar = $db->get_semestrlar();
    $qoshimcha_dars_turlar = $db->get_data_by_table_all('qoshimcha_dars_turlar');
    $kafedralar = $db->get_data_by_table_all('kafedralar');
    $fanlar = $db->get_data_by_table_all('fanlar');
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Qo‘shimcha o‘quv reja yaratish</title>
    <link rel="stylesheet" href="../assets/css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-navbar">
                <h1>Qo‘shimcha o‘quv reja yaratish</h1>
            </header>
            <div class="content-container">
                <form id="oquvRejaForm" class="card">
                    <h3 class="section-title">Umumiy ma’lumot</h3>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Semestr</label>
                            <select class="form-control" name="semestr_id" id="semestrSelect"  required>
                                <option value="">Tanlang</option>
                                    <?php foreach ($semestrlar as $s): 
                                        $short = '';
                                        $words = preg_split('/\s+/u', trim($s['yonalish_name']));
                                        foreach ($words as $w) {
                                            $short .= mb_strtoupper(mb_substr($w, 0, 1, 'UTF-8'), 'UTF-8');
                                        }    
                                    ?>
                                    <option value="<?= $s['id'] ?>"
                                        data-talaba="<?= $s['jami_talabalar'] ?>">
                                        <?= $short . '_' . $s['kirish_yili'] . ' - ' . $s['semestr'] . '-semestr('.$s['jami_talabalar'].')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div id="rejaWrapper">
                        <div class="reja-card" data-index="0">
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Fan nomi</label>
                                    <input type="text" class="form-control" name="fan_nomi[]" required>
                                </div>
                                <div class="form-group">
                                    <label>Qo'shimcha dars turi</label>
                                    <select class="form-control" name="qoshimcha_dars_id[]" required>
                                        <option value="">Tanlang</option>
                                        <?php foreach ($qoshimcha_dars_turlar as $qdt): ?>
                                            <option value="<?= $qdt['id'] ?>"
                                                data-koef="<?= $qdt['koifesent'] ?>">
                                                <?= htmlspecialchars($qdt['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Fan soati</label>
                                    <input type="number" class="form-control" name="fan_soat[]" required>
                                </div>
                            </div>
                            <div class="darsSoatWrapper">
                                <div class="form-grid-2 dars-soat-row">
                                    <div class="form-group">
                                        <label>Kafedra</label>
                                        <select class="form-control" name="kafedra_id[0][]" required>
                                            <option value="">Tanlang</option>
                                            <?php foreach ($kafedralar as $k): ?>
                                                <option value="<?= $k['id'] ?>">
                                                    <?= htmlspecialchars($k['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Dars soati</label>
                                        <input type="number"
                                            class="form-control"
                                            name="dars_soati[0][]"
                                            min="0"
                                            required>
                                    </div>
                                </div>
                                <div class="dars-soat-actions">
                                    <button type="button" class="btn btn-outline btn-sm addDarsSoat">
                                        <i class="fas fa-plus"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm removeDarsSoat">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="reja-actions">
                                <button type="button" class="btn btn-outline btn-sm addReja">
                                    <i class="fas fa-plus"></i> Yana fan
                                </button>

                                <button type="button" class="btn btn-danger btn-sm removeReja">
                                    <i class="fas fa-times"></i> O‘chirish
                                </button>
                            </div>
                        </div>
                        <!-- /reja-card -->

                    </div>
                    <div class="form-group mt-3">
                        <label>Izoh</label>
                        <textarea class="form-control"
                                name="izoh"
                                rows="3"
                                placeholder="O‘quv reja bo‘yicha umumiy izoh..."></textarea>
                    </div>
                    <div class="form-actions mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Saqlash
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                                               
    <script>
        $(document).ready(function() {
            $(document).on('change', '#semestrSelect, select[name="qoshimcha_dars_id[]"]', function() {
                const card = $(this).closest('.reja-card');
                calculateForSingleCard(card);
            });
        });

        function calculateForSingleCard(card) {
            const semestrSelect = $('#semestrSelect');
            const qoshimchaSelect = card.find('select[name="qoshimcha_dars_id[]"]');
            const fanSoatInput = card.find('input[name="fan_soat[]"]');
            if (!semestrSelect.val() || !qoshimchaSelect.val()) {
                fanSoatInput.val('');
                return;
            }
            const semestrTalabalar = semestrSelect.find('option:selected').data('talaba') || 0;
            const koef = qoshimchaSelect.find('option:selected').data('koef') || 0;
            const fanSoati = Math.round(semestrTalabalar * koef);
            fanSoatInput.val(fanSoati);
        }
        $(document).ready(function() {
            $('#semestrSelect').select2({
                placeholder: "Semestrni tanlang",
                allowClear: true,
                width: '100%',
            });
            
            initInitialSelect2();
        });
        
        function initInitialSelect2() {
            $('select[name="qoshimcha_dars_id[]"]').select2({
                placeholder: "Qo'shimcha dars turini tanlang",
                allowClear: true,
                width: '100%',
            });
            
            $('select[name="kafedra_id[0][]"]').select2({
                placeholder: "Kafedrani tanlang",
                allowClear: true,
                width: '100%',
            });
        }
                
        let fanIndex = 0;
        
        function createNewReja() {
            fanIndex++;
            
            const originalHtml = `
                <div class="reja-card" data-index="${fanIndex}">
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label>Fan nomi</label>
                            <input type="text" class="form-control" name="fan_nomi[]" required>
                        </div>
                        <div class="form-group">
                            <label>Qo'shimcha dars turi</label>
                            <select class="form-control qoshimcha-select" name="qoshimcha_dars_id[]" required>
                                <option value="">Tanlang</option>
                                <?php foreach ($qoshimcha_dars_turlar as $qdt): ?>
                                    <option value="<?= $qdt['id'] ?>"
                                        data-koef="<?= $qdt['koifesent'] ?>">
                                        <?= htmlspecialchars($qdt['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fan soati</label>
                            <input type="number" class="form-control" name="fan_soat[]" required>
                        </div>
                    </div>
                    <div class="darsSoatWrapper">
                        <div class="form-grid-2 dars-soat-row">
                            <div class="form-group">
                                <label>Kafedra</label>
                                <select class="form-control kafedra-select" name="kafedra_id[${fanIndex}][]" required>
                                    <option value="">Tanlang</option>
                                    <?php foreach ($kafedralar as $k): ?>
                                        <option value="<?= $k['id'] ?>">
                                            <?= htmlspecialchars($k['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dars soati</label>
                                <input type="number" class="form-control" name="dars_soati[${fanIndex}][]" min="0" required>
                            </div>
                        </div>
                        <div class="dars-soat-actions">
                            <button type="button" class="btn btn-outline btn-sm addDarsSoat">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm removeDarsSoat">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="reja-actions">
                        <button type="button" class="btn btn-outline btn-sm addReja">
                            <i class="fas fa-plus"></i> Yana fan
                        </button>
                        <button type="button" class="btn btn-danger btn-sm removeReja">
                            <i class="fas fa-times"></i> O'chirish
                        </button>
                    </div>
                </div>
            `;
            
            const newReja = $(originalHtml);
            $('#rejaWrapper').append(newReja);
            
            setTimeout(() => {
                newReja.find('.qoshimcha-select').select2({
                    placeholder: "Qo'shimcha dars turini tanlang",
                    allowClear: true,
                    width: '100%',
                });
                
                newReja.find('.kafedra-select').select2({
                    placeholder: "Kafedrani tanlang",
                    allowClear: true,
                    width: '100%',
                });
            }, 50);
            
            return newReja;
        }
        
        $(document).on('click', '.addDarsSoat', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const wrapper = $(this).closest('.darsSoatWrapper');
            const card = $(this).closest('.reja-card');
            const index = card.data('index');
            
            const newRowHtml = `
                <div class="form-grid-2 dars-soat-row">
                    <div class="form-group">
                        <label>Kafedra</label>
                        <select class="form-control kafedra-select" name="kafedra_id[${index}][]" required>
                            <option value="">Tanlang</option>
                            <?php foreach ($kafedralar as $k): ?>
                                <option value="<?= $k['id'] ?>">
                                    <?= htmlspecialchars($k['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Dars soati</label>
                        <input type="number" class="form-control" name="dars_soati[${index}][]" min="0" required>
                    </div>
                </div>
            `;
            
            const newRow = $(newRowHtml);
            wrapper.find('.dars-soat-actions').before(newRow);
            
            setTimeout(() => {
                newRow.find('.kafedra-select').select2({
                    placeholder: "Kafedrani tanlang",
                    allowClear: true,
                    width: '100%',
                });
            }, 50);
        });
        
        $(document).on('click', '.removeDarsSoat', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const wrapper = $(this).closest('.darsSoatWrapper');
            const rows = wrapper.find('.dars-soat-row');
            
            if (rows.length > 1) {
                const lastRow = rows.last();
                
                const select = lastRow.find('.kafedra-select');
                if (select.hasClass('select2-hidden-accessible')) {
                    select.select2('destroy');
                }
                
                lastRow.remove();
            }
        });
        
        $(document).on('click', '.addReja', function(e) {
            e.preventDefault();
            e.stopPropagation();
            createNewReja();
        });
        
        $(document).on('click', '.removeReja', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const rejas = $('.reja-card');
            const currentReja = $(this).closest('.reja-card');
            const currentIndex = currentReja.data('index');
            
            if (rejas.length > 1 && currentIndex !== 0) {
                currentReja.find('select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
                
                currentReja.remove();
            }
        });
        
       $(document).on('submit', '#oquvRejaForm', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const errors = [];
            if (!$('#semestrSelect').val()) {
                isValid = false;
                errors.push('Semestr tanlanmagan');
                $('#semestrSelect').next('.select2-container').css('border-color', '#e74c3c');
            } else {
                $('#semestrSelect').next('.select2-container').css('border-color', '');
            }
            
            $('.reja-card').each(function(index) {
                const card = $(this);
                const cardIndex = index + 1;
                
                const fanNomi = card.find('input[name="fan_nomi[]"]');
                if (!fanNomi.val().trim()) {
                    isValid = false;
                    errors.push(`${cardIndex}-fan nomi kiritilmagan`);
                    fanNomi.css('border-color', '#e74c3c');
                } else {
                    fanNomi.css('border-color', '');
                }
                
                const fanSoat = card.find('input[name="fan_soat[]"]');
                if (!fanSoat.val() || fanSoat.val() <= 0) {
                    isValid = false;
                    errors.push(`${cardIndex}-fan soati noto'g'ri`);
                    fanSoat.css('border-color', '#e74c3c');
                } else {
                    fanSoat.css('border-color', '');
                }
                
                const qoshimchaSelect = card.find('select[name="qoshimcha_dars_id[]"]');
                if (!qoshimchaSelect.val()) {
                    isValid = false;
                    errors.push(`${cardIndex}-fan uchun qo'shimcha dars turi tanlanmagan`);
                    qoshimchaSelect.next('.select2-container').css('border-color', '#e74c3c');
                } else {
                    qoshimchaSelect.next('.select2-container').css('border-color', '');
                }
                
                card.find('select[name^="kafedra_id"]').each(function(kafedraIndex) {
                    if (!$(this).val()) {
                        isValid = false;
                        errors.push(`${cardIndex}-fan uchun ${kafedraIndex + 1}-kafedra tanlanmagan`);
                        $(this).next('.select2-container').css('border-color', '#e74c3c');
                    } else {
                        $(this).next('.select2-container').css('border-color', '');
                    }
                });
                
                card.find('input[name^="dars_soati"]').each(function(soatIndex) {
                    if (!$(this).val() || $(this).val() < 0) {
                        isValid = false;
                        errors.push(`${cardIndex}-fan uchun ${soatIndex + 1}-dars soati noto'g'ri`);
                        $(this).css('border-color', '#e74c3c');
                    } else {
                        $(this).css('border-color', '');
                    }
                });
            });
            
            if (!isValid) {
                Toast.fire({
                    icon: 'error',
                    title: errors[0] || 'Iltimos, barcha maydonlarni to\'ldiring'
                });
                return;
            }
            
            let hourMismatch = false;
            let mismatchMessage = "";
            
            $('.reja-card').each(function(index) {
                const card = $(this);
                const cardIndex = index + 1;

                const fanSoat = card.find('input[name="fan_soat[]"]');
                const fanSoatValue = parseFloat(fanSoat.val()) || 0;
                let kafedraSoatlariYigindisi = 0;
                
                card.find('input[name^="dars_soati"]').each(function() {
                    const soatValue = parseFloat($(this).val()) || 0;
                    kafedraSoatlariYigindisi += soatValue;
                });
                
                if (fanSoatValue !== kafedraSoatlariYigindisi) {
                    hourMismatch = true;
                    mismatchMessage = `${cardIndex}-fan soati (${fanSoatValue}) kafedralarga bo'lingan soatlar yig'indisiga (${kafedraSoatlariYigindisi}) teng emas!`;
                    return false; 
                }
            });
            
            if (hourMismatch) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Soatlar mos emas!',
                    text: mismatchMessage,
                    confirmButtonText: 'Tushunarli'
                });
                return;
            }
            
            const form = $(this);
            const formData = new FormData(this);
            
            fetch('insert/add_qoshimcha_oquv_reja.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Qo\'shimcha o\'quv reja muvaffaqiyatli saqlandi'
                    });

                    form[0].reset();
                    
                    $('#semestrSelect').val(null).trigger('change');
                    $('select[name="qoshimcha_dars_id[]"]').each(function() {
                        $(this).val(null).trigger('change');
                    });
                    $('select[name^="kafedra_id"]').each(function() {
                        $(this).val(null).trigger('change');
                    });
                    
                    $('.reja-card').each(function(index) {
                        if (index > 0) {
                            $(this).find('select').each(function() {
                                if ($(this).hasClass('select2-hidden-accessible')) {
                                    $(this).select2('destroy');
                                }
                            });
                            $(this).remove();
                        }
                    });
                    
                    fanIndex = 0;

                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message || 'Xatolik yuz berdi'
                    });
                }
            })
            .catch(() => {
                Toast.fire({
                    icon: 'error',
                    title: 'Server bilan bog\'lanib bo\'lmadi'
                });
            });
        });
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    </script>
</body>
</html>
