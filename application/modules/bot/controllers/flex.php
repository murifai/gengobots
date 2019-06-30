<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - button()
* - singlebutton()
* - singlebtn()
* - bunpou()
* - padanan()
* - mode()
* - backbutton()
* - score()
* - pilihlatihan()
* - pillat()
* - latihan()
* Classes list:
* - Flex extends MY_Controller
*/
class Flex extends MY_Controller {

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this
      ->load
      ->model(array(
      'Dbs'
    ));

    date_default_timezone_set("Asia/Bangkok");

  }

  //hasil decode simpen di fungsi yang baru
  //contoh kaya gini
  function button($header, $message1, $message2) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'text',
            'text' => $header,
            'align' => 'start',
            'wrap' => true,
          ) ,
        ) ,
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'horizontal',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'YA',
              'text' => $message1,
            ) ,
            'color' => '#FF7376',
            'style' => 'primary',
          ) ,
          1 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'TIDAK',
              'text' => $message2,
            ) ,
            'color' => '#DFDFDF',
            'style' => 'secondary',
          ) ,
        ) ,
      ) ,
      'styles' => array(
        'body' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
        'footer' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
      ) ,
    );
    return $item;
  }

  function singlebutton($header, $message1, $message2) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'text',
            'text' => $header,
            'align' => 'start',
            'wrap' => true,
          ) ,
        ) ,
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'horizontal',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'YA',
              'text' => $message1,
            ) ,
            'color' => '#FF7376',
            'style' => 'primary',
          ) ,
        ) ,
      ) ,
      'styles' => array(
        'body' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
        'footer' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
      ) ,
    );
    return $item;
  }

  function singlebtn($header, $label, $message1) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'text',
            'text' => $header,
            'align' => 'start',
            'wrap' => true,
          ) ,
        ) ,
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'horizontal',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => $label,
              'text' => $message1,
            ) ,
            'color' => '#FF7376',
            'style' => 'primary',
          ) ,
        ) ,
      ) ,
      'styles' => array(
        'body' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
        'footer' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
      ) ,
    );
    return $item;
  }

  function bunpou($header, $message1, $message2) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'header' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'text',
            'text' => 'Pilih Level Bunpou',
            'size' => 'lg',
            'align' => 'center',
          ) ,
        ) ,
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'ALL',
              'text' => '/BUNPOUALL',
            ) ,
            'style' => 'primary',
          ) ,
          1 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'N5',
              'text' => '/BUNPOUN5',
            ) ,
            'margin' => 'sm',
            'style' => 'primary',
          ) ,
          2 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'N4',
              'text' => '/BUNPOUN4',
            ) ,
            'margin' => 'sm',
            'style' => 'primary',
          ) ,
        ) ,
      ) ,
    );
    return $item;
  }

  function padanan($header, $message1, $message2) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'header' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'text',
            'text' => 'Pilih Level',
            'size' => 'lg',
            'align' => 'center',
          ) ,
        ) ,
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'ALL',
              'text' => '/INDOALL',
            ) ,
            'style' => 'primary',
          ) ,
          1 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'N5',
              'text' => '/INDON5',
            ) ,
            'margin' => 'sm',
            'style' => 'primary',
          ) ,
          2 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'N4',
              'text' => '/INDON4',
            ) ,
            'margin' => 'sm',
            'style' => 'primary',
          ) ,
        ) ,
      ) ,
    );
    return $item;
  }

  function mode($header, $message1, $message2) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'header' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'text',
            'text' => 'Pilih Mode',
            'size' => 'lg',
            'align' => 'center',
          ) ,
        ) ,
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'JP>ID',
              'text' => '/JP>ID',
            ) ,
            'style' => 'primary',
          ) ,
          1 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'ID>JP',
              'text' => '/ID>JP',
            ) ,
            'margin' => 'sm',
            'style' => 'primary',
          ) ,
          2 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'MENU UTAMA',
              'text' => '/MENU',
            ) ,
            'margin' => 'sm',
            'style' => 'primary',
          ) ,
        ) ,
      ) ,
    );
    return $item;
  }

  function backbutton($header, $message1, $message2) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'footer' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'KEMBALI',
              'text' => '/BACK',
            ) ,
            'style' => 'primary',
          ) ,
        ) ,
      ) ,
    );
    return $item;
  }

  function score($score) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => array(
          0 => array(
            'type' => 'text',
            'text' => 'SCORE ANDA',
            'size' => 'xl',
            'align' => 'center',
            'weight' => 'bold',
            'color' => '#000000',
          ) ,
          1 => array(
            'type' => 'text',
            'text' => $score,
            'flex' => 1,
            'size' => '5xl',
            'align' => 'center',
            'gravity' => 'top',
            'weight' => 'bold',
            'color' => '#FF7376',
          ) ,
        ) ,
      ) ,
      'styles' => array(
        'body' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
        'footer' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
      ) ,
    );

    return $item; //jangan lupa return variable item nya biar fungsi tadi menghasilkan nilai
    
  }

  function pilihlatihan($image, $tipe1, $tipe2) {
    $item = array(
      'type' => 'bubble',
      'direction' => 'ltr',
      'hero' => array(
        'type' => 'image',
        'url' => $image,
        'size' => 'full',
        'aspectRatio' => '4:3',
        'aspectMode' => 'fit',
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'horizontal',
        'spacing' => 'sm',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'TIPE 1',
              'text' => $tipe1,
            ) ,
            'style' => 'primary',
          ) ,
          1 => array(
            'type' => 'button',
            'action' => array(
              'type' => 'message',
              'label' => 'TIPE 2',
              'text' => $tipe2,
            ) ,
            'style' => 'primary',
          ) ,
        ) ,
      ) ,
    );

    return $item;
  }

  function pillat($level, $img, $aturan, $button1, $button2, $button3, $button4, $button5) {
    $item = array(
      'type' => 'carousel',
      'contents' => array(
        0 => array(
          'type' => 'bubble',
          'direction' => 'ltr',
          'header' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => $level,
                'align' => 'center',
                'weight' => 'bold',
              ) ,
            ) ,
          ) ,
          'hero' => array(
            'type' => 'image',
            'url' => $img,
            'size' => 'full',
            'aspectRatio' => '4:3',
            'aspectMode' => 'fit',
          ) ,
          'body' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => 'Bagian satu',
                'size' => 'md',
                'align' => 'center',
              ) ,
              1 => array(
                'type' => 'filler',
              ) ,
            ) ,
          ) ,
          'footer' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'MULAI',
                  'text' => $button1,
                ) ,
                'color' => '#FF7376',
                'style' => 'primary',
              ) ,
              1 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'LIHAT ATURAN',
                  'text' => $aturan,
                ) ,
              ) ,
            ) ,
          ) ,
        ) ,
        1 => array(
          'type' => 'bubble',
          'direction' => 'ltr',
          'header' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => $level,
                'align' => 'center',
                'weight' => 'bold',
              ) ,
            ) ,
          ) ,
          'hero' => array(
            'type' => 'image',
            'url' => $img,
            'size' => 'full',
            'aspectRatio' => '4:3',
            'aspectMode' => 'fit',
          ) ,
          'body' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => 'Bagian dua',
                'size' => 'md',
                'align' => 'center',
              ) ,
              1 => array(
                'type' => 'filler',
              ) ,
            ) ,
          ) ,
          'footer' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'MULAI',
                  'text' => $button2,
                ) ,
                'color' => '#FF7376',
                'style' => 'primary',
              ) ,
              1 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'LIHAT ATURAN',
                  'text' => $aturan,
                ) ,
              ) ,
            ) ,
          ) ,
        ) ,
        2 => array(
          'type' => 'bubble',
          'direction' => 'ltr',
          'header' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => $level,
                'align' => 'center',
                'weight' => 'bold',
              ) ,
            ) ,
          ) ,
          'hero' => array(
            'type' => 'image',
            'url' => $img,
            'size' => 'full',
            'aspectRatio' => '4:3',
            'aspectMode' => 'fit',
          ) ,
          'body' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => 'Bagian tiga',
                'size' => 'md',
                'align' => 'center',
              ) ,
              1 => array(
                'type' => 'filler',
              ) ,
            ) ,
          ) ,
          'footer' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'MULAI',
                  'text' => $button3,
                ) ,
                'color' => '#FF7376',
                'style' => 'primary',
              ) ,
              1 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'LIHAT ATURAN',
                  'text' => $aturan,
                ) ,
              ) ,
            ) ,
          ) ,
        ) ,
        3 => array(
          'type' => 'bubble',
          'direction' => 'ltr',
          'header' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => $level,
                'align' => 'center',
                'weight' => 'bold',
              ) ,
            ) ,
          ) ,
          'hero' => array(
            'type' => 'image',
            'url' => $img,
            'size' => 'full',
            'aspectRatio' => '4:3',
            'aspectMode' => 'fit',
          ) ,
          'body' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => 'Bagian empat',
                'size' => 'md',
                'align' => 'center',
              ) ,
              1 => array(
                'type' => 'filler',
              ) ,
            ) ,
          ) ,
          'footer' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'MULAI',
                  'text' => $button4,
                ) ,
                'color' => '#FF7376',
                'style' => 'primary',
              ) ,
              1 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'LIHAT ATURAN',
                  'text' => $aturan,
                ) ,
              ) ,
            ) ,
          ) ,
        ) ,
        4 => array(
          'type' => 'bubble',
          'direction' => 'ltr',
          'header' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => $level,
                'align' => 'center',
                'weight' => 'bold',
              ) ,
            ) ,
          ) ,
          'hero' => array(
            'type' => 'image',
            'url' => $img,
            'size' => 'full',
            'aspectRatio' => '4:3',
            'aspectMode' => 'fit',
          ) ,
          'body' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => 'Bagian lima',
                'size' => 'md',
                'align' => 'center',
              ) ,
              1 => array(
                'type' => 'filler',
              ) ,
            ) ,
          ) ,
          'footer' => array(
            'type' => 'box',
            'layout' => 'vertical',
            'spacing' => 'sm',
            'contents' => array(
              0 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'MULAI',
                  'text' => $button5,
                ) ,
                'color' => '#FF7376',
                'style' => 'primary',
              ) ,
              1 => array(
                'type' => 'button',
                'action' => array(
                  'type' => 'message',
                  'label' => 'LIHAT ATURAN',
                  'text' => $aturan,
                ) ,
              ) ,
            ) ,
          ) ,
        ) ,
      ) ,
    );
    return $item;
  }

  function latihan($nosoal, $pilsoal, $pilgan1, $pilgan2, $pilgan3, $pilgan4, $jaw1, $jaw2, $jaw3, $jaw4) {
    $item = array(
      'type' => 'bubble',
      'styles' => array(
        'footer' => array(
          'separator' => true,
        ) ,
      ) ,
      'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'spacing' => 'md',
        'contents' => array(
          0 => array(
            'type' => 'box',
            'layout' => 'vertical',
            'contents' => array(
              0 => array(
                'type' => 'text',
                'text' => '質問' . $nosoal,
                'align' => 'center',
                'size' => 'xxl',
                'weight' => 'bold',
              ) ,
              1 => array(
                'type' => 'text',
                'text' => $pilsoal,
                'wrap' => true,
                'weight' => 'bold',
                'margin' => 'lg',
              ) ,
            ) ,
          ) ,
          1 => array(
            'type' => 'separator',
          ) ,
          2 => array(
            'type' => 'box',
            'layout' => 'vertical',
            'margin' => 'lg',
            'contents' => array(
              0 => array(
                'type' => 'box',
                'layout' => 'baseline',
                'contents' => array(
                  0 => array(
                    'type' => 'text',
                    'text' => '1.',
                    'flex' => 1,
                    'size' => 'lg',
                    'weight' => 'bold',
                    'color' => '#666666',
                  ) ,
                  1 => array(
                    'type' => 'text',
                    'text' => $pilgan1,
                    'wrap' => true,
                    'flex' => 9,
                  ) ,
                ) ,
              ) ,
              1 => array(
                'type' => 'box',
                'layout' => 'baseline',
                'contents' => array(
                  0 => array(
                    'type' => 'text',
                    'text' => '2.',
                    'flex' => 1,
                    'size' => 'lg',
                    'weight' => 'bold',
                    'color' => '#666666',
                  ) ,
                  1 => array(
                    'type' => 'text',
                    'text' => $pilgan2,
                    'wrap' => true,
                    'flex' => 9,
                  ) ,
                ) ,
              ) ,
              2 => array(
                'type' => 'box',
                'layout' => 'baseline',
                'contents' => array(
                  0 => array(
                    'type' => 'text',
                    'text' => '3.',
                    'flex' => 1,
                    'size' => 'lg',
                    'weight' => 'bold',
                    'color' => '#666666',
                  ) ,
                  1 => array(
                    'type' => 'text',
                    'text' => $pilgan3,
                    'wrap' => true,
                    'flex' => 9,
                  ) ,
                ) ,
              ) ,
              3 => array(
                'type' => 'box',
                'layout' => 'baseline',
                'contents' => array(
                  0 => array(
                    'type' => 'text',
                    'text' => '4.',
                    'flex' => 1,
                    'size' => 'lg',
                    'weight' => 'bold',
                    'color' => '#666666',
                  ) ,
                  1 => array(
                    'type' => 'text',
                    'text' => $pilgan4,
                    'wrap' => true,
                    'flex' => 9,
                  ) ,
                ) ,
              ) ,
            ) ,
          ) ,
        ) ,
      ) ,
      'footer' => array(
        'type' => 'box',
        'layout' => 'horizontal',
        'spacing' => 'sm',
        'contents' => array(
          0 => array(
            'type' => 'button',
            'style' => 'primary',
            'color' => '#FF7376',
            'height' => 'sm',
            'action' => array(
              'type' => 'message',
              'label' => '1',
              'text' => $jaw1,
            ) ,
          ) ,
          1 => array(
            'type' => 'button',
            'style' => 'primary',
            'color' => '#FF7376',
            'height' => 'sm',
            'action' => array(
              'type' => 'message',
              'label' => '2',
              'text' => $jaw2,
            ) ,
          ) ,
          2 => array(
            'type' => 'button',
            'style' => 'primary',
            'color' => '#FF7376',
            'height' => 'sm',
            'action' => array(
              'type' => 'message',
              'label' => '3',
              'text' => $jaw3,
            ) ,
          ) ,
          3 => array(
            'type' => 'button',
            'style' => 'primary',
            'color' => '#FF7376',
            'height' => 'sm',
            'action' => array(
              'type' => 'message',
              'label' => '4',
              'text' => $jaw4,
            ) ,
          ) ,
        ) ,
      ) ,
      'styles' => array(
        'header' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
        'body' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
        'footer' => array(
          'backgroundColor' => '#E6EFEF',
        ) ,
      ) ,
    );
    return $item; //jangan lupa return variable item nya biar fungsi tadi menghasilkan nilai
    
  }

