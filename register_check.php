<?php 
    include_once 'config.php';
    session_start();
    header('Content-Type: application/json; charset=utf-8');

    $fullname = $_POST['fullname'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $password = !empty($_POST['password']) ? md5($_POST['password']) : '';

    $db = new Database();

    // 1. Bo‘sh maydon tekshirish
    if (empty($fullname) || empty($phone) || empty($password)) {
        echo json_encode(['error' => 1, 'message' => 'Iltimos barcha maydonlarni kiriting!']);
        exit;
    }

    $phoneCheck = $db->get_data_by_table('users', ['phone' => $phone]);
    if (!empty($phoneCheck)) {
        echo json_encode(['error' => 1, 'message' => 'Bu telefon raqam allaqachon ro‘yxatdan o‘tgan']);
        exit;
    }

    $insert = $db->insert('users', [
        'fullname' => $fullname,
        'phone'    => $phone,
        'password' => $password
    ]);

    if ($insert != 0) {
        $user = $db->get_data_by_table('users', ['id' => $insert]);

        if ($user && isset($user['id'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['fullname'] = $fullname;
            $_SESSION['phone'] = $phone;
        }
        echo json_encode(['error' => 0, 'message' => 'Muvaffaqiyatli ro‘yxatdan o‘tdingiz!']);
    } else {
        echo json_encode(['error' => 1, 'message' => 'Xatolik yuz berdi, qaytadan urinib ko‘ring!']);
    }
?>
