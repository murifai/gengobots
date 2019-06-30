<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - reset()
* - insert()
* - getdata()
* - getdata_frompadanan()
* - getdata_frombunpou()
* - getdata_frombunpou4()
* - getdata_frompadanan4()
* - getdata_frombunpou5()
* - getdata_frompadanan5()
* - getindexbpn5()
* - getindexpdn5()
* - getindexbpn4()
* - getindexpdn4()
* - getindexbp()
* - getindexpd()
* - getnosoal()
* - getnosoal4()
* - gettotal()
* - getAllData()
* - getdistance()
* - getevent()
* - update()
* - updatescore()
* Classes list:
* - Dbs extends CI_Model
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Dbs extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    function reset($target, $data)
    {
        $this->db->get($target);
        $db = $this->db->update($target, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    //insert data ke tabel
    function insert($data, $to)
    {
        $insert = $this->db->insert($to, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    //mengambil berdasarkan data userid
    function getdata($where, $from)
    {
        $this->db->where($where);
        $db = $this->db->get($from);
        return $db;
    }

    //Fungsi pencarian
    function getdata_frompadanan($keyword)
    {
        $sql = "SELECT * FROM `bunpoudb` where `padanan` LIKE '%$keyword%'";
        return $this->db->query($sql);
    }
    function getdata_frombunpou($keyword)
    {
        $sql = "SELECT * FROM `bunpoudb` where `bunpou` LIKE '%$keyword%'";
        return $this->db->query($sql);
    }
    function getdata_frombunpou4($keyword)
    {
        $sql = "SELECT * FROM `bunpoudb` where `level`= 'n4' AND `bunpou` LIKE '%$keyword%'";
        return $this->db->query($sql);
    }
    function getdata_frompadanan4($keyword)
    {
        $sql = "SELECT * FROM `bunpoudb` where `level`= 'n4' AND `padanan` LIKE '%$keyword%'";
        return $this->db->query($sql);
    }
    function getdata_frombunpou5($keyword)
    {
        $sql = "SELECT * FROM `bunpoudb` where `level`= 'n5' AND `bunpou` LIKE '%$keyword%'";
        return $this->db->query($sql);
    }
    function getdata_frompadanan5($keyword)
    {
        $sql = "SELECT * FROM `bunpoudb` where `level`= 'n5' AND `padanan` LIKE '%$keyword%'";
        return $this->db->query($sql);
    }
    //udahan fungsi pencarian

    //fungsi indeks
    function getindexbpn5()
    {
        $sql = "SELECT GROUP_CONCAT(`bunpou`) as `bunpou` FROM `bunpoudb` WHERE `level` = 'n5'";
        return $this->db->query($sql);
    }
    function getindexpdn5()
    {
        $sql = "SELECT GROUP_CONCAT(`padanan`) as `padanan` FROM `bunpoudb` WHERE `level` = 'n5'";
        return $this->db->query($sql);
    }
    function getindexbpn4()
    {
        $sql = "SELECT GROUP_CONCAT(`bunpou`) as `bunpou` FROM `bunpoudb` WHERE `level` = 'n4'";
        return $this->db->query($sql);
    }
    function getindexpdn4()
    {
        $sql = "SELECT GROUP_CONCAT(`padanan`) as `padanan` FROM `bunpoudb` WHERE `level` = 'n4'";
        return $this->db->query($sql);
    }
    function getindexbp()
    {
        $sql = "SELECT GROUP_CONCAT(`bunpou`) as `bunpou` FROM `bunpoudb`";
        return $this->db->query($sql);
    }
    function getindexpd()
    {
        $sql = "SELECT GROUP_CONCAT(`padanan`) as `padanan` FROM `bunpoudb`";
        return $this->db->query($sql);
    }
    //udahan fungsi indeks


    //   fungsi quiz
    function getnosoal($idsoal)
    {
        $sql = "SELECT * FROM `soaln5` WHERE `nosoal`=$idsoal";
        return $this->db->query($sql);
    }
    function getnosoal4($idsoal)
    {
        $sql = "SELECT * FROM `soaln4` WHERE `nosoal`=$idsoal";
        return $this->db->query($sql);
    }
    function gettotal($userId)
    {
        $sql = "SELECT score1,score2,score3,score4,score5,score6,score7,score8,score9,score10, (score1+score2+score3+score4+score5+score6+score7+score8+score9+score10) as total FROM quiz";
        return $this->db->query($sql);
    }
    //udahan fungsi quiz


    function getAllData($table)
    {
        return $this->db->get($table);
    }
    //fungsi untuk mengambil lokasi terdekat berdasarkan longitude latitude di parameter
    function getdistance($kilo, $lat, $lng, $userid, $session1, $session2)
    {
        $this->db->select("*, ( 6371 * acos( cos( radians($lat) ) * cos( radians( cur_lat ) ) * cos( radians( cur_long ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( cur_lat ) ) ) ) AS distance");
        $this->db->having('distance <= ' . $kilo);
        $this->db->order_by('distance');
        $this->db->limit(20, 0);
        $this->db->where('userid !=', $userid);
        $this->db->where('flag !=', $session1);
        $this->db->where('flag !=', $session2);
        $this->db->where('cur_order', null);
        $this->db->where('cur_help', null);
        $db = $this->db->get('donatur');
        return $db;
    }
    function getevent($kilo, $lat, $lng)
    {
        $this->db->select("*, ( 6371 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance");
        $this->db->having('distance <= ' . $kilo);
        $this->db->order_by('distance');
        $this->db->limit(20, 0);
        $db = $this->db->get('event');
        return $db;
    }
    //fungsi untuk update field berdasarkan userid
    function update($where, $data, $to)
    {
        $this->db->where($where);
        $db = $this->db->update($to, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function updatescore($data, $to)
    {
        $db = $this->db->update($to, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function leaderboard(){
    	$sql = "SELECT * FROM `quiz` ORDER BY `quiz`.`totalscore` DESC LIMIT 10";
    	return $this->db->query($sql);
    }
}