// function pushlb (){
// 	$array_nama=[];//array kosong
//     $array_skor=[];//array kosong
//     array_push($array_nama,$item);//masukin tiap item yang diambil ke array kosong
//     }
//     $soal=array (
//   'type' => 'text',
//   'text' => 'わたしはほんをよむ＿＿＿、おんがくを聞きます。',
//   'size' => 'md',
//   'align' => 'start',
//   'wrap' => true,
// );
// array_push($array_pilihan_soal,$soal); //pertama kali ngepush ke array kosong itu
// $separator=array (
//   'type' => 'separator',
//   'margin' => 'sm',
// );
// array_push($array_pilihan_soal,$separator); //pertama kali ngepush ke array kosong itu
//     foreach ($get_pilihan_soal as $g) {
//       $item2=array (
//   'type' => 'box',
//   'layout' => 'horizontal',
//   'margin' => 'lg',
//   'contents' => 
//   array (
//     0 => 
//     array (
//       'type' => 'text',
//       'text' => '1.',
//       'flex' => 1,
//       'weight' => 'bold',
//     ),
//     1 => 
//     array (
//       'type' => 'text',
//       'text' => 'ながら',
//       'flex' => 10,
//       'wrap' => true,
//     ),
//   ),
// );
// array_push($array_pilihan_soal,$item2);
//       // code...
//     }
// }



