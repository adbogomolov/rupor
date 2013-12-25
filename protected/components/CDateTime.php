<?php

class CDateTime
{
	/**
	* Returns a formatted descriptive date string for given datetime string.
	* 
	* If the given date is today, the returned string could be "Today, 6:54 pm".
	* If the given date was yesterday, the returned string could be "Yesterday, 6:54 pm".
	* If $dateString's year is the current year, the returned string does not
	* include mention of the year.
	* 
	* @param string $dateString Datetime string or Unix timestamp
	* @return string Described, relative date string
	*/
	public static function format($dateString = null, $newly = true)
	{
		$date = ($dateString == null) ? time() : strtotime($dateString);
		
		if (self::isToday($date))
			$result = $newly && self::isNewly($date, 1) ? self::timeAgo($date) : sprintf('Today at %s', date('H:i', $date));
		else if (self::wasYesterday($date))
			$result = sprintf('Yesterday at %s', date('H:i', $date));
		else
			$result = sprintf('%s at %s', date('d M'. (!self::isThisYear($date) ? ' Y' : ''), $date), date('H:i', $date));
		
		return self::locale($result);
	}
	
	/**
	* @ignore
	*/
	public static function date($format='d F Y', $time=null)
	{
		if (is_null($time)) $time = time();
		return self::locale(date($format,$time));
	}
	
	/**
	* @ignore
	*/
	public static function localTime( $intTimeStamp, $format = '%Y-%m-%d %H:%M:%S'){
		if(!is_int($intTimeStamp)) $intTimeStamp = strtotime($intTimeStamp);
		return strftime( $format, $intTimeStamp);
	}	
	
	/**
	* @ignore
	*/
	public static function locale($date)
	{
		$locale = (array) Yii::t('app', 'datetime');
		$locale+=array_combine(array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'), Yii::app()->locale->getMonthNames('abbreviated'));
		$locale+=array_combine(array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), Yii::app()->locale->getMonthNames('wide'));
		$locale+=array_combine(array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), Yii::app()->locale->getWeekDayNames('wide'));
		return strtr($date, $locale);
	}

	/**
	* @ignore
	*/
	public static function isNotEmpty($date)
	{
		return ($date && $date != '0000-00-00' && $date != '0000-00-00 00:00:00');
	}
	
	/**
	* Returns true if given date is newly from period.
	*
	* @param string $date Unix timestamp
	* @param int $hours Hours
	* @return boolean True if date is newly
	*/
	public static function isNewly($date, $hours = 3)
	{
		return self::isToday($date) && (date('H', $date) + $hours) >= date('H');
	}
	
	/**
	* Returns true if given date is today.
	*
	* @param string $date Unix timestamp
	* @return boolean True if date is today
	*/
	public static function isToday($date)
	{
		return date('Y-m-d', $date) == date('Y-m-d');
	}
	
	/**
	* Returns true if given date was yesterday
	*
	* @param string $date Unix timestamp
	* @return boolean True if date was yesterday
	*/
	public static function wasYesterday($date)
	{
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
	}
	
	/**
	* Returns true if given date is in this year
	*
	* @param string $date Unix timestamp
	* @return boolean True if date is in this year
	*/
	public static function isThisYear($date)
	{
		return date('Y', $date) == date('Y', time());
	}
	
	/**
	* Returns true if given date is in this week
	*
	* @param string $date Unix timestamp
	* @return boolean True if date is in this week
	*/
	public static function isThisWeek($date)
	{
		return date('W Y', $date) == date('W Y', time());
	}
	
	/**
	* Returns true if given date is in this month
	*
	* @param string $date Unix timestamp
	* @return boolean True if date is in this month
	*/
	public static function isThisMonth($date)
	{
		return date('m Y',$date) == date('m Y', time());
	}
	
	/**
	 * Возвращает количество дней в месяце
	 * 
	 * @param integer $month
	 * @param integer $year
	 * @return string
	 */
	public function daysInMonth($month=null, $year=null)
	{
		if (!is_numeric($month) || $month<1 || $month>12)
			$month = date('m');

		if (!is_numeric($year) || $year<0)
			$year = date('Y');

		return date('t', mktime(0,0,0,$month,1,$year));
	}
	
	/**
	 * Вычислить расхождение времени
	 * @ignore
	 */
	public static function timeDiff($date, $splice = 2, $delim=', ')
	{
		$time_unix = ctype_digit($date) ? $date : strtotime($date);
		$diff = abs(time()-$time_unix);
		$tokens = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		$result = array();
		foreach($tokens as $unit => $sec)
		{
			if ($diff>=$sec)
			{
				$result[$unit] = Yii::t('app', '{n} '.$unit.'|{n} '.$unit.'s', floor($diff/$sec));
				$diff = $diff % $sec;
			}
		}
		if ($splice)
		{
			$result = array_splice($result,0,$splice);
		}
		return implode($delim, $result);
	}
	
	/**
	 * @ignore
	 */
	public static function timeAgo($date)
	{
		$diff = self::timeDiff($date);
		return $diff ? $diff . ' ago' : 'just now';
	}
	
}