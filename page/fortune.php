<?php
/*
|--------------------------------------------------------------------------
| CNTECH STORE - FORTUNE / ດູດວງ
|--------------------------------------------------------------------------
| File      : fortune.php
| Language  : Lao
| Theme     : Black / Red
| Database  : Not Required
|
| แนวคิด:
| - 12 ລາສີ ใช้สำหรับส่วนความเชื่อ/ความบันเทิง
| - หลักธรรมใช้เป็น "ข้อคิด" ไม่อ้างว่าเป็นคำทำนายจากพระไตรปิฎก
| - ประวัติศาสตร์/วัฒนธรรมลาวแยกจากคำทำนาย
|--------------------------------------------------------------------------
*/

error_reporting(0);
ini_set('display_errors', '0');

session_start();

const SITE_NAME = 'CNTECH STORE';
const SITE_URL  = 'https://cntechstore.shop';

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function laoNumber($number)
{
    return str_replace(
        ['0','1','2','3','4','5','6','7','8','9'],
        ['0','1','2','3','4','5','6','7','8','9'],
        (string)$number
    );
}

/*
|--------------------------------------------------------------------------
| MONTHS
|--------------------------------------------------------------------------
*/

$months = [
    1  => 'ມັງກອນ',
    2  => 'ກຸມພາ',
    3  => 'ມີນາ',
    4  => 'ເມສາ',
    5  => 'ພຶດສະພາ',
    6  => 'ມິຖຸນາ',
    7  => 'ກໍລະກົດ',
    8  => 'ສິງຫາ',
    9  => 'ກັນຍາ',
    10 => 'ຕຸລາ',
    11 => 'ພະຈິກ',
    12 => 'ທັນວາ'
];

/*
|--------------------------------------------------------------------------
| 12 ZODIACS
|--------------------------------------------------------------------------
*/