function leaderboard() { 
  $hasil = $this->Dbs->leaderboard()->result();
  $a = 1;
  $array_lb=[];//array kosong
  foreach ($hasil as $h) {

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
  $item = array (
    'type' => 'bubble',
    'direction' => 'ltr',
    'hero' => 
    array (
      'type' => 'image',
      'url' => 'https://developers.line.biz/assets/images/services/bot-designer-icon.png',
      'size' => 'full',
      'aspectRatio' => '1.51:1',
      'aspectMode' => 'fit',
    ),
    'body' => 
    array (
      'type' => 'box',
      'layout' => 'vertical',
      'contents' => 
      array (
        0 => 
        array (
          'type' => 'box',
          'layout' => 'baseline',
          'contents' => 
          array (
            0 => 
            array (
              'type' => 'text',
              'text' => '#',
              'flex' => 1,
              'weight' => 'bold',
            ),
            1 => 
            array (
              'type' => 'text',
              'text' => 'Nama',
              'flex' => 6,
              'weight' => 'bold',
            ),
            2 => 
            array (
              'type' => 'text',
              'text' => 'Score',
              'flex' => 3,
              'weight' => 'bold',
            ),
          ),
        ),
        1 => 
        array (
          'type' => 'separator',
          'margin' => 'sm',
        ),
        $item2
	                 
      ),
    ),
  );

  return $item; //jangan lupa return variable item nya biar fungsi tadi menghasilkan nilai
    
  }




}

