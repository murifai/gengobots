<?php
/**
* Class and Function List:
* Function list:
* - reply()
* - text()
* - sticker()
* - audio()
* - flex()
* - image()
* - video()
* - location()
* - imagemap()
* - imagemapurl()
* - imagemaptext()
* - imagemapmenu()
* - imagemapbahasa()
* - imagemapmore()
* - imagemapn()
* - buttonMessage()
* - confirmMessage()
* - carouselMessage()
* - itemCarousel()
* - carouselImage()
* - itemImage()
* - quickreply()
* Classes list:
* - MessageBuilder
*/
class MessageBuilder
{
    //digunakan untuk membalas pesan dengan berbagai type
    //KostLab
    public function reply($replyToken, $array) //Fungsi utama
    {
        $reply = array(
            'replyToken' => $replyToken,
            'messages' => $array
        );
        return $reply;
    }
    public function text($string)
    {
        $typeMessage = array(
            'type' => 'text',
            'text' => $string
        );
        return $typeMessage;
    }
    public function sticker($string)
    {
        $typeMessage = array(
            'type' => 'sticker',
            'stickerid' => $string
        );
        return $typeMessage;
    }
    public function audio($url)
    {
        $typeMessage = array(
            'type' => 'audio',
            'originalContentUrl' => $url,
            'duration' => 240000
        );
        return $typeMessage;
    }
    public function flex($alt, $content)
    {
        $typeMessage = array(
            'type' => 'flex',
            'altText' => $alt,
            'contents' => $content
        );
        return $typeMessage;
    }
    public function image($url)
    {
        $typeMessage = array(
            'type' => 'image',
            'originalContentUrl' => $url,
            'previewImageUrl' => $url
        );
        return $typeMessage;
    }
    public function video($urlVideo, $urlImage)
    {
        $typeMessage = array(
            'type' => 'video',
            'originalContentUrl' => $urlVideo,
            'previewImageUrl' => $urlImage
        );
        return $typeMessage;
    }
    public function location($title, $address, $latitude, $longitude)
    {
        $typeMessage = array(
            'type' => 'location',
            'title' => $title,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude
        );
        return $typeMessage;
    }
    public function imagemap($baseUrl, $altText)
    {
        $typeMessage = array(
            'type' => 'imagemap',
            'baseUrl' => $baseUrl,
            'altText' => $altText,
            'baseSize' => array(
                'height' => 1040,
                'width' => 1040
            ),
            'actions' => array(
                0 => array(
                    'type' => 'message',
                    'text' => '-',
                    'area' => array(
                        'x' => 0,
                        'y' => 0,
                        'width' => 1,
                        'height' => 1
                    )
                )
            )
        );
        return $typeMessage;
    }
    public function imagemapurl($baseUrl, $altText, $url)
    {
        $typeMessage = array(
            'type' => 'imagemap',
            'baseUrl' => $baseUrl,
            'altText' => $altText,
            'baseSize' => array(
                'height' => 1040,
                'width' => 1040
            ),
            'actions' => array(
                0 => array(
                    'type' => 'uri',
                    'linkUri' => $url,
                    'area' => array(
                        'x' => 0,
                        'y' => 0,
                        'width' => 1040,
                        'height' => 1040
                    )
                )
            )
        );
        return $typeMessage;
    }
    public function imagemaptext($baseUrl, $altText, $text)
    {
        $typeMessage = array(
            'type' => 'imagemap',
            'baseUrl' => $baseUrl,
            'altText' => $altText,
            'baseSize' => array(
                'height' => 1040,
                'width' => 1040
            ),
            'actions' => array(
                0 => array(
                    'type' => 'message',
                    'text' => $text,
                    'area' => array(
                        'x' => 0,
                        'y' => 0,
                        'width' => 1040,
                        'height' => 1040
                    )
                )
            )
        );
        return $typeMessage;
    }
    public function imagemapmenu($baseUrl, $altText, $text2, $text3, $text4, $text5, $text6)
    {
        $typeMessage = array(
            'type' => 'imagemap',
            'baseUrl' => $baseUrl,
            'altText' => $altText,
            'baseSize' => array(
                'height' => 698,
                'width' => 1040
            ),
            'actions' => array(
                0 => array(
                    'type' => 'message',
                    'text' => $text2,
                    'area' => array(
                        'x' => 7,
                        'y' => 7,
                        'width' => 509,
                        'height' => 430
                    )
                ),
                1 => array(
                    'type' => 'message',
                    'text' => $text3,
                    'area' => array(
                        'x' => 525,
                        'y' => 7,
                        'width' => 509,
                        'height' => 430
                    )
                ),
                2 => array(
                    'type' => 'message',
                    'text' => $text4,
                    'area' => array(
                        'x' => 7,
                        'y' => 356,
                        'width' => 335,
                        'height' => 335
                    )
                ),
                3 => array(
                    'type' => 'message',
                    'text' => $text5,
                    'area' => array(
                        'x' => 349,
                        'y' => 356,
                        'width' => 335,
                        'height' => 335
                    )
                ),
                4 => array(
                    'type' => 'message',
                    'text' => $text6,
                    'area' => array(
                        'x' => 703,
                        'y' => 356,
                        'width' => 335,
                        'height' => 335
                    )
                )
            )
        );
        return $typeMessage;
    }
    public function imagemapbahasa($baseUrl, $altText, $text, $text2, $text3)
    {
        $typeMessage = array(
            'type' => 'imagemap',
            'baseUrl' => $baseUrl,
            'altText' => $altText,
            'baseSize' => array(
                'height' => 520,
                'width' => 1040
            ),
            'actions' => array(
                0 => array(
                    'type' => 'message',
                    'text' => $text,
                    'area' => array(
                        'x' => 25,
                        'y' => 105,
                        'width' => 331,
                        'height' => 331
                    )
                ),
                1 => array(
                    'type' => 'message',
                    'text' => $text2,
                    'area' => array(
                        'x' => 356,
                        'y' => 102,
                        'width' => 331,
                        'height' => 331
                    )
                ),
                2 => array(
                    'type' => 'message',
                    'text' => $text3,
                    'area' => array(
                        'x' => 667,
                        'y' => 106,
                        'width' => 331,
                        'height' => 331
                    )
                )
            )
        );
        return $typeMessage;
    }
    public function imagemapmore($baseUrl, $altText, $text, $text2, $text3)
    {
        $typeMessage = array(
            'type' => 'imagemap',
            'baseUrl' => $baseUrl,
            'altText' => $altText,
            'baseSize' => array(
                'height' => 697,
                'width' => 1040
            ),
            'actions' => array(
                0 => array(
                    'type' => 'message',
                    'text' => $text,
                    'area' => array(
                        'x' => 61,
                        'y' => 61,
                        'width' => 286,
                        'height' => 593
                    )
                ),
                1 => array(
                    'type' => 'message',
                    'text' => $text2,
                    'area' => array(
                        'x' => 353,
                        'y' => 151,
                        'width' => 315,
                        'height' => 504
                    )
                ),
                2 => array(
                    'type' => 'message',
                    'text' => $text3,
                    'area' => array(
                        'x' => 683,
                        'y' => 64,
                        'width' => 319,
                        'height' => 588
                    )
                )
            )
        );
        return $typeMessage;
    }
    public function imagemapn($baseUrl, $altText, $text, $text2)
    {
        $typeMessage = array(
            'type' => 'imagemap',
            'baseUrl' => $baseUrl,
            'altText' => $altText,
            'baseSize' => array(
                'height' => 700,
                'width' => 1040
            ),
            'actions' => array(
                0 => array(
                    'type' => 'message',
                    'text' => $text,
                    'area' => array(
                        'x' => 33,
                        'y' => 111,
                        'width' => 491,
                        'height' => 491
                    )
                ),
                1 => array(
                    'type' => 'message',
                    'text' => $text2,
                    'area' => array(
                        'x' => 528,
                        'y' => 111,
                        'width' => 491,
                        'height' => 491
                    )
                )
            )
        );
        return $typeMessage;
    }
    ///Template Message nya LINE
    public function buttonMessage($imageUrl, $altText, $title, $text, $action)
    {
        $typeMessage = array(
            'type' => 'template',
            'altText' => $altText,
            'template' => array(
                'type' => 'buttons',
                'thumbnailImageUrl' => $imageUrl,
                // 'imageAspectRatio' => 'rectangle',
                // 'imageSize' => 'cover',
                // 'imageBackgroundColor' => '#FFFFFF',
                'title' => $title,
                'text' => $text,
                'actions' => $action
            )
        );
        return $typeMessage;
    }
    public function confirmMessage($altText, $text, $action)
    {
        $typeMessage = array(
            'type' => 'template',
            'altText' => $altText,
            'template' => array(
                'type' => 'confirm',
                'text' => $text,
                'actions' => $action
            )
        );
        return $typeMessage;
    }
    //Carousel Message
    public function carouselMessage($altText, $columns)
    {
        $typeMessage = array(
            'type' => 'template',
            'altText' => $altText,
            'template' => array(
                'type' => 'carousel',
                'columns' => $columns
                // 'imageAspectRatio' => 'rectangle',
                // 'imageSize' => 'cover',
            )
        );
        return $typeMessage;
    }
    public function itemCarousel($imgUrl, $title, $text, $buttons)
    {
        $item = array(
            'thumbnailImageUrl' => $imgUrl,
            'title' => $title,
            'text' => $text,
            'actions' => $buttons
        );
        return $item;
    }
    //Carousel Message End
    //Image Carousel
    public function carouselImage($altText, $columns)
    {
        $typeMessage = array(
            'type' => 'template',
            'altText' => $altText,
            'template' => array(
                'type' => 'image_carousel',
                'columns' => $columns
            )
        );
        return $typeMessage;
    }
    public function itemImage($imgUrl, $button)
    {
        $item = array(
            'imageUrl' => $imgUrl,
            'action' => $button
        );
        return $item;
    }
    //Image Carousel END
    // QuickReply
    public function quickreply()
    {
        $item = array(
            'type' => 'text',
            'text' => 'Select your favorite food category or send me your location!',
            'quickReply' => array(
                'items' => array(
                    0 => array(
                        'type' => 'action',
                        'imageUrl' => 'https://example.com/sushi.png',
                        'action' => array(
                            'type' => 'message',
                            'label' => 'Sushi',
                            'text' => 'Sushi'
                        )
                    ),
                    1 => array(
                        'type' => 'action',
                        'imageUrl' => 'https://example.com/tempura.png',
                        'action' => array(
                            'type' => 'message',
                            'label' => 'Tempura',
                            'text' => 'Tempura'
                        )
                    ),
                    2 => array(
                        'type' => 'action',
                        'action' => array(
                            'type' => 'location',
                            'label' => 'Send location'
                        )
                    )
                )
            )
        );
        return $item;
    }
}
