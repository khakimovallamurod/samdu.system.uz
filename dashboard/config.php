<?php

class Database{
    private $host = 'localhost';
    private $db_name = 'samdu_systemdb';
    private $username = 'root';
    private $password = '';
    private $link;
    function __construct() {
        $this->link = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        if (!$this->link) {
            exit("Bazaga ulanmadi!");
        }
    }
    public function query($query) {
        return mysqli_query($this->link, $query);
    }
    public function get_data_by_table($table, $arr, $con = 'no'){
        $sql = "SELECT * FROM ".$table. " WHERE ";
        $t = '';
        $i=0;
        $n = count($arr);
        foreach($arr as $key=>$val){
            $i++;
            if($i==$n){
                $t .= "$key = '$val'";
            }else{
                $t .= "$key = '$val' AND ";
            }
        }
        $sql .= $t;
        if ($con != 'no'){
            $sql .= $con;
        }
        $fetch = mysqli_fetch_assoc($this->query($sql));
        return $fetch;
    }
    public function get_data_by_table_all($table, $con = 'no'){
        $sql = "SELECT * FROM ".$table;
        if ($con != 'no'){
            $sql .= " ".$con;
        }
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function insert($table, $arr){
        $sql = "INSERT INTO ".$table. " ";
        $t1 = '';
        $t2 = '';
        $i = 0;
        $n = count($arr);
        foreach($arr as $key=>$val){
            $val = mysqli_real_escape_string($this->link, $val);
            $i++;
            if($i==$n){
                $t1 .= $key;
                $t2 .= "'".$val."'";
            }else{
                $t1 .= $key.', ';
                $t2 .= "'".$val."', ";
            }
        }
        $sql .= "($t1) VALUES ($t2);";
        $result = $this->query($sql);

        if ($result) {
            return mysqli_insert_id($this->link);
        } else {
            return 0;
        }
    }
    public function update($table, $arr, $con = 'no'){
        $sql = "UPDATE ".$table. " SET ";
        $t = '';
        $i=0;
        $n = count($arr);
        foreach($arr as $key=>$val){
            $val = addslashes($val);

            $i++;
            if($i==$n){
                $t .= "$key = '$val'";
            }else{
                $t .= "$key = '$val', ";
            }
        }
        $sql .= $t;
        if ($con != 'no'){
            $sql .= " WHERE ".$con;
        }

        return $this->query($sql);
    }

    public function delete($table, $con = 'no'){
        $sql = "DELETE FROM ".$table;
        if ($con != 'no'){
            $sql .= " WHERE ".$con;
        }
        return $this -> query($sql);
    }
    public function get_yunalishlar_with_details(){
        $sql = "SELECT 
            y.id,
            y.name            AS yonalish_nomi,
            y.code            AS yonalish_kodi,
            y.muddati         AS talim_muddati,
            y.kirish_yili     AS kirish_yili,
            ad.name           AS akademik_daraja,
            ts.name           AS talim_shakli,
            y.kvalifikatsiya,
            f.name            AS fakultet,
            y.create_at
        FROM yonalishlar y
        LEFT JOIN akademik_darajalar ad ON y.akademik_daraja_id = ad.id
        LEFT JOIN talim_shakllar ts    ON y.talim_shakli_id = ts.id
        LEFT JOIN fakultetlar f         ON y.fakultet_id = f.id
        ORDER BY y.id DESC;
        ";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;

    }
    public function get_kafedralar(){
        $sql = "SELECT k.id, k.name, k.create_at, f.name AS fakultet_name FROM `kafedralar` k
        LEFT JOIN fakultetlar f ON k.fakultet_id = f.id
        ";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_semestrlar(){
        $sql = "SELECT 
            s.id,
            f.name AS fakultet_name,
            y.name AS yonalish_name,
            y.kirish_yili,
            s.semestr,
            s.create_at,
            COALESCE(SUM(g.soni), 0) AS jami_talabalar
        FROM semestrlar s
        LEFT JOIN fakultetlar f ON f.id = s.fakultet_id
        LEFT JOIN yonalishlar y ON y.id = s.yonalish_id
        LEFT JOIN guruhlar g ON g.yonalish_id = y.id
        GROUP BY
            s.id,
            f.name,
            y.name,
            y.kirish_yili,
            s.semestr,
            s.create_at;
        ;";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function insert_semestrlar(){
        $sql = "
            INSERT IGNORE INTO semestrlar (fakultet_id, yonalish_id, semestr)
            SELECT
                y.fakultet_id,
                y.id AS yonalish_id,
                n.num AS semestr
            FROM yonalishlar y
            JOIN numbers n
                ON n.num <= y.muddati * 2
            WHERE y.muddati IS NOT NULL
            AND y.muddati > 0
        ";
        $result = $this->query($sql);

        return $result;
    }
    public function get_oquv_rejalar(){
        $sql = "
                SELECT
            s.semestr,
            f.fan_code,
            f.fan_name,
            k.name AS kafedra_name,
            SUM(CASE WHEN dst.id=1      THEN o.dars_soat ELSE 0 END) AS lecture,
            SUM(CASE WHEN dst.id=2       THEN o.dars_soat ELSE 0 END) AS practical,
            SUM(CASE WHEN dst.id=3  THEN o.dars_soat ELSE 0 END) AS lab,
            SUM(CASE WHEN dst.id=4      THEN o.dars_soat ELSE 0 END) AS seminar,
            SUM(CASE WHEN dst.id=5  THEN o.dars_soat ELSE 0 END) AS mustaqilTalim,
            SUM(CASE WHEN dst.name = 'Kurs ishi' THEN o.dars_soat ELSE 0 END) AS kursIshi,
            SUM(CASE WHEN dst.name = 'Malaka amaliyoti' THEN o.dars_soat ELSE 0 END) AS malakaAmaliyot
        FROM oquv_rejalar o
        JOIN fanlar f ON f.id = o.fan_id
        JOIN dars_soat_turlar dst ON dst.id = o.dars_tur_id
        JOIN semestrlar s ON s.id = f.semestr_id
        JOIN kafedralar k ON k.id = f.kafedra_id
        GROUP BY
            f.semestr_id,
            s.semestr,
            f.fan_code,
            f.fan_name,
            k.name

        ORDER BY
            s.semestr,
            f.fan_code;
        ";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_guruhlar(){
        $sql = "SELECT g.id, g.guruh_nomer, g.soni, g.create_at, y.name AS yonalish_name FROM guruhlar g
        JOIN yonalishlar y ON y.id = g.yonalish_id ";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_oqtuvchi_total_hours($teacher_id){
        $sql = "SELECT 
            t.id,
            t.soat,
            t.type,
            o.fio,
            o.lavozim,
            f.fan_name,
            f.fan_code,
            dst.name AS dars_turi,
            r.dars_soat
        FROM taqsimotlar t
        JOIN oqituvchilar o ON o.id = t.teacher_id
        JOIN oquv_rejalar r ON r.id = t.oquv_reja_id
        JOIN dars_soat_turlar dst ON dst.id = r.dars_tur_id
        JOIN fanlar f ON f.id = r.fan_id
        WHERE t.teacher_id = $teacher_id
        ";
        $result = $this->query($sql);
        $details = [];
        $totalHours = 0;
        $fio = '';
        $lavozim = '';

        while ($row = mysqli_fetch_assoc($result)) {
            $details[] = $row;
            $totalHours += (float)$row['soat'];
            $fio = $row['fio'];
            $lavozim = $row['lavozim'];
        }

        return [
            'fio' => $fio,
            'lavozim' => $lavozim,
            'total_hours' => $totalHours,
            'details' => $details
        ];
    }
    public function get_oquv_haftaliklar(){
        $sql = "SELECT oh.*, y.name as yonalish_nomi, y.code as yonalish_code, y.muddati 
            FROM oquv_haftaliklar oh 
            JOIN yonalishlar y ON oh.yonalish_id = y.id 
            ORDER BY oh.create_at DESC";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_talaba_soni($semestr_id){
        $sql = "SELECT COALESCE(SUM(g.soni),0) AS talabalar_soni
        FROM guruhlar g
        WHERE g.yonalish_id = (
            SELECT yonalish_id FROM semestrlar WHERE id = $semestr_id
        );";
        $result = $this->query($sql);
        $data = mysqli_fetch_assoc($result);
        return $data;
    }
    public function get_taqsimot_by_teacher($oquvreja_id, $type){
        $sql = "SELECT t.id, t.soat as soat_soni, t.type, o.fio, o.lavozim, t.oquv_reja_id
        FROM `taqsimotlar` t 
        JOIN oqituvchilar o ON o.id = t.teacher_id
        WHERE t.oquv_reja_id=$oquvreja_id AND t.type='$type';";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function get_oquv_yuklamalar($filters = []){
        $where = [];
        if (!empty($filters['kafedra_id'])) {
            $where[] = "k.id = " . (int)$filters['kafedra_id'];
        }
        if (!empty($filters['semestr'])) {
            $where[] = "s.semestr = " . (int)$filters['semestr'];
        }
        $whereSQL = '';
        if (!empty($where)) {
            $whereSQL = 'WHERE ' . implode(' AND ', $where);
        }        
        $sql = "
            WITH fan_reja AS (
                SELECT
                    f.id AS fan_id,
                    f.fan_name,
                    f.fan_code,
                    f.semestr_id,
                    f.kafedra_id,

                    SUM(CASE WHEN r.dars_tur_id = 1 THEN r.dars_soat ELSE 0 END) AS maruza_soat,
                    SUM(CASE WHEN r.dars_tur_id = 2 THEN r.dars_soat ELSE 0 END) AS amaliy_soat,
                    SUM(CASE WHEN r.dars_tur_id = 3 THEN r.dars_soat ELSE 0 END) AS laboratoriya_soat,
                    SUM(CASE WHEN r.dars_tur_id = 4 THEN r.dars_soat ELSE 0 END) AS seminar_soat

                FROM fanlar f
                JOIN oquv_rejalar r ON r.fan_id = f.id
                GROUP BY f.id, f.fan_name, f.fan_code, f.semestr_id, f.kafedra_id
            ),
            guruh_agg AS (
                SELECT
                    yonalish_id,
                    GROUP_CONCAT(guruh_nomer SEPARATOR '-') AS guruh_raqami,
                    COUNT(id) AS guruhlar_soni,
                    SUM(soni) AS talabalar_soni
                FROM guruhlar
                GROUP BY yonalish_id
            )

            SELECT
                fr.fan_name,
                y.name AS talim_yonalishi,
                y.code AS yonalish_code,
                k.name AS kafedra_nomi,
                tsh.name AS oquv_shakli,
                s.semestr,
                FLOOR((s.semestr + 1)/2) AS kurs,

                ga.guruh_raqami,
                ga.guruhlar_soni,
                ga.talabalar_soni,

                y.patok_soni,
                y.kattaguruh_soni,
                y.kichikguruh_soni,

                fr.maruza_soat,
                fr.amaliy_soat,
                fr.laboratoriya_soat,
                fr.seminar_soat,
                fr.maruza_soat * y.patok_soni AS amalda_maruz,
                fr.amaliy_soat * y.kattaguruh_soni AS amalda_amaliy,
                fr.laboratoriya_soat * y.kichikguruh_soni AS amalda_lab,
                fr.seminar_soat * y.kattaguruh_soni AS amalda_seminar,
                fr.maruza_soat * y.patok_soni
                + fr.amaliy_soat * y.kattaguruh_soni
                + fr.laboratoriya_soat * y.kichikguruh_soni
                + fr.seminar_soat * y.kattaguruh_soni
                AS jami_soat

            FROM fan_reja fr
            JOIN semestrlar s ON s.id = fr.semestr_id
            JOIN yonalishlar y ON y.id = s.yonalish_id
            JOIN talim_shakllar tsh ON tsh.id = y.talim_shakli_id
            JOIN kafedralar k ON k.id = fr.kafedra_id
            JOIN guruh_agg ga ON ga.yonalish_id = y.id
            $whereSQL
            ORDER BY s.semestr, fr.fan_name;
        ";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_qoshimcha_oquv_yuklamalar($filters = []){
        $where = [];
        if (!empty($filters['kafedra_id'])) {
            $where[] = "k.id = " . (int)$filters['kafedra_id'];
        }
        if (!empty($filters['semestr'])) {
            $where[] = "s.semestr = " . (int)$filters['semestr'];
        }
        $whereSQL = '';
        if (!empty($where)) {
            $whereSQL = 'WHERE ' . implode(' AND ', $where);
        }        
        $sql = "
            WITH guruh_agg AS (
                SELECT
                    yonalish_id,
                    GROUP_CONCAT(guruh_nomer SEPARATOR '-') AS guruh_raqami,
                    COUNT(id) AS guruhlar_soni,
                    SUM(soni) AS talabalar_soni
                FROM guruhlar
                GROUP BY yonalish_id
            )

            SELECT
                qdt.name AS fan_nomi,  
                y.name AS talim_yonalishi,
                y.code AS yonalish_code,
                k.name AS kafedra_nomi,
                tsh.name AS oquv_shakli,
                s.semestr,
                FLOOR((s.semestr + 1)/2) AS kurs,

                ga.guruh_raqami,
                ga.guruhlar_soni,
                ga.talabalar_soni,
                ga.talabalar_soni * 0.4 AS oraliq_nazorat,
                ga.talabalar_soni * 0.3 AS yakuniy_nazorat,
                y.patok_soni,
                y.kattaguruh_soni,
                y.kichikguruh_soni,
                SUM(CASE WHEN qdt.id = 1 THEN q.dars_soati ELSE 0 END) AS kurs_ishi,
                SUM(CASE WHEN qdt.id = 2 THEN q.dars_soati ELSE 0 END) AS kurs_loyiha,
                SUM(CASE WHEN qdt.id = 3 THEN q.dars_soati ELSE 0 END) AS oquv_ped_amaliyot,
                SUM(CASE WHEN qdt.id = 4 THEN q.dars_soati ELSE 0 END) AS uzluksiz_malakaviy,
                SUM(CASE WHEN qdt.id = 5 THEN q.dars_soati ELSE 0 END) AS dala_amaliyoti_otm,
                SUM(CASE WHEN qdt.id = 6 THEN q.dars_soati ELSE 0 END) AS dala_amaliyoti_tashqarida,
                SUM(CASE WHEN qdt.id = 7 THEN q.dars_soati ELSE 0 END) AS ishlab_chiqarish,
                SUM(CASE WHEN qdt.id = 8 THEN q.dars_soati ELSE 0 END) AS bmi_rahbarligi,
                SUM(CASE WHEN qdt.id = 9 THEN q.dars_soati ELSE 0 END) AS ilmiy_tadqiqot_ishi,
                SUM(CASE WHEN qdt.id = 10 THEN q.dars_soati ELSE 0 END) AS ilmiy_pedagogik_ishi,
                SUM(CASE WHEN qdt.id = 11 THEN q.dars_soati ELSE 0 END) AS ilmiy_stajirovka,
                SUM(CASE WHEN qdt.id = 12 THEN q.dars_soati ELSE 0 END) AS tayanch_doktorantura,
                SUM(CASE WHEN qdt.id = 13 THEN q.dars_soati ELSE 0 END) AS katta_ilmiy_tadqiqotchi,
                SUM(CASE WHEN qdt.id = 14 THEN q.dars_soati ELSE 0 END) AS stajyor_tadqiqotchi,
                SUM(CASE WHEN qdt.id = 15 THEN q.dars_soati ELSE 0 END) AS ochiq_dars,
                SUM(CASE WHEN qdt.id = 16 THEN q.dars_soati ELSE 0 END) AS yadak,
                SUM(CASE WHEN qdt.id = 17 THEN q.dars_soati ELSE 0 END) AS boshqa_soatlar,

                SUM(q.dars_soati) + ga.talabalar_soni * 0.4 + ga.talabalar_soni * 0.3 AS jami_soat

            FROM qoshimcha_oquv_rejalar q
            JOIN qoshimcha_fanlar qf ON qf.id = q.qoshimcha_fanid
            JOIN qoshimcha_dars_turlar qdt ON qdt.id = qf.qoshimcha_dars_id

            JOIN semestrlar s ON s.id = qf.semestr_id
            JOIN yonalishlar y ON y.id = s.yonalish_id
            JOIN talim_shakllar tsh ON tsh.id = y.talim_shakli_id
            JOIN kafedralar k ON k.id = q.kafedra_id
            JOIN guruh_agg ga ON ga.yonalish_id = y.id
            $whereSQL
            GROUP BY qf.id, q.kafedra_id

            ORDER BY s.semestr, qdt.name;
        ";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_oqtuvchilar(){
        $sql = "SELECT o.*, f.name AS fakultet_name, k.name AS kafedra_name, iu.name AS ilmiy_unvon_name, id.name AS ilmiy_daraja_name
        FROM `oqituvchilar` o
        JOIN fakultetlar f ON f.id=o.fakultet_id
        JOIN kafedralar k ON k.id=o.kafedra_id
        JOIN ilmiy_unvonlar iu ON iu.id=o.ilmiy_unvon_id
        JOIN ilmiy_darajalar id ON id.id=o.ilmiy_daraja_id;";
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_oquv_taqsimotlar($filters=[]){
        $where = [];
        if (!empty($filters['kafedra_id'])) {
            $where[] = "k.id = " . (int)$filters['kafedra_id'];
        }
        if (!empty($filters['semestr'])) {
            $where[] = "s.semestr = " . (int)$filters['semestr'];
        }
        $whereSQL = '';
        if (!empty($where)) {
            $whereSQL = 'WHERE ' . implode(' AND ', $where);
        }        
        $sql = "WITH fan_reja AS (
            SELECT
                f.id AS fan_id,
                f.fan_name,
                f.fan_code,
                f.semestr_id,
                f.kafedra_id,

                MAX(CASE WHEN r.dars_tur_id = 1 THEN r.id END) AS maruza_reja_id,
                MAX(CASE WHEN r.dars_tur_id = 2 THEN r.id END) AS amaliy_reja_id,
                MAX(CASE WHEN r.dars_tur_id = 3 THEN r.id END) AS laboratoriya_reja_id,
                MAX(CASE WHEN r.dars_tur_id = 4 THEN r.id END) AS seminar_reja_id,

                SUM(CASE WHEN r.dars_tur_id = 1 THEN r.dars_soat ELSE 0 END) AS maruza_soat,
                SUM(CASE WHEN r.dars_tur_id = 2 THEN r.dars_soat ELSE 0 END) AS amaliy_soat,
                SUM(CASE WHEN r.dars_tur_id = 3 THEN r.dars_soat ELSE 0 END) AS laboratoriya_soat,
                SUM(CASE WHEN r.dars_tur_id = 4 THEN r.dars_soat ELSE 0 END) AS seminar_soat

            FROM fanlar f
            JOIN oquv_rejalar r ON r.fan_id = f.id
            GROUP BY f.id, f.fan_name, f.fan_code, f.semestr_id, f.kafedra_id
        ),
        guruh_agg AS (
            SELECT
                g.yonalish_id,
                GROUP_CONCAT(DISTINCT g.guruh_nomer ORDER BY g.guruh_nomer SEPARATOR '-') AS guruh_raqami,
                COUNT(DISTINCT g.id)  AS guruhlar_soni,
                SUM(g.soni)           AS talabalar_soni
            FROM guruhlar g
            GROUP BY g.yonalish_id
        )
        SELECT
            fr.maruza_reja_id,
            fr.amaliy_reja_id,
            fr.laboratoriya_reja_id,
            fr.seminar_reja_id,
            fr.fan_name AS fan_nomi,
            y.name AS talim_yonalishi,
            y.code AS yonalish_code,
            k.name AS kafedra_nomi,
            tsh.name AS oquv_shakli,
            s.semestr,
            FLOOR((s.semestr + 1) / 2) AS kurs,
            ga.guruh_raqami,
            ga.guruhlar_soni,
            ga.talabalar_soni,
            y.patok_soni,
            y.kattaguruh_soni,
            y.kichikguruh_soni,
            fr.maruza_soat AS reja_maruz,
            fr.amaliy_soat AS reja_amaliy,
            fr.laboratoriya_soat AS reja_laboratoriya,
            fr.seminar_soat AS reja_seminar,
            fr.maruza_soat * y.patok_soni AS amalda_maruz,
            fr.amaliy_soat * y.kattaguruh_soni AS amalda_amaliy,
            fr.laboratoriya_soat * y.kichikguruh_soni AS amalda_laboratoriya,
            fr.seminar_soat * y.kattaguruh_soni AS amalda_seminar,
            fr.maruza_soat * y.patok_soni
            + fr.amaliy_soat * y.kattaguruh_soni
            + fr.laboratoriya_soat * y.kichikguruh_soni
            + fr.seminar_soat * y.kattaguruh_soni
            AS jami_soat
        FROM fan_reja fr
        JOIN semestrlar s ON s.id = fr.semestr_id
        JOIN yonalishlar y ON y.id = s.yonalish_id
        JOIN kafedralar k ON k.id = fr.kafedra_id
        JOIN talim_shakllar tsh ON tsh.id = y.talim_shakli_id
        JOIN guruh_agg ga ON ga.yonalish_id = y.id
        $whereSQL
        ORDER BY s.semestr, fr.fan_name;
        ";

        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_qoshimcha_oquv_taqsimotlar($filters = []){
        $where = [];
        if (!empty($filters['kafedra_id'])) {
            $where[] = "k.id = " . (int)$filters['kafedra_id'];
        }
        if (!empty($filters['semestr'])) {
            $where[] = "s.semestr = " . (int)$filters['semestr'];
        }
        if (!empty($filters['qoshimcha_oquv_reja_id'])){
            $where[] = "q.id = " . (int)$filters['qoshimcha_oquv_reja_id'];
        }
        $whereSQL = '';
        if (!empty($where)) {
            $whereSQL = 'WHERE ' . implode(' AND ', $where);
        } 
        $sql = "
            WITH guruh_agg AS (
                SELECT
                    yonalish_id,
                    GROUP_CONCAT(DISTINCT guruh_nomer ORDER BY guruh_nomer SEPARATOR '-') AS guruh_raqami,
                    COUNT(DISTINCT id) AS guruhlar_soni,
                    SUM(soni) AS talabalar_soni
                FROM guruhlar
                GROUP BY yonalish_id
            )
            SELECT
                q.id AS qoshimcha_reja_id, 
                qf.fan_name AS fan_nomi,
                y.name AS talim_yonalishi,
                y.code AS yonalish_code,
                k.name AS kafedra_nomi,
                tsh.name AS oquv_shakli,
                s.semestr,
                FLOOR((s.semestr + 1)/2) AS kurs,

                ga.guruh_raqami,
                ga.guruhlar_soni,
                ga.talabalar_soni,
                ga.talabalar_soni * 0.4 AS oraliq_nazorat,
                ga.talabalar_soni * 0.3 AS yakuniy_nazorat,
                y.patok_soni,
                y.kattaguruh_soni,
                y.kichikguruh_soni,
                CASE WHEN qf.qoshimcha_dars_id = 1  THEN q.dars_soati ELSE 0 END AS kurs_ishi,
                CASE WHEN qf.qoshimcha_dars_id = 2  THEN q.dars_soati ELSE 0 END AS kurs_loyiha,
                CASE WHEN qf.qoshimcha_dars_id = 3  THEN q.dars_soati ELSE 0 END AS oquv_ped_amaliyot,
                CASE WHEN qf.qoshimcha_dars_id = 4  THEN q.dars_soati ELSE 0 END AS uzluksiz_malakaviy,
                CASE WHEN qf.qoshimcha_dars_id = 5  THEN q.dars_soati ELSE 0 END AS dala_amaliyoti_otm,
                CASE WHEN qf.qoshimcha_dars_id = 6  THEN q.dars_soati ELSE 0 END AS dala_amaliyoti_tashqarida,
                CASE WHEN qf.qoshimcha_dars_id = 7  THEN q.dars_soati ELSE 0 END AS ishlab_chiqarish,
                CASE WHEN qf.qoshimcha_dars_id = 8  THEN q.dars_soati ELSE 0 END AS bmi_rahbarligi,
                CASE WHEN qf.qoshimcha_dars_id = 9  THEN q.dars_soati ELSE 0 END AS ilmiy_tadqiqot_ishi,
                CASE WHEN qf.qoshimcha_dars_id = 10 THEN q.dars_soati ELSE 0 END AS ilmiy_pedagogik_ishi,
                CASE WHEN qf.qoshimcha_dars_id = 11 THEN q.dars_soati ELSE 0 END AS ilmiy_stajirovka,
                CASE WHEN qf.qoshimcha_dars_id = 12 THEN q.dars_soati ELSE 0 END AS tayanch_doktorantura,
                CASE WHEN qf.qoshimcha_dars_id = 13 THEN q.dars_soati ELSE 0 END AS katta_ilmiy_tadqiqotchi,
                CASE WHEN qf.qoshimcha_dars_id = 14 THEN q.dars_soati ELSE 0 END AS stajyor_tadqiqotchi,
                CASE WHEN qf.qoshimcha_dars_id = 15 THEN q.dars_soati ELSE 0 END AS ochiq_dars,
                CASE WHEN qf.qoshimcha_dars_id = 16 THEN q.dars_soati ELSE 0 END AS yadak,
                CASE WHEN qf.qoshimcha_dars_id = 17 THEN q.dars_soati ELSE 0 END AS boshqa_soatlar,

                q.dars_soati + ga.talabalar_soni * 0.4 + ga.talabalar_soni * 0.3 AS jami_soat
            FROM qoshimcha_oquv_rejalar q
            JOIN qoshimcha_fanlar qf ON qf.id = q.qoshimcha_fanid
            JOIN semestrlar s ON s.id = qf.semestr_id
            JOIN yonalishlar y ON y.id = s.yonalish_id
            JOIN talim_shakllar tsh ON tsh.id = y.talim_shakli_id
            JOIN kafedralar k ON k.id = q.kafedra_id
            JOIN guruh_agg ga ON ga.yonalish_id = y.id
            $whereSQL
            ORDER BY s.semestr, q.id;
        ";
        
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;

    }

   
}

?>