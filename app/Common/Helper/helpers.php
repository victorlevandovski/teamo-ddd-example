<?php

//function appLocales()
//{
//    return ['en', 'ru'];
//}

function date_ui(DateTimeImmutable $date)
{
    $time = $date->getTimestamp();
    $difference = time() - $time;

    if ($difference < 60) {
        return trans('date.moment_ago');
    } elseif ($difference < 120) {
        return trans('date.minute_ago');
    } elseif ($difference < 3600) {
        $t = floor($difference / 60);
        return  $t.' '.pluralize_ui($t, 'date.minute').' '.trans('date.ago');
    } elseif ($difference < 7200) {
        return trans('date.hour_ago');
    } elseif ($difference < 86400) {
        $t = floor($difference / 3600);
        return  $t.' '.pluralize_ui($t, 'date.hour').' '.trans('date.ago');
    } elseif ($difference < 172800) {
        return trans('date.day_ago');
    } elseif ($difference < 2592000) {
        $t = floor($difference / 86400);
        return  $t.' '.pluralize_ui($t, 'date.day').' '.trans('date.ago');
    } elseif ($difference < 5184000) {
        return trans('date.month_ago');
    } else {
        $t = floor($difference / 2592000);
        return  $t.' '.pluralize_ui($t, 'date.month').' '.trans('date.ago');
    }
}

function pluralize_ui($count, $transKey)
{
    $count = intval($count);
    $index = 0;

    if (\App::isLocale('ru')) {
        if ($count < 11 || $count > 19) {
            $last = (int) substr(strval($count), -1);

            if ($last == 0 || ($last > 4 && $last < 10)) {
                $index = 0;
            } elseif ($last == 1) {
                $index = 1;
            } else {
                $index = 2;
            }
        }
    } else {
        if ($count == 1) {
            $index = 1;
        }
    }

    return trans($transKey.'_'.$index);
}

function save_thumb($file, $directory, $id, $width, $height, $saveAs = null)
{
    if (is_null($saveAs)) {
        $saveAs = $id . '.jpg';
    }

    $arr_image_details = getimagesize($file);

    $source_w = $arr_image_details[0];
    $source_h = $arr_image_details[1];

    $ratio = $width / $height;

    if ($ratio > ($source_w / $source_h)) {
        $src_h = $source_w / $ratio;
        $src_w = $source_w;
        $src_x = 0;
        $src_y = (int) round(($source_h - $src_h) / 2);
    } else {
        $src_h = $source_h;
        $src_w = $source_h * $ratio;
        $src_x = (int) round(($source_w - $src_w) / 2);
        $src_y = 0;
    }

    $dir = $directory.'/'.thumb_dir($id);

    if (!is_dir($dir)) {
        $base_dir = $directory.'/'.explode('/', thumb_dir($id))[0];
        if (!is_dir($base_dir)) {
            mkdir($base_dir);
        }

        mkdir($dir);
    }

    $image = null;

    switch ($arr_image_details[2]) {
        case 1:
            $image = imagecreatefromgif($file);
            break;

        case 2:
            $image = imagecreatefromjpeg($file);
            break;

        case 3:
            $image = imagecreatefrompng($file);
            break;

        default:
            break;
    }

    if (!is_null($image)) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $image, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h);
        imagejpeg($new_image, $dir.'/'.$saveAs, 70);
    }
}

function thumb_dir($id)
{
    return substr($id, 0, 2) . '/' . substr($id, 2, 2);
}

function save_avatar($data, $directory, $id)
{
    $paths = [];

    $image = imagecreatefromstring(base64_decode(substr($data, 23)));

    $width = imagesx($image);
    $height = imagesy($image);

    foreach ([48, 96] as $size) {
        $dir = $directory . '/' . $size . '/' . thumb_dir($id);

        if (!is_dir($dir)) {
            $base_dir = $directory . '/' . $size . '/' . explode('/', thumb_dir($id))[0];
            if (!is_dir($base_dir)) {
                mkdir($base_dir);
            }

            mkdir($dir);
        }

        $new_image = imagecreatetruecolor($size, $size);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $size, $size, $width, $height);
        imagejpeg($new_image, $dir . '/' . $id . '.jpg', 90);

        $paths[$size] = str_replace(public_path(), '', $dir . '/' . $id . '.jpg');
    }

    return $paths;
}

function remove_avatar($id)
{
    @unlink(public_path('/avatars/96/' . thumb_dir($id) . '/' . $id . '.jpg'));
    @unlink(public_path('/avatars/48/' . thumb_dir($id) . '/' . $id . '.jpg'));
}

