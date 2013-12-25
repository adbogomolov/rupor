<?php
/**
 * Text helper class. Provides simple methods for working with text.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2007-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */

class Text
{
	/**
	 * @var array number units and text equivalents
	 */
	public static $units = array(
		1000000000 => 'billion',
		1000000    => 'million',
		1000       => 'thousand',
		100        => 'hundred',
		90 => 'ninety',
		80 => 'eighty',
		70 => 'seventy',
		60 => 'sixty',
		50 => 'fifty',
		40 => 'fourty',
		30 => 'thirty',
		20 => 'twenty',
		19 => 'nineteen',
		18 => 'eighteen',
		17 => 'seventeen',
		16 => 'sixteen',
		15 => 'fifteen',
		14 => 'fourteen',
		13 => 'thirteen',
		12 => 'twelve',
		11 => 'eleven',
		10 => 'ten',
		9  => 'nine',
		8  => 'eight',
		7  => 'seven',
		6  => 'six',
		5  => 'five',
		4  => 'four',
		3  => 'three',
		2  => 'two',
		1  => 'one',
	);
	
	/**
	 * @ignore
	 */
    public static function hl($str, $query)
    {
		return $text;
	}
	
	/**
	 * @ignore
	 */
    public static function translit($str)
    {
        $str = str_replace(' ', '-', $str);
        $str = str_replace('_', '-', $str);

        $tr = array(
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
            "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I",
            "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
            "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
            "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
            "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
            "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
            "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya"
        );
		
        $str = strtolower(strtr($str, $tr));
        $str = preg_replace('/[^0-9a-z\-]/', '', $str);
        return $str;
    }
	
	/**
	 * Склонение слова с использованием Yandex Склонятора
         IME = 1;
         ROD = 2;
         DAT = 3;
         VIN = 4;
         TVO = 5;
         PRE = 6;
	 * @ignore
	 */
	public static function decline($str, $case = 1)
	{
		$id = 'decline.'. md5($str);
		
		if ($case<=0 || $case>6)
			$case = 1;
		
		if (Yii::app()->hasComponent('cache'))
			$result = Yii::app()->cache->get($id);
		else
			$result = Yii::app()->db->createCommand("SELECT * FROM `_declines` WHERE original = '". CHtml::encode($str) ."'")->queryRow();
		
		// From remote
		if (empty($result))
		{
			$json = @file_get_contents('http://export.yandex.ru/inflect.xml?format=json&name=' . urlencode($str));
			
			if (empty($json))
				return $str;
			
			$result = @json_decode($json, true);
			
			if (empty($result))
				return $str;
			
			if (Yii::app()->hasComponent('cache'))
				Yii::app()->cache->set($id, $result);
			else
				Yii::app()->db->createCommand()->insert('_declines', $result);
		}
		
		return !empty($result[$case]) ? $result[$case] : $result[1];
	}
	
	/**
	 * Limits a phrase to a given number of words.
	 *
	 *     $text = Text::limit_words($text);
	 *
	 * @param   string   phrase to limit words of
	 * @param   integer  number of words to limit to
	 * @param   string   end character or entity
	 * @return  string
	 */
	public static function limit_words($str, $limit = 100, $end_char = NULL)
	{
		$limit = (int) $limit;
		$end_char = ($end_char === NULL) ? '…' : $end_char;
		
		if (trim($str) === '')
			return $str;

		if ($limit <= 0)
			return $end_char;

		preg_match('/^\s*+(?:\S++\s*+){1,'.$limit.'}/u', $str, $matches);

		// Only attach the end character if the matched string is shorter
		// than the starting string.
		return rtrim($matches[0]).((strlen($matches[0]) === strlen($str)) ? '' : $end_char);
	}

	/**
	 * Limit a phrase to a given text length with no word breaks
	 * @param string $input Text to limit
	 * @param integer $length Maximum text length
	 * @param boolen $ellipses Add ellipses at the end of limited phrase
	 * @param boolen $strip_html Apply `strip_tags` function before limitation procedure
	 * @return string Limited phrase
	 */
	public static function cutTextByWord( $input, $length, $ellipses = true, $strip_html = true)
	{
	    if ($strip_html) {
	        $input = strip_tags($input);
	    }
	    if (strlen($input) <= $length) {
	        return $input;
	    }
	    $last_space = strrpos(substr($input, 0, $length), ' ');
	    $trimmed_text = substr($input, 0, $last_space);
	    if ($ellipses) {
	        $trimmed_text .= '...';
	    }
	    return $trimmed_text;
	}		
	
