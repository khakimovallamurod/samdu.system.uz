<?php 
    session_start();
    include_once 'config.php';
    header('Content-Type: application/json; charset=utf-8');

    $phone = $_POST['phone'] ?? '';
    $password = !empty($_POST['password']) ? md5($_POST['password']) : '';

    $db = new Database();
    $ret = [];

    // 1. Bo‘sh maydon
    if(empty($phone) || empty($password)){
        echo json_encode(['error' => 1, 'message' => "Telefon yoki parol kiritilmadi!"]);
        exit;
    }

    // 2. Userni telefon raqam orqali qidirish
    $fetch = $db->get_data_by_table('users', ['phone' => $phone, 'password' => $password]);
    // 3. Tekshirish
    if ($fetch) {
        $_SESSION['id'] = $fetch['id'];
        $_SESSION['fullname'] = $fetch['fullname'];
        $_SESSION['phone'] = $fetch['phone'];
        $ret = [
            'error'   => 0, 
            'message' => "Muvaffaqiyatli tizimga kirdingiz!",
        ];
    } else {
        $ret = [
            'error'   => 1, 
            'message' => "Telefon raqam yoki parol noto'g'ri!"
        ];
    }

    echo json_encode($ret);
    exit;
?>