function avatar($path)
{
    if (file_exists(public_path($path))) {
        return $path . '?' . filemtime(public_path($path));
    }

    return $path;
}


function utc2local($timestamp, $format, $timezone)
{
    static $difference = -1;

    $timestamp = $timestamp instanceof \Carbon\Carbon ? $timestamp->timestamp : $timestamp;

    if ($difference == -1) {
        $now = time();
        $date = new DateTime(date('Y-m-d H:i:s', $now), new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone($timezone));
        $time = strtotime($date->format('Y-m-d H:i:s'));
        $difference = $now - $time;
    }

    return is_null($format) ? $timestamp - $difference : date($format, $timestamp - $difference);
}

function local2utc($timestamp, $format, $timezone)
{
    static $difference = -1;

    $timestamp = $timestamp instanceof \Carbon\Carbon ? $timestamp->timestamp : $timestamp;

    if ($difference == -1) {
        $now = time();
        $date = new DateTime(date('Y-m-d H:i:s', $now), new DateTimeZone($timezone));
        $date->setTimezone(new DateTimeZone('UTC'));
        $time = strtotime($date->format('Y-m-d H:i:s'));
        $difference = $now - $time;
    }

    return is_null($format) ? $timestamp - $difference : date($format, $timestamp - $difference);
}

function month_day_ui($date, $timezone)
{
    $month = 'date.'.strtolower(utc2local($date, 'F', $timezone)).'_d';
    $day = ['day' => utc2local($date, ' j', $timezone)];

    return trans($month, $day);
}

function timepicker_hours($timeFormat)
{
    if ($timeFormat == 12) {
        return [
            '00' => '12 am', '01' => '1 am', '02' => '2 am', '03' => '3 am', '04' => '4 am', '05' => '5 am',
            '06' => '6 am', '07' => '7 am', '08' => '8 am', '09' => '9 am', '10' => '10 am', '11' => '11 am',
            '12' => '12 pm', '13' => '1 pm', '14' => '2 pm', '15' => '3 pm', '16' => '4 pm', '17' => '5 pm',
            '18' => '6 pm', '19' => '7 pm', '20' => '8 pm', '21' => '9 pm', '22' => '10 pm', '23' => '11 pm'
        ];
    } else {
        return [
            '00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05',
            '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11',
            '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17',
            '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'
        ];
    }
}

function timepicker_minutes()
{
    return ['00' => '00', '15' => '15', '30' => '30', '45' => '45'];
}

function date_formats($timezone)
{
    return [
        'dd.mm.yyyy' => utc2local(time(), 'd.m.Y', $timezone),
        'mm/dd/yyyy' => utc2local(time(), 'm/d/Y', $timezone),
    ];
}

function time_formats($timezone)
{
    return [
        12 => utc2local(time(), 'g:i a', $timezone),
        24 => utc2local(time(), 'H:i', $timezone)
    ];
}

function week_days()
{
    return [
        trans('date.sunday'),
        trans('date.monday'),
        trans('date.tuesday'),
        trans('date.wednesday'),
        trans('date.thursday'),
        trans('date.friday'),
        trans('date.saturday'),
    ];
}

function languages()
{
    return [
        'en' => 'English',
        'ru' => 'Русский',
    ];
}

function utc_date_formatted($date, $dateFormat, $timezone)
{
    if ($dateFormat == 'mm/dd/yyyy') {
        return utc2local($date, 'm/d/Y', $timezone);
    } else {
        return utc2local($date, 'd.m.Y', $timezone);
    }
}