	/**
	 * Limits a phrase to a given number of characters.
	 * 
	 * $text = Text::limit_chars($text);
	 * 
	 * @param   string   phrase to limit characters of
	 * @param   integer  number of characters to limit to
	 * @param   string   end character or entity
	 * @param   boolean  enable or disable the preservation of words while limiting
	 * @return  string
	 * @uses    mb_strlen
	 */
	public static function limit_chars($str, $limit = 100, $end_char = NULL, $preserve_words = FALSE)
	{
		$end_char = ($end_char === NULL) ? '…' : $end_char;

		$limit = (int) $limit;

		if (trim($str) === '' OR mb_strlen($str, 'UTF-8') <= $limit)
			return $str;

		if ($limit <= 0)
			return $end_char;

		if ($preserve_words === FALSE)
			return rtrim(mb_substr($str, 0, $limit, 'UTF-8')).$end_char;

		// Don't preserve words. The limit is considered the top limit.
		// No strings with a length longer than $limit should be returned.
		if ( ! preg_match('/^.{0,'.$limit.'}\s/us', $str, $matches))
			return $end_char;

		return rtrim($matches[0]).((strlen($matches[0]) === strlen($str)) ? '' : $end_char);
	}

	/**
	 * Alternates between two or more strings.
	 *
	 *     echo Text::alternate('one', 'two'); // "one"
	 *     echo Text::alternate('one', 'two'); // "two"
	 *     echo Text::alternate('one', 'two'); // "one"
	 *
	 * Note that using multiple iterations of different strings may produce
	 * unexpected results.
	 *
	 * @param   string  strings to alternate between
	 * @return  string
	 */
	public static function alternate()
	{
		static $i;

		if (func_num_args() === 0)
		{
			$i = 0;
			return '';
		}

		$args = func_get_args();
		return $args[($i++ % count($args))];
	}

	/**
	 * Generates a random string of a given type and length.
	 *
	 *
	 *     $str = Text::random(); // 8 character random string
	 *
	 * The following types are supported:
	 *
	 * alnum
	 * :  Upper and lower case a-z, 0-9 (default)
	 *
	 * alpha
	 * :  Upper and lower case a-z
	 *
	 * hexdec
	 * :  Hexadecimal characters a-f, 0-9
	 *
	 * distinct
	 * :  Uppercase characters and numbers that cannot be confused
	 *
	 * You can also create a custom type by providing the "pool" of characters
	 * as the type.
	 *
	 * @param   string   a type of pool, or a string of characters to use as the pool
	 * @param   integer  length of string to return
	 * @return  string
	 * @uses    mb_split
	 */
	public static function random($type = NULL, $length = 8)
	{
		if ($type === NULL)
		{
			// Default is to generate an alphanumeric string
			$type = 'alnum';
		}
		
		switch ($type)
		{
			case 'alnum':
				$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
			case 'alpha':
				$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
			case 'hexdec':
				$pool = '0123456789abcdef';
			break;
			case 'numeric':
				$pool = '0123456789';
			break;
			case 'nozero':
				$pool = '123456789';
			break;
			case 'distinct':
				$pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
			break;
			default:
				$pool = (string) $type;
			break;
		}

		// Split the pool into an array of characters
		$pool = str_split($pool, 1);

		// Largest pool key
		$max = count($pool) - 1;

		$str = '';
		for ($i = 0; $i < $length; $i++)
		{
			// Select a random character from the pool and add it to the string
			$str .= $pool[mt_rand(0, $max)];
		}

		// Make sure alnum strings contain at least one letter and one digit
		if ($type === 'alnum' AND $length > 1)
		{
			if (ctype_alpha($str))
			{
				// Add a random digit
				$str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
			}
			elseif (ctype_digit($str))
			{
				// Add a random letter
				$str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
			}
		}

		return $str;
	}

	/**
	 * Uppercase words that are not separated by spaces, using a custom
	 * delimiter or the default.
	 * 
	 *      $str = Text::ucfirst('content-type'); // returns "Content-Type" 
	 *
	 * @param   string    string to transform
	 * @param   string    delemiter to use
	 * @return  string
	 */
	public static function ucfirst($string, $delimiter = '-')
	{
		// Put the keys back the Case-Convention expected
		return implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
	}

	/**
	 * Reduces multiple slashes in a string to single slashes.
	 *
	 *     $str = Text::reduce_slashes('foo//bar/baz'); // "foo/bar/baz"
	 *
	 * @param   string  string to reduce slashes of
	 * @return  string
	 */
	public static function reduce_slashes($str)
	{
		return preg_replace('#(?<!:)//+#', '/', $str);
	}