$zodiacs = [

    'aries' => [
        'name' => 'ລາສີເມດ',
        'symbol' => '♈',
        'date' => '21 ມີນາ - 19 ເມສາ',
        'element' => 'ໄຟ',
        'color' => 'ສີແດງ',
        'numbers' => '3 • 9 • 21',

        'love' =>
            'ຄວາມສຳພັນມີແນວໂນ້ມເຄື່ອນໄຫວ. ຄວນເວົ້າກັນຢ່າງຈິງໃຈ ແລະ ບໍ່ຕັດສິນຈາກອາລົມຊົ່ວຄາວ.',

        'work' =>
            'ເໝາະກັບການເລີ່ມວຽກ ຫຼື ໂຄງການໃໝ່. ຄວນວາງແຜນກ່ອນລົງມື.',

        'money' =>
            'ຄວນຈັດແບ່ງລາຍຮັບ ແລະ ລາຍຈ່າຍໃຫ້ຊັດເຈນ. ຫຼີກລ້ຽງການຊື້ຂອງຕາມອາລົມ.',

        'luck' =>
            'ໂອກາດອາດມາຈາກການລົງມືເຮັດ ແລະ ການພົບປະຄົນໃໝ່.'
    ],

    'taurus' => [
        'name' => 'ລາສີພຶດສົບ',
        'symbol' => '♉',
        'date' => '20 ເມສາ - 20 ພຶດສະພາ',
        'element' => 'ດິນ',
        'color' => 'ສີຂຽວ',
        'numbers' => '2 • 6 • 24',

        'love' =>
            'ຄວາມຮັກເນັ້ນຄວາມໝັ້ນຄົງ. ການຮັບຟັງກັນຈະຊ່ວຍໃຫ້ຄວາມສຳພັນດີຂຶ້ນ.',

        'work' =>
            'ຄວາມພະຍາຍາມແບບສະເໝີຈະໃຫ້ຜົນດີ. ຢ່າຮີບຮ້ອນປ່ຽນແຜນ.',

        'money' =>
            'ເໝາະກັບການເກັບອອມ ແລະ ວາງແຜນລະຍະຍາວ.',

        'luck' =>
            'ອາດໄດ້ຮັບໂອກາດຈາກຄົນທີ່ເຄີຍຮ່ວມງານ ຫຼື ຄົນຮູ້ຈັກ.'
    ],

    'gemini' => [
        'name' => 'ລາສີເມຖຸນ',
        'symbol' => '♊',
        'date' => '21 ພຶດສະພາ - 20 ມິຖຸນາ',
        'element' => 'ລົມ',
        'color' => 'ສີເຫຼືອງ',
        'numbers' => '5 • 14 • 23',

        'love' =>
            'ການສື່ສານເປັນສິ່ງສຳຄັນ. ເວົ້າໃນສິ່ງທີ່ຮູ້ສຶກຢ່າງສຸພາບ.',

        'work' =>
            'ວຽກທີ່ໃຊ້ຄວາມຄິດ ການສື່ສານ ແລະ ເຕັກໂນໂລຊີມີແນວໂນ້ມໂດດເດັ່ນ.',

        'money' =>
            'ອາດມີແນວທາງຫາລາຍໄດ້ເສີມ. ຄວນແຍກລາຍໄດ້ຈາກລາຍຈ່າຍ.',

        'luck' =>
            'ໂອກາດອາດມາຈາກການຕິດຕໍ່ ການຮຽນຮູ້ ແລະ ອອນລາຍ.'
    ],

    'cancer' => [
        'name' => 'ລາສີກໍລະກົດ',
        'symbol' => '♋',
        'date' => '21 ມິຖຸນາ - 22 ກໍລະກົດ',
        'element' => 'ນ້ຳ',
        'color' => 'ສີຂາວ',
        'numbers' => '2 • 7 • 22',

        'love' =>
            'ຄວາມເຂົ້າໃຈຈະຊ່ວຍຮັກສາຄວາມສຳພັນ. ຢ່າເກັບຄວາມກັງວົນໄວ້ຄົນດຽວ.',

        'work' =>
            'ການຮ່ວມມືຈະຊ່ວຍໃຫ້ວຽກເດີນໜ້າ.',

        'money' =>
            'ຄວນວາງແຜນລາຍຈ່າຍ ແລະ ສຳຮອງເງິນໄວ້.',

        'luck' =>
            'ໂຊກອາດມາຈາກຄອບຄົວ ຫຼື ຄົນໃກ້ຊິດ.'
    ],

    'leo' => [
        'name' => 'ລາສີສິງ',
        'symbol' => '♌',
        'date' => '23 ກໍລະກົດ - 22 ສິງຫາ',
        'element' => 'ໄຟ',
        'color' => 'ສີທອງ',
        'numbers' => '1 • 8 • 19',

        'love' =>
            'ສະເໜ່ຂອງທ່ານໂດດເດັ່ນ. ຄວນໃຫ້ກຽດຄວາມຄິດຂອງອີກຝ່າຍ.',

        'work' =>
            'ມີໂອກາດຮັບຜິດຊອບຫນ້າທີ່ສຳຄັນ. ການເປັນຜູ້ນຳຄວນມາພ້ອມກັບຄວາມຮັບຟັງ.',

        'money' =>
            'ລາຍໄດ້ອາດດີຂຶ້ນ ແຕ່ຄວນຄວບຄຸມລາຍຈ່າຍ.',

        'luck' =>
            'ໂອກາດອາດມາຈາກຄວາມກ້າ ແລະ ການລົງມືເຮັດ.'
    ],

    'virgo' => [
        'name' => 'ລາສີກັນ',
        'symbol' => '♍',
        'date' => '23 ສິງຫາ - 22 ກັນຍາ',
        'element' => 'ດິນ',
        'color' => 'ສີຟ້າ',
        'numbers' => '4 • 6 • 18',

        'love' =>
            'ຄວາມຮັກຈະດີຂຶ້ນເມື່ອຫຼຸດການຄິດຫຼາຍ ແລະ ເພີ່ມການສື່ສານ.',

        'work' =>
            'ຄວາມລະອຽດ ແລະ ວິໄນເປັນຈຸດແຂງ.',

        'money' =>
            'ເໝາະກັບການຈັດງົບ ແລະ ວາງແຜນການເງິນ.',

        'luck' =>
            'ໂຊກອາດມາຈາກການວາງແຜນຢ່າງຮອບຄອບ.'
    ],

    'libra' => [
        'name' => 'ລາສີຕຸນ',
        'symbol' => '♎',
        'date' => '23 ກັນຍາ - 22 ຕຸລາ',
        'element' => 'ລົມ',
        'color' => 'ສີບົວ',
        'numbers' => '6 • 15 • 24',

        'love' =>
            'ຄວາມສົມດຸນເປັນຫົວໃຈ. ຢ່າພະຍາຍາມເອົາໃຈທຸກຄົນຈົນລືມຄວາມຕ້ອງການຕົນເອງ.',

        'work' =>
            'ການເຈລະຈາ ແລະ ການຮ່ວມມືຈະຊ່ວຍເປີດໂອກາດ.',

        'money' =>
            'ຄວນຮັກສາສົມດຸນລະຫວ່າງການໃຊ້ຈ່າຍ ແລະ ການເກັບອອມ.',

        'luck' =>
            'ໂອກາດອາດມາຈາກມິດຕະພາບ ແລະ ການຮ່ວມມື.'
    ],

    'scorpio' => [
        'name' => 'ລາສີພິຈິກ',
        'symbol' => '♏',
        'date' => '23 ຕຸລາ - 21 ພະຈິກ',
        'element' => 'ນ້ຳ',
        'color' => 'ສີດຳ',
        'numbers' => '8 • 11 • 20',

        'love' =>
            'ຄວາມຈິງໃຈຈະຊ່ວຍໃຫ້ຄວາມສຳພັນແຂງແຮງ.',

        'work' =>
            'ເໝາະກັບວຽກທີ່ຕ້ອງວິເຄາະ ແລະ ໃຊ້ສະມາທິ.',

        'money' =>
            'ຄວນຫຼີກລ້ຽງການສ່ຽງເງິນໂດຍຂາດຂໍ້ມູນ.',

        'luck' =>
            'ອາດພົບໂອກາດຈາກສິ່ງທີ່ຄາດບໍ່ເຖິງ.'
    ],

    'sagittarius' => [
        'name' => 'ລາສີທະນູ',
        'symbol' => '♐',
        'date' => '22 ພະຈິກ - 21 ທັນວາ',
        'element' => 'ໄຟ',
        'color' => 'ສີມ່ວງ',
        'numbers' => '3 • 12 • 27',

        'love' =>
            'ຄວາມສົດໃໝ່ຈະຊ່ວຍໃຫ້ຄວາມຮັກມີສີສັນ. ຄວນໃຫ້ພື້ນທີ່ກັນ.',

        'work' =>
            'ການຮຽນຮູ້ ການເດີນທາງ ແລະ ໂລກອອນລາຍອາດເປີດໂອກາດ.',

        'money' =>
            'ມີແນວທາງຫາລາຍໄດ້ໃໝ່ ແຕ່ຄວນຄິດກ່ອນໃຊ້.',

        'luck' =>
            'ໂອກາດອາດມາຈາກການເດີນທາງ ຫຼື ຄົນຕ່າງຖິ່ນ.'
    ],

    'capricorn' => [
        'name' => 'ລາສີມັງກອນ',
        'symbol' => '♑',
        'date' => '22 ທັນວາ - 19 ມັງກອນ',
        'element' => 'ດິນ',
        'color' => 'ສີນ້ຳຕານ',
        'numbers' => '4 • 10 • 28',

        'love' =>
            'ຄວາມສຳພັນເນັ້ນຄວາມຈິງຈັງ. ການຮັກສາຄຳເວົ້າຈະສ້າງຄວາມໄວ້ວາງໃຈ.',

        'work' =>
            'ຄວາມອົດທົນ ແລະ ວິໄນຈະຊ່ວຍໃຫ້ບັນລຸເປົ້າໝາຍ.',

        'money' =>
            'ເໝາະກັບການວາງແຜນເງິນລະຍະຍາວ.',

        'luck' =>
            'ໂອກາດມັກມາຈາກຄວາມພະຍາຍາມ.'
    ],

    'aquarius' => [
        'name' => 'ລາສີກຸມ',
        'symbol' => '♒',
        'date' => '20 ມັງກອນ - 18 ກຸມພາ',
        'element' => 'ລົມ',
        'color' => 'ສີຟ້າ',
        'numbers' => '7 • 17 • 26',

        'love' =>
            'ຄວາມສຳພັນອາດເລີ່ມຈາກມິດຕະພາບ. ການເປີດໃຈຈະຊ່ວຍໃຫ້ສື່ສານກັນງ່າຍຂຶ້ນ.',

        'work' =>
            'ແນວຄິດໃໝ່ ແລະ ເຕັກໂນໂລຊີເປັນຈຸດແຂງ.',

        'money' =>
            'ລາຍໄດ້ອາດມາຈາກວຽກອອນລາຍ ຫຼື ວຽກເສີມ.',

        'luck' =>
            'ໂອກາດອາດມາຈາກເທັກໂນໂລຊີ ແລະ ແນວຄິດໃໝ່.'
    ],

    'pisces' => [
        'name' => 'ລາສີມີນ',
        'symbol' => '♓',
        'date' => '19 ກຸມພາ - 20 ມີນາ',
        'element' => 'ນ້ຳ',
        'color' => 'ສີຟ້າອ່ອນ',
        'numbers' => '3 • 9 • 18',

        'love' =>
            'ຄວາມອ່ອນໂຍນເປັນຈຸດແຂງ. ຄວນສື່ສານໃຫ້ຊັດເຈນ.',

        'work' =>
            'ຄວາມຄິດສ້າງສັນອາດເປັນຈຸດເດັ່ນ.',

        'money' =>
            'ຄວນແຍກເງິນສ່ວນຕົວ ແລະ ເງິນວຽກໃຫ້ຊັດເຈນ.',

        'luck' =>
            'ໂອກາດອາດມາຈາກສິລະປະ ຄວາມຄິດສ້າງສັນ ແລະ ມິດຕະພາບ.'
    ]
];