function timezones($timezone)
{
    $timezones = [
        'Pacific/Midway' => '(UTC-11:00) Midway Island',
        'Pacific/Samoa' => '(UTC-11:00) Samoa',
        'Pacific/Honolulu' => '(UTC-10:00) Hawaii',
        'US/Alaska' => '(UTC-09:00) Alaska',
        'America/Los_Angeles' => '(UTC-08:00) Pacific Time (US & Canada)',
        'America/Tijuana' => '(UTC-08:00) Tijuana',
        'US/Arizona' => '(UTC-07:00) Arizona',
        'America/Chihuahua' => '(UTC-07:00) Chihuahua',
        'America/Mazatlan' => '(UTC-07:00) Mazatlan',
        'US/Mountain' => '(UTC-07:00) Mountain Time (US & Canada)',
        'America/Managua' => '(UTC-06:00) Central America',
        'US/Central' => '(UTC-06:00) Central Time (US & Canada)',
        'America/Mexico_City' => '(UTC-06:00) Mexico City',
        'America/Monterrey' => '(UTC-06:00) Monterrey',
        'Canada/Saskatchewan' => '(UTC-06:00) Saskatchewan',
        'America/Bogota' => '(UTC-05:00) Bogota',
        'US/Eastern' => '(UTC-05:00) Eastern Time (US & Canada)',
        'US/East-Indiana' => '(UTC-05:00) Indiana (East)',
        'America/Lima' => '(UTC-05:00) Lima',
        'Canada/Atlantic' => '(UTC-04:00) Atlantic Time (Canada)',
        'America/Caracas' => '(UTC-04:30) Caracas',
        'America/La_Paz' => '(UTC-04:00) La Paz',
        'America/Santiago' => '(UTC-04:00) Santiago',
        'Canada/Newfoundland' => '(UTC-03:30) Newfoundland',
        'America/Sao_Paulo' => '(UTC-03:00) Brasilia',
        'America/Argentina/Buenos_Aires' => '(UTC-03:00) Buenos Aires',
        'America/Godthab' => '(UTC-03:00) Greenland',
        'America/Noronha' => '(UTC-02:00) Mid-Atlantic',
        'Atlantic/Azores' => '(UTC-01:00) Azores',
        'Atlantic/Cape_Verde' => '(UTC-01:00) Cape Verde Is.',
        'Africa/Casablanca' => '(UTC+00:00) Casablanca',
        'Etc/Greenwich' => '(UTC+00:00) Greenwich Mean Time : Dublin',
        'Europe/Lisbon' => '(UTC+00:00) Lisbon',
        'Europe/London' => '(UTC+00:00) London',
        'Africa/Monrovia' => '(UTC+00:00) Monrovia',
        'UTC' => '(UTC+00:00) UTC',
        'Europe/Amsterdam' => '(UTC+01:00) Amsterdam',
        'Europe/Belgrade' => '(UTC+01:00) Belgrade',
        'Europe/Berlin' => '(UTC+01:00) Berlin',
        'Europe/Bratislava' => '(UTC+01:00) Bratislava',
        'Europe/Brussels' => '(UTC+01:00) Brussels',
        'Europe/Budapest' => '(UTC+01:00) Budapest',
        'Europe/Copenhagen' => '(UTC+01:00) Copenhagen',
        'Europe/Ljubljana' => '(UTC+01:00) Ljubljana',
        'Europe/Madrid' => '(UTC+01:00) Madrid',
        'Europe/Paris' => '(UTC+01:00) Paris',
        'Europe/Prague' => '(UTC+01:00) Prague',
        'Europe/Rome' => '(UTC+01:00) Rome',
        'Europe/Sarajevo' => '(UTC+01:00) Sarajevo',
        'Europe/Skopje' => '(UTC+01:00) Skopje',
        'Europe/Stockholm' => '(UTC+01:00) Stockholm',
        'Europe/Vienna' => '(UTC+01:00) Vienna',
        'Europe/Warsaw' => '(UTC+01:00) Warsaw',
        'Africa/Lagos' => '(UTC+01:00) West Central Africa',
        'Europe/Zagreb' => '(UTC+01:00) Zagreb',
        'Europe/Athens' => '(UTC+02:00) Athens',
        'Europe/Bucharest' => '(UTC+02:00) Bucharest',
        'Africa/Cairo' => '(UTC+02:00) Cairo',
        'Africa/Harare' => '(UTC+02:00) Harare',
        'Europe/Helsinki' => '(UTC+02:00) Helsinki',
        'Europe/Istanbul' => '(UTC+02:00) Istanbul',
        'Asia/Jerusalem' => '(UTC+02:00) Jerusalem',
        'Europe/Kiev' => '(UTC+02:00) Kyiv',
        'Africa/Johannesburg' => '(UTC+02:00) Pretoria',
        'Europe/Riga' => '(UTC+02:00) Riga',
        'Europe/Sofia' => '(UTC+02:00) Sofia',
        'Europe/Tallinn' => '(UTC+02:00) Tallinn',
        'Europe/Vilnius' => '(UTC+02:00) Vilnius',
        'Asia/Baghdad' => '(UTC+03:00) Baghdad',
        'Asia/Kuwait' => '(UTC+03:00) Kuwait',
        'Europe/Minsk' => '(UTC+03:00) Minsk',
        'Africa/Nairobi' => '(UTC+03:00) Nairobi',
        'Asia/Riyadh' => '(UTC+03:00) Riyadh',
        'Europe/Volgograd' => '(UTC+03:00) Volgograd',
        'Asia/Tehran' => '(UTC+03:30) Tehran',
        'Asia/Muscat' => '(UTC+04:00) Abu Dhabi',
        'Asia/Baku' => '(UTC+04:00) Baku',
        'Europe/Moscow' => '(UTC+04:00) Moscow',
        'Asia/Tbilisi' => '(UTC+04:00) Tbilisi',
        'Asia/Yerevan' => '(UTC+04:00) Yerevan',
        'Asia/Kabul' => '(UTC+04:30) Kabul',
        'Asia/Karachi' => '(UTC+05:00) Karachi',
        'Asia/Tashkent' => '(UTC+05:00) Tashkent',
        'Asia/Kolkata' => '(UTC+05:30) Kolkata',
        'Asia/Calcutta' => '(UTC+05:30) New Delhi',
        'Asia/Katmandu' => '(UTC+05:45) Kathmandu',
        'Asia/Almaty' => '(UTC+06:00) Almaty',
        'Asia/Dhaka' => '(UTC+06:00) Astana',
        'Asia/Yekaterinburg' => '(UTC+06:00) Ekaterinburg',
        'Asia/Rangoon' => '(UTC+06:30) Rangoon',
        'Asia/Bangkok' => '(UTC+07:00) Bangkok',
        'Asia/Jakarta' => '(UTC+07:00) Jakarta',
        'Asia/Novosibirsk' => '(UTC+07:00) Novosibirsk',
        'Asia/Chongqing' => '(UTC+08:00) Chongqing',
        'Asia/Hong_Kong' => '(UTC+08:00) Hong Kong',
        'Asia/Krasnoyarsk' => '(UTC+08:00) Krasnoyarsk',
        'Asia/Kuala_Lumpur' => '(UTC+08:00) Kuala Lumpur',
        'Australia/Perth' => '(UTC+08:00) Perth',
        'Asia/Singapore' => '(UTC+08:00) Singapore',
        'Asia/Taipei' => '(UTC+08:00) Taipei',
        'Asia/Ulan_Bator' => '(UTC+08:00) Ulaan Bataar',
        'Asia/Urumqi' => '(UTC+08:00) Urumqi',
        'Asia/Irkutsk' => '(UTC+09:00) Irkutsk',
        'Asia/Seoul' => '(UTC+09:00) Seoul',
        'Asia/Tokyo' => '(UTC+09:00) Tokyo',
        'Australia/Adelaide' => '(UTC+09:30) Adelaide',
        'Australia/Darwin' => '(UTC+09:30) Darwin',
        'Australia/Brisbane' => '(UTC+10:00) Brisbane',
        'Australia/Canberra' => '(UTC+10:00) Canberra',
        'Pacific/Guam' => '(UTC+10:00) Guam',
        'Australia/Hobart' => '(UTC+10:00) Hobart',
        'Australia/Melbourne' => '(UTC+10:00) Melbourne',
        'Pacific/Port_Moresby' => '(UTC+10:00) Port Moresby',
        'Australia/Sydney' => '(UTC+10:00) Sydney',
        'Asia/Yakutsk' => '(UTC+10:00) Yakutsk',
        'Asia/Vladivostok' => '(UTC+11:00) Vladivostok',
        'Pacific/Auckland' => '(UTC+12:00) Auckland',
        'Pacific/Fiji' => '(UTC+12:00) Fiji',
        'Pacific/Kwajalein' => '(UTC+12:00) International Date Line West',
        'Asia/Kamchatka' => '(UTC+12:00) Kamchatka',
        'Asia/Magadan' => '(UTC+12:00) Magadan',
        'Pacific/Tongatapu' => '(UTC+13:00) Nuku\'alofa'
    ];

    if (!array_key_exists($timezone, $timezones)) {
        $timezones[$timezone] = 'Autodetect';
    }

    return $timezones;
}

function strip_content($content)
{
    $content = str_replace('&nbsp;', ' ', $content);
    $content = strip_tags(trim($content), '<p><strong><em><s><ol><ul><li><blockquote>');

    $pattern = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/[^\<\s]*)?/";

    if (preg_match($pattern, $content, $url)) {
        $content = preg_replace($pattern, '<a href="$0" target="_blank">$0</a>', $content);
    }

    return $content;
}

function is_authenticated(\Teamo\Common\Domain\Id $id): bool
{
    return $id->id() == (string) \Illuminate\Support\Facades\Auth::id();
}