	/**
	 * Replaces the given words with a string.
	 *
	 *     // Displays "What the #####, man!"
	 *     echo Text::censor('What the frick, man!', array(
	 *         'frick' => '#####',
	 *     ));
	 *
	 * @param   string   phrase to replace words in
	 * @param   array    words to replace
	 * @param   string   replacement string
	 * @param   boolean  replace words across word boundries (space, period, etc)
	 * @return  string
	 * @uses    mb_strlen
	 */
	public static function censor($str, $badwords, $replacement = '#', $replace_partial_words = TRUE)
	{
		foreach ( (array) $badwords as $key => $badword)
		{
			$badwords[$key] = str_replace('\*', '\S*?', preg_quote( (string) $badword));
		}

		$regex = '('.implode('|', $badwords).')';

		if ($replace_partial_words === FALSE)
		{
			// Just using \b isn't sufficient when we need to replace a badword that already contains word boundaries itself
			$regex = '(?<=\b|\s|^)'.$regex.'(?=\b|\s|$)';
		}

		$regex = '!'.$regex.'!ui';

		if (mb_strlen($replacement) == 1)
		{
			$regex .= 'e';
			return preg_replace($regex, 'str_repeat($replacement, mb_strlen(\'$1\'))', $str);
		}

		return preg_replace($regex, $replacement, $str);
	}

	/**
	 * Finds the text that is similar between a set of words.
	 *
	 *     $match = Text::similar(array('fred', 'fran', 'free'); // "fr"
	 *
	 * @param   array   words to find similar text of
	 * @return  string
	 */
	public static function similar(array $words)
	{
		// First word is the word to match against
		$word = current($words);

		for ($i = 0, $max = strlen($word); $i < $max; ++$i)
		{
			foreach ($words as $w)
			{
				// Once a difference is found, break out of the loops
				if ( ! isset($w[$i]) OR $w[$i] !== $word[$i])
					break 2;
			}
		}

		// Return the similar text
		return substr($word, 0, $i);
	}

	/**
	 * Converts text email addresses and anchors into links. Existing links
	 * will not be altered.
	 *
	 *     echo Text::auto_link($text);
	 *
	 * [!!] This method is not foolproof since it uses regex to parse HTML.
	 *
	 * @param   string   text to auto link
	 * @return  string
	 * @uses    Text::auto_link_urls
	 * @uses    Text::auto_link_emails
	 */
	public static function auto_link($text)
	{
		// Auto link emails first to prevent problems with "www.domain.com@example.com"
		return self::auto_link_urls(self::auto_link_emails($text));
	}

	/**
	 * Converts text anchors into links. Existing links will not be altered.
	 *
	 *     echo Text::auto_link_urls($text);
	 *
	 * [!!] This method is not foolproof since it uses regex to parse HTML.
	 *
	 * @param   string   text to auto link
	 * @return  string
	 * @uses    HTML::anchor
	 */
	public static function auto_link_urls($text)
	{
		// Find and replace all http/https/ftp/ftps links that are not part of an existing html anchor
		$text = preg_replace_callback('~\b(?<!href="|">)(?:ht|f)tps?://[^<\s]+(?:/|\b)~i', 'self::_auto_link_urls_callback1', $text);

		// Find and replace all naked www.links.com (without http://)
		return preg_replace_callback('~\b(?<!://|">)www(?:\.[a-z0-9][-a-z0-9]*+)+\.[a-z]{2,6}\b~i', 'self::_auto_link_urls_callback2', $text);
	}

	protected static function _auto_link_urls_callback1($matches)
	{
		return CHtml::link($matches[0],$matches[0],array('target'=>'_blank'));
	}

	protected static function _auto_link_urls_callback2($matches)
	{
		return CHtml::link($matches[0], 'http://'.$matches[0],array('target'=>'_blank'));
	}

	/**
	 * Converts text email addresses into links. Existing links will not
	 * be altered.
	 *
	 *     echo Text::auto_link_emails($text);
	 *
	 * [!!] This method is not foolproof since it uses regex to parse HTML.
	 *
	 * @param   string   text to auto link
	 * @return  string
	 * @uses    HTML::mailto
	 */
	public static function auto_link_emails($text)
	{
		// Find and replace all email addresses that are not part of an existing html mailto anchor
		// Note: The "58;" negative lookbehind prevents matching of existing encoded html mailto anchors
		//       The html entity for a colon (:) is &#58; or &#058; or &#0058; etc.
		return preg_replace_callback('~\b(?<!href="mailto:|58;)(?!\.)[-+_a-z0-9.]++(?<!\.)@(?![-.])[-a-z0-9.]+(?<!\.)\.[a-z]{2,6}\b(?!</a>)~i', 'self::_auto_link_emails_callback', $text);
	}