/*
|--------------------------------------------------------------------------
| ZODIAC CALCULATOR
|--------------------------------------------------------------------------
*/

function getZodiac($day, $month)
{
    if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
        return 'aries';
    }

    if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
        return 'taurus';
    }

    if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 20)) {
        return 'gemini';
    }

    if (($month == 6 && $day >= 21) || ($month == 7 && $day <= 22)) {
        return 'cancer';
    }

    if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
        return 'leo';
    }

    if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
        return 'virgo';
    }

    if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 22)) {
        return 'libra';
    }

    if (($month == 10 && $day >= 23) || ($month == 11 && $day <= 21)) {
        return 'scorpio';
    }

    if (($month == 11 && $day >= 22) || ($month == 12 && $day <= 21)) {
        return 'sagittarius';
    }

    if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
        return 'capricorn';
    }

    if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
        return 'aquarius';
    }

    return 'pisces';
}

/*
|--------------------------------------------------------------------------
| DAILY DHAMMA
|--------------------------------------------------------------------------
|
| ไม่อ้างว่าเป็น "คำทำนายจากพระไตรปิฎก"
| แต่ใช้เป็นข้อคิดเชิงธรรมะ
|--------------------------------------------------------------------------
*/

$dhamma = [

    [
        'title' => 'ສະຕິ',
        'icon' => 'fa-brain',
        'text' =>
            'ກ່ອນເຮັດສິ່ງໃດ ຄວນຮູ້ຕົວ ແລະ ພິຈາລະນາໃຫ້ຮອບຄອບ.'
    ],

    [
        'title' => 'ຄວາມພຽນ',
        'icon' => 'fa-person-running',
        'text' =>
            'ຄວາມສຳເລັດບໍ່ຄວນອາໄສການລໍຖ້າພຽງຢ່າງດຽວ. ຄວາມພຽນ ແລະ ການກະທຳທີ່ດີມີຄວາມສຳຄັນ.'
    ],

    [
        'title' => 'ຄຳເວົ້າ',
        'icon' => 'fa-comment',
        'text' =>
            'ຄຳເວົ້າທີ່ຈິງ ສຸພາບ ແລະ ເປັນປະໂຫຍດ ສາມາດສ້າງຄວາມໄວ້ວາງໃຈໄດ້.'
    ],

    [
        'title' => 'ຄວາມກະຕັນຍູ',
        'icon' => 'fa-heart',
        'text' =>
            'ຈົ່ງຮູ້ຄຸນຄົນທີ່ເຄີຍຊ່ວຍເຫຼືອ ແລະ ຕອບແທນດ້ວຍຄວາມດີ.'
    ],

    [
        'title' => 'ອາຊີບສຸຈະລິດ',
        'icon' => 'fa-briefcase',
        'text' =>
            'ການຫາລາຍໄດ້ຄວນຢູ່ເທິງຄວາມສຸຈະລິດ ແລະ ບໍ່ເບຽດບຽນຜູ້ອື່ນ.'
    ]
];

