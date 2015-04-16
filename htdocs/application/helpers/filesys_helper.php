<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('unique_filename'))
{
	function unique_filename($dir, $prefix, $suffix = "")
	{
		$file_name = "";
		$dir = trim($dir);
		$prefix = trim($prefix);
		$suffix = trim($suffix);
		if(!empty($dir) && !empty($prefix))
		{
			$dir = rtrim($dir, "/");
			do{
				$now = time();
				$chunk = base64_encode(crypt("$now"));
				$chunk = substr($chunk, mt_rand(4, 33), 10);
				$name = "{$prefix}{$chunk}{$suffix}";
				$path = "$dir/$name";
			}while(file_exists($path));
			$file_name = $name;
		}
		return $file_name;
	}
}

/* End of file email_helper.php */
/* Location: ./system/helpers/email_helper.php */