	protected static function _auto_link_emails_callback($matches)
	{
		return CHtml::mailto($matches[0]);
	}

	/**
	 * Automatically applies "p" and "br" markup to text.
	 * Basically [nl2br](http://php.net/nl2br) on steroids.
	 *
	 *     echo Text::auto_p($text);
	 *
	 * [!!] This method is not foolproof since it uses regex to parse HTML.
	 *
	 * @param   string   subject
	 * @param   boolean  convert single linebreaks to <br />
	 * @return  string
	 */
	public static function auto_p($str, $br = TRUE)
	{
		// Trim whitespace
		if (($str = trim($str)) === '')
			return '';

		// Standardize newlines
		$str = str_replace(array("\r\n", "\r"), "\n", $str);

		// Trim whitespace on each line
		$str = preg_replace('~^[ \t]+~m', '', $str);
		$str = preg_replace('~[ \t]+$~m', '', $str);

		// The following regexes only need to be executed if the string contains html
		if ($html_found = (strpos($str, '<') !== FALSE))
		{
			// Elements that should not be surrounded by p tags
			$no_p = '(?:p|div|h[1-6r]|ul|ol|li|blockquote|d[dlt]|pre|t[dhr]|t(?:able|body|foot|head)|c(?:aption|olgroup)|form|s(?:elect|tyle)|a(?:ddress|rea)|ma(?:p|th))';

			// Put at least two linebreaks before and after $no_p elements
			$str = preg_replace('~^<'.$no_p.'[^>]*+>~im', "\n$0", $str);
			$str = preg_replace('~</'.$no_p.'\s*+>$~im', "$0\n", $str);
		}

		// Do the <p> magic!
		$str = '<p>'.trim($str).'</p>';
		$str = preg_replace('~\n{2,}~', "</p>\n\n<p>", $str);

		// The following regexes only need to be executed if the string contains html
		if ($html_found !== FALSE)
		{
			// Remove p tags around $no_p elements
			$str = preg_replace('~<p>(?=</?'.$no_p.'[^>]*+>)~i', '', $str);
			$str = preg_replace('~(</?'.$no_p.'[^>]*+>)</p>~i', '$1', $str);
		}

		// Convert single linebreaks to <br />
		if ($br === TRUE)
		{
			$str = preg_replace('~(?<!\n)\n(?!\n)~', "<br />\n", $str);
		}

		return $str;
	}

	/**
	 * Normalize text
	 * @param string subject
	 * @return string
	 */
	public static function normalize($str)
	{
		$str = str_replace(array("\r\n", "\r"), "\n", $str);
		$str = str_replace("\n\t", "\n", $str);
		// $str = str_replace("\t", str_repeat('&nbsp;', 4), $str);
		$str = str_replace("\t", '&emsp;', $str); // replace tabs
		$str = nl2br($str);
		
		return $str;
	}
	
	/**
	 * Returns human readable sizes. Based on original functions written by
	 * [Aidan Lister](http://aidanlister.com/repos/v/function.size_readable.php)
	 * and [Quentin Zervaas](http://www.phpriot.com/d/code/strings/filesize-format/).
	 *
	 *     echo Text::bytes(filesize($file));
	 *
	 * @param   integer  size in bytes
	 * @param   string   a definitive unit
	 * @param   string   the return string format
	 * @param   boolean  whether to use SI prefixes or IEC
	 * @return  string
	 */
	public static function bytes($bytes, $force_unit = NULL, $format = NULL, $si = TRUE)
	{
		// Format string
		$format = ($format === NULL) ? '%01.2f&nbsp;%s' : (string) $format;

		// IEC prefixes (binary)
		if ($si == FALSE OR strpos($force_unit, 'i') !== FALSE)
		{
			$units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
			$mod   = 1024;
		}
		// SI prefixes (decimal)
		else
		{
			$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
			$mod   = 1000;
		}

		// Determine unit to use
		if (($power = array_search( (string) $force_unit, $units)) === FALSE)
		{
			$power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
		}

		return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
	}