$dhammaToday =
    $dhamma[
        ((int)date('z')) % count($dhamma)
    ];

/*
|--------------------------------------------------------------------------
| LAO CULTURE / HISTORY
|--------------------------------------------------------------------------
*/

$culture = [

    [
        'title' => 'ປະຫວັດສາດລາວ',
        'text' =>
            'ການຮຽນຮູ້ປະຫວັດສາດຊ່ວຍໃຫ້ເຂົ້າໃຈຮາກເຫງົ້າ ແລະ ວັດທະນະທຳຂອງລາວ.'
    ],

    [
        'title' => 'ມໍລະດົກ',
        'text' =>
            'ຄວາມຮູ້ ພາສາ ປະເພນີ ແລະ ວັດທະນະທຳ ແມ່ນມໍລະດົກທີ່ຄວນຮັກສາ.'
    ],

    [
        'title' => 'ຕຳນານ',
        'text' =>
            'ຕຳນານລາວຄວນອ່ານໃນຖານະເລື່ອງເລົ່າ ແລະ ມໍລະດົກທາງວັດທະນະທຳ.'
    ]
];

$cultureToday =
    $culture[
        ((int)date('z')) % count($culture)
    ];

/*
|--------------------------------------------------------------------------
| INPUT
|--------------------------------------------------------------------------
*/

$day = (int)($_POST['day'] ?? 0);
$month = (int)($_POST['month'] ?? 0);
$year = (int)($_POST['year'] ?? 0);

$result = null;
$error = '';

/*
|--------------------------------------------------------------------------
| PROCESS
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $currentYear = (int)date('Y');

    if (
        $day < 1 ||
        $month < 1 ||
        $month > 12 ||
        $year < 1900 ||
        $year > $currentYear
    ) {

        $error =
            'ກະລຸນາກວດສອບວັນ ເດືອນ ແລະ ປີເກີດ.';

    } elseif (!checkdate($month, $day, $year)) {

        $error =
            'ວັນເດືອນປີເກີດບໍ່ຖືກຕ້ອງ.';

    } else {

        $zodiacKey = getZodiac(
            $day,
            $month
        );

        $result = $zodiacs[$zodiacKey];

        $result['key'] = $zodiacKey;

        $result['birth'] =
            $day .
            ' ' .
            $months[$month] .
            ' ' .
            $year;

        /*
        |--------------------------------------------------------------------------
        | PERSONAL DHAMMA
        |--------------------------------------------------------------------------
        */

        $personalIndex =
            ($day + $month + $year)
            % count($dhamma);

        $result['dhamma'] =
            $dhamma[$personalIndex];

        /*
        |--------------------------------------------------------------------------
        | PERSONAL CULTURE
        |--------------------------------------------------------------------------
        */

        $cultureIndex =
            ($day + $month)
            % count($culture);

        $result['culture'] =
            $culture[$cultureIndex];

        /*
        |--------------------------------------------------------------------------
        | SIMPLE SCORE
        |--------------------------------------------------------------------------
        |
        | เป็นคะแนนเพื่อความบันเทิงเท่านั้น
        |--------------------------------------------------------------------------
        */

        $base =
            ($day * 3) +
            ($month * 7) +
            ($year % 100);

        $result['scoreLove'] =
            ($base * 3) % 41 + 60;

        $result['scoreWork'] =
            ($base * 5) % 41 + 60;

        $result['scoreMoney'] =
            ($base * 7) % 41 + 60;

        $result['scoreLuck'] =
            ($base * 11) % 41 + 60;
    }
}

/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

$today = date('Y-m-d');

$pageTitle =
    'ດູດວງ 12 ລາສີ ຕາມວັນເກີດ | CNTECH STORE';

$pageDescription =
    'CNTECH STORE ດູດວງ 12 ລາສີ ຕາມວັນ ເດືອນ ປີເກີດ ພ້ອມຂໍ້ຄິດດ້ານຫຼັກທຳ ແລະ ວັດທະນະທຳລາວ.';

$canonical =
    SITE_URL . '/fortune.php';

?>
<!DOCTYPE html>
<html lang="lo">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"
>

<meta
    name="theme-color"
    content="#050505"
>

<meta
    name="description"
    content="<?=e($pageDescription)?>"
>

<meta
    name="robots"
    content="index,follow,max-image-preview:large"
>

<link
    rel="canonical"
    href="<?=e($canonical)?>"
>

<meta
    property="og:type"
    content="website"
>

<meta
    property="og:title"
    content="<?=e($pageTitle)?>"
>

<meta
    property="og:description"
    content="<?=e($pageDescription)?>"
>

<meta
    property="og:url"
    content="<?=e($canonical)?>"
>

<meta
    property="og:site_name"
    content="<?=e(SITE_NAME)?>"
>

<meta
    name="twitter:card"
    content="summary"
>

<meta
    name="twitter:title"
    content="<?=e($pageTitle)?>"
>

