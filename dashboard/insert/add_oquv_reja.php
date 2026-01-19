<?php
    include_once '../config.php';
    header('Content-Type: application/json');
    $db = new Database();
    if (
        empty($_POST['semestr_id']) ||
        empty($_POST['fan_code']) ||
        empty($_POST['fan_nomi']) ||
        empty($_POST['dars_turi']) ||
        empty($_POST['dars_soati'])
    ) {
        echo json_encode([
            'success' => false,
            'message' => 'Maʼlumotlar to‘liq emas'
        ]);
        exit;
    }
    $semestr_id   = (int) $_POST['semestr_id'];
    $fan_codes    = $_POST['fan_code'];
    $fan_nomlar   = $_POST['fan_nomi'];
    $dars_turlari = $_POST['dars_turi'];
    $dars_soatlari= $_POST['dars_soati'];
    $izoh         = trim($_POST['izoh'] ?? '');

    $insertCount = 0;

    foreach ($fan_codes as $fanIndex => $fanCode) {

        $fanCode = trim($fanCode);
        $fanName = trim($fan_nomlar[$fanIndex] ?? '');

        if ($fanCode === '' || $fanName === '') {
            continue;
        }

        if (!isset($dars_turlari[$fanIndex], $dars_soatlari[$fanIndex])) {
            continue;
        }
        foreach ($dars_turlari[$fanIndex] as $i => $darsTurId) {

            $darsTurId = (int) $darsTurId;
            $darsSoat  = (int) ($dars_soatlari[$fanIndex][$i] ?? 0);

            if ($darsTurId <= 0 || $darsSoat <= 0) {
                continue;
            }
            $insert = $db->insert('oquv_rejalar', [
                'semestr_id'  => $semestr_id,
                'fan_code'    => $fanCode,
                'fan_name'    => $fanName,
                'dars_tur_id' => $darsTurId,
                'dars_soat'   => $darsSoat,
                'izoh'        => $izoh
            ]);

            if ($insert) {
                $insertCount++;
            }
        }
    }

    if ($insertCount === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Saqlash uchun yaroqli maʼlumot topilmadi'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => "O‘quv reja muvaffaqiyatli saqlandi ({$insertCount} ta)"
    ]);


?>