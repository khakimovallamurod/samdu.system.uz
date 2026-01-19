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
        $sql = "SELECT s.id, f.name AS fakultet_name, y.name AS yonalish_name, y.kirish_yili, s.semestr, s.create_at FROM semestrlar s
        LEFT JOIN fakultetlar f ON f.id = s.fakultet_id
        LEFT JOIN yonalishlar y ON y.id = s.yonalish_id;";
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
            o.semestr_id,
            o.fan_code,
            o.fan_name,
            s.semestr,
            k.name        AS kafedra_name,

            dst.id        AS dars_tur_id,
            dst.name      AS dars_tur_name,

            SUM(o.dars_soat) AS jami_soat

        FROM oquv_rejalar o

        JOIN dars_soat_turlar dst
            ON dst.id = o.dars_tur_id

        JOIN semestrlar s
            ON s.id = o.semestr_id

        JOIN kafedralar k
            ON k.fakultet_id = s.fakultet_id

        GROUP BY
            o.semestr_id,
            o.fan_code,
            o.fan_name,
            k.name,
            dst.id,
            dst.name

        ORDER BY
            o.fan_code,
            dst.id;
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