	/**
	 * Format a number to human-readable text.
	 *
	 *     // Display: one thousand and twenty-four
	 *     echo Text::number(1024);
	 *
	 *     // Display: five million, six hundred and thirty-two
	 *     echo Text::number(5000632);
	 *
	 * @param   integer   number to format
	 * @return  string
	 * @since   3.0.8
	 */
	public static function number($number)
	{
		// The number must always be an integer
		$number = (int) $number;

		// Uncompiled text version
		$text = array();

		// Last matched unit within the loop
		$last_unit = NULL;

		// The last matched item within the loop
		$last_item = '';

		foreach (self::$units as $unit => $name)
		{
			if ($number / $unit >= 1)
			{
				// $value = the number of times the number is divisble by unit
				$number -= $unit * ($value = (int) floor($number / $unit));
				// Temporary var for textifying the current unit
				$item = '';

				if ($unit < 100)
				{
					if ($last_unit < 100 AND $last_unit >= 20)
					{
						$last_item .= '-'.$name;
					}
					else
					{
						$item = $name;
					}
				}
				else
				{
					$item = self::number($value).' '.$name;
				}

				// In the situation that we need to make a composite number (i.e. twenty-three)
				// then we need to modify the previous entry
				if (empty($item))
				{
					array_pop($text);

					$item = $last_item;
				}

				$last_item = $text[] = $item;
				$last_unit = $unit;
			}
		}

		if (count($text) > 1)
		{
			$and = array_pop($text);
		}

		$text = implode(', ', $text);

		if (isset($and))
		{
			$text .= ' and '.$and;
		}

		return $text;
	}

	/**
	 * Prevents [widow words](http://www.shauninman.com/archive/2006/08/22/widont_wordpress_plugin)
	 * by inserting a non-breaking space between the last two words.
	 *
	 *     echo Text::widont($text);
	 *
	 * @param   string  text to remove widows from
	 * @return  string
	 */
	public static function widont($str)
	{
		$str = rtrim($str);
		$space = strrpos($str, ' ');

		if ($space !== FALSE)
		{
			$str = substr($str, 0, $space).'&nbsp;'.substr($str, $space + 1);
		}

		return $str;
	}
	
	/**
	 * @return string
	 */
	public static function get_vars($str)
	{
		preg_match_all('~\{([^{}]+)\}~', $str, $matches, PREG_SET_ORDER);
		$vars = array();
		foreach($matches as $match)
		{
			$params = explode(':', $match[1]);
			$name = array_shift($params);
			
			if ($params)
			{
				foreach($params as &$param)
				{
					parse_str($param, $param);
				}
			}
			
			$vars[$match[0]] = array($name, $params);
		}
		return $vars;
	}
	
	/**
	 * View more text
	 *
	 * @param string text
	 * @param integer as limit characters
	 * @return string
	 */
	public static function viewMore($text, $limit=30)
	{
		$_text = self::limit_words($text,$limit);
		
		if ($limit && strlen($text) > strlen($_text))
		{
			$text = CHtml::tag('div', array(), $_text) .
			CHtml::link(Yii::t('app','Expand text..'), '#', array('onclick'=>'$(this).prev().hide();$(this).next().show();$(this).hide();return false', 'class'=>'post_more')) .
			CHtml::tag('div', array('style'=>'display:none'), $text);
		}
		return $text;
	}

