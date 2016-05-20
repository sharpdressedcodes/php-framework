<?php

namespace WebsiteConnect\Framework\Utility;

class DateTime {

	public static $DAYS = array(
		'monday',
		'tuesday',
		'wednesday',
		'thursday',
		'friday',
		'saturday',
		'sunday'
	);
	public static $EXTENDED_DAYS = array(
		'monday',
		'tuesday',
		'wednesday',
		'thursday',
		'friday',
		'saturday',
		'sunday',
		'weekday',
		'weekend',
		'mon',
		'tue',
		'wed',
		'thu',
		'fri',
		'sat',
		'sun',
	);

	public static function secondsFromString($str){

		$arr = explode(' ', $str);
		$num = intval($arr[0]);
		$type = strtolower($arr[1]);
		$result = 0;

		if (substr($type, strlen($type) - 1, 1) === 's')
			$type = substr($type, 0, strlen($type) - 1);

		switch ($type){
			case 'second':
				$result = $num;
				break;
			case 'minute':
				$result = $num * 60;
				break;
			case 'hour':
				$result = ($num * 60) * 60;
				break;
			case 'day':
				$result = (($num * 60) * 60) * 24;
				break;
			case 'week':
				$result = ((($num * 60) * 60) * 24) * 7;
				break;
			case 'month':
				$result = (((($num * 60) * 60) * 24) * 7) * 4.5;
				break;
			case 'year':
				$result = ((($num * 60) * 60) * 24) * 365;
				break;
		}

		return $result;

	}

	public static function getCurrentDay($modifier = null){

		return date(is_null($modifier) ? 'l' : $modifier);

	}

	public static function getCurrentTime($format = null, $timezone = null){

		$date = new \DateTime();

		if (!is_null($timezone)){
			$date->setTimezone(new \DateTimeZone($timezone));
		}

		return $date->format(is_null($format) ? 'H:i' : $format);

	}

	public static function validateDayName($day, $useExtended){

		return in_array($day, $useExtended ? self::$EXTENDED_DAYS : self::$DAYS);

	}

}