<meta
    name="twitter:description"
    content="<?=e($pageDescription)?>"
>

<title>
<?=e($pageTitle)?>
</title>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<style>

*{
    box-sizing:border-box;
    -webkit-tap-highlight-color:transparent;
}

html{
    scroll-behavior:smooth;
}

body{

    margin:0;

    min-height:100vh;

    background:
        radial-gradient(
            circle at 50% -10%,
            #360909 0,
            #120505 22%,
            #080808 55%,
            #050505 100%
        );

    color:#fff;

    font-family:
        Arial,
        "Noto Sans Lao",
        "Phetsarath OT",
        sans-serif;

    padding-bottom:80px;
}

a{
    color:inherit;
    text-decoration:none;
}

button,
select{
    font-family:inherit;
}

/*
|--------------------------------------------------------------------------
| NAVBAR
|--------------------------------------------------------------------------
*/

.navbar{

    position:sticky;

    top:0;

    z-index:1000;

    height:62px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 15px;

    background:
        rgba(5,5,5,.94);

    border-bottom:
        1px solid #292929;

    backdrop-filter:
        blur(18px);
}

.logo{

    font-size:20px;

    font-weight:900;

    letter-spacing:-.5px;
}

.logo span{
    color:#ff2020;
}

.nav-actions{

    display:flex;

    gap:8px;
}

.nav-btn{

    width:40px;
    height:40px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:11px;

    background:#151515;

    border:1px solid #262626;

    color:#fff;
}

.nav-btn:active{
    background:#e51b23;
}

/*
|--------------------------------------------------------------------------
| CONTAINER
|--------------------------------------------------------------------------
*/

.container{

    width:100%;

    max-width:760px;

    margin:auto;

    padding:
        0 13px 35px;
}

/*
|--------------------------------------------------------------------------
| HERO
|--------------------------------------------------------------------------
*/

.hero{

    text-align:center;

    padding:
        30px 5px 25px;
}

.hero-icon{

    width:82px;
    height:82px;

    margin:auto;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:50%;

    background:
        radial-gradient(
            circle,
            #451010,
            #160606
        );

    border:
        1px solid #742020;

    color:#ff3030;

    font-size:36px;

    box-shadow:
        0 0 40px
        rgba(255,32,32,.18);
}

.hero h1{

    margin:
        16px 0 8px;

    font-size:29px;

    font-weight:900;
}

.hero h1 span{
    color:#ff2020;
}

.hero p{

    margin:0;

    color:#999;

    font-size:13px;

    line-height:1.8;
}

/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.card{

    background:
        linear-gradient(
            145deg,
            #151515,
            #0c0c0c
        );

    border:
        1px solid #292929;

    border-radius:21px;

    padding:18px;

    box-shadow:
        0 20px 50px
        rgba(0,0,0,.35);
}

.card-title{

    display:flex;

    align-items:center;

    gap:10px;

    margin-bottom:17px;

    font-size:15px;

    font-weight:900;
}

.card-title i{
    color:#ff2020;
}

/*
|--------------------------------------------------------------------------
| BIRTH INPUT
|--------------------------------------------------------------------------
*/

.birth-grid{

    display:grid;

    grid-template-columns:
        1fr 1.35fr 1.2fr;

    gap:8px;
}

.field label{

    display:block;

    margin-bottom:6px;

    color:#888;

    font-size:11px;
}

.field select{

    width:100%;

    height:48px;

    padding:
        0 9px;

    border:
        1px solid #303030;

    border-radius:11px;

    outline:none;

    background:#080808;

    color:#fff;

    font-size:13px;
}

.field select:focus{
    border-color:#e51b23;
}

/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/
.submit{

    width:100%;

    height:52px;

    margin-top:14px;

    border:0;

    border-radius:12px;

    background:
        linear-gradient(
            135deg,
            #d9151d,
            #ff3030
        );

    color:#fff;

    font-size:14px;

    font-weight:900;

    box-shadow:
        0 10px 25px
        rgba(229,27,35,.18);
}

.submit:active{
    transform:scale(.98);
}

/*
|--------------------------------------------------------------------------
| ERROR
|--------------------------------------------------------------------------
*/

.error{

    margin-top:13px;

    padding:12px;

    text-align:center;

    background:#281010;

    border:
        1px solid #632020;

    border-radius:11px;

    color:#ff7272;

    font-size:12px;
}

/*
|--------------------------------------------------------------------------
| RESULT
|--------------------------------------------------------------------------
*/

.result{

    margin-top:18px;

    animation:
        resultIn .4s ease;
}

