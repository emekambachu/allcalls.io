<?php
function convertDateToLocal($date, $format = 'Y-m-d H:i:s')
{
    $date = new DateTime($date);
    $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
    return $date->format($format);
    //return date($format, strtotime($date));
}
?>