	/**
	 * This function parses an absolute or relative URL and splits it
	 * into individual components.
	 *
	 * RFC3986 specifies the components of a Uniform Resource Identifier (URI).
	 * A portion of the ABNFs are repeated here:
	 *
	 *	URI-reference	= URI
	 *			/ relative-ref
	 *
	 *	URI		= scheme ":" hier-part [ "?" query ] [ "#" fragment ]
	 *
	 *	relative-ref	= relative-part [ "?" query ] [ "#" fragment ]
	 *
	 *	hier-part	= "//" authority path-abempty
	 *			/ path-absolute
	 *			/ path-rootless
	 *			/ path-empty
	 *
	 *	relative-part	= "//" authority path-abempty
	 *			/ path-absolute
	 *			/ path-noscheme
	 *			/ path-empty
	 *
	 *	authority	= [ userinfo "@" ] host [ ":" port ]
	 *
	 * So, a URL has the following major components:
	 *
	 *	scheme
	 *		The name of a method used to interpret the rest of
	 *		the URL.  Examples:  "http", "https", "mailto", "file'.
	 *
	 *	authority
	 *		The name of the authority governing the URL's name
	 *		space.  Examples:  "example.com", "user@example.com",
	 *		"example.com:80", "user:password@example.com:80".
	 *
	 *		The authority may include a host name, port number,
	 *		user name, and password.
	 *
	 *		The host may be a name, an IPv4 numeric address, or
	 *		an IPv6 numeric address.
	 *
	 *	path
	 *		The hierarchical path to the URL's resource.
	 *		Examples:  "/index.htm", "/scripts/page.php".
	 *
	 *	query
	 *		The data for a query.  Examples:  "?search=google.com".
	 *
	 *	fragment
	 *		The name of a secondary resource relative to that named
	 *		by the path.  Examples:  "#section1", "#header".
	 *
	 * An "absolute" URL must include a scheme and path.  The authority, query,
	 * and fragment components are optional.
	 *
	 * A "relative" URL does not include a scheme and must include a path.  The
	 * authority, query, and fragment components are optional.
	 *
	 * This function splits the $url argument into the following components
	 * and returns them in an associative array.  Keys to that array include:
	 *
	 *	"scheme"	The scheme, such as "http".
	 *	"host"		The host name, IPv4, or IPv6 address.
	 *	"port"		The port number.
	 *	"user"		The user name.
	 *	"pass"		The user password.
	 *	"path"		The path, such as a file path for "http".
	 *	"query"		The query.
	 *	"fragment"	The fragment.
	 *
	 * One or more of these may not be present, depending upon the URL.
	 *
	 * Optionally, the "user", "pass", "host" (if a name, not an IP address),
	 * "path", "query", and "fragment" may have percent-encoded characters
	 * decoded.  The "scheme" and "port" cannot include percent-encoded
	 * characters and are never decoded.  Decoding occurs after the URL has
	 * been parsed.
	 *
	 * Parameters:
	 * 	url		the URL to parse.
	 *
	 * 	decode		an optional boolean flag selecting whether
	 * 			to decode percent encoding or not.  Default = TRUE.
	 *
	 * Return values:
	 * 	the associative array of URL parts, or FALSE if the URL is
	 * 	too malformed to recognize any parts.
	 */
	public static function split_url($url, $decode=TRUE)
	{
		// Character sets from RFC3986.
		$xunressub     = 'a-zA-Z\d\-._~\!$&\'()*+,;=';
		$xpchar        = $xunressub . ':@%';

		// Scheme from RFC3986.
		$xscheme        = '([a-zA-Z][a-zA-Z\d+-.]*)';

		// User info (user + password) from RFC3986.
		$xuserinfo     = '((['  . $xunressub . '%]*)' .
						 '(:([' . $xunressub . ':%]*))?)';

		// IPv4 from RFC3986 (without digit constraints).
		$xipv4         = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';

		// IPv6 from RFC2732 (without digit and grouping constraints).
		$xipv6         = '(\[([a-fA-F\d.:]+)\])';

		// Host name from RFC1035.  Technically, must start with a letter.
		// Relax that restriction to better parse URL structure, then
		// leave host name validation to application.
		$xhost_name    = '([a-zA-Z\d-.%]+)';

		// Authority from RFC3986.  Skip IP future.
		$xhost         = '(' . $xhost_name . '|' . $xipv4 . '|' . $xipv6 . ')';
		$xport         = '(\d*)';
		$xauthority    = '((' . $xuserinfo . '@)?' . $xhost .
					 '?(:' . $xport . ')?)';

		// Path from RFC3986.  Blend absolute & relative for efficiency.
		$xslash_seg    = '(/[' . $xpchar . ']*)';
		$xpath_authabs = '((//' . $xauthority . ')((/[' . $xpchar . ']*)*))';
		$xpath_rel     = '([' . $xpchar . ']+' . $xslash_seg . '*)';
		$xpath_abs     = '(/(' . $xpath_rel . ')?)';
		$xapath        = '(' . $xpath_authabs . '|' . $xpath_abs .
				 '|' . $xpath_rel . ')';

		// Query and fragment from RFC3986.
		$xqueryfrag    = '([' . $xpchar . '/?' . ']*)';

		// URL.
		$xurl          = '^(' . $xscheme . ':)?' .  $xapath . '?' .
						 '(\?' . $xqueryfrag . ')?(#' . $xqueryfrag . ')?$';


		// Split the URL into components.
		if ( !preg_match( '!' . $xurl . '!', $url, $m ) )
			return FALSE;

		if ( !empty($m[2]) )		$parts['scheme']  = strtolower($m[2]);

		if ( !empty($m[7]) ) {
			if ( isset( $m[9] ) )	$parts['user']    = $m[9];
			else			$parts['user']    = '';
		}
		if ( !empty($m[10]) )		$parts['pass']    = $m[11];

		if ( !empty($m[13]) )		$h=$parts['host'] = $m[13];
		else if ( !empty($m[14]) )	$parts['host']    = $m[14];
		else if ( !empty($m[16]) )	$parts['host']    = $m[16];
		else if ( !empty( $m[5] ) )	$parts['host']    = '';
		if ( !empty($m[17]) )		$parts['port']    = $m[18];

		if ( !empty($m[19]) )		$parts['path']    = $m[19];
		else if ( !empty($m[21]) )	$parts['path']    = $m[21];
		else if ( !empty($m[25]) )	$parts['path']    = $m[25];

		if ( !empty($m[27]) )		$parts['query']   = $m[28];
		if ( !empty($m[29]) )		$parts['fragment']= $m[30];

		if ( !$decode )
			return $parts;
		if ( !empty($parts['user']) )
			$parts['user']     = rawurldecode( $parts['user'] );
		if ( !empty($parts['pass']) )
			$parts['pass']     = rawurldecode( $parts['pass'] );
		if ( !empty($parts['path']) )
			$parts['path']     = rawurldecode( $parts['path'] );
		if ( isset($h) )
			$parts['host']     = rawurldecode( $parts['host'] );
		if ( !empty($parts['query']) )
			$parts['query']    = rawurldecode( $parts['query'] );
		if ( !empty($parts['fragment']) )
			$parts['fragment'] = rawurldecode( $parts['fragment'] );
		return $parts;
	}
	
