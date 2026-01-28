<?php

    include_once 'config.php';
    $db = new Database();
    $semestrlar = $db->get_semestrlar();
    $dars_soat_turlari = $db->get_data_by_table_all('dars_soat_turlar');
    $kafedralar = $db->get_data_by_table_all('kafedralar');
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>O‘quv reja yaratish</title>
    <link rel="stylesheet" href="../assets/css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-navbar">
                <h1>O‘quv reja yaratish</h1>
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
                                    <option value="<?= $s['id'] ?>">
                                        <?= $short . '_' . $s['kirish_yili'] . ' - ' . $s['semestr'] . '-semestr'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div id="rejaWrapper">
                        <div class="reja-card" data-index="0">
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label>Fan kodi</label>
                                    <input type="text" class="form-control" name="fan_code[]" required>
                                </div>
                                <div class="form-group">
                                    <label>Fan nomi</label>
                                    <input type="text" class="form-control" name="fan_nomi[]" required>
                                </div>
                                <div class="form-group">
                                    <label>Kafedra</label>
                                    <select class="form-control" name="kafedra_id[]" required>
                                        <option value="">Tanlang</option>
                                        <?php foreach ($kafedralar as $k): ?>
                                            <option value="<?= $k['id'] ?>">
                                                <?= htmlspecialchars($k['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="darsSoatWrapper">
                                <div class="form-grid-2 dars-soat-row">
                                    <div class="form-group">
                                        <label>Dars turi</label>
                                        <select class="form-control" name="dars_turi[0][]" required>
                                            <option value="">Tanlang</option>
                                            <?php foreach ($dars_soat_turlari as $d): ?>
                                                <option value="<?= $d['id'] ?>">
                                                    <?= htmlspecialchars($d['name']) ?>
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
                                            >
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
    // Dastlabki select2 larni ishga tushirish
    $(document).ready(function() {
        $('#semestrSelect').select2({
            placeholder: "Semestrni tanlang",
            allowClear: true,
            width: '100%',
        });
        
        // Dastlabki kafedra select2 ni ishga tushirish
        $('select[name="kafedra_id[]"]').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    placeholder: "Kafedrani tanlang",
                    allowClear: true,
                    width: '100%',
                });
            }
        });
    });
    
    let fanIndex = 0;
    
    document.addEventListener('click', function (e) {
        if (e.target.closest('.addDarsSoat')) {
            const wrapper = e.target.closest('.darsSoatWrapper');
            const card = e.target.closest('.reja-card');
            const index = card.dataset.index;
            const row = wrapper.querySelector('.dars-soat-row');
            const clone = row.cloneNode(true);

            clone.querySelectorAll('input').forEach(i => i.value = '');
            clone.querySelector('select').selectedIndex = 0;

            clone.querySelector('select').name = `dars_turi[${index}][]`;
            clone.querySelector('input').name  = `dars_soati[${index}][]`;

            wrapper.insertBefore(clone, wrapper.querySelector('.dars-soat-actions'));
        }

        if (e.target.closest('.removeDarsSoat')) {
            const wrapper = e.target.closest('.darsSoatWrapper');
            const rows = wrapper.querySelectorAll('.dars-soat-row');
            if (rows.length > 1) rows[rows.length - 1].remove();
        }
        
        if (e.target.closest('.addReja')) {
            fanIndex++;

            const reja = e.target.closest('.reja-card');
            
            // Simple clone qilish
            const clone = reja.cloneNode(true);
            
            // Indexni yangilash
            clone.dataset.index = fanIndex;

            // Input va selectlarni tozalash
            clone.querySelectorAll('input[type="text"], input[type="number"]').forEach(i => {
                i.value = '';
            });
            
            clone.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
                select.value = '';
            });

            // Name atributlarini yangilash (faqat zarur bo'lganlarini)
            const fanCodeInput = clone.querySelector('input[name="fan_code[]"]');
            const fanNomiInput = clone.querySelector('input[name="fan_nomi[]"]');
            
            if (fanCodeInput) fanCodeInput.name = 'fan_code[]';
            if (fanNomiInput) fanNomiInput.name = 'fan_nomi[]';

            // Dars soati qatorlarini yangilash
            const darsSoatRows = clone.querySelectorAll('.dars-soat-row');
            darsSoatRows.forEach(row => {
                const select = row.querySelector('select');
                const input = row.querySelector('input[type="number"]');
                
                if (select) select.name = `dars_turi[${fanIndex}][]`;
                if (input) input.name = `dars_soati[${fanIndex}][]`;
            });

            // DOM ga qo'shish
            document.getElementById('rejaWrapper').appendChild(clone);
            
            // Yangi kafedra select uchun select2 ni ishga tushirish
            setTimeout(() => {
                const newKafedraSelect = clone.querySelector('select[name="kafedra_id[]"]');
                if (newKafedraSelect) {
                    // Avval select2 ni yo'q qilish (agar mavjud bo'lsa)
                    if ($(newKafedraSelect).hasClass('select2-hidden-accessible')) {
                        $(newKafedraSelect).select2('destroy');
                    }
                    
                    // Select2 containerini olib tashlash
                    const select2Container = newKafedraSelect.nextElementSibling;
                    if (select2Container && select2Container.classList.contains('select2-container')) {
                        select2Container.remove();
                    }
                    
                    // Yangi select2 ni ishga tushirish
                    $(newKafedraSelect).select2({
                        placeholder: "Kafedrani tanlang",
                        allowClear: true,
                        width: '100%',
                    });
                }
            }, 10);
        }

        if (e.target.closest('.removeReja')) {
            const rejas = document.querySelectorAll('.reja-card');
            if (rejas.length > 1) {
                const rejaToRemove = e.target.closest('.reja-card');
                
                // Select2 ni destroy qilish
                const kafedraSelect = rejaToRemove.querySelector('select[name="kafedra_id[]"]');
                if (kafedraSelect && $(kafedraSelect).hasClass('select2-hidden-accessible')) {
                    $(kafedraSelect).select2('destroy');
                }
                
                rejaToRemove.remove();
            }
        }
    });
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });
    
    document.getElementById('oquvRejaForm').addEventListener('submit', function (e) {
        e.preventDefault();
        
        // Kafedra tanlanganligini tekshirish
        let allKafedraSelected = true;
        document.querySelectorAll('select[name="kafedra_id[]"]').forEach(select => {
            if (!select.value) {
                allKafedraSelected = false;
                select.style.borderColor = '#e74c3c';
                const select2Container = select.nextElementSibling;
                if (select2Container && select2Container.classList.contains('select2-container')) {
                    select2Container.style.border = '1px solid #e74c3c';
                }
            } else {
                select.style.borderColor = '';
                const select2Container = select.nextElementSibling;
                if (select2Container && select2Container.classList.contains('select2-container')) {
                    select2Container.style.border = '';
                }
            }
        });
        
        if (!allKafedraSelected) {
            Toast.fire({
                icon: 'error',
                title: 'Iltimos, barcha fanlar uchun kafedrani tanlang'
            });
            return;
        }
        
        const form = this;
        const formData = new FormData(form);
        
        fetch('insert/add_oquv_reja.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || 'O‘quv reja muvaffaqiyatli saqlandi'
                });

                form.reset();
                
                // Select2 larni ham tozalash
                $('#semestrSelect').val(null).trigger('change');
                $('select[name="kafedra_id[]"]').each(function() {
                    $(this).val(null).trigger('change');
                });

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
                title: 'Server bilan bog‘lanib bo‘lmadi'
            });
        });
    });
</script>  
</body>
</html>
