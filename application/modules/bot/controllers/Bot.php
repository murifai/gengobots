<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ('line_class.php');
require_once ('class/MessageBuilder.php');
require_once ('class/Register.php');
class Bot extends MY_Controller {
    /*
        WELCOME TO KOSTLAB X CODEIGNITER FRAMEWORK
        Framework inidibuat untuk memudahkan development chatbot LINE
        Coders : @kostlab @fataelislami
    
    
        Dokumentasi Fungsi
           
                                   function update($where,$data,$to)
                                   function getdata($userid,$from)
                                   function insert($data,$to)
    
        Struktur Model
            DBS
        Struktur Controller
            Welcome
    
    
      Brief Docs
            Set Flag
    
      Check Flag
      if($db[0]->flag=='blablabla')
    
      Message Type
      if($message['type']=='location')
      if($message['type']=='text')
      if($message['type']=='image')
      if($message['type']=='audio')
      if($message['type']=='video')
    
      Event Type
      $event['type'] == 'follow'
      $event['type'] == 'unfollow'
      $event['type'] == 'join'
      $event['type'] == 'leave'
    
    
    
    
    */
    public function __construct() {
        parent::__construct();
        //Codeigniter : Write Less Do More
        $this->load->model(array('Dbs'));
        date_default_timezone_set("Asia/Bangkok");
    }
    public function index() {
        //Konfigurasi Chatbot
        $channelAccessToken = 'SITjpufKrlt83PTwi2Gmidv6NMYBO0/E5dI+AbNh7y2PM8xMy7/6DaR9SeE6RBMA3oS99PEYw+Wyasyon+j4mQLKQyGLEUOZAsPq4U1i+zM+Sx+UeEbr4mXBn1kjtzDu8W1SDLLzdax3EiJSj7oxIgdB04t89/1O/w1cDnyilFU=';
        $channelSecret = 'e2b08cf9d3962a4d4d05263de3521301'; //sesuaikan
        //Konfigurasi Chatbot END
        $client = new LINEBotTiny($channelAccessToken, $channelSecret);
        $send = new MessageBuilder();
        $reg = new Register();
        $userId = $client->parseEvents() [0]['source']['userId'];
        $groupId = $client->parseEvents() [0]['source']['groupId'];
        $replyToken = $client->parseEvents() [0]['replyToken'];
        $timestamp = $client->parseEvents() [0]['timestamp'];
        $message = $client->parseEvents() [0]['message'];
        $messageid = $client->parseEvents() [0]['message']['id'];
        $latitude = $client->parseEvents() [0]['message']['latitude'];
        $longitude = $client->parseEvents() [0]['message']['longitude'];
        $address = $client->parseEvents() [0]['message']['address'];
        $addresstitle = $client->parseEvents() [0]['message']['title'];
        $postback = $client->parseEvents() [0]['postback'];
        $profil = $client->profil($userId);
        $nama = $profil->displayName;
        $pesan_datang = $message['text'];
        $upPesan = strtoupper($pesan_datang);
        $pecahnama = explode(" ", $profil->displayName);
        $namapanggilan = $pecahnama[0];
        $event = $client->parseEvents() [0];
        $db = $this->Dbs->getdata(array('userid' => $userId), 'user')->row();
        $db2 = $this->Dbs->getdata(array('userid' => $userId), 'quiz')->row();
        function getRandom($length = 3) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0;$i < $length;$i++) {
                $randomString.= $characters[rand(0, $charactersLength - 1) ];
            }
            return $randomString;
        }
        // EVENT ADD
        if ($event['type'] == 'follow') {
            $data = array('userid' => $userId, 'name' => $nama,);
            $sql = $this->Dbs->insert($data, 'user');
            $data2 = array('userid' => $userId, 'nama' => $nama,);
            $sql2 = $this->Dbs->insert($data2, 'quiz');
            if ($sql) {
                $messages = [];
                $msg1 = $send->text("Selamat Menggunakan Chatbot");
                $imgmap = $send->imagemapbahasa("https://gengobot.com/bot/imagemap/ID/BAHASA", "PILIH BAHASA", "/ID", "/EN", "/JP");
                array_push($messages, $msg1, $imgmap);
                $output = $send->reply($replyToken, $messages);
            } else {
                $messages = [];
                $msg1 = $send->text("Selamat Menggunakan Chatbot");
                $imgmap = $send->imagemapbahasa("https://gengobot.com/bot/imagemap/ID/BAHASA", "PILIH BAHASA", "/ID", "/EN", "/JP");
                array_push($messages, $msg1, $imgmap);
                $output = $send->reply($replyToken, $messages);
            }
        } //END EVENT ADD
        // CCOBACOBA
        if ($upPesan == '/KONTAK') {
            $messages = [];
            $msg1 = $send->image("https://gengobot.com/bot/imagemap/CONTACT.jpg");
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == '/QR') {
            $messages = [];
            $msg1 = $send->quickreply();
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == '/LB') {
        	require 'flex.php';
            $flex = new flex();
            $content1 = $flex->leaderboard();
            $messages = [];
            $msg1 = $send->flex("Pilih Level Bunpou", $content1);
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == 'DAFTARLATIHAN') {
            $data = array('userid' => $userId, 'nama' => $nama,);
            $sql = $this->Dbs->insert($data, 'quiz');
            if ($sql) {
                $messages = [];
                $msg1 = $send->text("Selamat anda telah terdaftar untuk mengikuti latihan");
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else {
                $messages = [];
                $msg1 = $send->text("anda telah terdaftar");
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            }
        }
        // ENCOBA
        // MODE PILIH BAHASA
        if ($upPesan == '/ID') {
            $where = array('userid' => $userId);
            $data = array('bahasa' => 'indonesia');
            $sql = $this->Dbs->update($where, $data, 'user');
            if ($sql) {
                $messages = [];
                $msg1 = $send->text("Sedang menggunakan Bahasa Indonesia");
                $ballons = $send->imagemapmenu("https://gengobot.com/bot/imagemap/ID/MENU", "PILIH MENU", "/ID>JP", "/JP>ID", "/LATIHAN", "/INDEX", "/MORE");
                array_push($messages, $msg1, $ballons);
                $output = $send->reply($replyToken, $messages);
            } else {
                $messages = [];
                $msg1 = $send->text("Anda masih dalam mode Bahasa Indonesia");
                $ballons = $send->imagemapmenu("https://gengobot.com/bot/imagemap/ID/MENU", "PILIH MENU", "/ID>JP", "/JP>ID", "/LATIHAN", "/INDEX", "/MORE");
                array_push($messages, $msg1, $ballons);
                $output = $send->reply($replyToken, $messages);
            }
        } else if ($upPesan == '/JP') {
            $messages = [];
            $msg1 = $send->text("Mohon maaf, mode bahasa Jepang sedang dalam tahap pengembangan");
            $ballons = $send->imagemapmenu("https://gengobot.com/bot/imagemap/ID/MENU", "PILIH MENU", "/ID>JP", "/JP>ID", "/LATIHAN", "/INDEX", "/MORE");
            array_push($messages, $msg1, $ballons);
            $output = $send->reply($replyToken, $messages);
            //  $where=array('userid'=>$userId);
            //  $data=array('bahasa'=>'jepang');
            //  $sql=$this->Dbs->update($where,$data,'user');
            //  if($sql){
            //   $messages=[];
            //   $msg1=$send->text("日本語を使っています");
            //   array_push($messages,$msg1);
            //   $output=$send->reply($replyToken,$messages);
            //   }else{
            //     $messages=[];
            //   $msg1=$send->text("また日本語を使っています");
            //   array_push($messages,$msg1);
            //   $output=$send->reply($replyToken,$messages);
            //   }
            
        } else if ($upPesan == '/EN') {
            $messages = [];
            $msg1 = $send->text("Mohon maaf, mode bahasa Inggris sedang dalam tahap pengembangan");
            $ballons = $send->imagemapmenu("https://gengobot.com/bot/imagemap/ID/MENU", "PILIH MENU", "/ID>JP", "/JP>ID", "/LATIHAN", "/INDEX", "/MORE");
            array_push($messages, $msg1, $ballons);
            $output = $send->reply($replyToken, $messages);
            //  $where=array('userid'=>$userId);
            //  $data=array('bahasa'=>'english');
            //  $sql=$this->Dbs->update($where,$data,'user');
            //  if($sql){
            //   $messages=[];
            //   $msg1=$send->text("You are in English mode now");
            //   array_push($messages,$msg1);
            //   $output=$send->reply($replyToken,$messages);
            //   }else{
            //     $messages=[];
            //   $msg1=$send->text("You are still in english mode");
            //   array_push($messages,$msg1);
            //   $output=$send->reply($replyToken,$messages);
            //   }
            
        } // END MODE PILIH BAHASA
        // index
        if ($upPesan == '/INDEXJP5') {
            $loadDb = $this->Dbs->getindexbpn5()->row();
            $get = $loadDb->bunpou;
            $fixGet = substr($get, 0, 2000);
            $fixReplace = str_replace(",", "\r\n", $fixGet);
            $messages = [];
            $msg1 = $send->text("List bunpou N5:\r\n" . $fixReplace);
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == '/INDEXID5') {
            $loadDb = $this->Dbs->getindexpdn5()->row();
            $get = $loadDb->padanan;
            $fixGet = substr($get, 0, 3000);
            $fixReplace = str_replace(",", "\r\n", $fixGet);
            $messages = [];
            $msg1 = $send->text("List bahasa Indonesia (N5):\r\n" . $fixReplace);
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == '/INDEXJP4') {
            $loadDb = $this->Dbs->getindexbpn4()->row();
            $get = $loadDb->bunpou;
            $fixGet = substr($get, 0, 3000);
            $fixReplace = str_replace(",", "\r\n", $fixGet);
            $messages = [];
            $msg1 = $send->text("List bunpou N4 :\r\n" . $fixReplace);
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == '/INDEXID4') {
            $loadDb = $this->Dbs->getindexpdn4()->row();
            $get = $loadDb->padanan;
            $fixGet = substr($get, 0, 3000);
            $fixReplace = str_replace(",", "\r\n", $fixGet);
            $messages = [];
            $msg1 = $send->text("List bahasa Indonesia (N4):\r\n" . $fixReplace);
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == '/INDEXJPALL') {
            $loadDb = $this->Dbs->getindexbp()->row();
            $get = $loadDb->bunpou;
            $fixGet = substr($get, 0, 3000);
            $fixReplace = str_replace(",", "\r\n", $fixGet);
            $messages = [];
            $msg1 = $send->text("List bunpou (Semua):\r\n" . $fixReplace);
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        if ($upPesan == '/INDEXIDALL') {
            $loadDb = $this->Dbs->getindexpd()->row();
            $get = $loadDb->padanan;
            $fixGet = substr($get, 0, 3000);
            $fixReplace = str_replace(",", "\r\n", $fixGet);
            $messages = [];
            $msg1 = $send->text("List bahasa Indonesia (Semua):\r\n" . $fixReplace);
            array_push($messages, $msg1);
            $output = $send->reply($replyToken, $messages);
        }
        // END INDEX
        // INDO
        if ($db->bahasa == 'indonesia') {
            if ($upPesan == '/MODE') {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->mode();
                $messages = [];
                $msg1 = $send->flex("Pilih Level Bunpou", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "/JP>ID") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->bunpou();
                $messages = [];
                $msg1 = $send->flex("Pilih Level Bunpou", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "/ID>JP") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->padanan();
                $messages = [];
                $msg1 = $send->flex("Pilih Level", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == '/INDOALL') {
                $where = array('userid' => $userId);
                $data = array('flag' => 'padanan');
                $sql = $this->Dbs->update($where, $data, 'user');
                if ($sql) {
                    $messages = [];
                    $msg1 = $send->text("Silahkan masukan padanan");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else {
                    $messages = [];
                    $msg1 = $send->text("Terjadi error");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            } else if ($upPesan == '/BUNPOUALL') {
                $where = array('userid' => $userId);
                $data = array('flag' => 'bunpou');
                $sql = $this->Dbs->update($where, $data, 'user');
                if ($sql) {
                    $messages = [];
                    $msg1 = $send->text("Silahkan masukan bunpou");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else {
                    $messages = [];
                    $msg1 = $send->text("Terjadi error");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            } else if ($upPesan == '/BUNPOUN5') {
                $where = array('userid' => $userId);
                $data = array('flag' => 'bunpou5');
                $sql = $this->Dbs->update($where, $data, 'user');
                if ($sql) {
                    $messages = [];
                    $msg1 = $send->text("Silahkan masukan Bunpou");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else {
                    $messages = [];
                    $msg1 = $send->text("Terjadi error");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            } else if ($upPesan == '/INDON5') {
                $where = array('userid' => $userId);
                $data = array('flag' => 'padanan5');
                $sql = $this->Dbs->update($where, $data, 'user');
                if ($sql) {
                    $messages = [];
                    $msg1 = $send->text("Silahkan masukan padanan");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else {
                    $messages = [];
                    $msg1 = $send->text("Terjadi error");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            } else if ($upPesan == '/INDON4') {
                $where = array('userid' => $userId);
                $data = array('flag' => 'padanan4');
                $sql = $this->Dbs->update($where, $data, 'user');
                if ($sql) {
                    $messages = [];
                    $msg1 = $send->text("Silahkan masukan padanan");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else {
                    $messages = [];
                    $msg1 = $send->text("Terjadi error");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            } else if ($upPesan == '/BUNPOUN4') {
                $where = array('userid' => $userId);
                $data = array('flag' => 'bunpou4');
                $sql = $this->Dbs->update($where, $data, 'user');
                if ($sql) {
                    $messages = [];
                    $msg1 = $send->text("Silahkan masukan Bunpou");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else {
                    $messages = [];
                    $msg1 = $send->text("Terjadi error");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // PADANAN
            else if ($db->flag == 'padanan') { //CONTOH MAPPING KE PADANAN
                if ($upPesan == '/BACK') { //KETIKA BOT DI INPUT KEYWORD RESET
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    if ($sql) { //jika baris di database berhasil di update
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->mode();
                        $messages = [];
                        $msg1 = $send->text("Silahkan pilih mode kembali");
                        $msg2 = $send->flex("Pilih Mode", $content1);
                        array_push($messages, $msg1, $msg2);
                        $output = $send->reply($replyToken, $messages, $ballons);
                    }
                } else {
                    $loadDb = $this->Dbs->getdata_frompadanan($upPesan); //LOAD semua table dari padanan
                    $check = $loadDb->num_rows(); //cek number baris yang dihasilkan
                    if ($check > 0) {
                        $get = $this->Dbs->getdata_frompadanan($upPesan)->row(); //di eksekusi ketika data ditemukan
                        $messages = [];
                        $msg1 = $send->text("Tata Bahasa : \r\n" . $get->bunpou);
                        $msg2 = $send->text("Struktur : \r\n" . $get->rumus);
                        $msg3 = $send->text("Contoh : \r\n" . $get->contoh);
                        $msg4 = $send->text("Arti Contoh : \r\n" . $get->articontoh);
                        $msg5 = $send->text("Keterangan : \r\n" . $get->keterangan);
                        array_push($messages, $msg1, $msg2, $msg3, $msg4, $msg5);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->backbutton();
                        $messages = [];
                        $msg1 = $send->text("Maaf keyword yang anda kirim belum ada dalam database, silahkan cek kembali lagi nanti ^^");
                        $msg2 = $send->text("Atau Kembali ke pilihan mode");
                        $msg3 = $send->flex("Kembali", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
            } //END PADANAN
            // BUNPOU
            else if ($db->flag == 'bunpou') {
                if ($upPesan == '/BACK') { //KETIKA BOT DI INPUT KEYWORD RESET
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    if ($sql) { //jika baris di database berhasil di update
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->mode();
                        $messages = [];
                        $msg1 = $send->text("Silahkan pilih mode kembali");
                        $msg2 = $send->flex("Pilih Mode", $content1);
                        array_push($messages, $msg1, $msg2);
                        $output = $send->reply($replyToken, $messages, $ballons);
                    }
                } else {
                    $loadDb = $this->Dbs->getdata_frombunpou($upPesan); //LOAD semua table dari padanan
                    $check = $loadDb->num_rows(); //cek number baris yang dihasilkan
                    if ($check > 0) {
                        $get = $this->Dbs->getdata_frombunpou($upPesan)->row(); //di eksekusi ketika data ditemukan
                        $messages = [];
                        $msg1 = $send->text("Arti : \r\n" . $get->padanan);
                        $msg2 = $send->text("Struktur : \r\n" . $get->rumus);
                        $msg3 = $send->text("Contoh : \r\n" . $get->contoh);
                        $msg4 = $send->text("Arti Contoh : \r\n" . $get->articontoh);
                        $msg5 = $send->text("Keterangan : \r\n" . $get->keterangan);
                        array_push($messages, $msg1, $msg2, $msg3, $msg4, $msg5);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->backbutton();
                        $messages = [];
                        $msg1 = $send->text("Maaf keyword yang anda kirim belum ada dalam database, silahkan cek kembali lagi nanti ^^");
                        $msg2 = $send->text("Atau Kembali ke pilihan mode");
                        $msg3 = $send->flex("Kembali", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
            } //END FITUR BUNPOU
            // PADANAN4
            else if ($db->flag == 'padanan4') { //CONTOH MAPPING KE PADANAN
                if ($upPesan == '/BACK') { //KETIKA BOT DI INPUT KEYWORD RESET
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    if ($sql) { //jika baris di database berhasil di update
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->mode();
                        $messages = [];
                        $msg1 = $send->text("Silahkan pilih mode kembali");
                        $msg2 = $send->flex("Pilih Mode", $content1);
                        array_push($messages, $msg1, $msg2);
                        $output = $send->reply($replyToken, $messages, $ballons);
                    }
                } else {
                    $loadDb = $this->Dbs->getdata_frompadanan4($upPesan); //LOAD semua table dari padanan
                    $check = $loadDb->num_rows(); //cek number baris yang dihasilkan
                    if ($check > 0) {
                        $get = $this->Dbs->getdata_frompadanan4($upPesan)->row(); //di eksekusi ketika data ditemukan
                        $messages = [];
                        $msg1 = $send->text("Tata Bahasa : \r\n" . $get->bunpou);
                        $msg2 = $send->text("Struktur : \r\n" . $get->rumus);
                        $msg3 = $send->text("Contoh : \r\n" . $get->contoh);
                        $msg4 = $send->text("Arti Contoh : \r\n" . $get->articontoh);
                        $msg5 = $send->text("Keterangan : \r\n" . $get->keterangan);
                        array_push($messages, $msg1, $msg2, $msg3, $msg4, $msg5);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->backbutton();
                        $messages = [];
                        $msg1 = $send->text("Maaf keyword yang anda kirim belum ada dalam database, silahkan cek kembali lagi nanti ^^");
                        $msg2 = $send->text("Atau Kembali ke pilihan mode");
                        $msg3 = $send->flex("Kembali", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
            } //END PADANAN4
            // BUNPOU4
            else if ($db->flag == 'bunpou4') {
                if ($upPesan == '/BACK') { //KETIKA BOT DI INPUT KEYWORD RESET
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    if ($sql) { //jika baris di database berhasil di update
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->mode();
                        $messages = [];
                        $msg1 = $send->text("Silahkan pilih mode kembali");
                        $msg2 = $send->flex("Pilih Mode", $content1);
                        array_push($messages, $msg1, $msg2);
                        $output = $send->reply($replyToken, $messages, $ballons);
                    }
                } else {
                    $loadDb = $this->Dbs->getdata_frombunpou4($upPesan); //LOAD semua table dari padanan
                    $check = $loadDb->num_rows(); //cek number baris yang dihasilkan
                    if ($check > 0) {
                        $get = $this->Dbs->getdata_frombunpou4($upPesan)->row(); //di eksekusi ketika data ditemukan
                        $messages = [];
                        $msg1 = $send->text("Arti : \r\n" . $get->padanan);
                        $msg2 = $send->text("Struktur : \r\n" . $get->rumus);
                        $msg3 = $send->text("Contoh : \r\n" . $get->contoh);
                        $msg4 = $send->text("Arti Contoh : \r\n" . $get->articontoh);
                        $msg5 = $send->text("Keterangan : \r\n" . $get->keterangan);
                        array_push($messages, $msg1, $msg2, $msg3, $msg4, $msg5);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->backbutton();
                        $messages = [];
                        $msg1 = $send->text("Maaf keyword yang anda kirim belum ada dalam database, silahkan cek kembali lagi nanti ^^");
                        $msg2 = $send->text("Atau Kembali ke pilihan mode");
                        $msg3 = $send->flex("Kembali", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
            } //END FITUR BUNPOU4
            // PADANAN5
            else if ($db->flag == 'padanan5') { //CONTOH MAPPING KE PADANAN
                if ($upPesan == '/BACK') { //KETIKA BOT DI INPUT KEYWORD RESET
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    if ($sql) { //jika baris di database berhasil di update
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->mode();
                        $messages = [];
                        $msg1 = $send->text("Silahkan pilih mode kembali");
                        $msg2 = $send->flex("Pilih Mode", $content1);
                        array_push($messages, $msg1, $msg2);
                        $output = $send->reply($replyToken, $messages, $ballons);
                    }
                } else {
                    $loadDb = $this->Dbs->getdata_frompadanan5($upPesan); //LOAD semua table dari padanan
                    $check = $loadDb->num_rows(); //cek number baris yang dihasilkan
                    if ($check > 0) {
                        $get = $this->Dbs->getdata_frompadanan5($upPesan)->row(); //di eksekusi ketika data ditemukan
                        $messages = [];
                        $msg1 = $send->text("Tata Bahasa : \r\n" . $get->bunpou);
                        $msg2 = $send->text("Struktur : \r\n" . $get->rumus);
                        $msg3 = $send->text("Contoh : \r\n" . $get->contoh);
                        $msg4 = $send->text("Arti Contoh : \r\n" . $get->articontoh);
                        $msg5 = $send->text("Keterangan : \r\n" . $get->keterangan);
                        array_push($messages, $msg1, $msg2, $msg3, $msg4, $msg5);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->backbutton();
                        $messages = [];
                        $msg1 = $send->text("Maaf keyword yang anda kirim belum ada dalam database, silahkan cek kembali lagi nanti ^^");
                        $msg2 = $send->text("Atau Kembali ke pilihan mode");
                        $msg3 = $send->flex("Kembali", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
            } //END PADANAN5
            // BUNPOU5
            else if ($db->flag == 'bunpou5') {
                if ($upPesan == '/BACK') { //KETIKA BOT DI INPUT KEYWORD RESET
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    if ($sql) { //jika baris di database berhasil di update
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->mode();
                        $messages = [];
                        $msg1 = $send->text("Silahkan pilih mode kembali");
                        $msg2 = $send->flex("Pilih Mode", $content1);
                        array_push($messages, $msg1, $msg2);
                        $output = $send->reply($replyToken, $messages, $ballons);
                    }
                } else {
                    $loadDb = $this->Dbs->getdata_frombunpou5($upPesan); //LOAD semua table dari padanan
                    $check = $loadDb->num_rows(); //cek number baris yang dihasilkan
                    if ($check > 0) {
                        $get = $this->Dbs->getdata_frombunpou5($upPesan)->row(); //di eksekusi ketika data ditemukan
                        $messages = [];
                        $msg1 = $send->text("Arti : \r\n" . $get->padanan);
                        $msg2 = $send->text("Struktur : \r\n" . $get->rumus);
                        $msg3 = $send->text("Contoh : \r\n" . $get->contoh);
                        $msg4 = $send->text("Arti Contoh : \r\n" . $get->articontoh);
                        $msg5 = $send->text("Keterangan : \r\n" . $get->keterangan);
                        array_push($messages, $msg1, $msg2, $msg3, $msg4, $msg5);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        require 'flex.php';
                        $flex = new flex();
                        $content1 = $flex->backbutton();
                        $messages = [];
                        $msg1 = $send->text("Maaf keyword yang anda kirim belum ada dalam database, silahkan cek kembali lagi nanti ^^");
                        $msg2 = $send->text("Atau Kembali ke pilihan mode");
                        $msg3 = $send->flex("Kembali", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
            } //END FITUR BUNPOU5
            // PERMENUAN
            else if ($upPesan == '/MENU') {
                $ballons = array($send->imagemapmenu("https://gengobot.com/bot/imagemap/ID/MENU", "PILIH MENU", "/ID>JP", "/JP>ID", "/LATIHAN", "/INDEX", "/MORE"));
                $output = $send->reply($replyToken, $ballons);
            } else if ($upPesan == "/LATIHAN5") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->quiz();
                $messages = [];
                $msg1 = $send->flex("Latihan N5", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == '/BAHASA') {
                $ballons = array($send->imagemapbahasa("https://gengobot.com/bot/imagemap/ID/BAHASA", "PILIH BAHASA", "/ID", "/EN", "/JP"));
                $output = $send->reply($replyToken, $ballons);
            } else if ($upPesan == '/LATIHAN') {
                $where = array('userid' => $userId);
                $data = array('flag' => 'quiz');
                $sql = $this->Dbs->update($where, $data, 'user');
                $ballons = array($send->imagemapn("https://gengobot.com/bot/imagemap/ID/L54", "PILIH LATIHAN", "/LATIHANN5", "/LATIHANN4"));
                $output = $send->reply($replyToken, $ballons);
                $ballons = array($send->imagemapn("https://gengobot.com/bot/imagemap/ID/L54", "PILIH LATIHAN", "/LATIHANN5", "/LATIHANN4"));
            } else if ($upPesan == '/INDEX') {
                $ballons = array($send->imagemapbahasa("https://gengobot.com/bot/imagemap/ID/INDX", "PILIH INDEX", "/INDEXID", "/INDEXEN", "/INDEXJP"));
                $output = $send->reply($replyToken, $ballons);
            } else if ($upPesan == '/INDEXID') {
                $ballons = array($send->imagemapn("https://gengobot.com/bot/imagemap/ID/INDXN", "PILIH INDEX", "/INDEXID5", "/INDEXID4"));
                $output = $send->reply($replyToken, $ballons);
            } else if ($upPesan == '/INDEXJP') {
                $ballons = array($send->imagemapn("https://gengobot.com/bot/imagemap/ID/INDXN", "PILIH INDEX", "/INDEXJP5", "/INDEXJP4"));
                $output = $send->reply($replyToken, $ballons);
            } else if ($upPesan == '/INDEXEN') {
                $ballons = array($send->imagemapn("https://gengobot.com/bot/imagemap/ID/INDXN", "PILIH INDEX", "/INDEXEN5", "/INDEXEN4"));
                $output = $send->reply($replyToken, $ballons);
            } else if ($upPesan == '/HELP') {
                $messages = [];
                $msg1 = $send->text("Mohon di tunggu ya, mode HELP sedang dalam tahap pengembangan ^^");
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == '/MORE') {
                $ballons = array($send->imagemapmore("https://gengobot.com/bot/imagemap/ID/MORE", "PILIH", "/AISATSU", "/KEIGO", "/TABELKANA"));
                $output = $send->reply($replyToken, $ballons);
            }
            // END PERMENUAN
            //   QUIZ
            // QUIZ MENU
            else if ($upPesan == "/LATIHANN4") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->pilihlatihan("https://gengobot.com/bot/img/latn4.png", "N4TIPE1", "N4TIPE2");
                $messages = [];
                $msg1 = $send->flex("Latihan N4", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "/LATIHANN5") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->pilihlatihan("https://gengobot.com/bot/img/latn5.png", "N5TIPE1", "N5TIPE2");
                $messages = [];
                $msg1 = $send->flex("Latihan N5", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "N4TIPE1") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->pillat("Soal N4", "https://gengobot.com/bot/img/latn4.png", "/ATURANTIPE1", "4TIPE1/1", "4TIPE1/2", "4TIPE1/3", "4TIPE1/4", "4TIPE1/5");
                $messages = [];
                $msg1 = $send->flex("Latihan N4", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "N4TIPE2") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->pillat("Soal N4", "https://gengobot.com/bot/img/latn4.png", "/ATURANTIPE1", "4TIPE2/1", "4TIPE2/2", "4TIPE2/3", "4TIPE2/4", "4TIPE2/5");
                $messages = [];
                $msg1 = $send->flex("Latihan N4", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "N5TIPE1") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->pillat("Soal N5", "https://gengobot.com/bot/img/latn5.png", "/ATURANTIPE1", "5TIPE1/1", "5TIPE1/2", "5TIPE1/3", "5TIPE1/4", "5TIPE1/5");
                $messages = [];
                $msg1 = $send->flex("Latihan N5", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "N5TIPE2") {
                require 'flex.php';
                $flex = new flex();
                $content1 = $flex->pillat("Soal N5", "https://gengobot.com/bot/img/latn5.png", "/ATURANTIPE1", "5TIPE2/1", "5TIPE2/2", "5TIPE2/3", "5TIPE2/4", "5TIPE2/5");
                $messages = [];
                $msg1 = $send->flex("Latihan N5", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "/ATURANTIPE1") {
                $messages = [];
                $msg1 = $send->image("https://gengobot.com/bot/img/latn5.png");
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "/ATURANTIPE2") {
                $messages = [];
                $msg1 = $send->image("https://gengobot.com/bot/img/latn5.png");
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "TOTALSCORE") {
                $score1 = $db2->score1;
                $score2 = $db2->score2;
                $score3 = $db2->score3;
                $score4 = $db2->score4;
                $score5 = $db2->score5;
                $score6 = $db2->score6;
                $score7 = $db2->score7;
                $score8 = $db2->score8;
                $score9 = $db2->score9;
                $score10 = $db2->score10;
                $totalscore = $score1 + $score2 + $score3 + $score4 + $score5 + $score6 + $score7 + $score8 + $score9 + $score10;
                $where = array('userid' => $userId);
                $data = array('totalscore' => $totalscore);
                $sql = $this->Dbs->update($where, $data, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $score = $db2->totalscore;
                $content1 = $flex->score($score);
                $messages = [];
                $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            }
            // END QUIZ MENU
            //N4
            if ($upPesan == "4TIPE1/1") {
                $idnosoal = 1;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '1');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '1');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N5, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            }
            if ($upPesan == "4TIPE1/2") {
                $idnosoal = 11;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '11');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '2');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N5, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            }
            if ($upPesan == "4TIPE1/3") {
                $idnosoal = 21;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '21');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '3');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N5, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            }
            if ($upPesan == "4TIPE1/4") {
                $idnosoal = 31;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '31');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '4');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N5, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            }
            if ($upPesan == "4TIPE1/5") {
                $idnosoal = 41;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '41');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '5');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N5, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "4TIPE2/1") {
                $idnosoal = 51;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '51');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '6');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N4, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "4TIPE2/2") {
                $idnosoal = 61;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '61');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '7');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N4, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "4TIPE2/3") {
                $idnosoal = 71;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '71');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '8');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N4, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "4TIPE2/4") {
                $idnosoal = 81;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '81');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '9');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N4, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            } else if ($upPesan == "4TIPE2/5") {
                $idnosoal = 91;
                $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                $soal = $Load->soal;
                $pilgan1 = $Load->pil_a;
                $pilgan2 = $Load->pil_b;
                $pilgan3 = $Load->pil_c;
                $pilgan4 = $Load->pil_d;
                $kunjaw = $Load->kunjaw;
                $where = array('userid' => $userId);
                $data = array('soal' => '91');
                $sql = $this->Dbs->update($where, $data, 'quiz');
                $where2 = array('userid' => $userId);
                $data2 = array('bagian' => '10');
                $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                require 'flex.php';
                $flex = new Flex();
                $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                $messages = [];
                $msg1 = $send->flex("Latihan N4, Soal nomor 1", $content1);
                array_push($messages, $msg1);
                $output = $send->reply($replyToken, $messages);
            }
            // BAGIAN 1
            else if ($db2->bagian == '1') {
                //   soal 1
                if ($db2->soal == '1') {
                    $idnosoal = 1;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 1;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 2;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '2');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 1;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 2;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '2');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 1;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 2;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '2');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 1;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 2;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '2');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal1
                //   soal 2
                else if ($db2->soal == '2') {
                    $idnosoal = 2;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 2;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 3;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '3');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 2;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 3;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '3');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 2;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 3;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '3');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 2;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 3;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '3');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal2
                //   soal 3
                else if ($db2->soal == '3') {
                    $idnosoal = 3;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 3;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 4;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '4');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 3;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 4;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '4');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 3;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 4;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '4');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 3;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 4;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '4');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal3
                //   soal 4
                else if ($db2->soal == '4') {
                    $idnosoal = 4;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 4;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 5;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '5');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 4;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 5;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '5');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 4;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 5;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '5');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 4;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 5;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '5');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal4
                //   soal 5
                else if ($db2->soal == '5') {
                    $idnosoal = 5;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 5;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 6;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '6');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 5;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 6;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '6');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 5;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 6;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '6');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 5;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 6;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '6');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal5
                //   soal 6
                else if ($db2->soal == '6') {
                    $idnosoal = 6;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 6;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 7;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '7');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 6;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 7;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '7');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 6;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 7;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '7');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 6;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 7;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '7');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal6
                //   soal 7
                else if ($db2->soal == '7') {
                    $idnosoal = 7;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 7;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 8;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '8');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 7;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 8;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '8');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 7;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 8;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '8');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 7;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 8;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '8');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal7
                //   soal 8
                else if ($db2->soal == '8') {
                    $idnosoal = 8;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 8;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 9;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '9');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 8;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 9;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '9');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 8;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 9;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '9');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 8;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 9;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '9');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal8
                //   soal 9
                else if ($db2->soal == '9') {
                    $idnosoal = 9;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 9;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 10;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '10');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score1' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 9;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 10;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '10');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 9;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 10;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '10');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 9;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 10;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '10');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal9
                //   soal 10
                else if ($db2->soal == '10') {
                    $idnosoal = 10;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where = array('userid' => $userId);
                        $getscore = $db2->score1;
                        $tambahscore = $getscore + 10;
                        $data = array('score1' => $tambahscore);
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        $soalsebelumnya = 10;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 10;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 10;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 10;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal10
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score1;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN1
            // BAGIAN 2
            else if ($db2->bagian == '2') {
                //   soal 11
                if ($db2->soal == '11') {
                    $idnosoal = 11;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 11;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 12;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '12');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 11;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 12;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '12');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 11;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 12;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '12');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 11;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 12;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '12');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal11
                //   soal 12
                else if ($db2->soal == '12') {
                    $idnosoal = 12;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 12;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 13;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '13');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 12;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 13;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '13');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 12;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 13;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '13');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 12;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 13;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '13');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal 12
                //   soal 13
                else if ($db2->soal == '13') {
                    $idnosoal = 13;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 13;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 14;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '14');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 13;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 14;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '14');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 13;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 14;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '14');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 13;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 14;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '14');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal13
                //   soal 14
                else if ($db2->soal == '14') {
                    $idnosoal = 14;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 14;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 15;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '15');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 14;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 15;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '15');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 14;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 15;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '15');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 14;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 15;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '15');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal14
                //   soal 15
                else if ($db2->soal == '15') {
                    $idnosoal = 15;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 15;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 16;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '16');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 15;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 16;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '16');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 15;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 16;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '16');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 15;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 16;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '16');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal15
                //   soal 16
                else if ($db2->soal == '16') {
                    $idnosoal = 16;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 16;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 17;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '17');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 16;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 17;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '17');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 16;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 17;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '17');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 16;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 17;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '17');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal16
                //   soal 17
                else if ($db2->soal == '17') {
                    $idnosoal = 17;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 17;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 18;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '18');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 17;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 18;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '18');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 17;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 18;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '18');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 17;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 18;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '18');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal17
                //   soal 18
                else if ($db2->soal == '18') {
                    $idnosoal = 18;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 18;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 19;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '19');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 18;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 19;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '19');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 18;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 19;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '19');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 18;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 9;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '19');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal18
                //   soal 19
                else if ($db2->soal == '19') {
                    $idnosoal = 19;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 19;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 20;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '20');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 19;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 20;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '20');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 19;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 20;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '20');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 19;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 20;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '20');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal19
                //   soal 20
                else if ($db2->soal == '20') {
                    $idnosoal = 20;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 20;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 20;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 20;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score2;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score2' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 20;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal20
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score2;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN2
            // BAGIAN 3
            else if ($db2->bagian == '3') {
                //   soal 21
                if ($db2->soal == '21') {
                    $idnosoal = 21;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 21;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 22;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '22');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 21;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 22;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '22');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 21;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 22;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '2');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 21;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 22;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '2');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal21
                //   soal 22
                else if ($db2->soal == '22') {
                    $idnosoal = 22;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 22;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 23;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '23');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 22;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 23;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '23');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 22;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 23;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '23');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 22;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 23;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '23');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal22
                //   soal 23
                else if ($db2->soal == '23') {
                    $idnosoal = 23;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 23;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 24;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '24');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 23;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 24;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '24');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 23;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 24;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '24');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 23;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 24;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '24');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal23
                //   soal 24
                else if ($db2->soal == '24') {
                    $idnosoal = 24;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 24;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 25;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '25');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 24;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 25;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '25');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 24;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 25;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '25');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 24;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 25;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '25');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal24
                //   soal 25
                else if ($db2->soal == '25') {
                    $idnosoal = 25;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 25;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 26;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '26');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 25;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 26;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '26');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 25;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 26;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '26');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 25;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 26;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '26');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal25
                //   soal 26
                else if ($db2->soal == '26') {
                    $idnosoal = 26;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 26;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 27;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '27');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 26;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 27;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '27');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 26;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 27;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '27');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 26;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 27;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '27');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal6
                //   soal 7
                else if ($db2->soal == '27') {
                    $idnosoal = 27;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 27;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 28;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '28');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 27;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 28;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '28');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 27;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 28;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '28');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 27;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 28;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '28');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal27
                //   soal 28
                else if ($db2->soal == '28') {
                    $idnosoal = 28;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 28;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 29;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '29');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 28;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 29;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '29');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 28;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 29;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '29');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 28;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 29;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '29');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal28
                //   soal 29
                else if ($db2->soal == '29') {
                    $idnosoal = 29;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 29;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 30;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '30');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 29;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 30;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '30');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 29;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 30;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '30');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 29;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 30;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '30');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal29
                //   soal 30
                else if ($db2->soal == '30') {
                    $idnosoal = 30;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 30;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 30;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score3;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score3' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 30;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 30;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal10
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score3;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN 3
            // BAGIAN 4
            else if ($db2->bagian == '4') {
                //   soal 31
                if ($db2->soal == '31') {
                    $idnosoal = 31;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 31;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 32;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '32');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 31;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 32;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '32');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 31;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 32;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '32');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 31;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 32;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '32');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal31
                //   soal 32
                else if ($db2->soal == '32') {
                    $idnosoal = 32;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 32;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 33;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '33');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 32;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 33;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '33');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 32;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 33;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '33');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 32;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 33;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '33');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal2
                //   soal 3
                else if ($db2->soal == '33') {
                    $idnosoal = 33;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 33;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 34;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '34');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 33;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 34;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '34');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 33;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 34;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '34');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 33;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 34;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '34');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal33
                //   soal 34
                else if ($db2->soal == '34') {
                    $idnosoal = 34;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 34;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 35;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '35');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 34;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 35;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '35');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 34;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 35;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '35');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 34;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 35;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '35');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal34
                //   soal 35
                else if ($db2->soal == '35') {
                    $idnosoal = 35;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 35;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 36;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '36');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 35;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 36;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '36');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 35;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 36;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '36');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 35;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 36;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '36');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal35
                //   soal 36
                else if ($db2->soal == '36') {
                    $idnosoal = 36;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 36;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 37;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '37');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 36;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 37;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '37');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 36;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 37;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '37');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 36;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 37;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '37');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal6
                //   soal 7
                else if ($db2->soal == '37') {
                    $idnosoal = 37;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 37;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 38;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '38');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 37;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 38;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '38');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 37;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 38;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '38');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 37;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 38;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '38');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal37
                //   soal 38
                else if ($db2->soal == '38') {
                    $idnosoal = 38;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 38;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 39;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '39');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 38;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 39;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '9');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 38;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 39;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '39');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 38;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 39;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '39');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal38
                //   soal 39
                else if ($db2->soal == '39') {
                    $idnosoal = 39;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 39;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 40;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '40');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 39;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 40;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '40');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 39;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 40;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '40');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 39;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 40;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '40');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoa39
                //   soal 40
                else if ($db2->soal == '40') {
                    $idnosoal = 40;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 40;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score4;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score4' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 40;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 40;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 40;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal40
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score4;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN4
            // BAGIAN 5
            else if ($db2->bagian == '5') {
                //   soal 41
                if ($db2->soal == '41') {
                    $idnosoal = 41;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 41;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 42;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '42');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 41;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 42;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '42');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 41;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 42;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '42');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 41;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 42;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '42');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal41
                //   soal 42
                else if ($db2->soal == '42') {
                    $idnosoal = 42;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 42;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 43;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '43');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 42;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 43;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '43');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 42;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 43;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '43');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 42;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 43;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '43');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal42
                //   soal 43
                else if ($db2->soal == '43') {
                    $idnosoal = 43;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 43;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 44;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '44');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 43;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 44;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '44');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 43;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 44;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '44');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 43;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 44;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '44');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal43
                //   soal 44
                else if ($db2->soal == '44') {
                    $idnosoal = 44;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 44;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 45;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '45');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 44;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 45;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '45');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 44;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 45;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '45');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 44;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 45;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '45');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal44
                //   soal 45
                else if ($db2->soal == '45') {
                    $idnosoal = 45;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 45;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 46;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '46');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 45;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 46;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '46');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 45;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 46;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '46');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 45;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 46;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '46');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal45
                //   soal 46
                else if ($db2->soal == '46') {
                    $idnosoal = 46;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 46;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 47;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '47');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 46;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 47;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '47');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 46;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 47;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '47');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 46;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 47;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '47');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal46
                //   soal 47
                else if ($db2->soal == '47') {
                    $idnosoal = 47;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 47;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 48;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '48');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 47;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 48;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '48');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 47;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 48;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '48');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 47;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 48;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '48');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal47
                //   soal 48
                else if ($db2->soal == '48') {
                    $idnosoal = 48;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 48;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 49;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '49');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 48;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 49;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '49');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 48;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 49;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '49');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 48;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 49;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '49');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal48
                //   soal 49
                else if ($db2->soal == '49') {
                    $idnosoal = 49;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 49;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 50;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '10');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 49;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 50;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '50');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 49;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 50;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '50');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 49;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 50;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '50');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoa49
                //   soal 50
                else if ($db2->soal == '50') {
                    $idnosoal = 50;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 50;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score5;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score5' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 50;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 50;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 50;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal50
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score5;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN5
            // BAGIAN 6
            //   soal 1
            else if ($db2->bagian == '6') {
                if ($db2->soal == '51') {
                    $idnosoal = 51;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 51;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 52;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '52');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 51;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 52;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '52');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 51;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 52;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '52');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 51;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 52;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '52');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal51
                //   soal 52
                else if ($db2->soal == '52') {
                    $idnosoal = 52;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 52;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 53;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '53');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 52;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 53;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '53');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 52;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 53;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '53');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 52;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 53;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '53');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal52
                //   soal 53
                else if ($db2->soal == '53') {
                    $idnosoal = 53;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 53;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 54;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '54');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 53;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 54;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '54');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 53;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 54;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '54');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 53;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 54;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '54');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal53
                //   soal 54
                else if ($db2->soal == '54') {
                    $idnosoal = 54;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 54;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 55;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '55');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 54;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 55;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '55');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 54;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 55;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '55');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 54;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 55;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '55');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal54
                //   soal 55
                else if ($db2->soal == '55') {
                    $idnosoal = 55;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 55;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 56;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '56');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 55;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 56;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '56');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 55;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 56;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '56');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 55;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 56;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '56');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal55
                //   soal 56
                else if ($db2->soal == '56') {
                    $idnosoal = 56;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 56;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 57;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '57');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 56;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 57;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '57');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 56;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 57;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '57');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 56;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 57;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '57');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal56
                //   soal 57
                else if ($db2->soal == '57') {
                    $idnosoal = 57;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 57;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 58;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '58');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 57;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 58;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '58');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 57;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 58;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '58');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 57;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 58;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '58');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal7
                //   soal 8
                else if ($db2->soal == '58') {
                    $idnosoal = 58;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 58;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 59;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '59');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 58;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 59;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '59');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 58;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 59;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '59');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 58;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 59;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '59');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal58
                //   soal 59
                else if ($db2->soal == '59') {
                    $idnosoal = 59;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 59;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 60;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '60');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 59;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 60;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '60');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score6' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 59;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 60;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '60');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 59;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 60;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '60');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal9
                //   soal 10
                else if ($db2->soal == '60') {
                    $idnosoal = 60;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 60;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 60;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 60;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where = array('userid' => $userId);
                        $getscore = $db2->score6;
                        $tambahscore = $getscore + 10;
                        $data = array('score6' => $tambahscore);
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        $soalsebelumnya = 60;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal10
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score6;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN 6
            // BAGIAN 7
            else if ($db2->bagian == '7') {
                //   soal 61
                if ($db2->soal == '61') {
                    $idnosoal = 61;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 61;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 62;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '62');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 61;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 62;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '62');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 61;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 62;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '62');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 61;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 62;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '62');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal61
                //   soal 62
                else if ($db2->soal == '62') {
                    $idnosoal = 62;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 62;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 63;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '63');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 62;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 63;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '63');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 62;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 63;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '63');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 62;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 63;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '63');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal62
                //   soal 63
                else if ($db2->soal == '63') {
                    $idnosoal = 63;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 63;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 64;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '64');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 63;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 64;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '64');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 63;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 64;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '64');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 63;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 64;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '64');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal63
                //   soal 64
                else if ($db2->soal == '64') {
                    $idnosoal = 64;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 64;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 65;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '65');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 64;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 65;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '65');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 64;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 65;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '65');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 64;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 65;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '65');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal64
                //   soal 65
                else if ($db2->soal == '65') {
                    $idnosoal = 65;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 65;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 66;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '66');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 65;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 66;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '66');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 65;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 66;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '66');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 65;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 66;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '66');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal65
                //   soal 66
                else if ($db2->soal == '66') {
                    $idnosoal = 66;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 66;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 67;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '67');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 66;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 67;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '67');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 66;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 67;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '67');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 66;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 67;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '67');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal66
                //   soal 67
                else if ($db2->soal == '67') {
                    $idnosoal = 67;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 67;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 68;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '68');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 67;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 68;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '68');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 67;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 68;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '68');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 67;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 68;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '68');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal67
                //   soal 68
                else if ($db2->soal == '68') {
                    $idnosoal = 68;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 68;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 69;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '69');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 68;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 69;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '69');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 68;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 69;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '69');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 68;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 69;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '69');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal 68
                //   soal 69
                else if ($db2->soal == '69') {
                    $idnosoal = 69;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 69;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 70;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '70');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 69;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 70;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '70');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 69;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 70;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '70');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 69;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 70;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '70');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal69
                //   soal 70
                else if ($db2->soal == '70') {
                    $idnosoal = 70;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score7;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score7' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 70;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 70;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 70;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 70;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal70
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score7;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN7
            // BAGIAN 8
            else if ($db2->bagian == '8') {
                //   soal 71
                if ($db2->soal == '71') {
                    $idnosoal = 71;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 71;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 72;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '72');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 71;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 72;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '72');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 71;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 72;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '72');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 71;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 72;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '72');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal71
                //   soal 72
                else if ($db2->soal == '72') {
                    $idnosoal = 72;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 72;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 73;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '73');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 72;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 73;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '73');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 72;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 73;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '73');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 72;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 73;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '73');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal72
                //   soal 73
                else if ($db2->soal == '73') {
                    $idnosoal = 73;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 73;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 74;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '74');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 73;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 74;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '74');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 73;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 74;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '74');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 73;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 74;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '74');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal73
                //   soal 74
                else if ($db2->soal == '74') {
                    $idnosoal = 74;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 74;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 75;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '75');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 74;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 75;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '75');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 74;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 75;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '75');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 74;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 75;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '75');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal74
                //   soal 75
                else if ($db2->soal == '75') {
                    $idnosoal = 75;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 75;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 76;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '76');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 75;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 76;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '76');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 75;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 76;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '76');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 75;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 76;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '76');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal75
                //   soal 76
                else if ($db2->soal == '76') {
                    $idnosoal = 76;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 76;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 77;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '77');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 76;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 77;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '77');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 76;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 77;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '77');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 76;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 77;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '77');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal76
                //   soal 77
                else if ($db2->soal == '77') {
                    $idnosoal = 77;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 77;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 78;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '78');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 77;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 78;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '78');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 77;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 78;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '78');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 77;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 78;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '78');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal77
                //   soal 78
                else if ($db2->soal == '78') {
                    $idnosoal = 78;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 78;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 79;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '79');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 78;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 79;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '79');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 78;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 79;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '79');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 78;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 79;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '79');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal78
                //   soal 79
                else if ($db2->soal == '79') {
                    $idnosoal = 79;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 79;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 80;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '80');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 79;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 80;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '80');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 79;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 80;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '80');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 79;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 80;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '80');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal79
                //   soal 80
                else if ($db2->soal == '80') {
                    $idnosoal = 80;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score8;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score8' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 80;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 80;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 80;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 80;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal10
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score8;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN 8
            // BAGIAN 9
            else if ($db2->bagian == '9') {
                //   soal 81
                if ($db2->soal == '81') {
                    $idnosoal = 81;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 81;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 82;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '82');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 81;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 82;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '82');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 81;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 82;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '82');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 81;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 82;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '82');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal81
                //   soal 82
                else if ($db2->soal == '82') {
                    $idnosoal = 82;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 82;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 83;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '83');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 82;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 83;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '83');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 82;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 83;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '83');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 82;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 83;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '83');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal82
                //   soal 83
                else if ($db2->soal == '83') {
                    $idnosoal = 83;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 83;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 84;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '84');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 83;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 84;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '84');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 83;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 84;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '84');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 83;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 84;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '84');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal83
                //   soal 84
                else if ($db2->soal == '84') {
                    $idnosoal = 84;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 84;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 85;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '85');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 84;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 85;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '85');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 84;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 85;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '85');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 84;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 85;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '85');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal84
                //   soal 85
                else if ($db2->soal == '85') {
                    $idnosoal = 85;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 85;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 86;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '86');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 85;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 86;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '86');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 85;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 86;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '86');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 85;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 86;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '86');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal85
                //   soal 86
                else if ($db2->soal == '86') {
                    $idnosoal = 86;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 86;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 87;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '87');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 86;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 87;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '87');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 86;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 87;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '87');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 86;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 87;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '87');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal86
                //   soal 87
                else if ($db2->soal == '87') {
                    $idnosoal = 87;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 87;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 88;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '88');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 87;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 88;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '88');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 87;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 88;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '88');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 87;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 88;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '88');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal87
                //   soal 88
                else if ($db2->soal == '88') {
                    $idnosoal = 88;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 88;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 89;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '89');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 88;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 89;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '89');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 88;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 89;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '89');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 88;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 89;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '89');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal88
                //   soal 89
                else if ($db2->soal == '89') {
                    $idnosoal = 89;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 89;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 90;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '90');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 89;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 90;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '90');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 89;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 90;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '90');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 89;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 90;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '90');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal89
                //   soal 90
                else if ($db2->soal == '90') {
                    $idnosoal = 90;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 90;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 90;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score9;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score9' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 90;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 90;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal90
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score9;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN9
            // BAGIAN 10
            else if ($db2->bagian == '10') {
                //   soal 91
                if ($db2->soal == '91') {
                    $idnosoal = 91;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 91;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 92;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '92');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 91;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 92;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '92');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 91;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 92;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '92');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 91;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 92;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '92');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal91
                //   soal 92
                else if ($db2->soal == '92') {
                    $idnosoal = 92;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 92;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 93;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '93');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 92;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 93;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '93');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 92;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 93;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '93');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 92;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 93;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '93');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal92
                //   soal 93
                else if ($db2->soal == '93') {
                    $idnosoal = 93;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 93;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 94;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '94');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 93;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 94;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '94');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 93;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 94;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '94');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 93;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 94;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '94');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal93
                //   soal 94
                else if ($db2->soal == '94') {
                    $idnosoal = 94;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 94;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 95;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '95');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 94;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 95;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '95');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 94;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 95;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '95');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 94;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 95;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '95');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal94
                //   soal 95
                else if ($db2->soal == '95') {
                    $idnosoal = 95;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 95;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 96;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '96');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 95;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 96;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '96');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 95;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 96;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '96');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 95;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 96;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '96');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal95
                //   soal 96
                else if ($db2->soal == '96') {
                    $idnosoal = 96;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 96;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 97;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '97');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 96;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 97;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '97');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 96;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 97;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '97');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 96;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 97;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '97');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal96
                //   soal 97
                else if ($db2->soal == '97') {
                    $idnosoal = 97;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $soalsebelumnya = 97;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 98;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '98');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 97;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 98;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '98');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 97;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 98;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '98');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 97;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 98;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '98');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal97
                //   soal 98
                else if ($db2->soal == '98') {
                    $idnosoal = 98;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 98;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 99;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '99');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 98;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 99;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '99');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 98;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 99;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '99');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 98;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 99;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '99');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal98
                //   soal 99
                else if ($db2->soal == '99') {
                    $idnosoal = 99;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 99;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 100;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '100');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 99;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 100;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '100');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 99;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 100;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '100');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 99;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        $idnosoal = 100;
                        $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                        $soal = $Load->soal;
                        $pilgan1 = $Load->pil_a;
                        $pilgan2 = $Load->pil_b;
                        $pilgan3 = $Load->pil_c;
                        $pilgan4 = $Load->pil_d;
                        $where = array('userid' => $userId);
                        $data = array('soal' => '100');
                        $sql = $this->Dbs->update($where, $data, 'quiz');
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->latihan($idnosoal, $soal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $pilgan1, $pilgan2, $pilgan3, $pilgan4);
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Latihan N4, Soal nomor:" . $idnosoal, $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal99
                //   soal 100
                else if ($db2->soal == '100') {
                    $idnosoal = 100;
                    $Load = $this->Dbs->getnosoal4($idnosoal)->row();
                    $soal = $Load->soal;
                    $pilgan1 = $Load->pil_a;
                    $pilgan2 = $Load->pil_b;
                    $pilgan3 = $Load->pil_c;
                    $pilgan4 = $Load->pil_d;
                    $kunjaw = $Load->kunjaw;
                    if ($upPesan == $pilgan1) {
                        $where2 = array('userid' => $userId);
                        $getscore = $db2->score10;
                        $tambahscore = $getscore + 10;
                        $data2 = array('score10' => $tambahscore);
                        $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                        $soalsebelumnya = 100;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan2) {
                        $soalsebelumnya = 100;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan3) {
                        $soalsebelumnya = 100;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else if ($upPesan == $pilgan4) {
                        $soalsebelumnya = 100;
                        $Load2 = $this->Dbs->getnosoal4($soalsebelumnya)->row();
                        require 'flex.php';
                        $flex = new Flex();
                        $content1 = $flex->singlebutton("Anda telah menyelesaikan latihan ini. Pilih YA untuk melihat score", "YA", "TIDAK");
                        $messages = [];
                        $msg1 = $send->text("Jawaban yang benar adalah: \r\n" . $Load2->kunjaw);
                        $msg2 = $send->text("Penjelasan: \r\n" . $Load2->penjelasan);
                        $msg3 = $send->flex("Anda telah menyelesaikan latihan ini", $content1);
                        array_push($messages, $msg1, $msg2, $msg3);
                        $output = $send->reply($replyToken, $messages);
                    } else {
                        $messages = [];
                        $msg2 = $send->text("Anda belum menyelesaikan LATIHAN, tik: (END) jika ingin berhenti mengikuti LATIHAN-nya");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                }
                //endsoal100
                if ($upPesan == 'YA') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    require 'flex.php';
                    $score = $db2->score10;
                    $flex = new Flex();
                    $content1 = $flex->score($score);
                    $messages = [];
                    $msg1 = $send->flex("Score anda adalah : " . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == "END") { //jika ingin keluar dari latihan
                    require 'flex.php';
                    $flex = new Flex();
                    $content1 = $flex->button("Yakin ingin berhenti?", "YABERHENTI", "LANJUTKAN");
                    $messages = [];
                    $msg1 = $send->flex("Yakin ingin berhenti?" . $score, $content1);
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                } else if ($upPesan == 'YABERHENTI') {
                    $where = array('userid' => $userId); //RESET BERDASARKAN USERID yang sedang menginput
                    $data = array('flag' => 'default');
                    $sql = $this->Dbs->update($where, $data, 'user');
                    $where2 = array('userid' => $userId);
                    $data2 = array('soal' => 'default');
                    $sql2 = $this->Dbs->update($where2, $data2, 'quiz');
                    $where3 = array('userid' => $userId);
                    $data3 = array('bagian' => 'default');
                    $sql3 = $this->Dbs->update($where3, $data3, 'quiz');
                    if ($sql2) {
                        $messages = [];
                        $msg2 = $send->text("Anda telah berhenti");
                        array_push($messages, $msg2);
                        $output = $send->reply($replyToken, $messages);
                    }
                } else if ($upPesan == "LANJUTKAN") { //jika ingin melanjutkan
                    $messages = [];
                    $msg1 = $send->text("Silahkan isi soal sebelumnya.^^");
                    array_push($messages, $msg1);
                    $output = $send->reply($replyToken, $messages);
                }
            }
            // END BAGIAN10
            
        }
        //END INDO
        $client->replyMessage($output);
    }
    public function ini() {
        $hasil = $this->Dbs->leaderboard()->result();
  $a = 1;
  $array_lb=[];//array kosong
  foreach ($hasil as list ($h->nama,$h->totalscore)) {

     $item2 = array (
            'type' => 'box',
            'layout' => 'baseline',
            'contents' => 
            array (
              0 => 
              array (
                'type' => 'text',
                'text' => $a++,
                'flex' => 1,
              ),
              1 => 
              array (
                'type' => 'text',
                'text' => $h->nama,
                'flex' => 6,
                'wrap' => true,
              ),
              2 => 
              array (
                'type' => 'text',
                'text' => $h->totalscore,
                'flex' => 3,
              ),
            ),
          );
      }
      array_push($array_lb,$item2);
      print_r($array_lb);
        echo "kokokokokok";
        
    }
}
