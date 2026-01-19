<?php

    include_once 'config.php';
    $db = new Database();
    $semestrlar = $db->get_semestrlar();
    $dars_soat_turlari = $db->get_data_by_table_all('dars_soat_turlar');
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>O‘quv reja yaratish</title>

    <link rel="stylesheet" href="../assets/css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .reja-card {
            border: 1px solid #eee;
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            background: #fff;
        }

        .dars-soat-actions {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .reja-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
    </style>
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
                                <?php foreach ($semestrlar as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= implode(
                                            '',
                                            array_map(
                                                fn($w)=>mb_strtoupper(mb_substr($w,0,1,'UTF-8'),'UTF-8'),
                                                preg_split('/\s+/u', trim($s['yonalish_name']))
                                            )
                                        ).'_'.$s['kirish_yili'].' - '.$s['semestr'].'-semestr'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div id="rejaWrapper">
                        <div class="reja-card" data-index="0">
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label>Fan kodi</label>
                                    <input type="text" class="form-control" name="fan_code[]" required>
                                </div>

                                <div class="form-group">
                                    <label>Fan nomi</label>
                                    <input type="text" class="form-control" name="fan_nomi[]" required>
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
        $('#semestrSelect').select2({
            placeholder: "Semestrni tanlang",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#semestrSelect').parent()
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
                const clone = reja.cloneNode(true);

                clone.dataset.index = fanIndex;

                clone.querySelectorAll('input').forEach(i => i.value = '');

                clone.querySelectorAll('.dars-soat-row').forEach(row => {
                    row.querySelector('select').name = `dars_turi[${fanIndex}][]`;
                    row.querySelector('input').name  = `dars_soati[${fanIndex}][]`;
                });

                document.getElementById('rejaWrapper').appendChild(clone);
            }

            if (e.target.closest('.removeReja')) {
                const rejas = document.querySelectorAll('.reja-card');
                if (rejas.length > 1) e.target.closest('.reja-card').remove();
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