@keyframes resultIn{

    from{
        opacity:0;
        transform:translateY(12px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

/*
|--------------------------------------------------------------------------
| ZODIAC HERO
|--------------------------------------------------------------------------
*/

.zodiac{

    position:relative;

    overflow:hidden;

    text-align:center;

    padding:28px 15px;

    border-radius:21px;

    border:
        1px solid #682020;

    background:
        radial-gradient(
            circle at center,
            #3b0d0d,
            #121212 65%
        );

    box-shadow:
        0 18px 45px
        rgba(255,32,32,.10);
}

.zodiac-symbol{

    font-size:70px;

    line-height:1;

    margin-bottom:12px;

    filter:
        drop-shadow(
            0 0 15px
            rgba(255,255,255,.08)
        );
}

.zodiac h2{

    margin:0;

    font-size:24px;

    font-weight:900;
}

.zodiac-date{

    margin-top:7px;

    color:#aaa;

    font-size:12px;
}

.zodiac-birth{

    margin-top:12px;

    color:#ff5a5a;

    font-size:12px;
}

.zodiac-element{

    display:inline-flex;

    margin-top:10px;

    padding:5px 10px;

    border-radius:20px;

    background:#1d1d1d;

    color:#ccc;

    font-size:11px;
}

/*
|--------------------------------------------------------------------------
| SCORE
|--------------------------------------------------------------------------
*/

.score-grid{

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:8px;

    margin-top:12px;
}

.score{

    padding:13px 5px;

    text-align:center;

    background:#111;

    border:
        1px solid #292929;

    border-radius:14px;
}

.score i{

    display:block;

    margin-bottom:7px;

    color:#ff3030;

    font-size:16px;
}

.score-label{

    color:#777;

    font-size:10px;
}

.score-value{

    margin-top:5px;

    color:#fff;

    font-size:17px;

    font-weight:900;
}

/*
|--------------------------------------------------------------------------
| FORTUNE
|--------------------------------------------------------------------------
*/

.fortune-grid{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:10px;

    margin-top:12px;
}

.fortune-box{

    padding:16px;

    min-height:160px;

    border:
        1px solid #292929;

    border-radius:16px;

    background:#111;
}

.fortune-icon{

    width:39px;
    height:39px;

    display:flex;

    align-items:center;
    justify-content:center;

    margin-bottom:10px;

    border-radius:10px;

    background:#251010;

    color:#ff3030;
}

.fortune-box h3{

    margin:
        0 0 7px;

    font-size:14px;
}

.fortune-box p{

    margin:0;

    color:#999;

    font-size:12px;

    line-height:1.85;
}

/*
|--------------------------------------------------------------------------
| LUCK
|--------------------------------------------------------------------------
*/

.luck-grid{

    display:grid;

    grid-template-columns:
        1fr 1fr;

    gap:10px;

    margin-top:10px;
}

.luck-box{

    padding:16px;

    text-align:center;

    background:#111;

    border:
        1px solid #292929;

    border-radius:16px;
}

.luck-box i{

    color:#ff3030;

    font-size:18px;

    margin-bottom:7px;
}

.luck-label{

    color:#777;

    font-size:10px;
}

.luck-value{

    margin-top:5px;

    font-size:14px;

    font-weight:900;
}

/*
|--------------------------------------------------------------------------
| DHAMMA
|--------------------------------------------------------------------------
*/

.section-card{

    margin-top:12px;

    padding:17px;

    background:
        linear-gradient(
            145deg,
            #141414,
            #0e0e0e
        );

    border:
        1px solid #292929;

    border-radius:17px;
}

.section-head{

    display:flex;

    align-items:center;

    gap:9px;

    margin-bottom:12px;

    font-size:14px;

    font-weight:900;
}

.section-head i{
    color:#ff2020;
}

.section-text{

    color:#aaa;

    font-size:12px;

    line-height:1.9;
}

/*
|--------------------------------------------------------------------------
| SOURCES
|--------------------------------------------------------------------------
*/

.source-card{

    margin-top:12px;

    padding:15px;

    border-radius:15px;

    background:#0d0d0d;

    border:
        1px solid #252525;

    color:#777;

    font-size:10px;

    line-height:1.8;
}

.source-card strong{
    color:#aaa;
}

/*
|--------------------------------------------------------------------------
| NOTICE
|--------------------------------------------------------------------------
*/

.notice{

    margin-top:12px;

    padding:14px;

    border-radius:13px;

    background:#111;

    border:
        1px solid #282828;

    text-align:center;

    color:#666;

    font-size:10px;

    line-height:1.8;
}

/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.footer{

    margin-top:20px;

    padding:
        25px 15px 35px;

    text-align:center;

    background:#060606;

    border-top:
        1px solid #222;

    color:#666;

    font-size:10px;

    line-height:1.8;
}

.footer strong{

    display:block;

    color:#fff;

    font-size:15px;

    margin-bottom:4px;
}

.footer strong span{
    color:#ff2020;
}

.footer-social{

    display:flex;

    justify-content:center;

    gap:8px;

    margin:12px 0;
}

.footer-social a{

    width:38px;
    height:38px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:10px;

    background:#151515;

    border:
        1px solid #252525;

    color:#aaa;
}

.footer-social a:active{

    background:#e51b23;

    color:#fff;
}

/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media(max-width:600px){

    .navbar{
        height:58px;
    }

    .container{
        padding-left:10px;
        padding-right:10px;
    }

    .hero{
        padding-top:23px;
    }

    .hero-icon{
        width:70px;
        height:70px;
        font-size:30px;
    }

    .hero h1{
        font-size:23px;
    }

    .hero p{
        font-size:12px;
    }

    .card{
        padding:14px;
        border-radius:17px;
    }

    .birth-grid{
        grid-template-columns:
            1fr 1.25fr 1.1fr;
        gap:6px;
    }

    .field select{
        height:46px;
        font-size:12px;
    }

    .score-grid{
        grid-template-columns:
            repeat(2,1fr);
    }

    .fortune-grid{
        grid-template-columns:1fr;
    }

    .fortune-box{
        min-height:auto;
    }

}

@media(max-width:380px){

    .birth-grid{
        grid-template-columns:1fr;
    }

    .field select{
        height:48px;
    }

    .zodiac-symbol{
        font-size:58px;
    }

    .zodiac h2{
        font-size:21px;
    }

}

/*
|--------------------------------------------------------------------------
| REDUCE MOTION
|--------------------------------------------------------------------------
*/

@media(prefers-reduced-motion:reduce){

    *,
    *::before,
    *::after{

        animation-duration:.01ms !important;

        animation-iteration-count:1 !important;

        transition-duration:.01ms !important;

        scroll-behavior:auto !important;
    }
}

</style>

</head>

<body>

<!--
|--------------------------------------------------------------------------
| NAVBAR
|--------------------------------------------------------------------------
-->

<nav class="navbar">

    <a
        href="<?=e(SITE_URL)?>"
        class="logo"
    >
        CN<span>TECH</span>
    </a>

    <div class="nav-actions">

        <a
            href="<?=e(SITE_URL)?>"
            class="nav-btn"
            aria-label="Home"
        >
            <i class="fa-solid fa-house"></i>
        </a>

    </div>

</nav>


<main class="container">

<!--
|--------------------------------------------------------------------------
| HERO
|--------------------------------------------------------------------------
-->

<section class="hero">

    <div class="hero-icon">

        <i class="fa-solid fa-star-and-crescent"></i>

    </div>

    <h1>
        🔮 CN<span>TECH</span> ດູດວງ
    </h1>

    <p>
        ເບິ່ງລາສີຈາກວັນ ເດືອນ ປີເກີດ<br>
        ພ້ອມຂໍ້ຄິດດ້ານຫຼັກທຳ ແລະ ວັດທະນະທຳລາວ
    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
-->

<section class="card">

    <div class="card-title">

        <i class="fa-solid fa-calendar-days"></i>

        <span>
            ໃສ່ວັນເກີດຂອງທ່ານ
        </span>

    </div>

    <form
        method="POST"
        action=""
        autocomplete="off"
    >

        <div class="birth-grid">

            <div class="field">

                <label>
                    ວັນ
                </label>

                <select
                    name="day"
                    required
                >

                    <option value="">
                        ວັນ
                    </option>

                    <?php for($i=1;$i<=31;$i++): ?>

                    <option
                        value="<?=$i?>"
                        <?=$day === $i ? 'selected' : ''?>
                    >
                        <?=laoNumber($i)?>
                    </option>

                    <?php endfor; ?>

                </select>

            </div>


            <div class="field">

                <label>
                    ເດືອນ
                </label>

                <select
                    name="month"
                    required
                >

                    <option value="">
                        ເດືອນ
                    </option>

                    <?php foreach($months as $m => $monthName): ?>

                    <option
                        value="<?=$m?>"
                        <?=$month === $m ? 'selected' : ''?>
                    >
                        <?=e($monthName)?>
                    </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="field">

                <label>
                    ປີ
                </label>

                <select
                    name="year"
                    required
                >

                    <option value="">
                        ປີເກີດ
                    </option>

                    <?php

                    $currentYear =
                        (int)date('Y');

                    for(
                        $y = $currentYear;
                        $y >= 1900;
                        $y--
                    ):

                    ?>

                    <option
                        value="<?=$y?>"
                        <?=$year === $y ? 'selected' : ''?>
                    >
                        <?=laoNumber($y)?>
                    </option>

                    <?php endfor; ?>

                </select>

            </div>

        </div>


        <button
            type="submit"
            class="submit"
        >

            <i class="fa-solid fa-wand-magic-sparkles"></i>

            ເບິ່ງດວງຂອງຂ້ອຍ

        </button>

    </form>


    <?php if($error): ?>

    <div class="error">

        <i class="fa-solid fa-circle-exclamation"></i>

        <?=e($error)?>

    </div>

    <?php endif; ?>

</section>


<?php if($result): ?>

<!--
|--------------------------------------------------------------------------
| RESULT
|--------------------------------------------------------------------------
-->

<section
    class="result"
    id="fortune-result"
>

    <div class="zodiac">

        <div class="zodiac-symbol">
            <?=e($result['symbol'])?>
        </div>

        <h2>
            <?=e($result['name'])?>
        </h2>

        <div class="zodiac-date">
            <?=e($result['date'])?>
        </div>

        <div class="zodiac-element">

            <i
                class="fa-solid fa-fire-flame-curved"
                style="margin-right:6px"
            ></i>

            ທາດ:
            <?=e($result['element'])?>

        </div>

        <div class="zodiac-birth">

            <i class="fa-solid fa-cake-candles"></i>

            <?=e($result['birth'])?>

        </div>

    </div>


    <!-- SCORE -->

    <div class="score-grid">

        <div class="score">

            <i class="fa-solid fa-heart"></i>

            <div class="score-label">
                ຄວາມຮັກ
            </div>

            <div class="score-value">
                <?=e($result['scoreLove'])?>%
            </div>

        </div>


        <div class="score">

            <i class="fa-solid fa-briefcase"></i>

            <div class="score-label">
                ການງານ
            </div>

            <div class="score-value">
                <?=e($result['scoreWork'])?>%
            </div>

        </div>


        <div class="score">

            <i class="fa-solid fa-coins"></i>

            <div class="score-label">
                ການເງິນ
            </div>

            <div class="score-value">
                <?=e($result['scoreMoney'])?>%
            </div>

        </div>


        <div class="score">

            <i class="fa-solid fa-clover"></i>

            <div class="score-label">
                ໂຊກລາບ
            </div>

            <div class="score-value">
                <?=e($result['scoreLuck'])?>%
            </div>

        </div>

    </div>


    <!-- FORTUNE -->

    <div class="fortune-grid">

        <div class="fortune-box">

            <div class="fortune-icon">

                <i class="fa-solid fa-heart"></i>

            </div>

            <h3>
                ❤️ ຄວາມຮັກ
            </h3>

            <p>
                <?=e($result['love'])?>
            </p>

        </div>


        <div class="fortune-box">

            <div class="fortune-icon">

                <i class="fa-solid fa-briefcase"></i>

            </div>

            <h3>
                💼 ການງານ
            </h3>

            <p>
                <?=e($result['work'])?>
            </p>

        </div>


        <div class="fortune-box">

            <div class="fortune-icon">

                <i class="fa-solid fa-coins"></i>

            </div>

            <h3>
                💰 ການເງິນ
            </h3>

            <p>
                <?=e($result['money'])?>
            </p>

        </div>


        <div class="fortune-box">

            <div class="fortune-icon">

                <i class="fa-solid fa-clover"></i>

            </div>

            <h3>
                🍀 ໂຊກລາບ
            </h3>

            <p>
                <?=e($result['luck'])?>
            </p>

        </div>

    </div>


    <!-- LUCK -->

    <div class="luck-grid">

        <div class="luck-box">

            <i class="fa-solid fa-hashtag"></i>

            <div class="luck-label">
                ເລກທີ່ເຊື່ອວ່ານຳໂຊກ
            </div>

            <div class="luck-value">
                <?=e($result['numbers'])?>
            </div>

        </div>


        <div class="luck-box">

            <i class="fa-solid fa-palette"></i>

            <div class="luck-label">
                ສີທີ່ເໝາະ
            </div>

            <div class="luck-value">
                <?=e($result['color'])?>
            </div>

        </div>

    </div>


    <!-- DHAMMA -->

    <div class="section-card">

        <div class="section-head">

            <i
                class="fa-solid
                <?=e($result['dhamma']['icon'])?>"
            ></i>

            <span>
                🧘 ຂໍ້ຄິດດ້ານຫຼັກທຳ
            </span>

        </div>

        <div class="section-text">

            <strong
                style="color:#fff"
            >
                <?=e($result['dhamma']['title'])?>
            </strong>

            <br>

            <?=e($result['dhamma']['text'])?>

        </div>

    </div>


    <!-- CULTURE -->

    <div class="section-card">

        <div class="section-head">

            <i class="fa-solid fa-landmark"></i>

            <span>
                📜 ວັດທະນະທຳ ແລະ ປະຫວັດສາດລາວ
            </span>

        </div>

        <div class="section-text">

            <strong
                style="color:#fff"
            >
                <?=e($result['culture']['title'])?>
            </strong>

            <br>

            <?=e($result['culture']['text'])?>

        </div>

    </div>


    <!-- TODAY -->

    <div class="section-card">

        <div class="section-head">

            <i class="fa-solid fa-sun"></i>

            <span>
                🌅 ຂໍ້ຄິດປະຈຳວັນ
            </span>

        </div>

        <div class="section-text">

            <strong
                style="color:#fff"
            >
                <?=e($dhammaToday['title'])?>
            </strong>

            <br>

            <?=e($dhammaToday['text'])?>

        </div>

    </div>


    <!-- SOURCE -->

    <div class="source-card">

        <strong>
            📚 ແຫຼ່ງອ້າງອີງ
        </strong>

        <br><br>

        ສ່ວນຫຼັກທຳໃນໜ້ານີ້ເປັນຂໍ້ຄິດທົ່ວໄປ
        ຈາກແນວຄິດທາງພຸດທະສາສະໜາ
        ແລະ ບໍ່ໄດ້ອ້າງວ່າເປັນຄຳທຳນາຍຈາກພຣະໄຕຣປິດົກ.

        <br><br>

        ສ່ວນປະຫວັດສາດ ແລະ ຕຳນານລາວ
        ຄວນແຍກອອກຈາກຄຳທຳນາຍ
        ແລະ ພິຈາລະນາຕາມບໍລິບົດຂອງແຕ່ລະແຫຼ່ງ.

    </div>

  <!-- NOTICE -->

    <div class="notice">

        <i class="fa-solid fa-circle-info"></i>

        ລະບົບນີ້ເປັນການດູດວງ
        ແລະ ເນື້ອຫາເພື່ອຄວາມບັນເທີງ
        ແລະ ໃຫ້ຂໍ້ຄິດ.

        <br>

        ບໍ່ຄວນໃຊ້ຜົນການດູດວງ
        ເປັນຫຼັກຕັດສິນໃຈດ້ານການເງິນ
        ສຸຂະພາບ ຫຼື ເລື່ອງສຳຄັນໃນຊີວິດ.

    </div>

</section>

<?php endif; ?>

</main>


<!--
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
-->

<footer class="footer">

    <strong>
        CN<span>TECH</span> STORE
    </strong>

    <div>
        Computer • Mobile • Parts & Accessories
    </div>

    <div class="footer-social">

        <a
            href="<?=e(SITE_URL)?>"
            aria-label="Website"
        >
            <i class="fa-solid fa-globe"></i>
        </a>

        <a
            href="https://www.facebook.com/share/1EN43DD2jz/"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Facebook"
        >
            <i class="fa-brands fa-facebook-f"></i>
        </a>

    </div>

    <div>
        © <?=date('Y')?> CNTECH STORE
    </div>

</footer>


<?php if($result): ?>

<script>

/*
|--------------------------------------------------------------------------
| AUTO SCROLL TO RESULT
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    function(){

        const result =
            document.getElementById(
                "fortune-result"
            );

        if(result){

            setTimeout(
                function(){

                    result.scrollIntoView({
                        behavior:"smooth",
                        block:"start"
                    });

                },
                150
            );

        }

    }
);

</script>

<?php endif; ?>

</body>
</html>