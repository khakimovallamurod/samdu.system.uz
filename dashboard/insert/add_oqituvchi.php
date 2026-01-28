<?php
    include_once '../config.php';
    $db = new Database();
    $fakultet_id = isset($_POST['fakultet_id']) ? (int)$_POST['fakultet_id'] : 0;
    $kafedra_id = isset($_POST['kafedra_id']) ? (int)$_POST['kafedra_id'] : 0;
    $fio = isset($_POST['fio']) ? trim($_POST['fio']) : '';
    $ilmiy_unvon_id = isset($_POST['ilmiy_unvon_id']) ? (int)$_POST['ilmiy_unvon_id'] : 0;
    $ilmiy_daraja_id = isset($_POST['ilmiy_daraja_id']) ? (int)$_POST['ilmiy_daraja_id'] : 0;
    $lavozim = isset($_POST['lavozim']) ? trim($_POST['lavozim']) : '';
    if ($fakultet_id == 0 || $kafedra_id == 0 || empty($fio) || $ilmiy_unvon_id == 0 || $ilmiy_daraja_id == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Iltimos, barcha maydonlarni to‘ldiring.'
        ]);
        exit;
    }
    $data = [
        'fakultet_id' => $fakultet_id,
        'kafedra_id' => $kafedra_id,
        'fio' => $fio,
        'lavozim' => $lavozim,
        'ilmiy_unvon_id' => $ilmiy_unvon_id,
        'ilmiy_daraja_id' => $ilmiy_daraja_id
    ];
    $inserted = $db->insert('oqituvchilar', $data);
    if ($inserted) {
        echo json_encode([
            'success' => true,
            'message' => 'O‘qituvchi muvaffaqiyatli qo‘shildi.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'O‘qituvchini qo‘shishda xatolik yuz berdi.'
        ]);
    }

?>