	/**
	 * This function joins together URL components to form a complete URL.
	 *
	 * RFC3986 specifies the components of a Uniform Resource Identifier (URI).
	 * This function implements the specification's "component recomposition"
	 * algorithm for combining URI components into a full URI string.
	 *
	 * The $parts argument is an associative array containing zero or
	 * more of the following:
	 *
	 *	"scheme"	The scheme, such as "http".
	 *	"host"		The host name, IPv4, or IPv6 address.
	 *	"port"		The port number.
	 *	"user"		The user name.
	 *	"pass"		The user password.
	 *	"path"		The path, such as a file path for "http".
	 *	"query"		The query.
	 *	"fragment"	The fragment.
	 *
	 * The "port", "user", and "pass" values are only used when a "host"
	 * is present.
	 *
	 * The optional $encode argument indicates if appropriate URL components
	 * should be percent-encoded as they are assembled into the URL.  Encoding
	 * is only applied to the "user", "pass", "host" (if a host name, not an
	 * IP address), "path", "query", and "fragment" components.  The "scheme"
	 * and "port" are never encoded.  When a "scheme" and "host" are both
	 * present, the "path" is presumed to be hierarchical and encoding
	 * processes each segment of the hierarchy separately (i.e., the slashes
	 * are left alone).
	 *
	 * The assembled URL string is returned.
	 *
	 * Parameters:
	 * 	parts		an associative array of strings containing the
	 * 			individual parts of a URL.
	 *
	 * 	encode		an optional boolean flag selecting whether
	 * 			to do percent encoding or not.  Default = true.
	 *
	 * Return values:
	 * 	Returns the assembled URL string.  The string is an absolute
	 * 	URL if a scheme is supplied, and a relative URL if not.  An
	 * 	empty string is returned if the $parts array does not contain
	 * 	any of the needed values.
	 */
	public static function join_url($parts, $encode=TRUE)
	{
		if ( $encode )
		{
			if ( isset( $parts['user'] ) )
				$parts['user']     = rawurlencode( $parts['user'] );
			if ( isset( $parts['pass'] ) )
				$parts['pass']     = rawurlencode( $parts['pass'] );
			if ( isset( $parts['host'] ) &&
				!preg_match( '!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host'] ) )
				$parts['host']     = rawurlencode( $parts['host'] );
			if ( !empty( $parts['path'] ) )
				$parts['path']     = preg_replace( '!%2F!ui', '/',
					rawurlencode( $parts['path'] ) );
			if ( isset( $parts['query'] ) )
				$parts['query']    = rawurlencode( $parts['query'] );
			if ( isset( $parts['fragment'] ) )
				$parts['fragment'] = rawurlencode( $parts['fragment'] );
		}

