<?php
    include_once '../config.php';
    $db = new Database();
    header('Content-Type: application/json');
    $nomi = trim($_POST['nomi']);   
    $code = trim($_POST['code']);
    $muddati = trim($_POST['muddati']);
    $kvalifikatsiya = trim($_POST['kvalifikatsiya']);
    $akademik_daraja_id = trim($_POST['akademik_daraja_id']);
    $talim_shakli_id = trim($_POST['talim_shakli_id']);
    $fakultet_id = trim($_POST['fakultet_id']);
    $kirish_yili = trim($_POST['kirish_yili']);
    $patok_soni = ($_POST['patok_soni']);
    $kattaguruh_soni = ($_POST['kattaguruh_soni']);
    $kichikguruh_soni = ($_POST['kichikguruh_soni']);
    $insertsql = $db->insert('yonalishlar', [
        'name' => $nomi,
        'code' => $code,
        'muddati' => $muddati,
        'kvalifikatsiya' => $kvalifikatsiya,
        'akademik_daraja_id' => $akademik_daraja_id,
        'talim_shakli_id' => $talim_shakli_id,
        'fakultet_id' => $fakultet_id,
        'kirish_yili' => $kirish_yili,
        'patok_soni' => $patok_soni,
        'kattaguruh_soni' => $kattaguruh_soni,
        'kichikguruh_soni' => $kichikguruh_soni
    ]);
    if ($insertsql) {
        echo json_encode([
            'success' => true,
            'message' => 'Yo‘nalish muvaffaqiyatli qo\'shildi'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Yo‘nalish qo\'shishda xatolik yuz berdi'
        ]);
    }
    
?>