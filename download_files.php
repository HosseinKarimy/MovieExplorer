<?php
// تولید تصویر با متن دلخواه
function generateImage($text, $filePath)
{
    $width = 300;
    $height = 300;
    $image = imagecreatetruecolor($width, $height);

    // رنگ پس‌زمینه
    $backgroundColor = imagecolorallocate($image, 200, 200, 200);
    imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

    // متن
    $textColor = imagecolorallocate($image, 50, 50, 50);
    $fontSize = 5; // اندازه متن
    $x = ($width / 2) - (strlen($text) * imagefontwidth($fontSize) / 2);
    $y = ($height / 2) - (imagefontheight($fontSize) / 2);
    imagestring($image, $fontSize, $x, $y, $text, $textColor);

    // ذخیره تصویر
    imagejpeg($image, $filePath);
    imagedestroy($image);
}

// تولید تصاویر برای آدرس‌های داده‌شده
$images = [
    'assets/images/marlon_brando_1.jpg',
    'assets/images/marlon_brando_2.jpg',
    'assets/images/marlon_brando_3.jpg',
    'assets/images/marlon_brando_4.jpg',
    'assets/images/marlon_brando_5.jpg',
    'assets/images/al_pacino_1.jpg',
    'assets/images/al_pacino_2.jpg',
    'assets/images/al_pacino_3.jpg',
    'assets/images/al_pacino_4.jpg',
    'assets/images/al_pacino_5.jpg',
    'assets/images/james_caan_1.jpg',
    'assets/images/james_caan_2.jpg',
    'assets/images/james_caan_3.jpg',
    'assets/images/james_caan_4.jpg',
    'assets/images/james_caan_5.jpg',
    'assets/images/robert_duvall_1.jpg',
    'assets/images/robert_duvall_2.jpg',
    'assets/images/robert_duvall_3.jpg',
    'assets/images/robert_duvall_4.jpg',
    'assets/images/robert_duvall_5.jpg',
    'assets/images/diane_keaton_1.jpg',
    'assets/images/diane_keaton_2.jpg',
    'assets/images/diane_keaton_3.jpg',
    'assets/images/diane_keaton_4.jpg',
    'assets/images/diane_keaton_5.jpg'
];

foreach ($images as $imagePath) {
    generateImage($imagePath, $imagePath);
}