		$url = '';
		if ( !empty( $parts['scheme'] ) )
			$url .= $parts['scheme'] . ':';
		if ( isset( $parts['host'] ) )
		{
			$url .= '//';
			if ( isset( $parts['user'] ) )
			{
				$url .= $parts['user'];
				if ( isset( $parts['pass'] ) )
					$url .= ':' . $parts['pass'];
				$url .= '@';
			}
			if ( preg_match( '!^[\da-f]*:[\da-f.:]+$!ui', $parts['host'] ) )
				$url .= '[' . $parts['host'] . ']';	// IPv6
			else
				$url .= $parts['host'];			// IPv4 or name
			if ( isset( $parts['port'] ) )
				$url .= ':' . $parts['port'];
			if ( !empty( $parts['path'] ) && $parts['path'][0] != '/' )
				$url .= '/';
		}
		if ( !empty( $parts['path'] ) )
			$url .= $parts['path'];
		if ( isset( $parts['query'] ) )
			$url .= '?' . $parts['query'];
		if ( isset( $parts['fragment'] ) )
			$url .= '#' . $parts['fragment'];
		return $url;
	}
	
	/**
	 * Combine a base URL and a relative URL to produce a new
	 * absolute URL.  The base URL is often the URL of a page,
	 * and the relative URL is a URL embedded on that page.
	 *
	 * This function implements the "absolutize" algorithm from
	 * the RFC3986 specification for URLs.
	 *
	 * This function supports multi-byte characters with the UTF-8 encoding,
	 * per the URL specification.
	 *
	 * Parameters:
	 * 	baseUrl		the absolute base URL.
	 *
	 * 	url		the relative URL to convert.
	 *
	 * Return values:
	 * 	An absolute URL that combines parts of the base and relative
	 * 	URLs, or FALSE if the base URL is not absolute or if either
	 * 	URL cannot be parsed.
	 */
	public static function url_to_absolute($baseUrl, $relativeUrl)
	{
		// If relative URL has a scheme, clean path and return.
		$r = self::split_url( $relativeUrl );
		if ( $r === FALSE )
			return FALSE;
		if ( !empty( $r['scheme'] ) )
		{
			if ( !empty( $r['path'] ) && $r['path'][0] == '/' )
				$r['path'] = self::url_remove_dot_segments( $r['path'] );
			return self::join_url( $r );
		}

		// Make sure the base URL is absolute.
		$b = self::split_url( $baseUrl );
		
		if ( $b === FALSE || empty( $b['scheme'] ) || empty( $b['host'] ) )
			return FALSE;
		$r['scheme'] = $b['scheme'];

		// If relative URL has an authority, clean path and return.
		if ( isset( $r['host'] ) )
		{
			if ( !empty( $r['path'] ) )
				$r['path'] = self::url_remove_dot_segments( $r['path'] );
			return self::join_url( $r );
		}
		unset( $r['port'] );
		unset( $r['user'] );
		unset( $r['pass'] );

		// Copy base authority.
		$r['host'] = $b['host'];
		if ( isset( $b['port'] ) ) $r['port'] = $b['port'];
		if ( isset( $b['user'] ) ) $r['user'] = $b['user'];
		if ( isset( $b['pass'] ) ) $r['pass'] = $b['pass'];

		// If relative URL has no path, use base path
		if ( empty( $r['path'] ) )
		{
			if ( !empty( $b['path'] ) )
				$r['path'] = $b['path'];
			if ( !isset( $r['query'] ) && isset( $b['query'] ) )
				$r['query'] = $b['query'];
			return self::join_url( $r );
		}

		// If relative URL path doesn't start with /, merge with base path
		if ( $r['path'][0] != '/' )
		{
			$base = mb_strrchr( $b['path'], '/', TRUE, 'UTF-8' );
			if ( $base === FALSE ) $base = '';
			$r['path'] = $base . '/' . $r['path'];
		}
		$r['path'] = self::url_remove_dot_segments( $r['path'] );
		return self::join_url( $r );
	}

	/**
	 * Filter out "." and ".." segments from a URL's path and return
	 * the result.
	 *
	 * This function implements the "remove_dot_segments" algorithm from
	 * the RFC3986 specification for URLs.
	 *
	 * This function supports multi-byte characters with the UTF-8 encoding,
	 * per the URL specification.
	 *
	 * Parameters:
	 * 	path	the path to filter
	 *
	 * Return values:
	 * 	The filtered path with "." and ".." removed.
	 */
	public static function url_remove_dot_segments($path)
	{
		// multi-byte character explode
		$inSegs  = preg_split( '!/!u', $path );
		$outSegs = array( );
		foreach ( $inSegs as $seg )
		{
			if ( $seg == '' || $seg == '.')
				continue;
			if ( $seg == '..' )
				array_pop( $outSegs );
			else
				array_push( $outSegs, $seg );
		}
		$outPath = implode( '/', $outSegs );
		if ( $path[0] == '/' )
			$outPath = '/' . $outPath;
		// compare last multi-byte character against '/'
		if ( $outPath != '/' &&
			(mb_strlen($path)-1) == mb_strrpos( $path, '/', 'UTF-8' ) )
			$outPath .= '/';
		return $outPath;
	}
}
