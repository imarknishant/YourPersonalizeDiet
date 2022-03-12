<?php
/**
 * @package   Puvox.software - reusable PHP class
 * @author    T.Todua <support@puvox.software>
 * @link      https://github.com/Puvox/useful-library-files/
 *
 *
 *		 #################################################################
 *		 ########## Base Library & Classes for all our plugins. ##########
 *		 #################################################################
 *       This is main library file for our development, where we collect frequently used  methods. There are two classes:
 * 			 1) Library of useful PHP functions 
 * 			 2) Library of useful Wordpress-specific functions 
 *	     ### Example usage: ###
 *			 $helpers = new \Puvox\standard_php_library();
 *			 ...  $helpers->get_visitor_ip();
 *			 ...  $helpers->get_last_child_of_array( $array );
 *
*/


namespace Puvox;

if (!class_exists('\\Puvox\\standard_php_library')) 
{
  class standard_php_library
  { 
	public function __construct()
	{ 
		$this->init_defaults();
	}
	

	public function constantX($var)           { return (defined($var) ? constant($var) : (!is_null($value) ? $value : false ) );}
	public function property($propertyName)   { return property_exists($this, $propertyName) ? $this->{$propertyName} : null; }
	public function print_r($obj,$silent=true){ return print_r($obj, $silent); }
	public function v($obj){ 
		echo '<pre>'; var_dump($obj); echo '</pre>'; 
	}
	public function vv($obj){
		$out = '<pre>'; 
		$content = $this->jsonPretty($obj); //print_r($obj, true);
		$out .= htmlentities( $this->br2nl( $content )) ;  
		try{ 	
			$trace = debug_backtrace();
			if ( isset($trace[1]) )
				$out .= ($this->isCli() ? ' [' : '<span style="font-size:0.6em; margin:1px; padding:1px; background:pink;">') .$this->array_value( $trace[1],'file','').':'.$this->array_value( $trace[1],'line',''). ($this->isCli() ? '] ':'</span>'); 
		}
		catch(\Exception $e){} 
		$out .= '</pre>'; 
		return $out;
	} 
	public function vx($obj){ $this->vv($obj); exit; }
	public function var_dump($obj, $echo=true){ 
		if (is_a($obj,'Exception')) $obj = $this->ExceptionMessage($obj);
		$out= $this->vv($obj) ; 
		if ($echo) {echo $out."\n";} else return $out; 
	}
	public function var_dumpx($obj, $echo=true){ $this->var_dump($obj, $echo); exit; }
	public function ExceptionMessage($ex, $extended=true){ 	return "Exception Message: {$ex->getMessage()} \r\n[{$ex->getTraceAsString()}] \r\n";	}  //[{$ex->getFile()}::{$ex->getLine()}]
	
	public function br2nl($string) { return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string); }
	public static function sleep($seconds){ 
		if ( self::swoole_inside_coroutine() ) 
			\Swoole\Coroutine\System::sleep($seconds); 
		else {
			if ( filter_var($seconds, FILTER_VALIDATE_INT) !== false)
				sleep($seconds);
			else
				usleep($seconds*1000000);
		} 
	}
	public function usleep($milliseconds){ if (self::swoole_inside_coroutine()) \Swoole\Coroutine\System::sleep($milliseconds/1000000); else usleep($milliseconds); }

	public function force_https(){
		if(!$this->is_https) {  header("Location: https://" . $this->domainReal . $_SERVER["REQUEST_URI"], true, 301); exit;  }
	}
	public function string_to_truefalse($string) { return ( $string ==='true' ? true : ($string ==='false' ? false : $string)); }
	public function truefalse_to_string($string) { return ( $string === true ? 'true' : ($string ===false ? 'false' : $string)); }
	public function bool_to_sign($string) { return ( $string===true ||  $string==="true" ? 1 : ( $string===false || $string==="false" ? -1 : 0) ); }

	public function jsonPretty($array_or_txt, $reparse=false) {  
		if(is_string($array_or_txt)) { 
			if ( $reparse && $this->is_JSON($array_or_txt)) 
				return json_decode($array_or_txt, JSON_PRETTY_PRINT); 
			return $array_or_txt;
		}
		else 
			return $this->stringify($array_or_txt, true);
	}
	public function stringify($data, $pretty=false) { 
		if( $this->is_simple_type($data) ) 
			return (!is_bool($data) ? $data : ($data? 'true':'false') );
		else{
			if ( is_a($data, 'Exception') || is_a($data, 'Exception') ){  
				return print_r($data,true);  //exception (https://pastebin_com/P73cgSkq) are only handled well by this
			}
			else{
				return ( $pretty ? json_encode($data, JSON_PRETTY_PRINT) : json_encode($data));  
			}
		}
	}
	public function textify_or_not($data) { 
		if( $this->is_simple_type($data) ) 
			return (!is_bool($data) ? $data : ($data? 'true':'false') );
		else{
			return json_encode($data);
		}
	}
	public static function uniqueId($args, $addition=''){ return md5(json_encode($args)."_$addition"); }

	public function stripUnwantedTagsAndAttrs($html_str, $tags=null){
		$xml = new DOMDocument();
		libxml_use_internal_errors(true);
		$allowed_tags = is_null($tags) ? ["html", "body", "b", "br", "em", "hr", "i", "li", "ol", "p", "s", "span", "table", "tr", "td", "u", "ul"] :'';
		$allowed_attrs = ["class", "id", "style"];
		if (!strlen($html_str)){return false;}
		if ($xml->loadHTML($html_str, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)){
			foreach ($xml->getElementsByTagName("*") as $tag){
				if (!in_array($tag->tagName, $allowed_tags)){
					$tag->parentNode->removeChild($tag);
				}else{
					foreach ($tag->attributes as $attr){
						if (!in_array($attr->nodeName, $allowed_attrs)){
							$tag->removeAttribute($attr->nodeName);
						}
					}
				}
			}
		}
		return $xml->saveHTML();
	}


	public function get_visitor_ip() {
		$proxy_headers = array("CLIENT_IP", "FORWARDED", "FORWARDED_FOR", "FORWARDED_FOR_IP", "HTTP_CLIENT_IP", "HTTP_FORWARDED", "HTTP_FORWARDED_FOR", "HTTP_FORWARDED_FOR_IP", "HTTP_PC_REMOTE_ADDR", "HTTP_PROXY_CONNECTION", "HTTP_VIA", "HTTP_X_FORWARDED", "HTTP_X_FORWARDED_FOR", "HTTP_X_FORWARDED_FOR_IP", "HTTP_X_IMFORWARDS", "HTTP_XROXY_CONNECTION", "VIA", "X_FORWARDED", "X_FORWARDED_FOR");
		foreach($proxy_headers as $proxy_header) {
			if (isset($_SERVER[$proxy_header])) {
				if(preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $_SERVER[$proxy_header])) {
					return $_SERVER[$proxy_header];
				}
				else if (stristr(",", $_SERVER[$proxy_header]) !== FALSE) {
					$proxy_header_temp = trim(array_shift(explode(",", $_SERVER[$proxy_header])));
					if (($pos_temp = stripos($proxy_header_temp, ":")) !== FALSE) {$proxy_header_temp = substr($proxy_header_temp, 0, $pos_temp); }
					if (preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $proxy_header_temp)) { return $proxy_header_temp; }
				}
			}
		}
		return ( !empty($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '__UNDEFINED_REMOTE_ADDR__');
	}

	public function mail_scrambler($email) {  return str_replace('@', '&#64;', $email);}



	public function expire_headers()
	{
		ini_set('session.cookie_httponly', 1);		
			//always display as new
		header("Cache-Control: no-cache, must-revalidate, max-age=0");
			//expired in past
		header("Expires: ".			date	('D, d M Y H:i:s', time() - 86400 *2) . " GMT");
		header("Vary: Accept-Encoding");
		header("Last-Modified: ".	gmdate	("D, d M Y H:i:s", time() - 86400 *2) . " GMT"); 
	}
	public static function var_dump_umlimited(){
		ini_set("xdebug.var_display_max_children", '-1');
		ini_set("xdebug.var_display_max_data", '10000');
		ini_set("xdebug.var_display_max_depth", '-1');
	}

	public static function currentUrlContains($phrase,$case_sens=true){
		return self::contains($_SERVER['REQUEST_URI'], $phrase, $case_sens);
	}

	public function change_max_upload_post()
	{
		if (property_exists($this,'upload_max_limit')) 
		{
			$this->upload_max_limit = max($this->upload_max_limit, ini_get('post_max_size'));
			ini_set('post_max_size', $this->upload_max_limit.'M'); ini_set('upload_max_filesize', upload_max_limit.'M');   ini_set('upload_max_size', upload_max_limit.'M');
		}		
	}


	#region    timers
	public function t1()	{ $this->timerstart();	}
	public function t2()	{ $this->timermiddle();	}
	public function t3()	{ $this->timerend();	}
	public function timerstart($echo=false)	{ $this->timer_started= microtime(true); if ($echo) echo '<pre>'.self::number_format( $this->timer_started ).'</pre>';	}
	public function timermiddle($echo=false){ $this->timer_middle = microtime(true); if ($echo) echo '<pre>'.self::number_format( $this->timer_middle ).'</pre>';	}
	public function timerend($echo=false)		
	{ 
		$this->timer_ended	=microtime(true);  if ($echo) echo '<pre>'.self::number_format( $this->timer_ended ).'</pre>'; 
		if(!empty($this->timer_middle)) {
			echo '<br/>^Start - Middle:'.self::number_format($first=($this->timer_middle-$this->timer_started) );
			echo '<br/>^Middle - End : '.self::number_format($second=($this->timer_ended-$this->timer_middle) ) ;	 echo ' [<b style="color:red;">' . round( max($first,$second)/min($first,$second), 2)  . '</b>x diff]<br/>';  
		}
		echo "<br/>^Whole cycle: ". self::number_format($this->timer_ended - $this->timer_started) ;
		exit;	
	}
	public function timernow($name){
		echo $name . ": "; 
		$now =  floatval(microtime(true));
		if(empty($this->lastime)) echo "start" ; else  $this->decimal_outputer($now - $this->lastime ) ;
		$this->lastime=  floatval(microtime(true));
		echo "\r\n";
	}	

	public function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	public function microtime()	{ return $this->microtime_float();	}

	//only for explicit call
	public function test_load_times_1(callable $func1, $iterations=1000)
	{
		var_dump($this->microtime(true));
		for ($i=1; $i<$iterations; $i++) { $func1(); }
		var_dump($this->microtime(true));
	}
	#endregion

	//  if ( is_admin() && file_exists($lib_start=__DIR__."/$name") && !defined("_puvox_machine_") ) { rename($lib_start, $lib_final); } require_once($lib_final);

	public function convert_urls_in_text($text) {
		return preg_replace('@([^\"\']https?://([-\w\.]+)+(:\d+)?(/([\w/_\.%-=#][^<]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $text);
	}

	public function randomString($length = 11) {
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1, $length);    //random_stringg($length= 15){ return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);}
	}

	public function PlainString(&$text1=false,&$text2=false,&$text3=false,&$text4=false,&$text5=false,&$text6=false,&$text7=false,&$text8=false){
		for($i=1; $i<=8; $i++){    if(${'text'.$i}) {${'text'.$i} = preg_replace('/\W/si','',${'text'.$i});} 	}
		return $text1;
	}

	public function adjustedUrlPrefixes($url){
		if(strpos($url, '://') !== false){
			return preg_replace('/^(http(s|)|):\/\/(www.|)/i', 'https://www.', $url);
		}
		else{
			return 'https://www.'.$url;
		}
	}

	public function remove_www($url) 	{ 
		return str_replace( ['://www.'], '://', $url ); 
	}

	public function remove_https_www($url){
		return str_replace( ['https://www.','http://www.','http://','https://'], '', $url ); 
	}

	public function slashesForward($url, $add_trailing_slash=true){ 
		return $this->remove_double_slashes ( str_replace(['\\','/'], '/', $url) );
	}
	
	public function slashesBackward($url, $add_trailing_slash=true){ 
		return $this->remove_double_slashes ( str_replace(['\\','/'], '\\', $url) );
	}
	
	public function normalize_with_slashes($url, $add_trailing_slash=true){ 
		return rtrim( $this->OneSlash($url), '/')  . ($add_trailing_slash ? '/' : '') ; 
	}

	public function OneSlash($url){
		$prefix='';
		if(substr($url,0,2)=='//'){
			$prefix = '//';
			$url=substr($url,2);
		}
		return $prefix.preg_replace( '/([^:])\/\//',  '$1/', $url);
	}
	
	//function to replace double-slashes with one slashes
	public function remove_double_slashes($input){
		$isSchemed = stripos($input, '://') !==false;
		$input=str_replace('//','/', $input);  $input=str_replace('\\\\','\\', $input);  return ($isSchemed ? str_replace(':/','://', $input) : $input);
	}
	
	public function replace_slashes($path){
		return 	str_replace( ['/','\\',DIRECTORY_SEPARATOR], '/', $path); 
	}
	public function remove_extra_slashes($path){
		return 	str_replace( '//', '/', $path); 
	}
	
	public function urlify($path){
		return str_replace( '\\', "/", $path); 
	}
	public function IsRestirctedDirecotryRequested($url=false, $dieORreturn=true ){ if (!$url) {$url=$_SERVER['REQUEST_URI'];}
		$url =stripslashes($url);
		if (  stristr($url,'\\')  ||   substr($url, 0, 2)=='..' || stristr($url,'../')  ||  stristr($url,'/..')  ||  stristr($url,'?')  ||  stristr($url,'*')  ||  stristr($url,'.php')	){
			if ($dieORreturn) {die("incorrect path requested.. error4292");} 	else{ return true;}
		}
	}

	public function directory_separatored($path){
		return str_replace(array('/','\\'),DIRECTORY_SEPARATOR, $path); 
	}

	// https://www.php.net/manual/en/function.realpath.php
	public static function realpath($path){
        // Cleaning path regarding OS
        $path = mb_ereg_replace('\\\\|/', DIRECTORY_SEPARATOR, $path, 'msr');
        // Check if path start with a separator (UNIX)
        $startWithSeparator = $path[0] === DIRECTORY_SEPARATOR;
        // Check if start with drive letter
        preg_match('/^[a-z]:/', $path, $matches);
        $startWithLetterDir = isset($matches[0]) ? $matches[0] : false;
        // Get and filter empty sub paths
        $subPaths = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'mb_strlen');

        $absolutes = [];
        foreach ($subPaths as $subPath) {
            if ('.' === $subPath) {
                continue;
            }
            // if $startWithSeparator is false
            // and $startWithLetterDir
            // and (absolutes is empty or all previous values are ..)
            // save absolute cause that's a relative and we can't deal with that and just forget that we want go up
            if ('..' === $subPath
                && !$startWithSeparator
                && !$startWithLetterDir
                && empty(array_filter($absolutes, function ($value) { return !('..' === $value); }))
            ) {
                $absolutes[] = $subPath;
                continue;
            }
            if ('..' === $subPath) {
                array_pop($absolutes);
                continue;
            }
            $absolutes[] = $subPath;
        }

        return
            (($startWithSeparator ? DIRECTORY_SEPARATOR : $startWithLetterDir) ?
                $startWithLetterDir.DIRECTORY_SEPARATOR : ''
            ).implode(DIRECTORY_SEPARATOR, $absolutes);
	}






	public function stripUrlPrefixes($url){
		return preg_replace('/http(s|):\/\/(www.|)/i', '',  $url);
	}

	public function getDomain($url){
		return preg_replace('/http(s|):\/\/(www.|)(.*?)(\/.*|$)/i', '$3', $url);
	}

	public function stripDomain($url){
		return str_replace( $this->adjustedUrlPrefixes($this->domainReal), '', $this->adjustedUrlPrefixes($url) );
	}

	// i.e. 5m, 1H, 2H, 1D, 240M, etc...
	public function stockTF_to_seconds($string, $minuteSymbol="m", $monthSymbol="M"){
		$res=$string;
		$arr=['s'=>1, 'S'=>1, $minuteSymbol=>1*60, 'h'=>60*60, 'H'=>60*60, 'd'=>24*60*60, 'D'=>24*60*60, 'w'=>7*24*60*60, 'W'=>7*24*60*60, $monthSymbol=>31*24*60*60, 'y'=>365*24*60*60, 'Y'=>365*24*60*60];
		foreach ($arr as $key=>$val) { if (empty($key)) continue; if (strpos($string, $key)!==false) { $res = str_ireplace($key, '', $string) * $val; break; }  }
		return $res;
	}
	
 

	public function str_replace_last($search, $replace, $subject)
	{
		$pos = strrpos($subject, $search);
		if($pos !== false) $subject = substr_replace($subject, $replace, $pos, strlen($search)); 
		return $subject;
	}
	
	public function str_replace_first($from, $to, $content, $type="plain"){
		if($type=="plain"){
			$pos = strpos($content, $from);
			if ($pos !== false) {
				$content = substr_replace($content, $to, $pos, strlen($from));
			}
			return $content;
		}
		elseif($type=="regex"){
			$from = '/'.preg_quote($from, '/').'/';
			return preg_replace($from, $to, $content, 1);
		}
	}
	
	public function timeMS(){ return round(microtime(true)*1000); }
	
	public function toString($inp){
		return $inp."";
	}
	public function contains_numeric($str){
		$str=$this->toString($str);
		for($i=0; $i<=9; $i++) {
			if (strpos($str, $this->toString($i) )!==false){
				return true;
			}
		}
		return false;
	}
	
	public function dayForTime($time){
		return strtotime(date('Y-m-d', $time));
	}
	public function currentDatetime($time='', $MS=false){
		$format = 'Y-m-d H:i:s'.($MS?'.v':'') ;
		return ( !empty($time) ? gmdate($format, $time) : gmdate($format) );
	}
	public static function timeToDate($time, $ms=000){
		return self::time_to_date($time, $ms=000);
	}
	public static function time_to_date($time, $ms=000){
		return date("Y-m-d H:i:s.$ms", $time);
	}
	public static function timeToDateTZ($time){
		$time_rounded = floor($time);
		// if milliseconds
		if ($time_rounded>1000000000000){
			$ms = $time_rounded % 1000;
			$timeS= floor($time_rounded/1000);
			$dat = date("Y-m-d\TH:i:s.$ms\Z", $timeS);
		}
		else{
			$dat = date("Y-m-d\TH:i:s.000\Z", $time_rounded);
		}
		return $dat;
	}
	
	public function isWeekend($time){
		return date('N',$time) > 5;
	}

	public static function safemode_basedir_set(){
		return ( ini_get('open_basedir') || ini_get('safe_mode') ) ;
	}
	public static function header($type){
		switch ($type){
			case "json" : header('Content-Type: application/json;  charset=utf-8'); break;
			case "text" : header('Content-Type: text/plain;  charset=utf-8'); break;
			case "js"   : header('Content-Type: application/javascript;  charset=utf-8'); break;
		}
	}

	public function file_get_contents($path, $createIfNotExistWithContent=null )
	{
		$path = self::realpath($path);  
		if (!file_exists($path)){
			if (is_null($createIfNotExistWithContent)){
				return "";
			}
			else{
				if (method_exists($this,'localdata_set')){
					$this->localdata_set($path, $createIfNotExistWithContent);
				}
				return $createIfNotExistWithContent;
			}
		} 
		else {
			// is_readable( $path ) will not work here, because of LOCK-waiting
			$wait_for_lock_seconds= 0.2;
			if($fp = fopen($path,'r')) {
				$startTime = microtime(true);
				do{
					$canRead = flock($fp, LOCK_SH);
					if(!$canRead) {
						self::sleep( 0.01 );   // Releases CPU for others
					}
					// If the lock is not acquired and the timeout has not expired, continue to acquire the lock
				} while((!$canRead) && ((microtime(true) - $startTime) < $wait_for_lock_seconds ));
				if($canRead) {
					//file_get_contents($path)
					$contents=file_get_contents($path); // fread($fp, filesize($path));
					flock($fp,LOCK_UN);  
				}
				else{
					throw new \Exception("Could not get read-access to file $path");
				}
				fclose($fp);
				return $contents;
			}
			else{
				throw new \Exception("Could not open access to file $path");
			}
		}
	}


	public static function exitPlain($content, $encode=false){
		self::header('text');
		if ($encode) $content = json_encode($content);
		print($content); exit;
	}

	public static function exitJson($content){
		self::header('json');
		if (is_array($content)) $content = json_encode($content);
		exit($content);
	}

	public function try_increase_exec_time($seconds, $memory=null){
		if( ! $this-> safemode_basedir_set() ) {
			if(!is_null($memory)) $this->try_increase_memory($memory);
			return ini_set('max_execution_time', $seconds); //stackoverflow.com/questions/8914257
		}
		return false;
	}

	public static function set_memory_limit($new_limit = 'mbs'){
		if( ! self::safemode_basedir_set() ) {
			$limitBytes = $new_limit * 1048576;
			$currentLimit = trim(ini_get('memory_limit'));
			$lastChar = strtolower($currentLimit[strlen((int) $currentLimit)-1]);
			switch($lastChar) {
				case 'g': $currentLimit *= 1024;
				case 'm': $currentLimit *= 1024;
				case 'k': $currentLimit *= 1024;
			}
			if ($currentLimit < $limitBytes)
				return ini_set('memory_limit', $new_limit . 'M');
		}
		return false;
	}

	// find defined bits/flags/enums
	public static function binding_flags($flag)
	{
		$setBits = array();
		for ($i = 1; $i <= 32; $i++) {
			if ($flag & (1 << $i)) {
				$setBits[] = (1 << $i);
			}
		}
	
		// Sort array to order the bits
		sort($setBits);
	
		return $setBits;
	}
	// find if bit/flag/enum is defined
	public static function flag_exists($existing, $target){
		return in_array($target,self::binding_flags($existing));//$target & (1 << $target);
	}

	public function MessageAgainstMaliciousAttempt(){
		return 'Not allowed. Try again.';//'Well... I know that these words won\'t change you, but I\'ll do it again: Developers try to create a balance & harmony in internet, and some people like you try to steal things from other people. Even if you can it, please don\'t do that.';
	}

	public function link($link, $text){ return $this->href_url($link, $text);  }
	public function href_url($link, $text){ return '<a href="'.$link.'" target="_blank">'.$text.'</a>'; }
	public function href($link, $text){ return $this->href_url($link, $text); }

	public function mkdir($dest, $permissions=0755, $create=true){ return $this->mkdir_recursive($dest, $permissions, $create); }
	public function mkdir_recursive($dest, $permissions=0755, $create=true){
		if(!is_dir($dest)){
			//at first, recursively create parent directory if doesn't exist
			$parent = dirname($dest);
			if( !is_dir($parent ) ){ $this->mkdir_recursive($parent, $permissions, $create); }
			else {
				if ( is_writable( $parent ) ){
					return mkdir($dest, $permissions, $create); 
				}
				else{
					var_dump("This plugin don't have permission to create directory: $parent");
					return false;
				}
				
			}
		}
		else{return true;}
	}

	public function rmdir($dirPath){ return $this->rmdir_recursive($dirPath); }
	public function rmdir_recursive($dirPath){
		if(!empty($dirPath) && is_dir($dirPath) ){
			$dir  = new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS); //upper dirs not included,otherwise DISASTER HAPPENS :)
			$files = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::CHILD_FIRST);
			foreach ($files as $path) $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname()); //{if (is_file($path)) {unlink($path);} else {$empty_dirs[] = $path;} } if (!empty($empty_dirs)) {foreach ($empty_dirs as $eachDir) {rmdir($eachDir);}} 
			rmdir($dirPath);
			return true;
		}
		return true;
		//include_once(ABSPATH.'/wp-admin/includes/class-wp-filesystem-base.php');
		//\WP_Filesystem_Base::rmdir($fullPath, true);
	}
	public function emptyDir($dirPath){
		return array_map( 'unlink', array_filter((array) glob("$dirPath/*") ) );
	}
	
	public function copy_recursive($source, $dest, $permissions = 0755){
		if (is_link($source))	{ return symlink(readlink($source), $dest); }
		elseif (is_file($source))	{ 
			if(!file_exists(dirname($dest))){$this->mkdir_recursive(dirname($dest), $permissions, true); }
			if(!copy($source, $dest)) {echo "not copied ($source ---> $dest )";} return true; 
		}
		elseif (is_dir($source))	{ 
			$this->mkdir_recursive($dest, $permissions, true); 
			foreach (glob($source.'/*') as $each){	$basen= basename($each);
				if ($basen != '.' && $basen != '..') { $this->copy_recursive("$each", "$dest/$basen", $permissions);	}
			}
		}
	}

    // ##### CUSTOM OPTIONS #####
	public function optsNameX1(){ return 'all_options_'.$this->module_NAMESPACE; }
	public function get_option($name, $defaultValue=null){ 
		if($this->isWP)
		{
			$opts = get_site_option($this->optsNameX1(),[]);
		}
		else{
			$opts = $this->get_option_json($name,$defaultValue); 
		}
		return is_null($name) ? $opts : (array_key_exists($name, $opts) ? $opts[$name] : '');
	}
	public function update_option($name, $value, $autoload=null){  
		$opts = $this->get_option($this->optsNameX1(),[]);
		if( !is_null($name) ){
			$opts[$name]= $value;
		}
		else{
			$opts = $value;
		}
		return ($this->isWP) ? update_site_option($this->optsNameX1(), $opts, $autoload) :  $this->update_option_json($name,$value,$autoload);  
	}

	public function add_my_site_options($array)
	{ 
		$this->extra_options_enabled=true;
		$this->_my_site_options=$this->get_option(null);
		$final=[];
		foreach($array as $key=>$value){
			$final[$key]=array_key_exists($key, $this->_my_site_options) ? $this->_my_site_options[$key] : $value;
		}
		if($this->_my_site_options!=$final) { $this->update_my_site_options($final); }
	}
	public function get_my_site_option($name=null, $default=null, $force_update=false)
	{
		$this->_my_site_options=$this->get_option($name);
		if ($name!=null)
		{
			if (! array_key_exists($name, $this->_my_site_options) || $force_update){
				$this->_my_site_options[$name]=$default;
				$this->update_my_site_options();
			}
			return $this->_my_site_options[$name];
		}
		return $this->_my_site_options;
	}
	public function update_my_site_options($array=null)
	{
		$this->update_option(null,  ( $array ?: $this->_my_site_options) );
	}



	// ########################### SQLITE ###########################
	//  $db = new \PDO('sqlite:'.$db_path);  $db ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	public function sqlite_db_init($db_path="/example/my.db"){
		try {
			$db = new \SQLite3($db_path);
		} catch(\Exception $ex) { 
			die('Error: '.$ex->getMessage());
		}
		return $db ;
	} 
	public function sqlite_create_table_PDO($db){
		$db->exec( "CREATE TABLE IF NOT EXISTS myValuesTable (
			id INTEGER PRIMARY KEY, 
			title TEXT, 
			text TEXT, 
			status TEXT, 
			time INTEGER)"
		);
	} 
	public function sqlite_insert_PDO($title, $text, $status, $time) {
        $sql = 'INSERT INTO myValuesTable(title,text,status,time) VALUES(:title,:text,:status,:time)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title'	=> $title,
            ':text'		=> $text,
            ':status'	=> $status,
            ':time' 	=> $time,
        ]);
        return $this->pdo->lastInsertId();
    } 
	public function sqlitePDO_update($id, $title, $text, $status, $time) {
        // SQL statement to update status of a task to completed
        $sql = "UPDATE myValuesTable SET "
				. "title  = :title, "
                . "text   = :text, "
                . "status = :status, "
                . "time   = :time "
                . "WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title'  => $title,
            ':text'   => $text,
            ':status' => $status,
            ':time'   => $time,
            ':id'     => $id,
        ]);
    }

	public function sqlitePDO_fetchAll($db, $tablename) {
		$which = '*'; //'project_id, project_name';//
        $stmt = $db->query('SELECT '.$which .' FROM '.$tablename);
        $res = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }
        return $res;
    }
	
    public function pdoCommand($db_name="db_all.db") { 
        $this->pdo	= $this->sqlite_db_init($db_name);
        $statement->execute(); 
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
	
	public function sqlite_create_table_TRANSLATIONS($db)
	{
		$sql =
		'CREATE TABLE IF NOT EXISTS translations (	
			ID INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
			program_name		VARCHAR(150),
			string				TEXT 	NOT NULL,
			lang				TEXT 	NOT NULL,
			value				TEXT 	NOT NULL,
			time				INT,
			suggestion			TEXT
		);';
		return $db->query($sql);     //possibles: VARCHAR(50)  ,  PRIMARY KEY (`ID`), UNIQUE KEY `ID` (`ID`) 	) AUTO_INCREMENT=1;'; 
	}	
	
	public function sqlite_insert_TRANSLATION($db, $string, $lang, $value, $time, $program_name, $suggestion) { 
		$statement = $db->prepare('INSERT INTO translations ( string, lang, value, time, program_name, suggestion ) VALUES (:string, :lang, :value, :time, :program_name,  :suggestion );');
		return $statement->execute([':string'=>$string, ':suggestion'=>$suggestion]);
	}
			
	public function sqlite_string_exists($string, $lang=false, $program_name=false) 
	{
		$statement = $this->db->prepare('SELECT * from translations where string= :string'. ( $lang? ' and lang = :lang' : '') .' LIMIT 1' ); // . ( $program_name? ' and program_name = :program_name' : '') 
		$statement->bindValue(':string',	$string);
			if ($lang)			
		$statement->bindValue(':lang',	$lang); 
		$res = $statement->execute();  
		return !empty($res->fetchArray(SQLITE3_ASSOC));
	}
	
	public function sqlite_get()
	{
		$statement = $this->db->prepare('SELECT * from translations where string= :string and lang= :lang ');  //. ( $program_name? ' program_name = :program_name' : '') 
		$statement->bindParam(':string',$string);
		$statement->bindParam(':lang',	$lang);
		$ret = $statement->execute(); 
		$res = $ret->fetchArray(SQLITE3_ASSOC);
		if(!empty($res)){
			$this->found=true;
			$return= $res['value'];
		}
	}
		//$current_day_logname = 'log_'.date('Y-m-d'). ($new_file ? time().$new_file : '') . '.txt'; 
		//$this->helpers->filecreate($current_day_logname,$data, FILE_APPEND); 
	// ########################### END SQLITE ###########################
	
	

	// DOM PARSER
	public function new_dom_document($content)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');
		$internalErrors = libxml_use_internal_errors(true);	//disable
		$dom->loadHTML( $content);
		libxml_use_internal_errors($internalErrors);		//restore
		$finder = new \DOMXpath( $dom );
		$nodes= $finder->query( "//*" );
		foreach ($nodes as $node) {
			if ($node->hasAttributes())
			{
				$error = $node->ownerDocument->saveHTML($node); break;
			}
		}
	}
	
	public function domDocument_load($content)
	{
		if (!property_exists($this,'tempDom')) $this->tempDom = new \DOMDocument('1.0', 'UTF-8');
		$internalErrors = libxml_use_internal_errors(true);	//disable
		$this->tempDom->loadHTML( $content);
		libxml_use_internal_errors($internalErrors);		//restore
		return $this->tempDom;
	}
	public function domDocument_remove($el)
	{
		$el->parentNode->removeChild($el);
	}
	public function domDocument_body($dom)
	{ 
		$body = $dom->getElementsByTagName('body');
		if ( $body && 0<$body->length ) {
			$body = $body->item(0);
			return $dom->savehtml($body);
		}
		return "-1";
	}

	
	public function domDocument_getElementById($dom, $idName, $showError=false) {
		try{
			return $dom->getElementById($idName)->nodeValue;
		}
		catch(\Exception $ex){
			return ($showError ? "DomError:".$ex->getMessage() : null);
		}
	}

	public function domDocument_getElementsByClassName($dom, $ClassName, $tagName=null) {
		$Elements = $tagName ? $dom->getElementsByTagName($tagName) : $dom->getElementsByTagName("*");
		$Matched = array();
		for($i=0;$i<$Elements->length;$i++) {
			if($Elements->item($i)->attributes->getNamedItem('class')){
				if($Elements->item($i)->attributes->getNamedItem('class')->nodeValue == $ClassName) {
					$Matched[]=$Elements->item($i);
				}
			}
		}
		return $Matched;
	}
	public function domDocument_getElementsByClass_2(&$parentNode, $tagName, $className) {
		$nodes=array();
		$childNodeList = $parentNode->getElementsByTagName($tagName);
		for ($i = 0; $i < $childNodeList->length; $i++) {
			$temp = $childNodeList->item($i);
			if (stripos($temp->getAttribute('class'), $className) !== false) {
				$nodes[]=$temp;
			}
		}

		return $nodes;
	}


	public function get_dom_element_data($html_data, $tag_type='id', $tag_key='')
	{
        $dom = new \DOMDocument('1.0', 'UTF-8');;  
        $dom->loadHTML($html_data, LIBXML_HTML_NOIMPLIED | LIBXML_DTDVALID | LIBXML_NOENT | LIBXML_NOERROR );   // https://www.php.net/manual/en/libxml.constants.php#constant.libxml-html-noimplied
        $dom->preserveWhiteSpace = false; //disact whitespace
        if( $tag_type=='id'){
			$json_obj = $dom->getElementById($tag_key); 
			$data = $json_obj->nodeValue;
		}
        try{
			return json_decode($data);
		}
		catch(\Exception $ex){
			return $data;
		}
	}
	// ========================

	
	public function include_dir($dir){ 
		foreach(glob($dir ."/*.php$") as $file) include_once($file);
	}


	public function filesContents($files=[], $inModule=true){ 
		$cont = '';
		foreach($files as $file){
			$cont .= $this->file_get_contents( ($inModule? $this->moduleDIR : '').$file);
		}
		return $cont;
	}

	// files collection
	public function globFiles($glob_pattern, $first='', $last='')
	{
		$files = glob($glob_pattern);
		$new_files =$files;
		$first_file='';
		$last_file ='';
		foreach($files as $file)
		{
			if( !empty($first) && strpos($file, $first)!==false ) {
				$first_file = $file;
				$new_files=array_diff( $new_files, [$file] );
			}
			if( !empty($last)  && strpos($file, $last) !==false ) {
				$last_file = $file;
				$new_files=array_diff( $new_files, [$file] );
			}
		}
		if( !empty($first_file) ) array_unshift($new_files, $first_file);
		if( !empty($last_file) ) array_push($new_files, $last_file);
		return $new_files;
	}

	public function fileUrl($file){ 
		return $this->moduleURL."/$file?vers_=".$this->filedate($this->moduleDIR. "/$file");
	}

	public function FullIframeScript(){ ?>
		<script>
		function MakeIframeFullHeight_tt(iframeElement, cycling, overwrite_margin){
			cycling= cycling || false;
			overwrite_margin= overwrite_margin || false;
			iframeElement.style.width	= "100%";
			var ifrD = iframeElement.contentDocument || iframeElement.contentWindow.document;
			var mHeight = parseInt( window.getComputedStyle( ifrD.documentElement).height );  // Math.max( ifrD.body.scrollHeight, .. offsetHeight, ....clientHeight,
			var margins = ifrD.body.style.margin + ifrD.body.style.padding + ifrD.documentElement.style.margin + ifrD.documentElement.style.padding;
			if(margins=="") { margins=0; if(overwrite_margin) {  ifrD.body.style.margin="0px"; } }
			(function(){
				var interval = setInterval(function(){
				if(ifrD.readyState  == 'complete' ){
					setTimeout( function(){
						if(!cycling) { setTimeout( function(){ clearInterval(interval);}, 500); }
						iframeElement.style.height	= (parseInt(window.getComputedStyle( ifrD.documentElement).height) + parseInt(margins)+1) +"px";
					}, 200 );
				}
				},200)
			})();
				//var funcname= arguments.callee.name;
				//window.setTimeout( function(){ console.log(funcname); console.log(cycling); window[funcname](iframeElement, cycling); }, 500 );
		}
		</script>
		<?php
	}




	public function stripslashes_from_strings_only( $value ) {
		return is_string( $value ) ? stripslashes( $value ) : $value;	
	}
	public function stripslashes_deep($value)
	{
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		return $value;
	}
	
	public function stripslashes_deep2($value){ 
		return $this->array_map_deep([$this,'stripslashes_from_strings_only'] , $value ); 
	}

	
    public function childrenCheckboxed($array, $keyname, $post_array=null)
    {
		$ref_Array= !is_null($post_array) ? $post_array : $array;
        foreach ($array as $key=>$name)
        {
            $array[$key][$keyname]= isset($ref_Array[$key][$keyname]);
        }
        return $array;
    }
	// ================================================





	//only open and close the same-origin creator of session  (argument can be TRUE/FALSE or STRING too
	public function session_state ($arg) { 
		if($arg===true)	{	if(session_status() == PHP_SESSION_NONE)	{ $GLOBALS['my_session_pp']='sess'.rand(1,99999999); session_start();  return $GLOBALS['my_session_pp']; }   	}     
		else			{	if(session_status() == PHP_SESSION_ACTIVE)	{ if(!$arg || $arg==$GLOBALS['my_session_pp']) session_write_close();  }   	}  
	}
	public function set_session_var ($name,$value) {
		$id= $this->session_state(true);
		$_SESSION[$name] = $value;
		$this->session_state($id);
	}
	
	public function startSessionIfNotStarted(){
		if(session_status() == PHP_SESSION_NONE)  { $this->session_being_opened = true; session_start();  }
	}
	public function endSessionIfWasStarted( $method=2){
		if(session_status() != PHP_SESSION_NONE && property_exists($this,"session_being_opened") )  {
			unset($this->session_being_opened);
			if($method==1) session_destroy();
			elseif($method==2) session_write_close();
			elseif($method==3) session_abort();
			elseif($method==4) session_unset();
		}
	}









	// ########################################################### //
	// ###################### ARRAY OPERATIONS ################### //
	// ########################################################### //
	// NOTE: Generaly, creating new variable has much better memory performance, then "unset"

	// ======== manual  ========
	//convert unsorted array (i.e. [ 'first'=>["a","b","c"], 'second'=>[1,2,3] , ] ) to associative   [ "a"=>1, "b"=2 ]
	public function array_to_associative($array) {
		
	}
	public function string_to_array($string, $divider='|', $allow_empty=false){ $res=( array_map('trim', !empty($string)?explode($divider, $string):[] ) ); return $allow_empty? $res : array_filter($res); }
	public function array_to_string($array)	{ return implode(",",  array_map('trim',array_filter($array)) ); }
	public function arrayPhpToJs($array)	{ return '["'. implode('","', (!empty($array[0]) && is_array($array[0]) ? $this->arrayPhpToJs($array) : $array) ) .'"]'; }

	public function object_to_array($data) //faster than json_encode & decode : https://stackoverflow.com/a/4345578/2377343
	{
		if (is_array($data) || is_object($data))
		{
			$result = [];
			foreach ($data as $key => $value)
			{
				$result[$key] = (is_array($data) || is_object($data)) ? $this->object_to_array($value) : $value;
			}
			return $result;
		}
		return $data;
	}
	public static function array_to_object($data) { 
		if (is_object($data))
		{
			foreach ($data as $key=>$value)
			{
				if(is_array($value) || is_object($value))
					$data->$key = self::array_to_object($value);
			}
			return $data;
		}
		else{
			$result = new \stdClass();
			foreach ($data as $key => $value)
			{
				$result->$key = (is_array($value) || is_object($value)) ? self::array_to_object($value) : $value;
			}
			return $result;
		}
	}

	
	public static function array_add_prefix_to_keys($array, $prefix){
		$new_array =[];
		foreach ($array as $k => $v) {
			$new_array[$prefix.$k] = $v;
		}
		return $new_array;
	}
	public static function add_prefix_to_array_keys($array, $prefix){
		return self::array_add_prefix_to_keys($array, $prefix);
	}
	public static function array_add_prefix_to_values($array, $prefix){
		$new_array =[];
		foreach ($array as $k => $v) {
			$new_array[$k] = $prefix.$v;
		}
		return $new_array;
	}

	public static function array_shuffle($list) {
		if (!is_array($list)) return $list;
		$keys = array_keys($list);
		shuffle($keys);
		$random = [];
		foreach ($keys as $key)
			$random[$key] = $list[$key];
		return $random;
	}
	

	public function array_map_recursive( $func, $value) {  //removed callable $func
		if (is_array($func)){
			$array = $value;
			foreach($func as $each_func){
				$array = $this->array_map_recursive($each_func,$array);
			}
			return $array;
		}
		else{
			return filter_var($value, \FILTER_CALLBACK, ['options' => $func]);
		}
	}
	
	public function array_map_deep( $callback , $value) 
	{
		if ( is_array( $value ) ) {
			foreach ( $value as $index => $item ) {
					$value[ $index ] = $this->array_map_deep($callback,  $item );
			}
		} elseif ( is_object( $value ) ) {
			$object_vars = get_object_vars( $value );
			foreach ( $object_vars as $property_name => $property_value ) {
					$value->$property_name = $this->array_map_deep( $callback, $property_value );
			}
		} else {
			$value = call_user_func( $callback, $value );
		}
		return $value;
	}


	public function array_keys($arrayOrObject){
		if(is_array($arrayOrObject))
			return array_keys($arrayOrObject);
		elseif (is_object($arrayOrObject))
			return array_keys(get_object_vars($arrayOrObject));
		return [];
	}

	public static function count($arr_or_object)
	{
		if(is_object($arr_or_object)) return count(get_object_vars( $arr_or_object ));
		else return count($arr_or_object);
	}

	public static function childValue($array, $key, $default=''){
		if (is_object($array)) {
			return (property_exists($array, $key) ? $array->$key : $default);
		}
		else{
			return (array_key_exists($key, $array) ? $array[$key] : $default);
		}
	}
	public static function array_value($array, $key, $default=''){
		return self::childValue($array, $key, $default);
	}
	public static function arrayValueSafe($array, $key, $default=''){
		return self::childValue( $array ?:[], $key, $default);
	}

	public function array_value_sub($array, $key1, $key2, $default=''){
		if (is_object($array)) {
			return ( !property_exists($array, $key1) || !property_exists($array->$key1, $key2) ) ? $default : $array->$key1->$key2;
		}
		else{
			return ( !array_key_exists($key1,$array) || !array_key_exists($key2, $array[$key1]) ) ? $default : $array[$key1][$key2];
		}
	}

	public static function in_array($needle, $haystack) {
		return in_array($needle, self::array_values($haystack) );
	}

	public static function in_arrayi($needle, $haystack) {
		return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}

	public static function array_values($array_or_object){
		if (is_object($array_or_object)){
			$arr = [];
			foreach($array_or_object as $key=>$val){
				$arr[] = $val;
			}
			return $arr;
		}
		else{
			return array_values($array_or_object);
		}
	}

	public static function arrayize($val) {
		return self::is_array($val) ? $val : [$val];
	}
	public function valueIs($array, $key, $value){
		return $this->array_value($array, $key)===$value;
	}
	public function valueSetDefault(&$array, $key, $value){
		if ( ! array_key_exists($key, $array) ) $array[$key]==$value;
		return $array;
	}
	public function array_child($array, $child_relation='i.e. response->items'){
		if (!empty($child_relation))
		{
			$childTargetArr= $this->string_to_array($child_relation, '->');
			foreach ( $childTargetArr as $childKey) {
				$array = is_array($array) ? $array[$childKey] : $array->$childKey;
			}
		}
		return $array;
	}


	// ##########################
	public function array_makeKeyedBySubkey($array, $targetKey){  //this is 2x faster than below
		return array_column($array, null, $targetKey);
	}
	// strict defines, whether throw exceptions if targetkey not found
	public function array_makeKeyedBySubkey_manual($array, $targetKey, $strict=true){
		$new_array=[];
		foreach($array as $key=>$subArray)
		{
			if ($strict){
				$name = $this->is_array($subArray) ? $subArray[$targetKey] : $subArray->{$targetKey};
			}
			else{
				if ( is_array($subArray) && array_key_exists($targetKey,$subArray) )
					$name = $subArray[$targetKey];
				else if ( is_object($subArray) && property_exists($subArray, $targetKey) )
					$name = $subArray->{$targetKey};
				else
					$name = $key;
			}
			$new_array[$name] = $subArray;
		}
		return $new_array;
	}
	public function array_makeKeyedByValue($array){
		$new_array=[];
		foreach($array as $value)
		{
			$new_array[$value] = $value;
		}
		return $new_array;
	}

	//insert sub-child item in array-children with the child's keyname
	public function array_insertChildSameKey(&$array, $targetKey){
		$new_array=[];
		foreach($array as $key=>$subArray)
		{
			$subArray[$targetKey] =$key;
			$new_array[$key] = $subArray;
		}
		$array = $new_array;
		return $array;
	}
	public function array_parentize($array, $targetSubKey_1, $targetSubKey_2=''){
		$new_array=[];
		foreach($array as $key=>$subArray)
		{
			$parent1_key_will_be = $this->array_value($subArray,$targetSubKey_1); 
			$parent2_key_will_be = empty($targetSubKey_2) ? $key : $this->array_value($subArray, $targetSubKey_2); 
			$new_array[$parent1_key_will_be][$parent2_key_will_be] = $subArray;
		}
		return $new_array;
	}	


	public function array_sub_with_keyvalue($array, $keyName, $shouldBeEqualTo, $resortKeysFromZero=false, $case_sensitive=true)
	{ 
		$newArray = [];
		foreach($array as $key=>$subArray) {
			if (is_array($subArray))
			{
				$add=false;
				if ( array_key_exists($keyName, $subArray) )
				{
					$v_1 = $subArray[$keyName];
					$v_2 = $shouldBeEqualTo;
					if( is_numeric($v_1) && is_numeric($v_2) )
					{
						$v_1 =self::number_format($v_1);
						$v_2 =self::number_format($v_2);
					}
					if( $v_1 === $v_2){
						$add=true;
					}
				}
				if ($add){
					if ($resortKeysFromZero)
						$newArray[] = $subArray;
					else
						$newArray[$key] = $subArray;
				}
			}
			elseif ( is_object($subArray) ) {
				$add=false;
				if ( property_exists($subArray,$keyName) )
				{
					$v_1 = $subArray->$keyName;
					$v_2 = $shouldBeEqualTo;
					if( is_numeric($v_1) && is_numeric($v_2) )
					{
						$v_1 =self::number_format($v_1);
						$v_2 =self::number_format($v_2);
					}
					if( $v_1 === $v_2){
						$add=true;
					}
				}
				if ($add){
					if ($resortKeysFromZero)
						$newArray[] = $subArray;
					else
						$newArray[$key] = $subArray;
				}
			}
		}
		return $newArray;
	}	
	public function array_sub_without_keyvalue($array, $keyName, $shouldBeEqualTo, $resortKeysFromZero=false, $case_sensitive=true)
	{ 
		$newArray = [];
		foreach($array as $key=>$subArray) {
			if (is_array($subArray))
			{
				$add=true;
				if ( array_key_exists($keyName, $subArray) )
				{
					$v_1 = $subArray[$keyName];
					$v_2 = $shouldBeEqualTo;
					if( is_numeric($v_1) && is_numeric($v_2) )
					{
						$v_1 =(float)($v_1);
						$v_2 =(float)($v_2);
					}
					if( $v_1 === $v_2){
						$add=false;
					}
				}
				if ($add){
					if ($resortKeysFromZero)
						$newArray[] = $subArray;
					else
						$newArray[$key] = $subArray;
				}
			}
			elseif ( is_object($subArray) ) {
				$add=true;
				if ( property_exists($subArray,$keyName) )
				{
					$v_1 = $subArray->$keyName;
					$v_2 = $shouldBeEqualTo;
					if( is_numeric($v_1) && is_numeric($v_2) )
					{
						$v_1 =(float)($v_1);
						$v_2 =(float)($v_2);
					}
					if( $v_1 === $v_2){
						$add=false;
					}
				}
				if ($add){
					if ($resortKeysFromZero)
						$newArray[] = $subArray;
					else
						$newArray[$key] = $subArray;
				}
			}
		}
		return $newArray;
	}
	
	
	// CONTAIN //
	public function array_sub_with_keyvalue_contain($array, $keyName, $shoulContain, $resortKeysFromZero=false, $case_sensitive=true)
	{ 
	   //todo
	}
	//TODO
	public function array_sub_without_keyvalue_contain($array, $keyName, $shouldNotContain, $resortKeysFromZero=false, $case_sensitive=true)
	{ 
		foreach($array as $key=>$subArray) {
			if ( array_key_exists($keyName, $subArray) && $this->contains($subArray[$keyName],$shouldNotContain,$case_sensitive) ){
				unset($array[$key]);
			}
		}
		return $array;
	}
	//TODO
	public function array_sub_sub_without_keyvalue_contain($array, $childKey, $keyName, $shouldNotContain, $resortKeysFromZero=false, $case_sensitive=true)
	{ 
		foreach($array as $key_1=>$subArray_1) {
			foreach($subArray_1[$childKey] as $key_2=>$subArray_2) {
				if ( array_key_exists($keyName, $subArray_2) && $this->contains($subArray_2[$keyName],$shouldNotContain,$case_sensitive) ){
					unset($array[$key_1][$childKey][$key_2]);
				}
			}
		}
		return $array;
	}
	// ### CONTAIN ### //

	//TODO
	public function array_sub_replace_keyname($array, $keyName, $replaceWithKeyname)
	{ 
		//$newArray = [];
		//foreach($array as $key=>$subArray) {
		//	if ( $keyName===$key ){
		//		$newArray[$replaceWithKeyname] = $subArray;
		//	}
		//	else{
		//		$newArray[$key] = $subArray;
		//	}
		//}
		//return $newArray;
	}

	public function array_sub_replace_value($array, $keyName, $replaceWhatValue, $replaceWithValue)
	{ 
		$newArray = [];
		foreach($array as $key=>$subArray) {
			if ( array_key_exists($keyName, $subArray) && $subArray[$keyName] === $replaceWhatValue )
				$subArray[$keyName]=$replaceWithValue;
			$newArray[$key] = $subArray;
		}
		return $newArray;
	}

	public function array_subarray_value_above($array, $keyName, $value, $allow_null=false)
	{ 
		$newArray = [];
		foreach($array as $key=>$subArray) {
			//if array
			if ( is_array($subArray) && array_key_exists($keyName, $subArray) && ($subArray[$keyName] >= $value || ($allow_null && is_null($subArray[$keyName]) ) ) ) 
				$newArray[$key] = $subArray;
			//if object
			if ( is_object($subArray) && property_exists($subArray, $keyName) && ($subArray->$keyName >= $value || ($allow_null && is_null($subArray->$keyName) ) ) ) 
				$newArray[$key] = $subArray;
		}
		return $newArray;
	}



	public function array_only_keys_that_contain($array, $string_or_array, $case_sens=true, $position='any'){
		$new=[];
		if (!empty($string_or_array))
		{
			$contained_strs = $this->arrayize($string_or_array);
			foreach($array as $key=>$block)  {
				if ( $this->contains_AgainstArray($key, $contained_strs, $case_sens,$position) ){
					$new[$key] = $block;
				}
			}
		}
		return $new;
	}
	public function array_only_keys_that_not_contain($array, $string_or_array, $case_sens=true, $position='any'){
		$new=[];
		if (!empty($string_or_array))
		{
			$contained_strs = $this->arrayize($string_or_array);
			foreach($array as $key=>$block)  {
				if ( ! $this->contains_AgainstArray($key, $contained_strs, $case_sens,$position) ){
					$new[$key] = $block;
				}
			}
		}
		return $new;
	}

	public function array_only_keys_that_contain_not_contain($array, $INCLUDE_string_or_array=[], $INCLUDE_case_sens=true, $INCLUDE_position='any', $EXCLUDE_string_or_array=[], $EXCLUDE_case_sens=true, $EXCLUDE_position='any'){
		$INCLUDEDs = is_array($INCLUDE_string_or_array) ? $INCLUDE_string_or_array : [$INCLUDE_string_or_array];
		$EXCLUDEDs = is_array($EXCLUDE_string_or_array) ? $EXCLUDE_string_or_array : [$EXCLUDE_string_or_array];
		$isInlcude= !empty($INCLUDEDs);
		$isExclude= !empty($EXCLUDEDs);

		$isObject = is_object($array);
		$new_array=$isObject ? (object) [] : [];
		foreach( $array as $key=>$block)  {
			$set=true;
			if ( $set && ($isInlcude && ! $this->contains_AgainstArray($key, $INCLUDEDs, $INCLUDE_case_sens, $INCLUDE_position) )){
				$set=false;
			}
			if ( $set && ($isExclude && $this->contains_AgainstArray($key, $EXCLUDEDs, $EXCLUDE_case_sens, $EXCLUDE_position) )){
				$set=false;
			}
			if($set){
				if($isObject) $new_array->$key = $block;
				else          $new_array[$key] = $block;
			}
		} 
		return $array;
	}
	public function array_only_values_that_contain($array, $string_or_array, $case_sens=true, $position='any'){
		$new_array=[];
		if (!empty($string_or_array))
		{
			if( $this->is_array($string_or_array) )
			{
				foreach($array as $key=>$block)  {
					if ( $this->contains_AgainstArray($block, $string_or_array, $case_sens, $position) ){
						$new_array[$key]=$block;
					}
				}
			}
			else{
				foreach($array as $key=>$block)  {
					if ( $this->contains($block, $string_or_array, $case_sens, $position) ){
						$new_array[$key]=$block;
					}
				}
			}
		}
		return $new_array;
	}
	public function array_only_values_that_not_contain($array, $string_or_array, $case_sens=true, $position='any'){
		$new_array=[];
		if (!empty($string_or_array))
		{
			if( $this->is_array($string_or_array) )
			{
				foreach($array as $key=>$block)  {
					if ( !$this->contains_AgainstArray($block, $string_or_array, $case_sens, $position) ){
						$new_array[$key]=$block;
					}
				}
			}
			else{
				foreach($array as $key=>$block)  {
					if ( !$this->contains($block, $string_or_array, $case_sens, $position) ){
						$new_array[$key]=$block;
					}
				}
			}
		}
		return $new_array;
	}


	public static function is_array($data){
		return is_array($data) || is_object($data);
	}

	public function arrayKeyRename($array, $keyToRemove, $keyToAdd){
		if (array_key_exists($keyToRemove,$array)) 
		{
			$array[$keyToAdd]=$array[$keyToRemove];
			unset($array[$keyToRemove]);
		}
		return $array;
	}
	
	public function arrayKeyRenameRecursive($array, $keyToRemove, $keyToAdd){
		$array = $this->arrayKeyRename($array, $keyToRemove, $keyToAdd);
		$new_array =[];
		if (is_array($array))
		{
			foreach($array as $key=>$value)
			{
				$new_array[$key]= !is_array($value) ? $value : $this->arrayKeyRenameRecursive($value, $keyToRemove, $keyToAdd);
			}
		}
		else{
			$new_array = $array;
		}
		return $new_array;
	}
	
	//another : https://stackoverflow.com/a/49993735/2377343
	public function arrayChangeKeyCaseRecursive(&$arr, $case = CASE_LOWER)
	{
		return array_map(function($item) use($case) {
			if(is_array($item))
				$item = $this->arrayChangeKeyCaseRecursive($item, $case);
			return $item;
		}, array_change_key_case($arr, $case));
	}
	public function arrayKeyLowercase(&$arr, $case = CASE_LOWER)
	{
		$arr = array_change_key_case($arr, $case);
		$arr = array_map(function(&$item) use($case) {
			if(is_array($item))
				$this->arrayKeyLowercase($item, $case);
			return $item;
		}, $arr );
	}
	
	public static function itemKeys($element) {
		return is_object($element) ? self::objectKeys($element) : self::objectKeys($element);
	}
    public static function hasChildWithKeyValue ($element, $targetKey, $targetValue) {
        $keys = self::itemKeys ($element);
        for ($i = 0; $i < count($keys); $i++) {
            $currentKey = $keys[$i];
            $childMember = $element[$currentKey];
            $value = self::memberValue ($childMember, $targetKey, null);
            if ($value === $target_keytargetValue) {
                return true;
            }
        }
        return false;
    }

	public function insertValueAtPosition($arr, $insertedArray, $position) {
		$i = 0;
		$new_array=[];
		foreach ($arr as $key => $value) {
			if ($i == $position) {
				foreach ($insertedArray as $ikey => $ivalue) {
					$new_array[$ikey] = $ivalue;
				}
			}
			$new_array[$key] = $value;
			$i++;
		}
		return $new_array;
	}

	public function array_has_subchild_with_value($array,$subchild_key, $subchild_value, $strict=true){
		$found = false;
		foreach($array as $key=>$child){
			$val =  $this->array_value($child, $subchild_key);
			if     ( $strict && $val===$subchild_value)
				$found=true;
			elseif (!$strict && $val==$subchild_value)
				$found=true;
		}
		return $found;
	} 

	// supports asterisks i.e. *->keyname  ( https://pastebin_com/BcC8ztDp )
	public function array_with_keys($array_or_object, $keyToRemain)
	{
		$asterisk       = '*'; //should always have child ->
		$child_divisor  = '->';
		$keyToRemain_ALL= is_array($keyToRemain)?$keyToRemain:[$keyToRemain];

		if (is_array($array_or_object))
		{
			$new_arr 	  = [];
			//normal
			$keysToRemain_NORMAL = array_filter($this->array_only_values_that_not_contain($keyToRemain_ALL, $child_divisor) );
			$keysToRemain_REGEX  = array_filter($this->array_only_values_that_contain($keyToRemain_ALL, $child_divisor) );
			foreach( $array_or_object as $key_1=>$val_1 )
			{
				if ( in_array($key_1, $keysToRemain_NORMAL) ){ 
					$new_arr[$key_1]=$val_1;
				}
			}
			foreach( $array_or_object as $key_1=>$val_1 )
			{
				if(array_key_exists($key_1, $new_arr) )
					continue;
				foreach( $keysToRemain_REGEX as $eachRegex )
				{
					$parts = explode($child_divisor, $eachRegex, 2 );
					$first = $parts[0];
					$second= $parts[1];
					if( $first===$asterisk || $first===$key_1){
						if(is_array($array_or_object[$key_1])){
							$sVals=  $this->array_with_keys($array_or_object[$key_1],$second);
							if (empty($new_arr[$key_1])) $new_arr[$key_1]=[];
							$new_arr[$key_1]= array_merge_recursive($new_arr[$key_1],$sVals); 
						}
						elseif( $first===$key_1 ){
						//	$new_arr[$key_1]=$array_or_object[$key_1];
						}
					}
				}	
			}
			return $new_arr;
		}
		elseif(is_object($array_or_object ))
		{ 
			foreach($array_or_object as $key=>$value) {
				if( !in_array($key,$keyToRemain_ALL) ){
					unset($array_or_object->$key);
				}
			}
			return $array_or_object;
		}
		else{
			return $array_or_object;
		}
	} //old: https://pastebin_com/Tbm8sEGH 

	
	// supports asterisks i.e. *->keyname  ( https://pastebin_com/BcC8ztDp )
	public function array_unset_keys($array_or_object, $keysToUnset)
	{ 
		$asterisk       = '*'; //should always have child ->
		$child_divisor  = '->';
		$keysToUnset_ALL= is_array($keysToUnset)?$keysToUnset:[$keysToUnset];
		
		$is_arr=is_array($array_or_object);
		$is_obj=is_object($array_or_object);
		if ($is_arr || $is_obj)
		{
			$new_arr      = [];
			$keysToUnset_NORMAL = array_filter($this->array_only_values_that_not_contain($keysToUnset_ALL, $child_divisor) );
			$keysToUnset_REGEX  = array_filter($this->array_only_values_that_contain($keysToUnset_ALL, $child_divisor) );

			foreach( $array_or_object as $key_1=>$val_1 )
			{
				if ( $is_arr && !in_array($key_1, $keysToUnset_NORMAL)){
					$new_arr[$key_1]=$val_1;
				}
				if( $is_obj && in_array($key_1, $keysToUnset_NORMAL)){
					unset($array_or_object->$key_1);
				}
			}

			foreach( ($is_arr ? $new_arr : $array_or_object) as $key_1=>$val_1 )
			{
				foreach( $keysToUnset_REGEX as $eachRegex )
				{
					$parts = explode($child_divisor, $eachRegex, 2 );
					$first = $parts[0];
					$second= $parts[1];
					if( $first===$asterisk || $first===$key_1 ){
						if( 
							($is_arr && !is_object($new_arr[$key_1]) && !is_array($new_arr[$key_1])) 
								|| 
							($is_obj && !is_object($array_or_object->$key_1) &&  !is_array($array_or_object->$key_1)  ) 
						)
						{
							//$new_arr[$key_1]=$array_or_object[$key_1];
						}
						else{
							if ( $is_arr )
								$new_arr[$key_1]=$this->array_unset_keys($new_arr[$key_1],$second);
							if ( $is_obj )
								$array_or_object->$key_1=$this->array_unset_keys($array_or_object->$key_1,$second); 
						}
					}
				}	
			}
			if($is_arr) return $new_arr;
			if($is_obj) return $array_or_object;
		}
	}
	///////////////


	public function non_empty_arrayyyy($x=array()){ if (!is_array($x) || empty($x) || (is_array($x) && count($x)==1 && $x[0]==null)  ){ return array('');} else return $x; }

	public function arrayKeyEquals($array, $key, $value)
	{
		return (is_array($array) && array_key_exists($key, $array) && $array[$key]==$value); //(!empty($array)
	}
	public function arrayKeyValue($array, $key, $value)
	{
		return ( is_array($array) && array_key_exists($key, $array) ? $array[$key] : $value);
	}
	
	public static function array_part($array, $amount, $from="start|end")
	{
		return self::array_trim($array, $amount, $from);
	}
	public static function array_trim($array, $amount, $from="start|end")
	{
		$count = count($array);
		return $count<=$amount ? $array : array_slice($array, $from="start" ? 0 : $count-$amount, $amount);
	}

	//add only in case the array didnt containted it already
	public function Add_in_array_if_not_already_added($my_arrayy,$target_value){
		if (array_search($target_value, $my_arrayy) !== true) {	$my_arrayy[] = $target_value;}			return $my_arrayy;
	}

	//remove item from array by value
	public function unset_by_value(&$my_arrayy, $target_value, $reindex=false){
		$new=[];
		if (!empty($my_arrayy) && is_array($my_arrayy) ) {
			foreach ($my_arrayy as $key => $value){  
				if ($value != $target_value) { 
					if(!$reindex)
						$new[$key] = $my_arrayy[$key]; 
					else
						$new[] = $my_arrayy[$key]; 
				}   
			}
		}
		$my_arrayy = $new;
		return $my_arrayy;
	}

	public function array_is_associative(array $array) {
		return count(array_filter(array_keys($array), 'is_string')) > 0;
	}
	
	public function isAssociative(array $arr){
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
	
	public function array_get_by_subkey($array, $subkey, $subvalue){
		foreach ($array as $each){
			//if()
		}
		return [];
	}

	public function nextKeyInArray($target_keyname, $array){
		$keys = array_keys($array);
		$index_of_target_keyname = array_search($target_keyname,  $keys , true);
		return (count($array) > $index_of_target_keyname+1 ) ? $keys[$index_of_target_keyname+1]  :  $keys[0];
	}

	public function nextValueInArray($target_value, $array, $by_key=false){
		$keys = array_keys($array);
		$target_keyname = $by_key ? $target_value : array_search($target_value,  $array, true );
		$index_of_target_keyname = array_search($target_keyname,  $keys, true );
		return (count($array) > $index_of_target_keyname+1 ) ? $array[ $keys[$index_of_target_keyname+1] ]  :  $array[  $keys[0]  ];
	}

	public function array_search($targetValue, $array){
		foreach($array as $key=>$value){
			if ($value===$targetValue)
				return $key;
		}
		return false;
	}

	
	public function stringContainsArrayValues($string, $array, $case_sensitive=false){
		return $this->stringContainsArrayValueAny($string, $array, $case_sensitive);		
	}
	public function stringContainsArrayValueAny($string, $array, $case_sensitive=false){
		$found='';
		$string = $case_sensitive ? $string : strtolower($string);
		$array = $case_sensitive ? $array : array_map('strtolower', $array);
		foreach($array as $each)
		{
			if (stripos($string,$each)!==false){
				$found=$each; break;
			}
		}
		return $found;
	}
	public function stringContainsArrayValueAll($string, $array, $case_sensitive=false){
		$result=true;
		$string = $case_sensitive ? $string : strtolower($string);
		$array  = $case_sensitive ? $array  : array_map('strtolower', $array);
		foreach($array as $each)
		{
			if (stripos($string,$each)===false){
				$result=false; break;
			}
		}
		return $result;
	}
	
	public function arrayValuesContainString($array, $string, $case_sensitive=false){
		$found=false;
		$string = $case_sensitive ? $string : strtolower($string);
		foreach($array as $each)
		{
			$each = $case_sensitive ? $each : strtolower($each);
			if (stripos($each,$string)!==false){
				$found=true; break;
			}
		}
		return $found;
	}

	public static function arrayKeys($array)   { return array_keys($array); }
	public static function objectKeys($object) { return get_object_vars($object); }

	public static function arrayKeyAt($array, $position) { return self::arrayKeys($array)[$position]; }
	public static function objectKeyAt($object, $position) { return self::objectKeys($object)[$position]; }

	public static function arrayMemberAt($array, $whichNum){
		$target_key = self::arrayKeyAt($array, $whichNum);
		return self::childValue($array, $target_key, null);
	}
	public static function objectMemberAt($object, $whichNum){
		$target_key = self::objectKeyAt($object, $whichNum);
		return self::childValue($object, $target_key, null);
	}

	public function array_members($array, $from_start=0, $from_end=0){
		$i=0;
		$new_arr=[];
		$count = count($array);
		foreach($array as $key=>$value)
		{
			$i++;
			if ($i<= $from_start )
				$new_arr[$key]=$value;
			if ($i> $count-$from_end )
				$new_arr[$key]=$value;
		}
		return $new_arr;
	}

	public function getIndexOfKey($array, $key){
		return array_search($key, array_keys($array) );
	}
	public function getIndexOfValue($array, $key){
		return array_search($key, $array );
	}

	public function getMemberByIndex($array, $idx){
		$keys= array_keys($array);
		return (!empty($keys) && !empty($array[$keys[$idx]])) ? $array[$keys[$idx]] : null ;
	}

	public function resortArrayByKey($array, $key, $remove_current= false){
		$remaining =  array_splice ($array, $this->getIndexOfKey($array, $key)   );
		if($remove_current){
			$array[$key]= $remaining[$key];
			unset($remaining[$key] );
		}
		return array_merge($remaining, $array);
	}
	
	//in multi dimensional array
	public function findArrayByKeyValue($array, $key, $value){
		foreach($array as $subArray){
			if (array_key_exists($key, $subArray) && $subArray[$key]==$value){
				return $subArray;
			}
		}
		return [];
	}
	public function findObjectByKeyValue($array, $key, $value){
		$item = null;
		foreach($array as $struct) {
			if (property_exists($struct,$key) && $struct->{$key} == $value) {
				return $struct;
			}
		}
		return new \stdClass();
	}

	// array_diff ( array $array , array ...$arrays ): Compares array against one or more other arrays and returns the values in array that are not present in any of the other arrays.
	//response only gives the items, that are in ARRAY_1, and doesn't matter if there are more things in ARRAY_2. See sample: pastebin_com/AUx4238H
	public function array_diff_assoc_recursive( $array_source, $array_toward)
	{
		$difference = [];
		if (empty($array_toward))
			return $array_source;
			
		foreach($array_source as $key => $value_1){
			// if target array does not have this key
			if ( 
				(is_array($array_source) && !array_key_exists($key, $array_toward) )
				||
				(is_object($array_source) && !property_exists($array_toward, $key) )
			)
			{
				$difference[$key] = $value_1;
			}
			else {
				$value_2 = is_array($array_toward) ? $array_toward[$key] :  $array_toward->$key;
				// if member is simple-type
				if( !is_array($value_1) && !is_object($value_1) &&$value_1!=$value_2 ){
					$difference[$key] = $value_1; 
				}
				// if member is array/object
				elseif ( is_array($value_1) || is_object($value_1) ) {
					if ( !is_array($value_2) && !is_object($value_2) ) {
						$difference[$key] = $value_1;
					}
					else{
						$newDiff = $this->array_diff_assoc_recursive($value_1, $value_2 );
						if( !empty($newDiff) )
						{
							$difference[$key] = $newDiff;
						}
					}
				}
			}
		}
		return $difference;
	}
	public function array_diff_recursive( $array_source, $array_compared_to )
	{
		return [];
	}
	public function array_difference( $array_source, $array_compared_to )
	{
		$diff = [
			'first'=> $this->array_diff_assoc_recursive($array_source, $array_compared_to),
			'second'=> $this->array_diff_assoc_recursive($array_compared_to, $array_source),
		];
		
		$diff['added'] = [];
		$diff['changed'] = [];
		$diff['removed'] = [];
		foreach ($diff['first'] as $keyNm => $block) {
			if ( !array_key_exists($keyNm, $diff['second']) ) 
				$diff['added'][$keyNm]=$block; 
			else 
				$diff['changed'][$keyNm]=$block; 
		}
		foreach ($diff['second'] as $keyNm => $block) {
			if ( !array_key_exists($keyNm, $diff['first']) ) 
				$diff['removed'][$keyNm]=$block; 
		}
		return $diff;
	}
	
	public function array_diff_key_full( $array_source, $array_toward )
	{
		$diff = [
			'added'=> array_diff_key($array_source, $array_toward),
			'removed'=> array_diff_key($array_toward, $array_source),
		];
		return $diff;
	}
	
	


	public function array_difference_full( $array_source, $array_toward )
	{
		$difference = [ 'new'=>[], 'old'=>[], 'different'=>[] ];
		if (empty($array_toward)){
			$difference['old'] = $array_source;
		}
		else{
			foreach($array_toward as $key => $value){
				// if target array does not have this key, then tell that this key is new
				if ( !array_key_exists($key, $array_source) )
				{
					$difference['new'][$key] = $value;
				}
			}

			foreach($array_source as $key => $value){
				// if target array does not have this key, then tell that this key is new
				if ( !array_key_exists($key, $array_toward) )
				{
					$difference['old'][$key] = $value;
				}
				else {
					$value_2 = $array_toward[$key];
					// if member is simple-type
					if( !is_array($value) )
					{
						if ( $value_2 != $value ) 
						{
							$difference['different'][$key] = $value;
						}
					}
					// if member is array/object
					else { 
						// if array
						if ( is_array($value) ) 
						{
							if (!is_array($value_2) ) {
								$difference['different'][$key] = $value;
							}
							else{
								$newDiff = $this->array_difference_full($value, $value_2);
								if( !empty($newDiff) )
								{
									$difference['different'][$key] = $newDiff;
								}
							}
						}
					}
				}
			}
		}
		return $difference;
	}

	public static function array_keys_diff($ar1, $ar2){ return self::array_diff_keys($ar1, $ar2); }
	public static function array_diff_keys($ar1, $ar2){
		$k1=array_keys($ar1);  $k2=array_keys($ar2);
		return [ array_diff($k1, $k2), array_diff($k2, $k1) ];
	}

	public function array_intersect_assoc_recursive(&$arr1, &$arr2) {
		if (!is_array($arr1) || !is_array($arr2)) {
	//      return $arr1 == $arr2; // Original line
			return (string) $arr1 == (string) $arr2;
		}
		$commonkeys = array_intersect(array_keys($arr1), array_keys($arr2));
		$ret = array();
		foreach ($commonkeys as $key) {
			$var= $this->array_intersect_assoc_recursive($arr1[$key], $arr2[$key]);
			$ret[$key] = & $var;
		}
		return $ret;
	}
	
	public function array_emptify_keys($array, $insert_key_in_body='')
	{
		$new=[];
        foreach($array as $key1=>$block) {
			$new[]=$block;
		}
		return $new;
	}
	public function array_emptify_keys_sub($array, $insert_key_in_body='')
	{
		$new=[];
        foreach($array as $key1=>$block1) {
			foreach($block1 as $key2=>$block2) {
				if(!empty($insert_key_in_body))
				{
					if(is_object($block2))
						$block2->$insert_key_in_body = $key2;
					else
						$block2[$insert_key_in_body] = $key2;
				}
				$new[$key1][]=$block2;
			}
		}
		return $new;
	}
	 

	public function array_merge($obj_or_array1, $obj_or_array2)
	{
		$is_obj = is_object($obj_or_array1);
		if ($is_obj){
			return (object) array_merge((array) $obj_or_array1, (array) $obj_or_array2);
		}
		else{
			return array_merge($obj_or_array1, $obj_or_array2);
		}
	}
    public function array_merge_sub($array, $unique=false) {
        $final_arr=[];
        foreach($array as $block) $final_arr=array_merge($final_arr, $block);
        return ($unique ? array_unique($final_arr) : $final_arr);
    }
    public function array_merge_sub_sub($array, $which_key, $unique=false) {
        $final_arr=[];
        foreach($array as $block) 
			$final_arr=array_merge($final_arr, $this->array_value($block,$which_key) );
        return ($unique ? array_unique($final_arr) : $final_arr);
    }
	public function array_merge_recursive_distinct ( array $array1, array $array2 )
	{
	  $merged = $array1;
	  foreach ( $array2 as $key => &$value )
	  {
		if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
		{
		  $merged[$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
		}
		else
		{
		  $merged[$key] = $value;
		}
	  }
	  return $merged;
	}

	public function recursive_for_array_value($array,$function_name=false){ 
		//on first run, we define the desired function name to be executed on values
		if ($function_name) { $GLOBALS['current_func_name']= $function_name; } else {$function_name=$GLOBALS['current_func_name'];}
		//now, if it's array, then recurse, otherwise execute function
		return is_array($array) ? array_map('recursive_for_array_value', $array) : $function_name($array); 
	}


	public function array_sort_by_key($array, $key, $direction=SORT_ASC){
		$is_obj= is_object($array);
		if ($is_obj)
			$array = (array) $array;
		$columns = array_column($array, $key);
        if ( array_multisort($columns, $direction, $array) )
			return ($is_obj ? (object) $array : $array);
		else
			throw new \Exception("Multisort failed");
	}

	public function keyAtIndex($index, $array){
		$keys = array_keys($array);
		return $keys[$index];
	}

	public function keyAfterKey($keyname, $array, $increment){
		$keys = array_keys($array);
		$current_key_index = array_search($keyname, $keys);
		return $keys[array_search($keyname,$keys)+$increment];
	}

	public function arraySetKeysFromChild($array, $keyName){
		$new=[];
		foreach($array as $key=>$value) { if (isset($value[$keyName])) $new[$value[$keyName]]=$value;   }
		return $new; 
	}
	public function array_column_with_keys($array,$keyName){ 
		$new=[]; foreach($array as $key=>$value) { if (isset($value[$keyName])) $new[$key]=$value[$keyName];   }
		return $new;  
	}

	public function ArrayColumnWithKey2($array,$keyName){
		return array_filter(array_combine(array_keys($array), array_column($array, $keyName)));
	}
	
	public function ArrayOnlyWithKey($array, $keyName, $target_level){
		$new	= []; 
		$old	= $array; 
		$value_0= $array;
		$cur_level = 0;
		if (is_array($value_0) && $target_level>=$cur_level)
		foreach($value_0 as $key_1=>$value_1) { 
			$cur_level = 1;
			if ($cur_level == $target_level && $key_1 == $keyName)
			{
				$new[$key_1] = $value_1;
				unset($old[$key_1]);
			}
			else{
				if (is_array($value_1) && $target_level>=$cur_level)
				foreach($value_1 as $key_2=>$value_2) { 
					$cur_level = 2;
					if ($cur_level == $target_level && $key_2 == $keyName)
					{
						$new[$key_1][$key_2] = $value_2;
						unset($old[$key_1][$key_2]);
					}
					else{
						if (is_array($value_2) && $target_level>=$cur_level)
						foreach($value_2 as $key_3=>$value_3) { 
							$cur_level = 3;
							if ($cur_level == $target_level && $key_3 == $keyName)
							{
								$new[$key_1][$key_2][$key_3] = $value_3;
								unset($old[$key_1][$key_2][$key_3]);
							}
							else{
								if (is_array($value_3) && $target_level>=$cur_level)
								foreach($value_3 as $key_4=>$value_4) { 
									$cur_level = 4;
									if ($cur_level == $target_level && $key_4 == $keyName)
									{
										$new[$key_1][$key_2][$key_3][$key_4] = $value_4;
										unset($old[$key_1][$key_2][$key_3][$key_4]);
									}
									else{
										if (is_array($value_4) && $target_level>=$cur_level)
										foreach($value_4 as $key_5=>$value_5) { 
											$cur_level = 5;
											if ($cur_level == $target_level && $key_5 == $keyName)
											{
												$new[$key_1][$key_2][$key_3][$key_4][$key_5] = $value_5;
												unset($old[$key_1][$key_2][$key_3][$key_4][$key_5]);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return ['with'=>$new, 'without'=>$old];  
	}


	public function array_last( $array )
	{
		if (is_array($array))
		{
			$keys = array_keys($array);
			$key_last = $keys[ count($keys)-1];
			return $array[$key_last];
		}
		elseif (is_object($array))
		{
			$keys = get_object_vars($array);
			$key_last = $keys[count($keys)-1];
			return $array->$key_last;
		}
	}






	// #################### conversions ##########################

	public function php_to_js_array($array){
		return '["'. implode('","', $array ) .'"]';
	}
	public function xmlToArray($content)
	{
		try
		{
			$xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
			return json_decode( json_encode($xml), TRUE);
		} catch (Exception $ex) {
			return ['xmlerror'=>$ex];
		}
	}

	public function xmlToArrayByKey($content, $keyName, $removeIfKeyIsOnlyChild=false)
	{
		try
		{
			$xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA );
			$array= json_decode( json_encode($xml), TRUE);
			return $this->xmlSetChild($array, $keyName, $removeIfKeyIsOnlyChild);
		} catch (Exception $ex) {
			return ['xmlerror'=>$ex];
		}
	}
	public function xmlSetChild($array, $keyName, $removeIfKeyIsOnlyChild=false)
	{
		$new_array= [];
		foreach ($array as $key_1=>$value_1)
		{
			if (is_array($value_1) && isset($value_1[0]))
			{
				$sub_arr=[];
				foreach ($value_1 as $idx=>$value_2)
				{
					$keyValue = $value_2['@attributes'][$keyName];
					if( $removeIfKeyIsOnlyChild && count($value_2)==1 && count($value_2['@attributes'])==1 ) 
						$new_array[$key_1][$keyValue] = null;
					else
						$new_array[$key_1][$keyValue] = $this->xmlSetChild($value_2, $keyName, $removeIfKeyIsOnlyChild);
				}
			}
			else{
				$new_array[$key_1]=$value_1;
			}
		}
		return $new_array;
	}

	public static function xml_to_array($xml_string, $replace_colons=false, $opts=0 ){
		return json_decode(self::xml_to_json($xml_string, $replace_colons, $opts), true);
	}
	public static function xml_to_json($xml_string, $replace_colons=false, $opts=0 ){
		if ($opts===0) 
			$opts = LIBXML_NOCDATA | LIBXML_COMPACT | LIBXML_NOENT ; //https://www.php.net/manual/en/libxml.constants.php : LIBXML_NOCDATA | LIBXML_COMPACT  | LIBXML_DTDLOAD |   
		if ($replace_colons)
			$xml_string = self::xml_tag_replace_colons($xml_string);
		$xml = simplexml_load_string($xml_string, null, $opts);  
		return json_encode($xml);
	}

	public static function xml_tag_replace_colons($xmlData){
		return preg_replace('~(</?|\s)([a-z0-9_]+):~is', '$1$2_', $xmlData);
	}
	
	
	public function array_to_xml_output($array) {
		$xml_data = new \SimpleXMLElement('<?xml version="1.0"?><xml_data></xml_data>');
		$this->array_to_xml($array, $xml_data);
		//$result = $xml_data->asXML('/file/path/name.xml');
		return $xml_data->asXML();
	}

	public function array_to_xml( $data, &$xml_data ) {
		foreach( $data as $key => $value ) {
			if( is_numeric($key) ){	$key = 'item'.$key; } //dealing with <0/>..<nuemric/> issues
			if( is_array($value) ) { $subnode = $xml_data->addChild($key);	array_to_xml($value, $subnode);	} 
			else {	$xml_data->addChild("$key",htmlspecialchars("$value"));	}
		}
	}

	//##############  ARRAYs #################

	
	public function unquote($txt){
		return str_replace('"', "", str_replace("'", "", $txt) );
	} 


	public function getExtension($fileUrl)
	{
		$array=explode('.', basename(parse_url($fileUrl)['path']));
		return $array[count($array)-1]; 
	}
	public function filename($fileUrl)
	{
		$array=explode('.', basename(parse_url($fileUrl)['path']));
		$min = min(count($array), 2);
		return basename($array[$min-2]); 
	}

	public function ListAllInDir($path, $only_files = false) {
		$all_list = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
				( $only_files ? \RecursiveIteratorIterator::LEAVES_ONLY : \RecursiveIteratorIterator::SELF_FIRST )
		);
		$files = [];
		foreach ($all_list as $file)
			$files[] = $file->getPathname();

		return $files;
	}


	
	public function setProperty($obj, $property, $value) {
		$reflection = new \ReflectionClass($obj);
		$property = $reflection->getProperty($property);
		$property->setAccessible(true);
		return $property->setValue($obj, $value);
	}


	public function is_localhost($domain=''){
		return in_array( (!empty($domain) ? $domain : $this->domainCurrentWithoutPort), ['localhost','127.0.0.1','::1']); 
	}
	

	public function is_JSON($string){
		return $this->is_JSON_string($string);
	}
	public function maybe_json($string){
		$firstLetter = substr($string, 0, 1); 
		$maybe_json = $firstLetter==='{' || $firstLetter==='[';
		return $maybe_json;
	}
	

	public function JsonData($string){
		if ( !is_string($string) ) return null;
		$x = json_decode($string, true);
		if (!is_array($x)) return null;
		return $x;
	}

	public function is_JSON_string($string){
		return (is_string($string) && is_array(json_decode($string, true)));
	}

	public function arrayed_json($answer){
		$result = [];
		if(!$this->is_JSON_string($answer)){
			$result['error'] = $answer;
		}
		else{
			$result = json_decode($answer, true);
		}
		return $result;
	}

	public function arrayed_answer($answer){
		$result = [];
		if(!$this->is_JSON_string($answer)){
			$result['error'] = $answer;
		}
		else{
			$result = json_decode($answer, true);
		}
		return $result;
	}

	
	


	#region ################  CACHE ###############
	public $CACHE_CHOSEN_PROGRAM= 'redis';
	public function cache_get($key, $default=null){
		return call_user_func([$this, "cache_get_{$this->CACHE_CHOSEN_PROGRAM}"], $key, $default);
	}
	public function cache_set($key, $data, $seconds = 8640000){
		return call_user_func([$this, "cache_set_{$this->CACHE_CHOSEN_PROGRAM}"], $key, $data, $seconds);
	}
	public function cache_append($key, $data, $seconds = 8640000){
		$existing_arr = call_user_func([$this, "cache_get_{$this->CACHE_CHOSEN_PROGRAM}"], $key, []);
		$new  = $this->is_array($existing_arr) ? $this->array_merge($existing_arr,$data) : $existing_arr. $data;
		$final_value  = ($this->is_simple_type($new) ? $new : serialize($new));
		return call_user_func([$this, "cache_set_{$this->CACHE_CHOSEN_PROGRAM}"], $key, $final_value, $seconds);
	}

	#region ### phpRedis (better than "pRedis" ) [edit: 02mxypZ1 ] ###
	public $redis_host_params = [
		'host' => '127.0.0.1',
		'port' => 6379,
		'connectTimeout' => 2.5,
		'db_index' => 0,
		'auth' => ['', ''],
		'ssl' => ['verify_peer' => false],
	];
	public $redis_keys_prefix='';
	public $redis_instance = null; 
	public $redis_default_key_pre = ':';
	public function cache_get_redis($key, $default=null){
		$this->helper_cache_redis_init_check($this->redis_host_params, true);
		$key = $this->redis_keys_prefix . $key; 
		$redis = $this->helper_redis_getInstance();
		$val = $redis->get($key);
		$this->helper_redis_IfSwooleCloseNeeded($redis);
		if (!$val)
			return $default;
		return (self::is_serialized($val) ? unserialize($val) : $val);
	}
	public function cache_set_redis($key, $data, $seconds = 8640000){
		$this->helper_cache_redis_init_check($this->redis_host_params, true);
		$key = $this->redis_keys_prefix . $key;
		$redis =$this->helper_redis_getInstance();
		$result = $redis->set($key, ($this->is_simple_type($data) ? $data : serialize($data)), $seconds );
		$this->helper_redis_IfSwooleCloseNeeded($redis);
		return $result;
	} 
	// public function cache_append_redis($key, $data, $seconds = 8640000){
	// 	$this->helper_cache_redis_init($this->redis_host);
	// 	$existing_arr = $this->cache_get_redis($key,[]);
	// 	$new  = $this->is_array($existing_arr) ? $this->array_merge($existing_arr,$data) : $existing_arr. $data;
	// 	$final_value  = ($this->is_simple_type($new) ? $new : serialize($new));
	// 	return $this->cache_set_redis($key, $final_value, $seconds);
	// }
	public function cache_clear_redis(){
		$this->helper_cache_redis_init_check($this->redis_host_params, true);
		$this->helper_redis_getInstance()->flushAll();
	}
	// helpers, with added swoole support
	private $redis_pool=null;   private $redis_start_inited=false;
	public function helper_cache_redis_init($host_params, $use_params=false){
		try{ 
			if (self::swoole_inside_coroutine()){
				if ( is_null($this->redis_pool) ){
					$authString = !empty($host_params['auth'][0]) ? $host_params['auth'][0].':'.$host_params['auth'][1] : '';
					$newR = (new \Swoole\Database\RedisConfig)->withHost($host_params['host'])->withPort($host_params['port'])->withAuth($authString)->withDbIndex($host_params['db_index'])->withTimeout($host_params['connectTimeout']);
					$this->redis_pool = new \Swoole\Database\RedisPool( $newR );
					$this->redis_instance= $this->helper_redis_getInstance(); 
				}
			}
			else {
				if ( is_null($this->redis_instance) ){
					$this->redis_instance= new \Redis(); 
					$this->redis_instance->connect( $host_params['host'], $host_params['port']); 
					if ($use_params)
					{
						// SERIALIZER_NONE | SERIALIZER_PHP | SERIALIZER_IGBINARY | SERIALIZER_MSGPACK );
						$this->redis_instance->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
						$this->redis_instance->setOption(\Redis::OPT_PREFIX, __NAMESPACE__. $this->redis_default_key_pre);	// use custom prefix on all keys
					}
				} 
			}
			if ($use_params)
				$this->redis_keys_prefix = $this->slug .'_';
		}
		catch(\Exception $ex){
			if (get_class($ex)==="RedisException"){
				if (!$this->redis_start_inited){
					$this->redis_start_inited=true;
					if(method_exists($this,'helper_redis_try_os_start')) $this->helper_redis_try_os_start();
					sleep(1);
					$this->helper_cache_redis_init($host_params);
				}
			}
		}
	} 
	public function helper_cache_redis_init_check($host_params, $use_params=false){
		if(is_null($this->redis_instance))
			$this->helper_cache_redis_init($host_params, $use_params);
	}

	public function helper_redis_getInstance()
	{
		return (self::swoole_inside_coroutine() ? $this->redis_pool->get() : $this->redis_instance);
	} 
	public function helper_redis_getAllKeys($redis_instance)
	{
		$keys=[];
		$it = NULL;
		do {
			$arr_keys = $redis_instance->scan($it);
			if ($arr_keys !== FALSE) {
				foreach($arr_keys as $str_key) {
					$keys[]= $str_key;
				}
			}
		} while ($it > 0);
		return $keys;
	} 
	           
	public function helper_redis_IfSwooleCloseNeeded($redis_instance)
	{
		if ( self::swoole_inside_coroutine() ){
			$this->redis_pool->put($redis_instance);
		}
	}
	#endregion 

	// ##### memcached: todo #####
	//
	//
	//
	//
	//
	//

	// ##### apcu: todo ##### (examples: https://pastebin_com/0Q5y28fA)
	//
	//
	//
	//
	//
	//


	// ####################### OBJECTS ####################### //
	// https://medium.com/@dylanwenzlau/500x-faster-caching-than-redis-memcache-apc-in-php-hhvm-dcd26e8447ad
	// sample ---> https://pastebin_com/0eUvyXaD

	public $cache_object_method='serialize'; //serialize | memcached
	public function cache_object_get($uniqFileName, $default='', $expire_seconds=86400 )
	{
		if ($this->cache_object_method=='apcu')
		{
		}
		else{
			$data = $this->cache_file_get($uniqFileName, $default, $expire_seconds, $decode=false);
			return unserialize( $data );
		}
	}
	public function cache_object_set($uniqFileName, $content, $throw_exception=true)
	{
		$res=false;
		if ($this->cache_object_method=='apcu')
		{
			
		}
		else{
			$res = $this->cache_file_set($uniqFileName, serialize($content), false);
		}
		if(!$res && $throw_exception){
			throw new \Exception('Was unable to set APCU cache for '.$uniqFileName);
		}
		return $res;
	}



	// ### CACHE DIRS ###
	private $cacheDirectory = __DIR__.'/_caches_php/';  //sys_get_temp_dir()
	public function cache_dir_set($dir=null, $auto_clear_seconds=null){ 
		if($dir)  $this->cacheDirectory = $dir;
		$res = $this->mkdir($this->cacheDirectory);
		if( !is_null($auto_clear_seconds))
		{
			$this->clearCacheDir($auto_clear_seconds); 
		}
		return $res;
	}
	public function cache_dir_get($create=true){ 
		$dir = $this->cacheDirectory;
		if($create && !is_dir($dir)){ mkdir($dir, 0755, true); }
		return $dir; 
	}	 

	// ### CACHE FILES ###
	public function cache_file_location($uniqFileName){
		$uniqFileName = is_string($uniqFileName) || is_numeric($uniqFileName) ? $uniqFileName : json_encode($uniqFileName);
		$uniqFileName = self::sanitize( substring($uniqFileName, 0, 10)) + "_"+ md5($uniqFileName);
		$filePath= $this->cache_dir_get() . $uniqFileName ."_tmp"; //"/". 
		return $filePath;
	}
	public function cache_file_get($uniqFileName, $default='', $expire_seconds=8640000, $decode='array' )
	{
		$filePath= $this->cache_file_location($uniqFileName);
		if ( strlen($filePath) < 3) return "too tiny filename";

		if ( file_exists($filePath) ){
			if (filemtime($filePath)+$expire_seconds<time() ){
				unlink($filePath);
				return $default;
			}
			else{	
				$cont = $this->file_get_contents($filePath);
				// if specifically array, then on empty, reckon as array
				if (empty($cont) && $default==[])
				{
					return $default;
				}
				if ($decode){
					try{
						return json_decode($cont, ($decode=='array'));
					}
					catch(\Exception $ex){
						return $cont;
					}
				}
				else{
					return $cont;
				}
			}
		}
		else {
			return $default;
		}
	}
	public function cache_file_set($uniqFileName, $content, $encode=true)
	{
		$filePath= $this->cache_file_location($uniqFileName);
		$contentFinal = ($encode && (is_array($content) || is_object($content)) ) ? json_encode($content): $content;
		return $this->localdata_set($filePath, $contentFinal);
	}
	
	public function cache_file_append_array($uniqKeyFileName, $data)
	{    
		$existing  = $this->cache_file_get($uniqKeyFileName,[]);
		$newData   = is_array($data) ? $data : [$data];
		$finalData = array_merge_recursive($existing,$newData);
		return $this->cache_file_set($uniqKeyFileName, $finalData);
	}


	public function cacheDirUrl(){ return basename($this->cache_dir_get()); }
	public function backupFileIntoCache($filename, $data){
		$filename = $this->cache_dir_get() .'/'. $filename . date('Y-m-d H-i-s') . "_".md5($data);
		$this->localdata_set($filename, is_array($data) ? json_encode($data) : $data );
	}

	// ####################### IDs ####################### //
	public $cached_IDS_type = 'file'; // file, wp, db, object (using redis or whatever, according to $CACHE_CHOSEN_PROGRAM) //$this->isWP; 
	public function cache_key_create($text){return md5( is_array($text) ? json_encode($text) : $text );	}
 
	// ########
	private function cache_ids_parentname($containerHint){ return "_px_cached_ids_".$containerHint;} 
	private function get_cached_ids_array($containerHint){
		$ContainerName = $this->cache_ids_parentname($containerHint);
		if ($this->cached_IDS_type=='file'){
			$filePath =$this->cache_dir_get() . $ContainerName;
			if ( empty($this->temp_cacheIdsArray) || empty($this->temp_cacheIdsArray[$filePath]) ) {
				$cont = $this->file_get_contents( $filePath );
				if ( empty($cont) ) {
					$this->temp_cacheIdsArray[$filePath] = [];
				}
				else {
					$this->temp_cacheIdsArray[$filePath] = json_decode($cont,true);
					//if error happened
					if (is_null($this->temp_cacheIdsArray[$filePath])){
						//if contains broken array due to rare overwrite problem, i.e. ["id_1"],"id2","id3"]
						if ($this->contains($cont, $delimiter = '"')){
							$arrs=$this->string_to_array($cont, $delimiter);
							$this->temp_cacheIdsArray[$filePath]=$arrs;
						}
					}
				}
			}
			$existing_ids = $this->temp_cacheIdsArray[$filePath];
		}
		elseif ($this->cached_IDS_type=='wp'){
			$existing_ids = get_option( $ContainerName, [] );
		}
		elseif ($this->cached_IDS_type=='object'){
			$existing_ids = $this->cache_get( $ContainerName, [] );
		}
		return $existing_ids;
	}
	private function set_cached_ids_array($containerHint, $existing_ids){
		$ContainerName = $this->cache_ids_parentname($containerHint);
		if ($this->cached_IDS_type=='file'){
			$filePath = $this->cache_dir_get() . $ContainerName;
			$this->temp_cacheIdsArray[$filePath] = $existing_ids;
			$this->localdata_set( $filePath, json_encode($existing_ids) );
		}
		elseif ($this->cached_IDS_type=='wp'){
			update_option( $ContainerName, $existing_ids );
		}
		elseif ($this->cached_IDS_type=='redis'){
			$this->cache_set( $ContainerName, $existing_ids);
		}
	}

	private function add_cached_id($containerHint, $cache_id){
		$ContainerName = $this->cache_ids_parentname($containerHint);
		//to ensure to preserve any overwrites happened within last few milliseconds
		$latest_current_ids = $this->get_cached_ids_array($containerHint); //
		//$recently_added_ids = array_diff($latest_current_ids, $added_ids);
		//$up_to_date_IDS = array_merge($existing_ids, $recently_added_ids); 
		$latest_current_ids[]=$cache_id;
		$this->set_cached_ids_array($containerHint, $latest_current_ids);
	}

	public function is_cached_id($cache_parent_key, $item_key_or_params){  
		$key = is_array($item_key_or_params) ? json_encode($item_key_or_params) : $item_key_or_params;
		$key = strlen($key) <=35 ? $key : md5($key); //if same length as md5, then prefer original readable key
		if( in_array($key, $this->get_cached_ids_array($cache_parent_key) ) )
		{
			return true;
		}
		else{
			$this->add_cached_id($cache_parent_key, $key);
			return false;
		}
	}
	public function clearCacheIdsOnCall($param_name){ 
		if (isset($_GET[$param_name]))
		{
			$this->clearCacheIds('todo');
		}
	}
	public function clearCacheIds($key_name){ 
		$key_fullname = $this->cache_ids_parentname($key_name);
		if ($this->cached_IDS_type=='local'){
			$this->rmdir($this->cache_dir_get());
			$this->mkdir($this->cache_dir_get());
		}
		elseif ($this->cached_IDS_type=='wp'){
			update_option( $this->cache_ids_parentname($key_name), []);
		}
		elseif ($this->cached_IDS_type=='object'){
			$this->cache_set( $key_fullname, []);
		}
		else {
		}
	}
	public function clearCacheDir($seconds=86400){
		$timerFile= $this->cache_dir_get().'/_cleanTime.blobz';
		if (file_exists($timerFile) && filemtime($timerFile)<time()-$seconds){
			array_map( 'unlink', array_filter((array) glob( $this->cache_dir_get()."*") ) );
			$this->localdata_set($timerFile, time());
		}
		else{
			$this->localdata_set($timerFile, time());
		}
	} 
	
	public function cacheDirLink(){
		return $this->baseURL .'/'. $this->cacheDirUrl() .'/';
	}


	// usage:  cachedFunction( [$xyzClass,'methodName'], $params, $cache_seconds=60*60*24, "mySitePeopleAges" )
	public function cachedFunction($callbackFunction, $params=[], $seconds=86400, $UniqCacheName='', $force_on_empty=true){
		$fileName = $this->funcStringName($callbackFunction, $params, $seconds, $UniqCacheName);
		$cache_file = $this->cache_dir_get() .'_'. $fileName ;
		$call = false;
		//if ( $this->isWpCache() )
		//{
		//}
		
		if ( $seconds<=0 || $this->forceNewCache || !file_exists($cache_file) || time() - filemtime($cache_file) > $seconds )  
		{
			$call=true;
		}
		else{
			$cont = $this->file_get_contents($cache_file);
			if ($cont=="" && $force_on_empty){
				$call=true;
			}
			else{
				$response = $cont;
			}
		}
		//
		if($call){
			$response = call_user_func_array($callbackFunction, $params);
			$this->localdata_set($cache_file, is_array($response) || is_object($response) ? json_encode($response) : $response );
		}

		try{
			return is_array($response) || is_object($response) || ! is_string($response) ? $response : json_decode($response);
		}
		catch(\Exception $e)
		{
			return $response;
		}
	}
	
	public function funcStringName($callbackFunction, $params, $seconds, $fixedName='')
	{ 
		if (!empty($fixedName))  
			return $fixedName; 
		$funcSlug = is_array($callbackFunction) && is_object($callbackFunction[0]) ? get_class($callbackFunction[0])."_".$callbackFunction[1] : (is_string($callbackFunction) ? $callbackFunction : md5(json_encode($callbackFunction)));
		$funcAliasString= md5( basename( $funcSlug ."_". md5(json_encode($params)) . "_". $seconds ) );
		return $funcAliasString; 
	}

	public function transientFunction($callbackFunction, $params=[], $seconds=86400, $transientName=''){
		$transientName = $this->funcStringName($callbackFunction, $params, $seconds, $transientName) ;
		if( ($value = get_transient($transientName))===false ) { 
			$value = call_user_func_array($callbackFunction, $params);
			set_transient($transientName, $value, $seconds);
		}
		return $value;
	}
	#endregion  ################ CACHE ALL ################
	


	//from Wordpress codex
	public static function is_serialized( $data, $strict = true ) { if ( ! is_string( $data ) ) { return false; } $data = trim( $data ); if ( 'N;' === $data ) { return true; } if ( strlen( $data ) < 4 ) { return false; } if ( ':' !== $data[1] ) { return false; } if ( $strict ) { $lastc = substr( $data, -1 ); if ( ';' !== $lastc && '}' !== $lastc ) { return false; } } else { $semicolon = strpos( $data, ';' ); $brace     = strpos( $data, '}' ); if ( false === $semicolon && false === $brace ) { return false; } if ( false !== $semicolon && $semicolon < 3 ) { return false; } if ( false !== $brace && $brace < 4 ) { return false; } } $token = $data[0]; switch ( $token ) { case 's': if ( $strict ) { if ( '"' !== substr( $data, -2, 1 ) ) { return false; } } elseif ( false === strpos( $data, '"' ) ) { return false; } case 'a': case 'O': return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data ); case 'b': case 'i': case 'd': $end = $strict ? '$' : ''; return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data ); } return false; }


	//https://www.php.net/manual/en/errorfunc.constants.php
	public function errors_exception(){
		//set_error_handler("warning_handler", E_WARNING);
		set_error_handler(function($errno, $errstr, $errfile, $errline) {
			// error was suppressed with the @-operator
			if (0 === error_reporting()) {
				return false;
			}
			
			throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
		});
	}


	// TODO - handle $_POST
	public function disable_cache($hard=false, $file=false){
		header("Expires: Mon, 4 Jan 1999 12:00:00 GMT");        // Expired already 
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");     
		header("Cache-Control: no-cache, must-revalidate");      // good for HTTP/1.1 
		header("Pragma: no-cache"); 
		if($hard){
			if(!isset($_GET['rand']))
				$this->php_redirect( $this->AddStringToUrl($_SERVER['REQUEST_URI'], 'rand='.rand(1,9999999) )   );
		}
		ini_set("opcache.enable", 0); 
		if($file){
			opcache_invalidate($file);
		}
	}


		
	public function my_mail($a=null,$b=null,$c=null,$d=null,$e=null){ return (!$this->definedTRUE("MAILS_DISABLED") ? mail($a,$b,$c,$d,$e) : "MAILS_NOT_ENABLED__error99234"); }

	public function get_yout_Vid_Aud_array($ID,$TITL)	{return yout_DownUrls($ID, $TITL);}

	public function default_mail_headers($from=false){ return $headers='MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n" . 'From: mesg@' .$_SERVER['HTTP_HOST'] ."\r\n".'Reply-To: mesg@'.$_SERVER['HTTP_HOST'] . "\r\n" . "X-Mailer: PHP/" . phpversion(); }	
		


	//use whenever you want to show something on the first happening
	// first_cookie_message('ini_get_noexits','<script>alert("ini_get doesnt work on server. i will hide forever now");</script>')
	public function first_cookie_message($identifier, $message){
		$cName=filter_var($identifier, FILTER_SANITIZE_STRING);
		if (!isset($_COOKIE[$cName])){
			setcookie($cName,'okk',time()+99999999, $this->constantX('homeFOLD','/'));
			die($message);
		}
	}

	public function CookieSet($name){ if (empty($_COOKIE[$name])) { return false;} else { return true;} }
	public function CookieSetOnceExecution($name){ if (empty($_COOKIE[$name])) { setcookie($name, time(), time()+ 999999,  $this->constantX('homeFOLD','/') ); return true; } return false; }
	public function CookieNotSet($name){ CookieSetOnceExecution($name); }

	public function set_cookie($name, $val, $time_length = 86400, $path=false, $domain=false, $httponly=true, $only_on_secure_https = false){
		$site_urls = parse_url( (function_exists('home_url') ? home_url() : $_SERVER['SERVER_NAME']) );
		$real_domain = $site_urls["host"];
		$path = $path ? $path : ( (!empty($this) && property_exists($this,'homeFOLDER') ) ?  $this->homeFOLDER : '/');
		$domain = $domain ? $domain : ((substr($real_domain, 0, 4) == "www.") ? substr($real_domain, 4) : $real_domain);
		setcookie ( $name , $val , time()+$time_length, $path = $path, $domain = $domain,  $only_on_secure_https,  $httponly  );
	}
	public function setcookie_secure($name, $val, $time_length = 86400, $httponly=true, $homeurl=false){
		$real_domain = $homeur ?: $_SERVER['HTTP_HOST'];
		$domain = (substr($real_domain, 0, 4) == "www.") ? substr($real_domain, 4) : $real_domain;
		$path = $path ?: ( (!empty($this) && property_exists('pathAfterDomain', $this) ) ?  $this->pathAfterDomain : '/');
		setcookie ( $name , $val , time()+$time_length, $path, $domain = $domain ,  $only_on_https = FALSE,  $httponly  );
	}

	public function page_load_limited_for_seconds($seconds = 3, $cookiename = 'pageloader_limiter'){
		if (isset($_COOKIE[$cookiename])) {
			
		}
	}
	public function siteSlug() { return str_replace(array('.','/',':'),'_', $this->domain  ); }

	public function site_visitor_default_cookiee() {return 'default_visitr_'.siteSlug(); }

	public function SetCookieForVisitors(){ setcookie(site_visitor_default_cookiee(), time()+1000, time()+1000, $this->constantX('homeFOLD','/'));  }
	//      SetCookieForVisitors();

	public function die_if_not_this_site_youtube(){if (!isset($_COOKIE[site_visitor_default_cookiee()])) {  die('noauth_6453'); } }

	public $share_urls =
	[
		'facebook'	=>'https://www.facebook.com/sharer/sharer.php?u=', 
		'twitter'	=>'https://twitter.com/share?url='
	];

	public function validate_mail( $mail ){  //$_POST['email']
		return !filter_var( $mail, FILTER_VALIDATE_EMAIL );
	}

	// only for explicit temp use
	public function password_site($password, $hint="Type password")
	{
		$rnd_ext = 'pss_'.str_replace('.','_', $this->domain);
		if ( isset($_POST['passwk']) && $password == $_POST['passwk'] ) { setcookie($rnd_ext, $password,  time()+1111111,  $this->homeFOLD); header("location:".$_SERVER['REQUEST_URI']);exit; } 
		elseif (!isset($_COOKIE[$rnd_ext]) || $_COOKIE[$rnd_ext]!=$password ){ echo '<div style="display:flex; justify-content:center;"><form action="" method="post">  <b>'.$hint.'</b>:<input name="passwk" value="">  <input type="submit" value="Enter"></form></dvi>';exit;}
	}	

	public function get_filename_($url){ return basename(parse_url($url)['path']); }

	public function scriptt($name, $with_css=false)	{ 
		return  ( (!empty($GLOBALS['already_loaded_'.$name])) ? '<!-- already outputed "'.$name.'" -->' :  $GLOBALS['already_loaded_'.$name]='<script type="text/javascript" src="'. $GLOBALS['odd']['scripts'][$name]['js'].'"></script>')  
			.  
		( !$with_css ? '' : '<link rel="stylesheet" href="'.  $GLOBALS['odd']['scripts'][$name]['css'].'"> '   );
	}

	public function scriptss(){
		foreach(func_get_args() as $key=>$value){ echo (!is_array($value) ? scriptt($value) : scriptt($value[0], $value[1]) ); }
	}


	public function translate__MONTH($text,$target_lang=''){   global $odd;	//switch ($text) { case 'January':	return TRANSLL('monthh1',$target_lang);
		if( !empty($odd['months_langs'][$target_lang]) && array_key_exists($text, $odd['months_langs'][$target_lang]))	{  
			$text = $odd['months_langs'][$target_lang][$text];
			if (mb_detect_encoding($text) =='UTF-8') {$text= mb_substr ($text,0,3,'utf-8') ; }  
		} 
		else{
			$text = TRANSLL($text,$target_lang);
		}
		return $text;
	}


	public function translate__DAY($text,$target_lang='') {	
		if (in_array($text, array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')) ) {
			return TRANSLL($text,$target_lang); 
		} return $text;
	}

	// language specifics
	public function GEO_to_ENG($input){  return strtr($input, array(
		"�?"=>"a",	"ბ"=>"b",	"გ"=>"g",	"დ"=>"d",	"ე"=>"e",	"ვ"=>"v",	"ზ"=>"z",	"თ"=>"T",	"ი"=>"i",
		"კ"=>"k",	"ლ"=>"l",	"მ"=>"m",	"ნ"=>"n",	"�?"=>"o",	"პ"=>"p",	"ჟ"=>"J",	"რ"=>"r",	"ს"=>"s",
		"ტ"=>"t",	"უ"=>"u",	"ფ"=>"f",	"ქ"=>"q",	"ღ"=>"R",	"ყ"=>"y",	"შ"=>"S",	"ჩ"=>"C",	"ც"=>"c",
		"ძ"=>"Z",	"წ"=>"w",	"ჭ"=>"W",	"ხ"=>"x",	"ჯ"=>"j",	"ჰ"=>"h"    	));
	}
	public function ENG_to_GEO($input) { return strtr($input, array(
		'a'=>'�?',	'b'=>'ბ',	'g'=>'გ',	'd'=>'დ',	'e'=>'ე',	'v'=>'ვ',	'z'=>'ზ',	'T'=>'თ',	'i'=>'ი',
		'k'=>'კ',	'l'=>'ლ',	'm'=>'მ',	'n'=>'ნ',	'o'=>'�?',	'p'=>'პ',	'J'=>'ჟ',	'r'=>'რ',	's'=>'ს',
		't'=>'ტ',	'u'=>'უ',	'f'=>'ფ',	'q'=>'ქ',	'R'=>'ღ',	'y'=>'ყ',	'S'=>'შ',	'C'=>'ჩ',	'c'=>'ც',
		'Z'=>'ძ',	'w'=>'წ',	'W'=>'ჭ',	'x'=>'ხ',	'j'=>'ჯ',	'h'=>'ჰ'		));
	}

	//UPPERCASE CHARS sometimes MESS-UP several FUNCTION's USAGE. So, sometimes we need lowercased words
	public function GEO_to_ENG__LowerCased($m) { return strtolower(strtr($m, array( 
		"�?"=>"a",	"ბ"=>"b",	"გ"=>"g",	"დ"=>"d",	"ე"=>"e",	"ვ"=>"v",	"ზ"=>"z",	"თ"=>"t",	"ი"=>"i",
		"კ"=>"k",	"ლ"=>"l",	"მ"=>"m",	"ნ"=>"n",	"�?"=>"o",	"პ"=>"p",	"ჟ"=>"dj",	"რ"=>"r",	"ს"=>"s",
		"ტ"=>"t",	"უ"=>"u",	"ფ"=>"f",	"ქ"=>"q",	"ღ"=>"gh",	"ყ"=>"y",	"შ"=>"sh",	"ჩ"=>"ch",	"ც"=>"c",
		"ძ"=>"dz",	"წ"=>"w",	"ჭ"=>"tch",	"ხ"=>"x",	"ჯ"=>"j",	"ჰ"=>"h"    	)));
	}

	public function Rus_To_Eng__LowerCased($input){  return strtr($input, array(
		"а"=>"a","�?"=>"a",		"б"=>"b","Б"=>"b",		"в"=>"v","В"=>"v",		"г"=>"g","Г"=>"g",		"д"=>"d","Д"=>"d",
		"е"=>"e","Е"=>"e",		"ё"=>"yo","�?"=>"yo",	"ж"=>"zh","Ж"=>"zh",	"з"=>"z","З"=>"z",		"и"=>"i","И"=>"i",
		"й"=>"j","Й"=>"j",		"к"=>"k","К"=>"k",		"л"=>"l","Л"=>"l",		"м"=>"m","М"=>"m",		"н"=>"n","�?"=>"n",
		"о"=>"o","О"=>"o",		"п"=>"p","П"=>"p",		"р"=>"r","Р"=>"r",		"�?"=>"s","С"=>"s",		"т"=>"t","Т"=>"t",
		"у"=>"u","У"=>"u",		"ф"=>"f","Ф"=>"f",		"х"=>"kh","Х"=>"kh",	"ц"=>"ts","Ц"=>"ts",	"ч"=>"ch","Ч"=>"ch",
		"ш"=>"sh","Ш"=>"sh",	"щ"=>"sch","Щ"=>"sch",	"ъ"=>"","Ъ"=>"",		"ы"=>"y","Ы"=>"y", 		"ь"=>"","Ь"=>"",
		"�?"=>"e","Э"=>"e",		"ю"=>"yu","Ю"=>"yu",	"�?"=>"ya","Я"=>"ya",    ));
	}
	public function ic1251_to_utf8($s){
		$s= str_replace('С?',$a1='fgr43443443',$s);
		$s= str_replace('Р?',$a2='tg5gh45h3hg3',$s);
		$s= str_replace('пїЅпїЅ?',$a3='fgr35gh35hg3gdfw',$s);
		$s= str_replace('СЊС?',$a4='XXX83rhf423888df8d23d1',$s);
		$s= str_replace('бѓ?',$a5='XXX83rhf423888df8d23d2',$s);
		$s= mb_convert_encoding($s, "windows-1251", "utf-8");
		$s= str_replace($a5,'ი',$s);
		$s= str_replace($a3,'ი',$s);
		$s= str_replace($a1,'ш',$s);
		$s= str_replace($a2,'И',$s);
		$s= str_replace($a4,'шь',$s);
		return $s;
	}

	public function INCORRECT_GEO_to_ENG($input){  return strtr($input, array(
		"áƒ�?"=>"a", "áƒ‘"=>"b", "áƒ’"=>"g",  "áƒ“"=>"d",  "áƒ�?"=>"e",  "áƒ•"=>"v",  "áƒ–"=>"z",  "áƒ—"=>"T",  "áƒ˜"=>"i",  "áƒ™"=>"k", "áƒš"=>"l",  "áƒ›"=>"m",  "áƒœ"=>"n",  "áƒ�?"=>"o", "áƒž"=>"p",  "áƒŸ"=>"J",  "áƒ "=>"r",  "áƒ¡"=>"s",    "áƒ¢"=>"t",  "áƒ£"=>"u",  "áƒ¤"=>"f",  "áƒ¥"=>"q",  "áƒ¦"=>"R",  "áƒ§"=>"y",  "áƒ¨"=>"S",  "áƒ©"=>"C",  "áƒª"=>"c",  "áƒ«"=>"Z",  "áƒ¬"=>"w",  "áƒ­"=>"W",  "áƒ®"=>"x",  "áƒ¯"=>"j",  "áƒ°"=>"h"   ));
	}
	
	// # language specifics
	
	
	public function validate_post_id($id)	 { if (!is_numeric($id) || strlen($id)>7) 								{die("incorrrrrect_postid error81"); }}
	public function validate_simple_word_of_s_GET($text){if (preg_match('/[\<\>\'\=\$\"\?\(\{]/si',$text))			{die("incorrrrrect error86");}}
	//
	// Validation
	public function validate_url($url)	{ return filter_var($url, FILTER_VALIDATE_URL) !== false && (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)); }  
	public function validate_email($email)	{
		if(empty($email))  return false;
		$regex = '/'. ($name='([a-z0-9_.-]+)').  ($at='@').  ($sub_domain='([a-z0-9.-]+){2,255}') . ($period='.').  ($ext='([a-z]+){2,10}'). '/i';
		return empty(preg_replace($regex, '', $email) );
	}
	// ########## Sanitization ##########
	// https://php.net/manual/en/filter.filters.sanitize.php
	
	public static function sanitize($key){ return preg_replace( '/[^a-zA-Z0-9_\-]/', "_", trim($key) ); }
	public static function sanitize_key($key, $use_dash=false ){ return preg_replace( '/[^a-z0-9_\-]/', ($use_dash===true ? "_": (is_string($use_dash) ? $use_dash: "") ), strtolower(trim($key) )); }  //same as wp
	public static function sanitize_key_($key, $use_dash=true ){ return self::str_replace_recursive( "__","_",  self::sanitize($key, $use_dash) ); }
	public static function sanitize_text($str,$use_dash=false) { return preg_replace("/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\-\_\+\=\,\.\/\?\;\[\]\{\}\|\s]+/", ($use_dash ? "_":""), trim($str)); }	 //  \= \/ 
	//Try this to remove everything except a-z, A-Z and 0-9, -, _, .
	public static function sanitize_nonoword($text)		{ return preg_replace('/\W/si','',$text); }   
	public static function sanitize_alhpabet($key){ return preg_replace( '/[^a-zA-Z]/',"", $key); }

	public static function sanitize_text_entities($str,$use_dash=false){ return self::sanitize_text(htmlentities($str,$use_dash)); }	
	public static function sanitize_text_filter($string) { return filter_var($string,FILTER_SANITIZE_STRING);}
			// other versions
			// [^a-zA-Z0-9\-\_\.]
			// return strtr($input, [ " "=>"-",	"."=>"--",	":"=>"--",	","=>"-",	"/"=>"-",	";"=>"--",	"—"=>"",	"–"=>"-" ]);
			// str_replace(array(' ','-',',','.','/','\\','|','!','@','#','$','%','^','&','*','(',')'),'_',   strip_tags( trim($str) ));
			// preg_replace('/[^\w\d_\-]/', '',  filter_var($input,	FILTER_SANITIZE_STRING)	);
	public static function sanitize_digits($string){ return filter_var($string,FILTER_SANITIZE_NUMBER_INT);}
	public static function sanitize_url($string)  	{ return filter_var($string,FILTER_SANITIZE_SPECIAL_CHARS);}
	public static function SanitizeSymbol($str)	{ return str_replace(array('/','\\','|','!','*'), '_',   strip_tags( strtoupper(trim($str) )) ) ; } 
	public static function sanitize_url_dots($url)	{ return self::remove_double_slashes(str_replace('/..','', str_replace('\\','', $url) ) ); }

	public static function sanitize_unicode($text, $replace_with=''){ 
		$x= preg_replace('/[\x00-\x1F\x7F-\xFF]/', $replace_with, $text); 
		return preg_replace('/['.$replace_with.']+/', $replace_with, $x);
	}

	public static function sanitize_text_field($text)
	{
		if(function_exists('sanitize_text_field'))
			return sanitize_text_field($text);
		else
			return self::sanitize_text($text);
	}
	public static function sanitize_text_field_recursive($data)
	{
		if ( empty($data) ) {
			return $data;
		}
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$value = self::sanitize_text_field_recursive($value);
				} else {
				   $value = stripslashes(sanitize_text_field($value));
				}
				$data[$key] = $value;
			}
			return $data;
		}
		return sanitize_text_field($data);
	}

	public static function sanitize_comma_array($string, $type="key")
	{
		$values = explode(',', $this->sanitize_text_field($string));
		$sanitized_values = $values;
		$sanitized_values = array_map('sanitize_key', $sanitized_values);
		$sanitized_values = array_map('trim', $sanitized_values);
		$sanitized_text = implode(',', $sanitized_values);
		return $sanitized_text;
	}
	public static function remove_new_lines($text)
	{
		return str_replace(["\r","\n"],'', $text);
	}
	//
	public static function removWhitespaces($input, $oneSpace=true){ 
		$what = $oneSpace ? ' ':'';
		$input= str_replace("   ",		$what,$input );
		$input= str_replace("  ",		$what,$input );
		$input= str_replace("\t\t",		$what,$input );
		$input= str_replace("\t",		$what,$input );
		$input= str_replace("\r\n\r\n",	$what,$input );
		$input= str_replace("\r\n ",	$what,$input );
		if (!$oneSpace){
			$input= str_replace(" ",	$what,$input );
		}
		return $input;
	}
	

	public static function stripCOODs($input){ return strip_shortcodes(strip_tags($input, '<h1></h1><br><br/><br /><br/ ><br / >< br>< br/>'));}

	public static function str_replace_recursive($value, $replace, $string) {
		$string = str_replace($value, $replace, $string);
		if (strpos($string, $value)!==false) {
			$string= self::str_replace_recursive($value, $replace, $string);
		}
		return $string;
	}

	// remove dots (.) and "after plus part" (+xxxx) from gmail address
	public function sanitize_gmail($user_mail)
	{
		$sanitized_email = preg_replace_callback( '/(.*)\@/si', 
			function($matches){return str_replace('.','',$matches[0]); },  
			preg_replace( '/\+.*\@/s', '@', $user_mail )
		);
		return $sanitized_email;
	}

	public function sanitize_utf8_filenamee($input){
		$filename_sanitized = $this->GEO_to_ENG__LowerCased($input);
		$filename_sanitized = $this->Rus_To_Eng__LowerCased($filename_sanitized);
		$filename_sanitized = str_replace(' ','-',$filename_sanitized);
		$filename_sanitized = utf8_encode($filename_sanitized);
		return $filename_sanitized;
	}
	public function remove_html_parts($content)
	{
		$content = preg_replace('/(.*)\<body\>/si','',$content);
		$content = preg_replace('/<script(.*?)script\>/si','',$content);
		$content = preg_replace('/<iframe(.*?)iframe\>/si','',$content);
		$content = preg_replace('/\<\/body(.*)/si','',$content);
		return trim($content);
	}
	
	public function ensure($value, $array){
		if (in_array($value, $array))
			return $value;
		else {
			$msg =  "Provided value was not in array. Value: " . $this->var_dump($value) . "\r\n<br/>Array:".$this->var_dump($array) ;
			throw new \Exception( $msg );
		}
	}
	
	public function benchmark_function($callback, $amount=100000, $hint=''){
		$before = microtime(true);
		$val=[];
		for ($i=0 ; $i<$amount; $i++) {
			$val[]=call_user_func($callback);
		}
		$after = microtime(true);
		echo "Benchmark results for $hint: ". self::number_format($after-$before, 5) . " sec\n<br/>";
		return $val;
	}
	
	public function decode_encoded_utf8($string){
		return preg_replace_callback('#\\\\u([0-9a-f]{4})#ism', function($matches) { return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE"); }, $string);
	}

	// directory correction
	public function directory_canonicalize3($address)
	{
		$address = explode('/', $address);
		$keys = array_keys($address, '..');

		foreach($keys AS $keypos => $key)
		{
			array_splice($address, $key - ($keypos * 2 + 1), 2);
		}

		$address = implode('/', $address);
		$address = str_replace('./', '', $address);

		return $address;
	}

	public function directory_canonicalize2($address)
	{
		$address =preg_replace_callback(
			'/(.*?|)\/(.*?)(\/..*?)\b/i',  
			function ($matches){
				if(!empty($matches[3])){
					return ($matches[3]);
				}
				return $matches[0];
			},
			$address
		);
		return $address;
	}

	public function RemoveParameterFromUrl($full_url, $param_name){
		return $final = preg_replace('/(\&|\?)'.$param_name.'(\=(.*?(&|#)|.*)|)/i', (!empty('$4') ? '$4' : ''), $full_url);
	}
	
	public function remove_query_from_url($url, $which_argument=false){ 
		return preg_replace( '/'.  (  $which_argument ? '(\&|)'.$which_argument.'(\=(.*?)((?=&(?!amp\;))|$)|(.*?)\b)' : '(\?.*)').'/i' , '', $url);  
	}
	public function get_query_from_url($url){ 
		$query= $this->array_value(parse_url($url), 'query','');
		parse_str($query, $output);
		return $output;
	}
	
	public function checked_if_value($array, $key){
		return ( $this->array_value($array, $key)  ? ' checked="checked"' : '');
    }
	// ##################################

	
	// Output decimals better, i.e.  $x= 0.000021;  or  $x= 123424235.325434645
	// method 1
	public static function remove_zero_from_end($input){
		return floatval($input);//

		// Method 2 : 
		// while( ($last=substr($input,-1))=="0" || $last=="." )
		// 	$input= substr($input,0,-1);
		// return $input;

		// Method 3 :
		// return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
	}
	public function add_zero_in_front($num, $maxLength){
		$finalNum = $num;
		while( strlen($finalNum)<$maxLength ){
			$finalNum = "0".$finalNum;
		}
		return $finalNum;
	}
	public function trim_zero_dot($input){
		$sanitized=rtrim( $input, "0");
		if(substr($sanitized, -1) =="."){
			$sanitized=substr($sanitized,0, -1);
		}
		return $sanitized;
	}

	public function doubleNormal($input, $round_to=15, $use_sprintf=true){ 
		return (!is_float($input) && !is_numeric($input) ? $input : (float) $this->trim_zero_dot( $use_sprintf ? sprintf("%.{$round_to}f", $input) : self::number_format($input, $round_to) ) );	
	}
	public static function number_format($input, $decimals=15, $method=1){ 
		if ($method===1)
			$ret = number_format($input, $decimals, null, ''); 
		else 
			$ret =sprintf("%.{$decimals}f", $input); 
		return self::remove_zero_from_end($ret);
	}
	

	
	// method 2
	public function decimal_outputer($input, $length=5, $only_dot=false){  
		$timeParts = explode('.', $input);
		if(count($timeParts)<=1) return $input;
		return ($only_dot ? '' : $timeParts[0] . '.') . substr($timeParts[1], 0, $length); //sprintf('%.10F',$input); 
	}
	//
	public function doubleNormalArray($array){
		return $this->array_map_deep([$this,'doubleNormal'], $array);
	}


	//WP immitations
	public function add_filterX($a=null,$b=null,$c=null,$d=null)	{if(function_exists('add_filter')) 		return add_filter($a,$b,$c,$d);  	}
	public function add_actionX($a=null,$b=null,$c=null,$d=null)	{if(function_exists('add_action')) 		return add_action($a,$b,$c,$d);  	}
	public function add_shortcodeX($a=null,$b=null,$c=null,$d=null)	{if(function_exists('add_shortcode'))	return add_shortcode($a,$b,$c,$d);  }
	
	public function cut__my($text, $chars, $points = "...") {  $text = strip_tags($text);	if( strlen($text) <= $chars) { return $text;} else { return mb_strimwidth($text,0,$chars, $points,'utf-8'); } }
	public function trim_string($text, $chars, $points = "...") {  if( strlen($text) <= $chars) { return $text;} else { return mb_strimwidth($text,0, $chars, $points,'utf-8'); } }

	public function myUTF8truncate($string, $width){
		if (mb_str_word_count($string) > $width) {
			$string= preg_replace('/((\w+\W*|| [\p{L}]+\W*){'.($width-1).'}(\w+))(.*)/', '${1}', $string);
		}
		return $string;
	}
	
	
	public function customm_word_length_sentence($got_content,$words_length,$StripOrNot=true, $preserved=''){
		$got_content = trim($got_content); 			//https://php.net/manual/en/function.trim.php
		//$got_content = strip_shortcodes($got_content); //https://stackoverflow.com/a/20403438/2165415
		$got_content = str_replace(']]>', ']]>', $got_content);
		$got_content= str_replace("\n",' ',$got_content);
		$got_content= str_replace("\r",' ',$got_content);
		$got_content = !$StripOrNot ? $got_content : strip_tags($got_content,$preserved) ;
		$words = explode(' ', $got_content, $words_length + 1);
		if(count($words) > $words_length) :
			array_pop($words);
			array_push($words, '…');
			$got_content = implode(' ', $words);
		endif;
		return $got_content;	
	}

	public function unicode_words_count($string) {	preg_match_all('/[\pL\pN\pPd]+/u', $string, $matches);	return count($matches[0]);}
	public function text_splitt($msg, $word_numbs) {
		$msg = preg_replace('/[\r\n]+/', ' ', $msg);
		$chunks = wordwrap($msg, $word_numbs*20 , '\n', true);
		return explode('\n', $chunks);
	}
	
	public function trim_to_charlength($text, $charlength) {
		$charlength++;

		if ( mb_strlen( $text ) > $charlength ) {
			$subex = mb_substr( $text, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				echo mb_substr( $subex, 0, $excut );
			} else {
				echo $subex;
			}
			echo '...';
		} else {
			echo $text;
		}
	}

	
	// ### substr shorthands:
	public function charsFromStart($word, $amount)
	{
		return substr($word, 0, $amount);
	}
	public function charsFromEnd($word, $amount)
	{
		return substr($word, -$amount);
	}
	public function charsWithoutStartEnd($word, $removeFromStart, $removeFromEnd)
	{
		return substr($word, $removeFromStart, -$removeFromEnd);
	}
	

	// makes a string from an assiciative array
	public function implodeAssoc($glue,$arr) 
	{ 
		$keys=array_keys($arr); 
		$values=array_values($arr);
		return(implode($glue,$keys).$glue.implode($glue,$values)); 
	}

	
	public function url_correction_for_html_output($content){ 
		return preg_replace_callback( 
			'/\<(img|link|iframe|frame|frameset|script|embed|video|audio)([^>]*)/si', 
			function($matches) { return '<'.$matches[1].preg_replace('/=(\"|\')(http(s|):)/si','=$1', $matches[2]);	}, 
			$content
		);
	}
	
	public function array_max_pair($array){
		$peakVal = -999999999999;
		$peakPair = [];
		foreach($array as $key=>$value){
			if ($value>=$peakVal){
				$peakVal=$value;
				$peakPair=[$key,$value];
			}
		}
		return $peakPair;
	}
	public function array_min_pair($array){
		$peakVal = +999999999999;
		$peakPair=[];
		foreach($array as $key=>$value){
			if ($value<=$peakVal){
				$peakVal=$value;
				$peakPair=[$key,$value];
			}
		}
		return $peakPair;
	}
	
	public function equals_string($content, $target){ 
		return strtolower($content)===strtolower($target);
	}

	public static function contains($content, $needle, $case_sens=true, $position='any'){ 
		if ($position==='start'){
			return $this->startsWith($content, $needle, $case_sens);
		}
		elseif ($position==='end'){
			return $this->endsWith($content, $needle, $case_sens);
		}
		else{
			return ($case_sens ? strpos($content, $needle)!==false : stripos($content, $needle)) !== false;
		}
	}

	public function contains_AgainstArray($content, $needles_array, $case_sens= true, $position='any'){   
		foreach($needles_array as $needle){
			if ($this->contains($content, $needle, $case_sens, $position) ){
				return true;
			}
		}
		return false;
	}

	// https://stackoverflow.com/a/860509/2377343
	public static function startsWith($haystack, $needle, $case_sens=true) {
		return $needle === "" || 
		( 
			( $case_sens && strpos($haystack, $needle, 0) === 0 )
				||
    		( !$case_sens && stripos($haystack, $needle, 0) === 0 )
		)
		; 
	}
	public static function startsWith_AgainstArray($haystack, $needles_array, $case_sens=true) { 
		foreach($needles_array as $needle){
			if (self::startsWith($haystack, $needle, $case_sens))
				return true;
		}
		return false;
	}
	public static function endsWith($haystack, $needle, $case_sens=true) { 
		$expectedPosition = strlen($haystack) - strlen($needle);
		if ($case_sens)
			return strrpos($haystack, $needle, 0) === $expectedPosition;
		return strripos($haystack, $needle, 0) === $expectedPosition;
	}
	public static function endsWith_AgainstArray($haystack, $needles_array, $case_sens=true) { 
		foreach($needles_array as $needle){
			if (self::endsWith($haystack, $needle,$case_sens))
				return true;
		}
		return false;
	}


	public function startsWithRemove($haystack, $needle) { return (!$this->startsWith($haystack, $needle) ? $haystack : substr($haystack, strlen($needle) ) ); }
	public function endsWithRemove($haystack, $needle) { return (!$this->endsWith($haystack, $needle) ? $haystack : substr($haystack, 0, -1 * strlen($needle) ) ); }
	public function startsWithReplace($haystack, $needle, $replace) { return (!$this->startsWith($haystack, $needle) ? $haystack : $replace.substr($haystack, strlen($needle) ) ); }

	public function die_if_not_this_site_visitor(){ //if half day passed
		if (empty($_COOKIE['ytdow___']) || $_COOKIE['ytdow___'] > time()*3 + 43200 ) {die('incorrect_download_<b>123</b>.<script type="text/javascript">top.window.location = "http://'.$_SERVER['HTTP_HOST'].'";</script>');}
	}

	public function js_redirect($url=false, $echo=true){
		$str = '<script>window.location = "'. ( $url ?: $_SERVER['REQUEST_URI'] ) .'"; document.body.style.opacity=0; </script>';
		if($echo) { exit($str); }  else { return $str; }
	}

	public function php_redirect($url=false, $code=302){
		//avoid redirection from customizer: if (!empty($_COOKIE['MLSS_cstRedirect']) || defined('MLSS_cstRedirect')) {return;}
		header("Cache-Control: no-store, no-cache, must-revalidate"); header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");   
		header("location: ". ( $url ?: $_SERVER['REQUEST_URI'] ), true, $code); exit;
	}
	public function redirect($url=false, $code=302){
		return $this->php_redirect($url,$code);
	}
	public function js_redirect_message($message,$url=false){
		echo '<script>alert(\''.$message.'\');</script>';
		$this->js_redirect($url);
	}
				
	public function get_output(callable $funct, $clear=true){  
		ob_start();
		$res = call_user_func($funct);
		if ($clear)
		{
			$cont= ob_get_clean(); 
			ob_flush(); 
		}
		else{
			$cont= ob_get_contents(); 
		}
		// $cont= ob_get_contents();
		//ob_get_clean();
		return $cont; 
	}	
	//output js header 
	public function get_js_header_output(){  

        header("Pragma: public");
        header("Cache-Control: public, maxage=".$expires);
		header("Content-type: application/javascript;  charset=utf-8");
        header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
	}
	public static function swap_pair($pairname, $divisor='/'){
		$arr= explode($divisor, $pairname);
		return (count($arr)<=0 ? $pairname : $arr[1].$divisor.$arr[0]);
	}
	
	



	#region ### TELEGRAM FUNCTIONS ###
	// public function telegram($text) { return $helpers->telegram_message( ['chat_id'=>'-1001234567890', 'text'=>$text, 'parse_mode'=>'html', 'disable_web_page_preview'=>true ],   $bot_key ); }          | resp: pastebin_com/u0J1Cph3
	public function telegram_message($array, $botid, $repeated_call=false){
		$array['disable_web_page_preview'] = array_key_exists('disable_web_page_preview', $array) ?  $array['disable_web_page_preview'] : true;
		$array['text'] = $this->br2nl($array['text']);
		$array['text'] = strip_tags($array['text'],'<b><strong><i><em><u><ins><s><strike><del><a><code><pre>'); // allowed: https://core.telegram.org/bots/api#html-style
		$array['text'] = substr($array['text'],0,4093); //max telegram message length 4096
		$res = $this->get_remote_data_array( ['url'=>'https://api.telegram.org/bot'.$botid.'/sendMessage', 'post'=> $array], true );  //'sendMessage?'.http_build_query($array, '');
		if (!$res->error){
			$answer = $res->response;
			$response = json_decode($answer);
			// if it was successfull
			if ($response->ok)
			{
				return $response;
			}
			//i.e. {"ok":false,"error_code":400,"description":"Bad Request: can't parse entities: Unsupported start tag \"br/\" at byte offset 43"}
			else{
				// for some reason, if still unsupported format submitted, resubmit the plain format
				$txt = "Bad Request: can't parse entities";
				if( stripos($response->description, $txt) !==false ){
					$array['text'] = "[SecondSend] \r\n". strip_tags($array['text']) ;
					if ( ! $repeated_call ){
						return $this->telegram_message($array, $botid, true);
					}
				}
				return $response;
			} 
		}	
		else{
			return (object)['ok'=> false, 'description'=> $res ];
		}
	}

	public $telegram_interval_ms = 40; //~30 times per second, so we'd better 40ms
	private $telegram_last_time=0;

	public function telegram_message_cached($array, $botid){
		$curMS  = $this->timeMS();
		$goneMS = $curMS - $this->telegram_last_time;
		if ( $goneMS < $this->telegram_interval_ms ){
			$this->usleep( ($this->telegram_interval_ms-$goneMS) *1000 );
		}
		$this->telegram_last_time = $curMS;

		$key = $this->cache_key_create(array_merge($array, [$botid]));
		if ( ! $this->is_cached_id('function__telegram_message_cached', $key) ){
			$res= $this->telegram_message($array, $botid);
			$ok='true';
		}
		else {
			$res= (object)( ["ok"=>true, "success"=>false, 'reason'=>"$key was cached", 'content'=> json_encode($array) ] );
			$ok='false';
		}
		//if(is_callable([$this,'notifications_db_entry'])) 
			$this->notifications_db_entry($key, $array['chat_id'], $this->stringify($res), time(), $ok );
		return $res;
	}

	public function telegram_message_cached_with_channel($array, $botid){
		$answer = $this->telegram_message_cached ( $array, $botid );
		$this->telegram_channel_name_save($answer);
		return $answer;
	}

	public function telegram_channel_name_save($response){
		$existing = $this->telegram_channel_name_get();
        $res = $response; //already decoded
		if ( $this->array_value($res,'ok') )
		{
			// check to ensure (because cached ids dont have result)
			if ( $this->array_value($res,'result') )
			{
				$id    = $res->result->chat->id;
				$title = $res->result->chat->title;
				$type  = $res->result->chat->type;  //group or channel
				$existing[$id] = (object)['title'=>$title, 'type'=>$type];
				update_option('telegram_channel_names_temp', $existing);
			}
		}
	} 
	public function telegram_channel_name_get($id=''){
		$channelsArray = get_option('telegram_channel_names_temp',[]);
		return !empty($id) ? $this->array_value($channelsArray, $id) : $channelsArray;
	}
	#endregion


	// ################
	// https://github.com/ttodua/useful-php-scripts/blob/master/get-remote-url-content-data.php 
	public static function get_remote_data($url, $post_params=null, $request_options=null)	
	{
		$func = $post_params ? "wp_remote_post" : "wp_remote_get";
		$is_wp = (function_exists($func));
		$request_options = !empty($request_options)? $request_options : [];

		//if (!$is_wp || ($force_static && is_callable('parent::get_remote_data'))  )
		//{
		//	return parent::get_remote_data($url, $post_params, $request_options);
		//}
		//else
		{ 
			if($func=="wp_remote_get") {
				$out= wp_remote_get($url, $request_options );
			}
			if($func=="wp_remote_post") {
				$post_array = (is_array($post_params)) ? $post_params : (parse_str($post_params , $new) ? $new : $new );		
				$args['body']=$post_array;
				$args= array_merge($args, $request_options);
				$out= wp_remote_post($url, $args );
				//$out= call_user_func($func, $url, $args );
			}
			return wp_remote_retrieve_body($out); //same as $out['body'] 
		}
		return "empty_data. Create your own remote function";
	}

	public function get_remote_data_array($arr, $force_curl=false, $repeated_call=false)
	{
		if (is_array($arr))
		{
			$url 			= $arr['url'];
			$post_params	= $this->array_value($arr,'post',    null);
			$request_options= $this->array_value($arr,'options', []);	if ($request_options==null) $request_options=[];
			$should_be_json = $this->array_value($arr,'json',    false);
			$retry			= $this->array_value($arr,'retry',   true);
		}
		else{
			$url 			= $arr;
			$post_params	= null;
			$request_options=[];
			$should_be_json = true;
			$retry			= true;
		}

        $request_options = array_merge_recursive($request_options, ['headers'=>['Cache-Control'=>'no-cache']] );

		$data = $this->get_remote_data($url, $post_params, $request_options, $force_curl) ;
		if (empty($data))
		{
			$res= (object)['error'=>'empty data', 'response'=>''];
		}
		else{
			if ($should_be_json)
			{
				$dataTemp = $this->JsonData($data);
				if (is_null($dataTemp))
					$res= (object)['error'=>'not json', 'response'=>($data) ];
				else
					$res= (object)['error'=>false, 'response'=>json_decode($data) ];
				/*
					try { 
						$res= (object)['error'=>false, 'response'=>json_decode($data) ];
					}
					catch(\Exception $ex){
						$res= (object)['error'=>'not json', 'response'=>($data) ];
					}
				*/
			}
			else{
				$res= (object)['error'=>false, 'response'=>$data ];
			}
		}
		// if still error, and retry allowed
		if ($res->error && $retry && !$repeated_call)
		{
			$this->usleep(100000);
			$res = $this->get_remote_data_array($arr, $force_curl, $repeated_call=true);
		}
		return $res;
	}

	 


    //i.e. set_cookies_from_url("http://example.com/?username=user&auth=key');
    public function set_cookies_from_url($url)
    {
        $d=$this->get_remote_data($url, false, ["curl_opts"=>["CURLOPT_HEADERFUNCTION"=>
            ( function ($ch, $headerLine) {
                if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookieArr) == 1)
                {
                    $cookie = $cookieArr[1];
                    $cookie_vars = explode('=', $cookie, 2);
                    $this->example_cookies[$cookie_vars[0]] = $cookie_vars[1];
                }
                return strlen($headerLine); // Needed by curl
                }
            )
            ]]
        );
        foreach($this->example_cookies as $key=>$name)
        {
            $this->set_cookie($key,$name, 86000, '/target_dir/');
        }
        $this->set_cookie("sample_confirm","1");
    }
   
	// ----
	public function get_client_ip() {
		$proxy_headers = array("CLIENT_IP", "FORWARDED", "FORWARDED_FOR", "FORWARDED_FOR_IP", "HTTP_CLIENT_IP", "HTTP_FORWARDED", "HTTP_FORWARDED_FOR", "HTTP_FORWARDED_FOR_IP", "HTTP_PC_REMOTE_ADDR", "HTTP_PROXY_CONNECTION", "HTTP_VIA", "HTTP_X_FORWARDED", "HTTP_X_FORWARDED_FOR", "HTTP_X_FORWARDED_FOR_IP", "HTTP_X_IMFORWARDS", "HTTP_XROXY_CONNECTION", "VIA", "X_FORWARDED", "X_FORWARDED_FOR");
		foreach($proxy_headers as $proxy_header) {
			if (isset($_SERVER[$proxy_header])) {
				if(preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $_SERVER[$proxy_header])) {
					return $_SERVER[$proxy_header];
				}
				else if (stristr(",", $_SERVER[$proxy_header]) !== FALSE) {
					$proxy_header_temp = trim(array_shift(explode(",", $_SERVER[$proxy_header])));
					if (($pos_temp = stripos($proxy_header_temp, ":")) !== FALSE) {$proxy_header_temp = substr($proxy_header_temp, 0, $pos_temp); }
					if (preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $proxy_header_temp)) { return $proxy_header_temp; }
				}
			}
		}
		return $_SERVER["REMOTE_ADDR"];
	}

	
	// $ipinfo = json_decode(getIpInfo($_SERVER['REMOTE_ADDR']), true);
	// if($ipinfo['country_name'] != 'Georgia'){
	public function getIpInfo($ip, $type=1, $api=""){
		$info="";
		if($type==1){
			$info = $this->get_remote_data('https://geoip-db.com/json/'.$ip);	
			//"country_code":"GE", "country_name":"Georgia", "city":"null", "postal":null, "latitude":42, "longitude":43.5, "IPv4":"xxx.xxx.xxx.xxx", "state":"null"
		}
		elseif($type==2){
			// PLEASE DONT USE THIS API
			$info_initial = $this->get_remote_data('https://geoipify.whoisxmlapi.com/api/v1?apiKey='.$api.'&ipAddress='.$ip);	
			// {"ip":"xxx.xxx.xxx.xxx","location":{"country":"AU","region":"Victoria","city":"Research","lat":-37.7,"lng":145.1833,"postalCode":"3095","timezone":"Australia\/Melbourne"}}
			$decoded = json_decode($info_initial, true);
			$loc =$decoded['location'] ;
			unset($decoded['location']) ;
			$ipinfo_new = array_merge( $decoded,$loc );
			return  $ipinfo_new;
		}
		return $info;
	}

 
	public function CurrentSiteIs($site){ return $site == $_SERVER['HTTP_HOST']; }

	public function output_js_headers()
	{
		session_cache_limiter('none');
		// https://stackoverflow.com/a/1385982/2377343
											$year=60*60*24*365;//year
		//Caching with "CACHE CONTROL"
			header('Cache-control: max-age='.$year .', public');
		//Caching with "EXPIRES"  (no need of EXPIRES when CACHE-CONTROL enabled)
			//header('Expires: '.gmdate(DATE_RFC1123,time()+$year));
		//To get best cacheability, send Last-Modified header and ...
			header('Last-Modified: '.gmdate(DATE_RFC1123,filemtime(__file__)));  //i.e.  1467220550 [it's 30 june,2016]
		//reply using: status 304 (with empty body) if browser sends If-Modified-Since header.... This is cheating a bit (doesn't verify the date), but remove if you dont want to be cached forever:
			// if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {  header('HTTP/1.1 304 Not Modified');   die();	}
		header("Content-type: application/javascript;  charset=utf-8");
	}

		
	public function input_fields_from_array($value, $keyname='', $replace_spaces=false){	//$keyname= (strpos($keyname,'[') === false) ? '['.$keyname.']' : $keyname;
		echo '<div class="array_fields1"><style>.array_fields1 textarea{max-height:200px!important;  border-radius: 5px; width:100%; color:#53ae14; border: 2px solid black; margin:0 0 0 0px; height:50px; }  .def_textareaa{height:70px;} .high_textarea{height:130px;} .new_block{MARGIN:0 0 0 50px; border:2px solid; border-width:0 0 0 2px;} .txtar{padding:0 0 0 25px;}  .new_block .keyname{color:rgb(248, 48, 83);} </style>';
		$this->input_fields_from_array_RECURSIVE($value, $keyname, $replace_spaces);
		echo '</div>';
	}
	public function input_fields_from_array_RECURSIVE($value, $keyname='', $replace_spaces=false){		
		if (!is_array($value)){
			$height=30; $lines=explode("\r\n",$value); 
				foreach($lines as $eachLINE){
					$height= $height+ceil(mb_strlen($eachLINE)/100) * 30; 
				}
				// replace multiple whitespaces with single
				$value =   !$replace_spaces ? $value : preg_replace('!\s+!', ' ', str_replace("\t",' ', $value));
			echo 
			'<div class="each_ln">
				<div class="keyname">'.$keyname.'</div>
				<div class="txtar"><textarea class="" style="height:'. $height.'px;" name="'.$keyname.'">'.$value.'</textarea></div>
			</div>';
		}
		else{
			echo '<div class="new_array_title">'.$keyname.'</div>';
			foreach ($value  as $keyname1=>$value1){
				echo '<div class="new_block">';
				$this->input_fields_from_array_RECURSIVE($value1, $keyname.'['.$keyname1.']',  $replace_spaces);
				echo '</div>';
			}
		}
	}

	public function random_color($alpha='FF') {
		return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT).$alpha;
	}

	public function contentHeight($content, $lineHeight=30){
		$lines=explode("\n", $content); 
		$height = $lineHeight;
		foreach($lines as $eachLINE){
			$height = $height+ceil(mb_strlen($eachLINE)/100) * $lineHeight; 
		} 
		return $height;
	}
	
	
	public function js_autosize_textarea($classname=null)
	{ ?><script>
		function autoSizeTextareas(className)
		{
			let tx = document.querySelectorAll(className);
			for (let i = 0; i < tx.length; i++) {
				tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;overflow-y:hidden;');
				var oninput = function () {
				  this.style.height = 'auto';
				  this.style.height = (this.scrollHeight) + 'px';
				};
				tx[i].addEventListener("input", oninput, false);
			}
		}
		</script> <?php
		if ($classname) { ?><script>document.addEventListener('readystatechange', event => {
			if (event.target.readyState === "interactive") {  
				autoSizeTextareas('<?php echo $classname;?>');
			}
		});</script><?php }
	}


	public function dropdown_from_array($array, $name, $selected){
		$out =
		'<select name="'.$name.'">';
			foreach($array as $each) $out .= 
		'<option value="'.$each.'"'. ( $each==$selected ?' selected ':'') . ">$each</option>";
		$out .= '</select>';
		return '<div>'.$out.'</div>';
	}

	public function dropdown_for_categories($ul___id_class, $ShowPlusMinusDropdown = false){	
		if (!defined('drp_already_out')) {   define('drp_already_out', true);  ?>
	<style>
	.ChildHidden {} 
	.OPCL_containtr{float:right; display: inline-block; text-align:right; height:30px;width:30px; }
	.drop_CLOSE{background:transparent url("<?php echo $this->baseURL.'library/media/other/sign-minus.png';?>") no-repeat scroll 0% 0%; }
	.drop_OPEN{background:transparent url("<?php echo $this->baseURL.'library/media/other/sign-plus.png';?>") no-repeat scroll 0% 0%;}
	.ChildHidden ul.sub-menu{display:none;}	
	.OpenCloseSp {display: inline-block;height: 30px;width: 30px;}
	zzzz body li.ChildHidden > a {display: inline-block;} 
	</style>
	<script type="text/javascript">
	public function make_element_children_dropdowned(element, ShowPlusMinusSign){
		if (element) {
			element.each(function( index,key ) { 
			  if (key.className.indexOf("menu-item-has-children") >= 0) {
				$( this ).addClass("ChildHidden");
				if (ShowPlusMinusSign) { $(this).children('a').append('<span class="OPCL_containtr"> <span class="OpenCloseSp drop_OPEN"> </span> </span>'); }
				
				$( this ).children('a').click(function() {
					if (ShowPlusMinusSign) { $(this).children('.OPCL_containtr').find('span.OpenCloseSp').toggleClass('drop_OPEN drop_CLOSE'); }
					$(this).siblings('ul.sub-menu').toggle();
					return false;
				});
			  }
			});
		}
	}
	</script>	
	<?php 
		} ?>
		<script type="text/javascript">
		var Containr = $("<?php echo $ul___id_class;?>");
		var ShowPlusMinusSign = false;  <?php if ($ShowPlusMinusDropdown) { ?> ShowPlusMinusSign = true;  <?php } ?>
		make_element_children_dropdowned(Containr,ShowPlusMinusSign);
		</script>
		<?php	
	}

	 
	public function expand_CHILD_menu_by_a_name($ul___a_class, $A_href_NAMEs=array() ){	?>
		<script type="text/javascript">
		var A_names = [<?php foreach ($A_href_NAMEs as $key=>$each) {echo '"'.$each.'"'; if($key != count($A_href_NAMEs)-1) echo ',';  } ?>];
		var Containr2 = $("<?php echo $ul___a_class;?>");
		if (Containr2) { 
			Containr2.each(function( index,key ) { 
			  if (A_names.indexOf(key.innerHTML) > -1) {
				var ff= $(this).siblings('ul.sub-menu').addClass("displayblock");
			  }
			});
		}
		</script>	
		<?php	
	}
	
	
	
	
	
	public function loader($type="")
	{
		$circlecolor="#ffffff"; 
		$head = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(241, 242, 243); display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">'; //< ?xml version="1.0" encoding="utf-8"? >
		
		if ($type=='infinity')
			$out = $head.'<circle cx="50" cy="50" fill="none" stroke="#292664" stroke-width="15" r="36" stroke-dasharray="169.64600329384882 58.548667764616276" transform="rotate(338.174 50 50)"> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="0.8s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform></circle>';
		elseif ($type=='eclipse')
			$out = $head.'<path d="M10 50A40 40 0 0 0 90 50A40 49.2 0 0 1 10 50" fill="#1d3f72" stroke="none" transform="rotate(235.214 50 54.6)"><animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 54.6;360 50 54.6"></animateTransform></path>';
		elseif ($type=='normal')
			$out = $head.'<path fill="none" stroke="#1d3f72" stroke-width="8" stroke-dasharray="42.76482137044271 42.76482137044271" d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z" stroke-linecap="round" style="transform:scale(0.8);transform-origin:50px 50px"><animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0;256.58892822265625"></animate></path>';
		else //dots
			$out = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 130 130" preserveAspectRatio="xMidYMid">            <g><circle cx="16" cy="64" r="16" fill="'.$circlecolor.'" fill-opacity="1"/><circle cx="16" cy="64" r="16" fill="'.$circlecolor.'" fill-opacity="0.67" transform="rotate(45,64,64)"/><circle cx="16" cy="64" r="16" fill="#ffffff" fill-opacity="0.42" transform="rotate(90,64,64)"/><circle cx="16" cy="64" r="16" fill="'.$circlecolor.'" fill-opacity="0.2" transform="rotate(135,64,64)"/><circle cx="16" cy="64" r="16" fill="'.$circlecolor.'" fill-opacity="0.12" transform="rotate(180,64,64)"/><circle cx="16" cy="64" r="16" fill="'.$circlecolor.'" fill-opacity="0.12" transform="rotate(225,64,64)"/><circle cx="16" cy="64" r="16" fill="'.$circlecolor.'" fill-opacity="0.12" transform="rotate(270,64,64)"/><circle cx="16" cy="64" r="16" fill="'.$circlecolor.'" fill-opacity="0.12" transform="rotate(315,64,64)"/><animateTransform attributeName="transform" type="rotate" values="0 64 64;315 64 64;270 64 64;225 64 64;180 64 64;135 64 64;90 64 64;45 64 64" calcMode="discrete" dur="720ms" repeatCount="indefinite"></animateTransform></g></svg>';
		$out = $out.'<!-- generated by https://loading.io/ --></svg>'; 
		return $out;
	}


	public function get_user_browser(){ 
		if (empty($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT']="unknown";
		$b = $_SERVER['HTTP_USER_AGENT']; $final =array();

		//(START FROM MOBILE check!!!!)
		if(
			preg_match('/android.+mobile|Windows Mobile|Nokia|avantgo|Mozilla(.*?)(Android|Mobile|Blackberry|Symbian)|OperaMini|Opera Mini|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|ap|od)|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$b)
			||
			preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($b,0,4))
			)									{	$final['brwsr'] = "Mobilee";	}
		//if typical browsers
		elseif(preg_match('/Firefox/i',$b))		{	$final['brwsr'] = "Firefox";	}
		elseif(preg_match('/Safari/i',$b))		{	$final['brwsr'] = "Safari";	}
		elseif(preg_match('/Chrome/i',$b))		{	$final['brwsr'] = "Chrome";	}
		elseif(preg_match('/Flock/i',$b))		{	$final['brwsr'] = "Flock";		}
		elseif(preg_match('/Opera/i',$b))		{	$final['brwsr'] = "Opera";		}
				elseif(preg_match('/MSIE 6/i',$b))				{$final['brwsr'] = "MSIE 6";	}
				elseif(preg_match('/MSIE 7/i',$b))				{$final['brwsr'] = "MSIE 7";	}
				elseif(preg_match('/MSIE 8/i',$b))				{$final['brwsr'] = "MSIE 8";	}
				elseif(preg_match('/MSIE 9/i',$b))				{$final['brwsr'] = "MSIE 9";	}
				elseif(preg_match('/MSIE 10/i',$b))				{$final['brwsr'] = "MSIE 10";	}
				elseif(preg_match('/Trident\/7.0; rv:11.0/',$b)){$final['brwsr'] = "MSIE 11";	}
				else											{$final['brwsr'] = "UNKNOWNNN";	}
		//===========================================================================================================
		$final['full_brwsr_namee']	 = $b;
		//other parameters
		return $final;
	}


	public function get_user_OperatingSystem() { 
		if (empty($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT']="unknown";
		$user_agent=$_SERVER['HTTP_USER_AGENT']; $final =array(); $final['os_namee']="_Unknown_OS_";  $final['os_typee']="_Unknown_OS_";
		$os_array=array(
			'MOUSED'	=> array(
				'/windows nt 10.0/i'=>'Windows 10', '/windows nt 6.3/i'=>'Windows 8.1', '/windows nt 6.2/i'=>'Windows 8', '/windows nt 6.1/i'=>'Windows 7',	'/windows nt 6.0/i'=>'Windows Vista','/windows nt 5.2/i'=>'Windows Server 2003/XP x64', '/windows nt 5.1/i'=>'Windows XP', '/windows xp/i'=>'Windows XP','/windows nt 5.0/i'=>'Windows 2000','/windows me/i'=>'Windows ME','/win98/i'=>'Windows 98','/win95/i'=>'Windows 95','/win16/i'=>'Windows 3.11',
				'/macintosh|mac os x/i' =>'Mac OS X','/mac_powerpc/i'=>'Mac OS 9', '/linux/i'=>'Linux','/ubuntu/i'=>'Ubuntu',
								),
			'NOMOUSED'	=> array(
				'/iphone/i'=>'iPhone','/ipod/i'=>'iPod','/ipad/i'=>'iPad','/android/i'=>'Android','/blackberry/i'=>'BlackBerry', '/webos/i'=>'Mobile'
								)
		); 
		foreach($os_array as $namee=>$valuee) { foreach ($valuee as $regex => $value1) {	if(preg_match($regex, $user_agent)){$final['os_namee']=$value1;  $final['os_typee'] = $namee;}		} }
		return $final;
	}


	public function OS_platforms()
	{
		if (property_exists($this, 'platforms_cached')) return $this->platforms_cached;
		$this->platforms_cached = array_merge( $this->get_user_browser(), $this->mobile_detect(), $this->get_user_OperatingSystem() );
		return $this->platforms_cached;
	}
	
	// https://stackoverflow.com/a/31476046/2377343 
	public function get_url_parts($url,$part){	 $x='';
		$pURL = parse_url($url);	$pthURL = pathinfo($url);		
		//for example: https://example.com/myfolder/sympony.mp3?aa=1&bb=2?cc=#gggg
		if		($part=='scheme'){ 	$x = !empty($pURL['scheme'])	?	$pURL['scheme']				:'';}	//  http
		elseif	($part=='hostname'){ 	$x = !empty($pURL['host'])		?	$pURL['host']				:'';}   //  example.com
		elseif	($part=='query'){ 		$x = !empty($pURL['query'])		?	$pURL['query']				:'';}   //  aa=1&bb=2?cc=
		elseif	($part=='hash'){ 		$x = !empty($pURL['fragment'])	?	$pURL['fragment']			:'';}   //  gggg
		elseif	($part=='file'){ 		$x = !empty($pURL['path'])		?	$pURL['path']				:'';}   //  /myfolder/sympony.mp3
		elseif	($part=='filename'){ 	$x = !empty($pURL['path'])		?	basename($pURL['path'])		:'';}   //  sympony.mp3
		elseif	($part=='extension'){	$x = !empty($pURL['path'])		?	pathinfo($pURL['path'], PATHINFO_EXTENSION) :'';}   //  mp3
		elseif	($part=='folder'){ 		$x = !empty($pURL['path'])		?	dirname($pURL['path'])		:'';}   //  /myfolder
		elseif	($part=='dirname'){ 	$x = !empty($pthURL['dirname'])	?	$pthURL['dirname']			:'';}   //  https://example.com/myfolder
		elseif	($part=='afterfolder'){	$x = !empty($pthURL['basename'])?	$pthURL['basename']			:'';}   //  sympony.mp3?aa=1&bb=2?cc=#ggg
		
		return $x;
	}

	public function urlencodeall($x) {
		$out = '';
		for ($i = 0; isset($x[$i]); $i++) {
			$c = $x[$i];
			if (!ctype_alnum($c)) $c = '%' . sprintf('%02X', ord($c));
			$out .= $c;
		}
		return $out;
	}

	public function json_encode_unicode($data){ return json_encode($data, JSON_UNESCAPED_UNICODE); }
	
	public function FilterUrlFromLang($url){	return preg_replace('/(\&|\?)lg\=((.*?)&|(.*))/si','',$url); }
	
	public function utf8_declarationn() { return '<meta http-equiv="content-type" content="text/html; charset=UTF-8">'; }
	public function utf8_declarationn_auto() { return '<meta http-equiv="content-type" content="'.get_bloginfo('html_type').'; charset='.get_bloginfo('charset').'">'; }


	public function HTML_DOCTYPE_DECLARATIONsss(){  $lng = (defined('LNG') ? LNG : '') ;
		return 
	'<!DOCTYPE html>
	<html id="pagehtml" class="LN_'.$lng.'" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:og="https://opengraphprotocol.org/schema/" xmlns="https://www.w3.org/1999/xhtml" lang="'.$lng.'" xml:lang="'.$lng.'" >';
	}


	public function default_rss_head_tags(){ 
	?> 	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
		<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /> <?php	
	}

	//add_actionX('wp_head','check_if_js_cookies_enabled');
	public function check_if_JS_enabled(){	$out = 
		'<noscript>
			<div style="text-align:center; position:absolute;background-color:red;">Enable Javascript in your Browser to avoid BROWSER problems!</div>
		</noscript>';
		return	$out;
	}				
	public function check_if_COOKIES_enabled(){ $out1 = 
			'<script>
			function check_if_cookies_are_enabled(){ 
				var temp_cooK_name="__verify=1"; var dattee = new Date();dattee.setTime(dattee.getTime()+(30*1000));
				document.cookie = temp_cooK_name + ";expires=" + dattee.toUTCString();
				var supportsCOOCKIES = document.cookie.length >= 1 && document.cookie.indexOf(temp_cooK_name) > -1;
				if (supportsCOOCKIES) {document.write(\'<div style="text-align:center; position:absolute;background-color:red;">Enable cookies in your<br/> browser to avoid <br/>browser problems!</div>\');}
			}
			check_if_cookies_are_enabled();
			</script>';
		return $out1;
	}			
		
	
	public function old_browser_message($first=null, $incompatible_browsers=array('MSIE') ){
		global $odd;
		if (in_array($this->platforms()['brwsr'], $incompatible_browsers) ) { echo '<div style="padding:20px;text-align:center;position:fixed; top:0px;left:0px; z-idnex:99; background:red;color:black; ">Your have an INCOMPATIBLE BROWSER! Please, use any modern browser (<b><a href="https://www.firefox.com">Firefox</a>, <a href="https://www.opera.com">Opera</a>, <a href="https://www.apple.com/safari/‎">Safari</a> , <a href="https://www.chrome.com">Chrome</a></b>..) to view site normally. </div>'; }
	}	


	public function facebook_rescarpe_url($url){  $x= $this->get_remote_data('https://graph.facebook.com/','id='.urlencode($url).'&scrape=true'); }




	// ==================== text to image==============
	// # Usage #
	//TextToImage_my( 
	//	$text='Helloooo World!' , 
	//	$separate_line_after_chars=40,   $font='./Arial%20Unicode.ttf',    $size=24,   $rotate=0,   $padding=0,   $transparent=true,  $color=['r'=>0,'g'=>0,'b'=>0],   $bg_color=['r'=>255,'h'=>255,'b'=>255] 
	//);
	
	public function TextToImage($text, $separate_line_after_chars=40,  $font='./Arial%20Unicode.ttf', 
		$size=24,$rotate=0,$padding=2,$transparent=true, $color=array('r'=>0,'g'=>0,'b'=>0), $bg_color=array('r'=>255,'g'=>255,'b'=>255) ){
		$amount_of_lines= ceil(strlen($text)/$separate_line_after_chars)+substr_count($text, '\n')+1;
		$all_lines=explode("\n", $text);  $amount_of_lines = count($all_lines);    $text_final='';
		foreach($all_lines as $key=>$value){ 
			while( mb_strlen($value,'utf-8')>$separate_line_after_chars){	
				$text_final .= mb_substr($value, 0, $separate_line_after_chars, 'utf-8')."\n";
				$value = mb_substr($value, $separate_line_after_chars, null, 'utf-8');
			}  
			$text_final .= mb_substr($value, 0, $separate_line_after_chars, 'utf-8') . ( $amount_of_lines-1 == $key ? "" : "\n");
		}

		Header("Content-type: image/png");
		$width=$height=$offset_x=$offset_y = 0;
		// you can use: if (!file_exists($font))  filecreat('https://github.com/edx/edx-certificates/raw/master/template_data/fonts/Arial%20Unicode.ttf', $font);
														// get the font height.
														$bounds = ImageTTFBBox($size, $rotate, $font, "W");
														if ($rotate < 0)		{$font_height = abs($bounds[7]-$bounds[1]);	} 
														elseif ($rotate > 0)	{$font_height = abs($bounds[1]-$bounds[7]);	} 
														else { $font_height = abs($bounds[7]-$bounds[1]);}
				
		// determine bounding box.
		$bounds = ImageTTFBBox($size, $rotate, $font, $text_final);
		if ($rotate < 0){		$width = abs($bounds[4]-$bounds[0]);					$height = abs($bounds[3]-$bounds[7]);
								$offset_y = $font_height;								$offset_x = 0;
		} 
		elseif ($rotate > 0) {	$width = abs($bounds[2]-$bounds[6]);					$height = abs($bounds[1]-$bounds[5]);
								$offset_y = abs($bounds[7]-$bounds[5])+$font_height;	$offset_x = abs($bounds[0]-$bounds[6]);
		} 
		else{					$width = abs($bounds[4]-$bounds[6]);					$height = abs($bounds[7]-$bounds[1]);
								$offset_y = $font_height;								$offset_x = 0;
		}
		$height = $height +  $font_height*($amount_of_lines+1);
		$image = imagecreate($width+($padding*2)+1,$height+($padding*2)+1);
		
		$background = ImageColorAllocate($image, $bg_color['r'], $bg_color['g'], $bg_color['b']);
		$foreground = ImageColorAllocate($image, $color['r'], $color['g'], $color['b']);

		if ($transparent) ImageColorTransparent($image, $background);
		ImageInterlace($image, true);
	  // render the image
		ImageTTFText($image, $size, $rotate, $offset_x+$padding, $offset_y+$padding, $foreground, $font, $text_final);
		imagealphablending($image, true);
		imagesavealpha($image, true);
	  // output PNG object.
		imagePNG($image);
	}
	
	public function textToImage2($your_text="heloooo", $width=250, $height=80)
	{
		$IMG = imagecreate( $width, $height );
		$background = imagecolorallocate($IMG, 0,0,255);
		$text_color = imagecolorallocate($IMG, 255,255,0); 
		$line_color = imagecolorallocate($IMG, 128,255,0);
		imagestring( $IMG, 10, 1, 25, $your_text,  $text_color );
		imagesetthickness ( $IMG, 5 );
		//imageline( $IMG, 30, 45, 165, 45, $line_color );
		header( "Content-type: image/png" );
		imagepng($IMG);
		imagecolordeallocate($IMG, $line_color );
		imagecolordeallocate($IMG, $text_color );
		imagecolordeallocate($IMG, $background );
		imagedestroy($IMG); 
		exit;   
	}
	// https://mekshq.com/how-to-convert-hexadecimal-color-code-to-rgb-or-rgba-using-php/
	public function hex2rgba($color, $opacity = false) {
		$default = 'rgb(0,0,0)';
		//Return default if no color provided
		if(empty($color))
			  return $default; 
		//Sanitize $color if "#" is provided 
		if ($color[0] == '#' ) {
			$color = substr( $color, 1 );
		}
		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
				return $default;
		}
		//Convert hexadec to rgb
		$rgb =  array_map('hexdec', $hex);
		//Check if opacity is set(rgba or rgb)
		if($opacity){
			if(abs($opacity) > 1)
				throw new \Exception("Opacity cant be more than 1");
			$opacity = self::number_format((float)$opacity, 2); 
			$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
		} else {
			$output = 'rgb('.implode(",",$rgb).')';
		} 
		return $output;
	}

	// https://stackoverflow.com/a/5925612/2377343  |  https://stackoverflow.com/questions/15852122/
	public function hex_color($name){
		$arr=['aliceblue'=>'F0F8FF', 'antiquewhite'=>'FAEBD7', 'aqua'=>'00FFFF', 'aquamarine'=>'7FFFD4', 'azure'=>'F0FFFF', 'beige'=>'F5F5DC', 'bisque'=>'FFE4C4', 'black'=>'000000', 'blanchedalmond '=>'FFEBCD', 'blue'=>'0000FF', 'blueviolet'=>'8A2BE2', 'brown'=>'A52A2A', 'burlywood'=>'DEB887', 'cadetblue'=>'5F9EA0', 'chartreuse'=>'7FFF00', 'chocolate'=>'D2691E', 'coral'=>'FF7F50', 'cornflowerblue'=>'6495ED', 'cornsilk'=>'FFF8DC', 'crimson'=>'DC143C', 'cyan'=>'00FFFF', 'darkblue'=>'00008B', 'darkcyan'=>'008B8B', 'darkgoldenrod'=>'B8860B', 'darkgray'=>'A9A9A9', 'darkgreen'=>'006400', 'darkgrey'=>'A9A9A9', 'darkkhaki'=>'BDB76B', 'darkmagenta'=>'8B008B', 'darkolivegreen'=>'556B2F', 'darkorange'=>'FF8C00', 'darkorchid'=>'9932CC', 'darkred'=>'8B0000', 'darksalmon'=>'E9967A', 'darkseagreen'=>'8FBC8F', 'darkslateblue'=>'483D8B', 'darkslategray'=>'2F4F4F', 'darkslategrey'=>'2F4F4F', 'darkturquoise'=>'00CED1', 'darkviolet'=>'9400D3', 'deeppink'=>'FF1493', 'deepskyblue'=>'00BFFF', 'dimgray'=>'696969', 'dimgrey'=>'696969', 'dodgerblue'=>'1E90FF', 'firebrick'=>'B22222', 'floralwhite'=>'FFFAF0', 'forestgreen'=>'228B22', 'fuchsia'=>'FF00FF', 'gainsboro'=>'DCDCDC', 'ghostwhite'=>'F8F8FF', 'gold'=>'FFD700', 'goldenrod'=>'DAA520', 'gray'=>'808080', 'green'=>'008000', 'greenyellow'=>'ADFF2F', 'grey'=>'808080', 'honeydew'=>'F0FFF0', 'hotpink'=>'FF69B4', 'indianred'=>'CD5C5C', 'indigo'=>'4B0082', 'ivory'=>'FFFFF0', 'khaki'=>'F0E68C', 'lavender'=>'E6E6FA', 'lavenderblush'=>'FFF0F5', 'lawngreen'=>'7CFC00', 'lemonchiffon'=>'FFFACD', 'lightblue'=>'ADD8E6', 'lightcoral'=>'F08080', 'lightcyan'=>'E0FFFF', 'lightgoldenrodyellow'=>'FAFAD2', 'lightgray'=>'D3D3D3', 'lightgreen'=>'90EE90', 'lightgrey'=>'D3D3D3', 'lightpink'=>'FFB6C1', 'lightsalmon'=>'FFA07A', 'lightseagreen'=>'20B2AA', 'lightskyblue'=>'87CEFA', 'lightslategray'=>'778899', 'lightslategrey'=>'778899', 'lightsteelblue'=>'B0C4DE', 'lightyellow'=>'FFFFE0', 'lime'=>'00FF00', 'limegreen'=>'32CD32', 'linen'=>'FAF0E6', 'magenta'=>'FF00FF', 'maroon'=>'800000', 'mediumaquamarine'=>'66CDAA', 'mediumblue'=>'0000CD', 'mediumorchid'=>'BA55D3', 'mediumpurple'=>'9370D0', 'mediumseagreen'=>'3CB371', 'mediumslateblue'=>'7B68EE', 'mediumspringgreen'=>'00FA9A', 'mediumturquoise'=>'48D1CC', 'mediumvioletred'=>'C71585', 'midnightblue'=>'191970', 'mintcream'=>'F5FFFA', 'mistyrose'=>'FFE4E1', 'moccasin'=>'FFE4B5', 'navajowhite'=>'FFDEAD', 'navy'=>'000080', 'oldlace'=>'FDF5E6', 'olive'=>'808000', 'olivedrab'=>'6B8E23', 'orange'=>'FFA500', 'orangered'=>'FF4500', 'orchid'=>'DA70D6', 'palegoldenrod'=>'EEE8AA', 'palegreen'=>'98FB98', 'paleturquoise'=>'AFEEEE', 'palevioletred'=>'DB7093', 'papayawhip'=>'FFEFD5', 'peachpuff'=>'FFDAB9', 'peru'=>'CD853F', 'pink'=>'FFC0CB', 'plum'=>'DDA0DD', 'powderblue'=>'B0E0E6', 'purple'=>'800080', 'red'=>'FF0000', 'rosybrown'=>'BC8F8F', 'royalblue'=>'4169E1', 'saddlebrown'=>'8B4513', 'salmon'=>'FA8072', 'sandybrown'=>'F4A460', 'seagreen'=>'2E8B57', 'seashell'=>'FFF5EE', 'sienna'=>'A0522D', 'silver'=>'C0C0C0', 'skyblue'=>'87CEEB', 'slateblue'=>'6A5ACD', 'slategray'=>'708090', 'slategrey'=>'708090', 'snow'=>'FFFAFA', 'springgreen'=>'00FF7F', 'steelblue'=>'4682B4', 'tan'=>'D2B48C', 'teal'=>'008080', 'thistle'=>'D8BFD8', 'tomato'=>'FF6347', 'turquoise'=>'40E0D0', 'violet'=>'EE82EE', 'wheat'=>'F5DEB3', 'white'=>'FFFFFF', 'whitesmoke'=>'F5F5F5', 'yellow'=>'FFFF00', 'yellowgreen'=>'9ACD32'];
		return '#'.trim($this->array_value($arr, $name, 'FFFFFFFF'));
	}
	
	//======helper function==========
	//if(!function_exists('mb_substr_replace')){
	  function mb_substr_replace($string, $replacement, $start, $length = null, $encoding = "UTF-8") {
		if (extension_loaded('mbstring') === true){
			$string_length = (is_null($encoding) === true) ? mb_strlen($string) : mb_strlen($string, $encoding);
			if ($start < 0) { $start = max(0, $string_length + $start); }
			else if ($start > $string_length) {$start = $string_length; }
			if ($length < 0){ $length = max(0, $string_length - $start + $length);  }
			else if ((is_null($length) === true) || ($length > $string_length)) { $length = $string_length; }
			if (($start + $length) > $string_length){$length = $string_length - $start;} 
			if (is_null($encoding) === true) {  return mb_substr($string, 0, $start) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length); }
			return mb_substr($string, 0, $start, $encoding) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length, $encoding);
		}
		return (is_null($length) === true) ? substr_replace($string, $replacement, $start) : substr_replace($string, $replacement, $start, $length);
	  }
	//}
	//if(!function_exists('mb_str_word_count')){
		function mb_str_word_count($string, $format = 0, $charlist = '[]') {
			$string=trim($string);
			if(empty($string)){$words = array();}    else {$words = preg_split('~[^\p{L}\p{N}\']+~u',$string);}
			switch ($format) {   case 0: return count($words); break;       case 1:      case 2: return $words; break;          default: return $words; break;    }
		}
	//}

	
	public function  header_mail($from=false, $host= false){ 
		$from = $from ? $from : "contact"; 
		$host = $host ? $host : $_SERVER['HTTP_HOST'];//$_SERVER['SERVER_ADDR']; 
		return array('From: '.$from.'@'.$host . "\r\n" .  'Reply-To: '.$from.'@'.$host . "\r\n" .  'X-Mailer: PHP/' . phpversion());
	}


	public function value_or_input_field($namee){
		if (!empty($GLOBALS['editing_inputs'])){
			
		}
		else{
			
		}
	}
	
	public function ksort_recursive(&$array) {
	   foreach ($array as &$value) {
		  if (is_array($value)) $this->ksort_recursive($value);
	   }
	   return ksort($array);
	}













	

	public function die_if_array_key($array, $key){ if (array_key_exists($key, $array)) exit($array[$key]); }

	public function chars_array_($alhpanumeric=true){  return  ( $alhpanumeric ?
			array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z')
			:
			array('!','$','+','<','[',']','%',',','.','=','&','-','<','>','|', '"', '\'', '\\', '~','(','/',')','!',' ',"\r","\n", '*', '{','}','?','`','@',':',';','^')
		);
	}


	public function preg_quote_fast($text){
		$specs =array('/', '.','\\','+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','-');
		$new_array_for_strtr = array();
		foreach($specs as $each){
			$new_array_for_strtr[$each] = '\\'.$each;
		}
		$text = strtr( $text, $new_array_for_strtr);
		return $text;
	}

	public function Convert_Empty_to_Zero ($var){ if (empty($var)) return 0; else return $var; }


	public function checkboxes($checkbox_name,$current_value, $unchecked_value,$checked_value){
		$out = '<input type="hidden" name="'.$checkbox_name.'" value="'.$unchecked_value.'" /><input class="chbkx" type="checkbox"  name="'.$checkbox_name.'" value="'.$checked_value.'" '. ($current_value==$checked_value ? 'checked="checked"': '') .' />'; return $out;
	}

	public function js_library($url_or_Tag=true, $defaultPath=""){
		if( empty($defaultPath) && function_exists('home_url') )
			$defaultPath = plugin_dir_url($this->plugin_entryfile);
		$url = $defaultPath . '/libray_standard.js';
		return $url_or_Tag ? $url : '<script src="'.$url.'"></script>';
	}

	public function OutputIfNotPC($var){ if($GLOBALS['odd']['is_portable_platform']){echo $var;} }

	// common funcs
			

	public function my_translate_month_inside($string = '27/January/2015'){
		foreach($GLOBALS['odd']['months_arr'] as $each){
			if(strpos($string,$each)!==false) { 
				$string = str_replace($each,translate__MONTH($each), $string);
			}
		}
		return $string;
	}

	public function get_First_words($sentence , $desired_words_amount=5){
		$all_words = explode(' ', $sentence);  $words_amount = count($all_words);  $words_index_amount=$words_amount-1;
		$out = '';
		if ($words_amount > $desired_words_amount) {
			for($i = 0; $i< $desired_words_amount; $i++) {
				if(array_key_exists( $i,$all_words)){
					$out = $out.' '.$all_words[$i];
				}
			}
		}
		else {$out = $sentence;  }
		return strip_tags($out);
	}

	public function get_Last_words($sentence , $desired_words_amount=5){
		$all_words = explode(' ', $sentence);  $words_amount = count($all_words);  $words_index_amount=$words_amount-1;
		$out = '';
		if ($words_amount > $desired_words_amount) {
			for($i = 0; $i< $desired_words_amount; $i++) {
				if(array_key_exists( ($words_index_amount-$i),$all_words)){
					$out = $all_words[($words_index_amount-$i)].' '.$out;
				}
			}
		}
		else {$out = $sentence;  }
		return strip_tags($out);
	}

	public function my_utf8_decode($textt){
		$var = $textt;	$var = iconv("UTF-8","ISO-8859-1//IGNORE",$var);	$var = iconv("ISO-8859-1","UTF-8",$var); $var = str_replace(' ','',$var);
		return $var;
	}



	// ============================================= YOUTUBE DOWNLOAD FUNCTIONS ====================================================
	// https://pastebin_com/bFePMkfy
	
	//	https://img.youtube.com/vi/XXXXXXXXX/0.jpg (a bit larger)   // 1,2,3
	//  https://img.youtube.com/vi/xxxxxxxxx/mqdefault.jpg
	//  https://img.youtube.com/vi/xxxxxxxxx/hqdefault.jpg
	//  https://img.youtube.com/vi/xxxxxxxxx/maxresdefault.jpg
	public function get_youtube_thumbnail($id,$quality='maxres'){return 'https://i.ytimg.com/vi/'.$id.'/'.$quality.'.jpg';}  
		

	//to check if variable are normal
	public function get_youtube_id_from_url($url) {
		preg_match('/(http(s|):|)\/\/(www\.|)youtu(be\.com|\.be)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $results); 
		return (isset($results[6]) ? $results[6] : false);
	}
	public function get_youtube_id_from_contents($url){ 
		if (stripos($url,'youtu.be/')!==false)			{preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/si', $url, $final_ID); $x= !empty($final_ID[4]) ? $final_ID[4] : '';}
		elseif  (stripos($url,'youtube.com/')!==false)	{preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/si', $url, $IDD);$x= !empty($IDD[5]) ? $IDD[5] : ''; }
		return (!empty($x) ? $x : '');
	}

	public function get_youtube_id_from_contents_JAVASCRIPT(){ return '<script type="text/javascript">'. 'function getYtIdFromURL(URLL){var r=URLL.match(/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/); return r[1];} '. '</script>';
	} 
	public function validate_youtube_id($id){ if (strlen($id)!=11 || preg_match('/[\<\>\'\=\$\"\?\(\{]/si',$text)) {die("incorrrrrect_ID_ error79");	 }}
	//#################################
		
	// force ssl	
	public function redirect_to_https(){
		if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")
		{
			$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $redirect);
			exit();
		}
	}

	public function redirect_to_nonwww($https=true){
		if( stripos($_SERVER['HTTP_HOST'],'www.') !== false ) {
			$redirect =  ($https ? 'https' : 'http') . '://' . str_replace('www.','', $_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $redirect);
			exit();
		}
	}
	
	public function Serialized_Fixer($serialized_string){
		// securities
		if (empty($serialized_string)) 						return '';
		if ( !preg_match('/^[aOs]:/', $serialized_string) )	return $serialized_string;
		if ( @unserialize($serialized_string) !== false ) 	return $serialized_string;
		
		return
		preg_replace_callback(
			'/s\:(\d+)\:\"(.*?)\";/s', 
			function ($matches){	return 's:'.strlen($matches[2]).':"'.$matches[2].'";';	},
			$serialized_string )
		;
	}

	public function myIMGurlencode2($imgUrl){
		preg_match('/(.*)\/(.*)/si',$imgUrl, $n);	$x = (!empty($n[1]) && !empty($n[2])) ? $n[1].'/'.str_replace('+','%20',urlencode($n[2])) : "error_29858";  return $x;
	}

	public function myIMGurlencode($imgUrl){
		return str_replace('/'.basename($imgUrl) ,  '/'.str_replace('+','%20',basename($imgUrl)),       $imgUrl);
	}


	public function AddStringToUrl($url, $string){
		return $url .( stripos($url,'?')===false ?  '?'.$string :  '&'.$string);
	}

	//check, if AJAX has requested error send
	public function check_error_AJAX_request(){	if (isset($_REQUEST['ErrorAjax'])){  	
		$this->error_notify_admin__MYDDD(  rawurldecode($_REQUEST['ErrorAjax']) ,  urlencode($_REQUEST['p'])  );  exit("sent");
	}}

	public function error_notify_admin($error_msg=false,$postidd=false){ return error_notify_admin__MYDDD($error_msg,$postidd); }
	public function error_notify_admin__MYDDD($error_msg=false,$postidd=false){ 	if (is_localhost) return;
		// usage https://github.com/ttodua/useful-javascript/blob/master/AJAX-examples
		//'<script type="text/javascript">myyAjaxRequest('error_ajaxx=' + encodeURIcomponent(document.URL) + '&p= [[[[$GLOBALS['post']->ID]]]] &bla=blabla');</script>';
			$message	="\r\n\r\n\r\n\r\n\r\n\r\n===============================================================".date("Y-m-d H:i:s")."\r\n" . $error_msg. ' ||| URL:'.  ($postidd ?  get_permalink($postidd) : "") . " | " . $_SERVER['REQUEST_URI']. ' | REFERER:'. $_SERVER['HTTP_REFERER']."\r\n\r\nbacktrace:\r\n".print_r(debug_backtrace(), true); 
		
		//write into file
			//$file=$this->baseDIR.'/zzz___ajax_error_notifications_'.$this->my_site_variables__secret('rand_name', RandomString(11)).'.txt';   OR    $this->filecreat($file,$message, FILE_APPEND);
		// send to mail
			$subjectt	='error_'.$_SERVER['HTTP_HOST'];
			$message=str_replace(array("\r\n","\n"),"<br/>",$message);  $message=str_replace(array("\s"," ","\t"),"&nbsp;",$message);
			return $this->my_mail($this->error_to_mailaddress, $subjectt, $message, $this->default_mail_headers() );
			return "mail was not sent... check functionality";
	}

	// i.e. get_remote_data(' tinyurl.com/api-create.php?url='.$url); 
	public function get_short_link($url) { return $url; }

	public function allowed_extensions_of_url( $url ) {
		$ext = array( 'jpeg', 'jpg', 'gif', 'png' );
		$info = (array) pathinfo( parse_url( $url, PHP_URL_PATH ) );
		return isset( $info['extension'] ) && in_array( strtolower( $info['extension'] ), $ext, TRUE );
	}

	public function m1($tag=""){ $this->var_dump("\r\n<br/>* [MemoryUsage]A$tag :". $this->memory_usage()); }
	public function memory_usage(){ return memory_get_usage()/pow(1024,2); }
	public function gc_enable(){ return gc_enable(); }
	public function gc_clean() { return gc_collect_cycles(); }

	
	// create: https://vectr.com/new      https://vectorpaint.yaks.co.nz/     
	// convert : https://hnet.com/png-to-svg/  ( https://image.online-convert.com/convert-to-svg | https://convertio.co/ )
	// view: https://www.rapidtables.com/web/tools/svg-viewer-editor.html
	public function images($which, $type="png", $url_or_tag=true)
	{
		$url=[];
		switch ($which)
		{
			 //see visually: https://i.imgur.com/MNxlU7s.png
			case "overlay-pro"		: $url['svg'] = '<svg height="15pt" preserveAspectRatio="xMidYMid meet" viewBox="0 0 14 15" width="14pt" xmlns="http://www.w3.org/2000/svg"><g transform="matrix(.1 0 0 -.1 0 15)"><path d="m20 125c-13-14-21-27-18-30 2-3 17 9 33 25 16 17 24 30 19 30-6 0-21-11-34-25z"/><path d="m53 91c-73-80-67-94 7-17 33 35 60 66 60 69 0 16-18 2-67-52z"/><path d="m85 50c-27-28-45-50-39-50 13 0 99 88 93 95-3 2-27-18-54-45z"/><path d="m125 10c-3-5-1-10 4-10 6 0 11 5 11 10 0 6-2 10-4 10-3 0-8-4-11-10z"/></g></svg>';  break; 
			 //see visually: https://i.imgur.com/6oHljXM.png
			case "questionMark-1"	: $url['svg'] = '<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="64px" height="64px" viewBox="0 0 640 640" preserveAspectRatio="xMidYMid meet"> <g id="layer101" fill="#2a589e" stroke="none"> <path d="M234 616 c-124 -40 -224 -175 -224 -301 0 -106 83 -231 185 -279 76 -37 184 -37 260 0 110 52 191 182 183 294 -8 111 -83 222 -182 270 -59 28 -163 36 -222 16z"/> </g> <g id="layer102" fill="#6a88b6" stroke="none"> <path d="M270 470 l0 -40 45 0 45 0 0 40 0 40 -45 0 -45 0 0 -40z"/> <path d="M36 389 c-20 -103 3 -203 65 -273 119 -135 329 -135 449 1 22 25 40 50 40 55 0 6 -20 -12 -45 -40 -43 -48 -115 -93 -146 -91 -8 0 -2 5 14 11 31 11 34 20 12 38 -8 7 -12 16 -10 21 3 4 -10 2 -29 -6 -20 -8 -52 -14 -73 -14 l-38 2 45 6 c84 13 130 54 130 114 0 12 -23 51 -50 86 -28 35 -50 72 -50 82 0 15 -7 19 -35 19 -31 0 -35 -3 -35 -26 0 -31 14 -60 47 -95 54 -59 17 -125 -58 -102 l-34 10 47 -1 c55 -1 67 7 49 36 -7 11 -8 17 -2 13 6 -3 11 -2 11 2 0 5 -23 16 -50 26 -70 25 -158 79 -199 121 -19 20 -38 36 -42 36 -3 0 -9 -14 -13 -31z"/> </g> <g id="layer103" fill="#e5ebf3" stroke="none"> <path d="M280 470 c0 -39 1 -40 35 -40 34 0 35 1 35 40 0 39 -1 40 -35 40 -34 0 -35 -1 -35 -40z"/> <path d="M280 372 c0 -19 51 -105 69 -116 15 -9 14 -59 -1 -74 -14 -14 -77 -16 -111 -3 -23 9 -25 7 -29 -20 -3 -16 0 -33 5 -36 5 -3 40 -8 77 -11 148 -10 196 76 105 188 -25 30 -45 63 -45 72 0 14 -8 18 -35 18 -27 0 -35 -4 -35 -18z"/> </g> </svg>'; break;
			 //see visually: https://i.imgur.com/73R7eLv.png
			case "questionMark-2"	: $url['svg'] = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:se="http://svg-edit.googlecode.com" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" width="220.36365738125096" height="219.96970986050064" style=""><rect id="backgroundrect" width="100%" height="100%" x="0" y="0" fill="#FFFFFF" stroke="none" class="" style=""/>  <g class="currentLayer" style=""><title>Layer 1</title><path fill="#4a90d6" fill-opacity="1" stroke="#ebeb1a" stroke-opacity="1" stroke-width="0" stroke-dasharray="none" stroke-linejoin="round" stroke-linecap="butt" stroke-dashoffset="" fill-rule="nonzero" opacity="1" marker-start="" marker-mid="" marker-end="" d="M0,110 C0,49.22651933701658 49.226519337016555,0 110,0 C170.77348066298345,0 220,49.22651933701658 220,110 C220,170.77348066298345 170.77348066298345,220 110,220 C49.226519337016555,220 0,170.77348066298345 0,110 z" id="svg_1" class="" filter=""/><foreignObject fill="#4a90d6" stroke="#222222" stroke-width="2" stroke-linejoin="round" stroke-dashoffset="" fill-rule="nonzero" font-size="156" font-family="Arial, Helvetica, sans-serif" letter-spacing="0" word-spacing="0" marker-start="" marker-mid="" marker-end="" id="svg_9" x="23.115700873212546" y="29.279336103256995" width="224.30167929102208" height="168.0040174784078" style="color: rgb(36, 36, 36);" class="" transform="rotate(0.08801647275686264 832.5809326171628,364.08416748049103) "><p style="border: none;outline: none;font-size: inherit;line-height: 1em;padding:0;margin:0;" xmlns="http://www.w3.org/1999/xhtml"><p xmlns="http://www.w3.org/1999/xhtml" style="border: none;outline: none;font-size: inherit;line-height: 1em;padding:0;margin:0;"></p><p xmlns="http://www.w3.org/1999/xhtml" style="border: none;outline: none;font-size: inherit;line-height: 1em;padding:0;margin:0;"></p><p xmlns="http://www.w3.org/1999/xhtml" style="border: none;outline: none;font-size: inherit;line-height: 1em;padding:0;margin:0;"> ?</p><p style="border: none;outline: none;font-size: inherit;line-height: 1em;padding:0;margin:0;"></p><p style="border: none;outline: none;font-size: inherit;line-height: 1em;padding:0;margin:0;"></p></p></foreignObject></g><defs><marker id="DotS" refX="0" refY="0" orient="auto" inkscape:stockid="DotS" overflow="visible"> <path transform="scale(.2) translate(7.4 1)" d="M-2.5-1c0 2.76-2.24 5-5 5s-5-2.24-5-5 2.24-5 5-5 5 2.24 5 5z" fill-rule="evenodd" stroke="#000" stroke-width="1pt" style="fill: rgb(235, 235, 26); stroke: rgb(235, 235, 26); stroke-dasharray: none;"/></marker></defs></svg>'; break;
			 //see visually: https://i.imgur.com/mx70WNM.png
			case "rating-transparent" : $url['svg'] = '<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="544px" height="128px" viewBox="0 0 5440 1280" preserveAspectRatio="xMidYMid meet"><g id="layer101" fill="#ffb900" stroke="none"><path d="M335 1134 c-51 -14 -56 -69 -25 -256 l21 -119 -91 -100 c-72 -79 -90 -106 -91 -132 -3 -60 3 -102 16 -115 7 -8 69 -21 149 -31 74 -10 139 -21 143 -25 5 -4 40 -69 78 -144 l70 -137 75 0 75 0 70 137 c39 75 73 140 78 144 4 4 68 15 142 25 127 16 143 16 270 0 74 -10 138 -21 142 -25 5 -4 40 -69 78 -144 l70 -137 75 0 75 0 70 137 c39 75 73 140 78 144 4 4 68 15 142 25 127 16 143 16 270 0 74 -10 138 -21 142 -25 5 -4 40 -69 78 -144 l70 -137 75 0 75 0 70 137 c39 75 73 140 78 144 4 4 68 15 142 25 127 16 143 16 270 0 74 -10 138 -21 142 -25 5 -4 40 -69 78 -144 l70 -137 75 0 75 0 70 137 c39 75 73 140 78 144 4 4 68 15 142 25 127 16 143 16 270 0 74 -10 138 -21 142 -25 5 -4 40 -69 78 -144 l70 -137 75 0 75 0 70 137 c39 75 73 140 78 144 4 4 69 15 143 25 80 10 142 23 149 31 7 7 14 29 16 48 5 89 3 95 -91 199 -49 56 -90 102 -90 103 0 2 9 55 20 118 23 135 25 209 6 235 -11 15 -27 19 -74 20 -54 0 -73 -6 -181 -60 l-121 -59 -121 59 c-108 54 -127 60 -181 60 -47 -1 -63 -5 -74 -20 -19 -26 -17 -97 6 -237 l21 -119 -72 -80 c-40 -43 -75 -79 -79 -79 -7 0 -130 134 -143 156 -3 6 2 62 13 125 23 135 25 208 6 234 -11 15 -27 19 -74 20 -54 0 -73 -6 -181 -60 l-121 -59 -121 59 c-108 54 -127 60 -181 60 -47 -1 -63 -5 -74 -20 -19 -26 -17 -97 6 -237 l21 -119 -72 -80 c-40 -43 -75 -79 -79 -79 -7 0 -130 134 -143 156 -3 6 2 62 13 125 23 135 25 208 6 234 -11 15 -27 19 -74 20 -54 0 -73 -6 -181 -60 l-121 -59 -121 59 c-108 54 -127 60 -181 60 -47 -1 -63 -5 -74 -20 -19 -26 -17 -97 6 -237 l21 -119 -72 -80 c-40 -43 -75 -79 -79 -79 -7 0 -130 134 -143 156 -3 6 2 62 13 125 23 135 25 208 6 234 -11 15 -27 19 -74 20 -54 0 -73 -6 -181 -60 l-121 -59 -121 59 c-108 54 -127 60 -181 60 -47 -1 -63 -5 -74 -20 -19 -26 -17 -97 6 -237 l21 -119 -72 -80 c-40 -43 -75 -79 -79 -79 -7 0 -130 134 -143 156 -3 6 2 62 13 125 23 135 25 208 6 234 -11 15 -27 19 -74 20 -54 0 -73 -6 -181 -60 l-121 -59 -117 57 c-113 57 -177 74 -228 61z"/></g></svg>'; break;
			//see visually: https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg
			case "paypal" : $url['svg'] = '<svg xmlns="http://www.w3.org/2000/svg" width="248" height="66" viewBox="0 0 124 33"><path fill="#253B80" d="M46.211 6.749h-6.839a.95.95 0 0 0-.939.802l-2.766 17.537a.57.57 0 0 0 .564.658h3.265a.95.95 0 0 0 .939-.803l.746-4.73a.95.95 0 0 1 .938-.803h2.165c4.505 0 7.105-2.18 7.784-6.5.306-1.89.013-3.375-.872-4.415-.972-1.142-2.696-1.746-4.985-1.746zM47 13.154c-.374 2.454-2.249 2.454-4.062 2.454h-1.032l.724-4.583a.57.57 0 0 1 .563-.481h.473c1.235 0 2.4 0 3.002.704.359.42.469 1.044.332 1.906zM66.654 13.075h-3.275a.57.57 0 0 0-.563.481l-.145.916-.229-.332c-.709-1.029-2.29-1.373-3.868-1.373-3.619 0-6.71 2.741-7.312 6.586-.313 1.918.132 3.752 1.22 5.031.998 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .562.66h2.95a.95.95 0 0 0 .939-.803l1.77-11.209a.568.568 0 0 0-.561-.658zm-4.565 6.374c-.316 1.871-1.801 3.127-3.695 3.127-.951 0-1.711-.305-2.199-.883-.484-.574-.668-1.391-.514-2.301.295-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.499.589.697 1.411.554 2.317zM84.096 13.075h-3.291a.954.954 0 0 0-.787.417l-4.539 6.686-1.924-6.425a.953.953 0 0 0-.912-.678h-3.234a.57.57 0 0 0-.541.754l3.625 10.638-3.408 4.811a.57.57 0 0 0 .465.9h3.287a.949.949 0 0 0 .781-.408l10.946-15.8a.57.57 0 0 0-.468-.895z"/><path fill="#179BD7" d="M94.992 6.749h-6.84a.95.95 0 0 0-.938.802l-2.766 17.537a.569.569 0 0 0 .562.658h3.51a.665.665 0 0 0 .656-.562l.785-4.971a.95.95 0 0 1 .938-.803h2.164c4.506 0 7.105-2.18 7.785-6.5.307-1.89.012-3.375-.873-4.415-.971-1.142-2.694-1.746-4.983-1.746zm.789 6.405c-.373 2.454-2.248 2.454-4.062 2.454h-1.031l.725-4.583a.568.568 0 0 1 .562-.481h.473c1.234 0 2.4 0 3.002.704.359.42.468 1.044.331 1.906zM115.434 13.075h-3.273a.567.567 0 0 0-.562.481l-.145.916-.23-.332c-.709-1.029-2.289-1.373-3.867-1.373-3.619 0-6.709 2.741-7.311 6.586-.312 1.918.131 3.752 1.219 5.031 1 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .564.66h2.949a.95.95 0 0 0 .938-.803l1.771-11.209a.571.571 0 0 0-.565-.658zm-4.565 6.374c-.314 1.871-1.801 3.127-3.695 3.127-.949 0-1.711-.305-2.199-.883-.484-.574-.666-1.391-.514-2.301.297-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.501.589.699 1.411.554 2.317zM119.295 7.23l-2.807 17.858a.569.569 0 0 0 .562.658h2.822c.469 0 .867-.34.939-.803l2.768-17.536a.57.57 0 0 0-.562-.659h-3.16a.571.571 0 0 0-.562.482z"/><path fill="#253B80" d="M7.266 29.154l.523-3.322-1.165-.027H1.061L4.927 1.292a.316.316 0 0 1 .314-.268h9.38c3.114 0 5.263.648 6.385 1.927.526.6.861 1.227 1.023 1.917.17.724.173 1.589.007 2.644l-.012.077v.676l.526.298a3.69 3.69 0 0 1 1.065.812c.45.513.741 1.165.864 1.938.127.795.085 1.741-.123 2.812-.24 1.232-.628 2.305-1.152 3.183a6.547 6.547 0 0 1-1.825 2c-.696.494-1.523.869-2.458 1.109-.906.236-1.939.355-3.072.355h-.73c-.522 0-1.029.188-1.427.525a2.21 2.21 0 0 0-.744 1.328l-.055.299-.924 5.855-.042.215c-.011.068-.03.102-.058.125a.155.155 0 0 1-.096.035H7.266z"/><path fill="#179BD7" d="M23.048 7.667c-.028.179-.06.362-.096.55-1.237 6.351-5.469 8.545-10.874 8.545H9.326c-.661 0-1.218.48-1.321 1.132L6.596 26.83l-.399 2.533a.704.704 0 0 0 .695.814h4.881c.578 0 1.069-.42 1.16-.99l.048-.248.919-5.832.059-.32c.09-.572.582-.992 1.16-.992h.73c4.729 0 8.431-1.92 9.513-7.476.452-2.321.218-4.259-.978-5.622a4.667 4.667 0 0 0-1.336-1.03z"/><path fill="#222D65" d="M21.754 7.151a9.757 9.757 0 0 0-1.203-.267 15.284 15.284 0 0 0-2.426-.177h-7.352a1.172 1.172 0 0 0-1.159.992L8.05 17.605l-.045.289a1.336 1.336 0 0 1 1.321-1.132h2.752c5.405 0 9.637-2.195 10.874-8.545.037-.188.068-.371.096-.55a6.594 6.594 0 0 0-1.017-.429 9.045 9.045 0 0 0-.277-.087z"/><path fill="#253B80" d="M9.614 7.699a1.169 1.169 0 0 1 1.159-.991h7.352c.871 0 1.684.057 2.426.177a9.757 9.757 0 0 1 1.481.353c.365.121.704.264 1.017.429.368-2.347-.003-3.945-1.272-5.392C20.378.682 17.853 0 14.622 0h-9.38c-.66 0-1.223.48-1.325 1.133L.01 25.898a.806.806 0 0 0 .795.932h5.791l1.454-9.225 1.564-9.906z"/></svg>'; break;
		}
		return $url[$type];
	}
	public function encodeSvg($content){ return str_replace(['<','>', '#', '"'], ['%3C','%3E', '%23', '\''], $content); }
	public function imageSvg($which){ return 'data:image/svg+xml;charset=UTF-8,'. $this->encodeSvg( $this->images($which, 'svg') ); }
	
	public function addQueryArg($url, $key, $value){
		$pair = urlencode($key).'='.urlencode($value);
		return ($this->contains($url,'?') ? $url."&$pair" :  $url."?$pair");
	}

	public function question_mark($text, $dialog=0, $question_mark="") { 
		$mouseover='';
		$content = '';
		if($dialog==0){
			$content = $text;
		}
		else if($dialog==1){
			$content = '';
			$mouseover = ' onmouseover="jQuery(\'#\'+this.parentNode.id).tooltip({ items:this,   content:\''.$text.'\', show: { effect: \'blind\', duration: 800 } 	}).tooltip(\'open\');"'; 	
		}
		else if($dialog==2){
			$content = '';
			$mouseover = ' onmouseover="jQuery(\'<div>'.$text.'</div>\').dialog({   modal:true,   width:600 });"';
		}
		if (empty($question_mark)) $question_mark=$this->imageSvg('questionMark-1');
		return '<span id="xx"><img src="'. $question_mark .'" class="question_mark" style="cursor:crosshair; width:20px;" alt="'.$content.'" title="'.$content.'" '.$mouseover.' /></span>';
	}


	
	public function between($a,$b,$c){
		return ($a<$b && $b<$c);
	}
	public function inside($a,$b,$c){
		return ($this->between($a,$b,$c) || $this->between($c,$b,$a) );
	}
	
	//addTextOnImage( ['text'=>'hello',  'input'=>'img.png', 'echo'=>false, 'method'=>'gd|imagick', 'fontsize'=>9, 'angle'=>-15, 'x'=>11, 'y'=>14, 'color'=>'#e7e7e7', 'opacity'=>0.5, 'stroke'=>['#e7e7e7',$width=4,$alpha=0.5], 'spaces'=>3]); //also, font
	public function addTextOnImage($opts=[])
	{
		//v_dump(glob("C:\Windows\Fonts\*"));
		//v_dump($Imagick->queryFonts("*"));
		$text 		= $opts['text'];
		$imagePath	= $opts['input'];
		$fontsize	= $opts['fontsize'];
		list($width, $height, $type, $attr) =getimagesize($imagePath); 
		$x_position = $this->arrayKeyValue($opts,'x',0);
		$y_position = $this->arrayKeyValue($opts,'y',0);
		if (strpos($x_position,'%')!==false) $x_position = $width * str_replace('%','',$x_position)/100;
		if (strpos($y_position,'%')!==false) $y_position = $height * str_replace('%','',$y_position)/100;
		
		if( $this->arrayKeyEquals($opts, 'text_repeat', true) )
		{
			$final_text="";
			$multiplier=4; //lets say 3 for assurance
			$spaces_between = $this->arrayKeyValue($opts, 'spaces',5);
			$repeated_per_width = ($width / (strlen($text) * $fontsize)) * $multiplier;
			$repeated_per_height= ($height / ($fontsize)) * $multiplier;
			
			for ($i=0; $i<$repeated_per_height; $i++)
			{
				$t= "";
				for ($j=0; $j<$repeated_per_width; $j++)
				{
					$t .= $text . str_repeat(" ", $spaces_between );
				}
				$final_text .=$t. "\r\n";
			}
			$text = $final_text;
		}

		if ( $this->arrayKeyEquals($opts, 'method', 'gd') ) 
		{
			// FETCH IMAGE & WRITE TEXT
			$im = imagecreatefrompng($imagePath); 
			//imagecolorclosest  imagecolorallocate
			$red = imagecolorclosest($im, 0xFF, 0x00, 0x00);		
			$black = imagecolorclosest($im, 0x00, 0x00, 0x00);		
			$white = imagecolorclosest($im, 255, 255, 255);
			// imagecolorallocate(imagecreatetruecolor(111, 111), 2, 2, 2)
			//$color = $red;//$red;

			imagefttext($im, $fontsize=$opts['fontsize'], $angle=$opts['angle'], $x_pos=$x_position, $y_pos=$y_position, $color=$opts['color'], $font=$opts['font'], $text);
			imagealphablending($im, false);
			imagesavealpha($im, true);
			if ($resize=false)
			{
				$percent=0.5;
				$new_width = $width * $percent;
				$new_height = $height * $percent;
				$image_p = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($im, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			}

			// Output and free memory
			header('Content-type: image/png');
			imagepng($im);
			imagedestroy($im);
		}
		else
		{
			// https://mlocati.github.io/articles/php-windows-imagick.html
			// https://www.php.net/manual/en/book.imagick.php
			$Imagick = new \Imagick();
			$Imagick->readImage($imagePath);
			$Imagick->setImageFormat( $format = $this->arrayKeyValue($opts,'format','png') );
			//$Imagick->setCompressionQuality ( 0 );
			
			$ImagickDraw = new \ImagickDraw();
			$ImagickDraw->setFontSize( $fontsize );
			$ImagickDraw->setTextAntialias ( true );
			if (array_key_exists('font',$opts) )
				$ImagickDraw->setFont( $font=$opts['font'] );
			
			if ( array_key_exists('stroke', $opts))
			{
				$ImagickDraw->setStrokeColor($opts['stroke'][0]);
				$ImagickDraw->setStrokeWidth($opts['stroke'][1]);
				$ImagickDraw->setStrokeOpacity($opts['stroke'][2]);
			}
			$ImagickDraw->setFillColor($color=$opts['color']);
			$ImagickDraw->setFillOpacity($opacity=$opts['opacity']);
			
			//$ImagickDraw->setGravity( Imagick::GRAVITY_CENTER );
			$Imagick->annotateImage( $ImagickDraw, $x_pos=$x_position, $y_pos=$y_position, $angle=$opts['angle'], $text);

			if ($opts['echo'])
			{
				header( "Content-Type: image/{$Imagick->getImageFormat()}" );
				echo $Imagick->getImageBlob();
			}
			else{
				$Imagick->writeImage($imagePath);
			}
		}
	}
	

	

	public static $nodepath='';
	public static function node_exec($filepath){
		//process.stdout.write("hii");
		return empty(self::$nodepath) ? 'Node path not set' : self::cmd(self::$nodepath .' '.$filepath);
	}


	

	#region     ############### ASYNC FUNCTIONS ############### //
	// https://www.reddit.com/r/PHP/comments/no7abs/

	public $async_methods_available = ['reactphp', 'amphp', 'amphp_parallel', 'spatie','parallel','fibers', 'swoole', 'exec', 'proc-open', 'popen', 'pcntl_fork', '_no_async_' ];
	// to execute non-blocking async callback-function |  args:   [$func,$args]  where $args is array 
	public function asyncFunction($callback, $which_method='spatie'){
		$method = strtolower($which_method);
		if ( !in_array( $method, $this->async_methods_available) ) 
			throw new \Exception( "$method not supported, use from: ". implode(' | ',$this->async_methods_available) );
		
		$func_name = 'asyncFunctions_helper_'.$method;
		call_user_func([$this,$func_name],  [ [$callback, $arg1=[]] ] );
	}

	// to execute blocking/non-blocking call of multiple gourp for async callback-functions
	// #######    EXAMPLE   #######
	//
	//   $func = function($param1=null, $param2=null, ... ){
	//	    echo "\nParams are : ". print_r(func_get_args(),true);
	//   };
	//
    //   $arr = [ [$func], [$func, "hi"], [$func, "good", "bye"] ] ;
	//   $helpers->asyncFunctions($arr,'parallel');

	public function asyncFunctions($callbacksArray, $which_method='spatie', $blocking=true, $exception_handler=null){
		$method = strtolower($which_method);
		if ( !in_array( $method, $this->async_methods_available) ) 
			throw new \Exception( "$method not supported, use from: ". implode(' | ',$this->async_methods_available) );

		$example = ': CALLBACK & ARGUMENT pair, like [$callback,$arg1,...] or [$callback], where $args behaves like as in call_user_func_array';
		if ( !is_array($callbacksArray) )
			throw new \Exception("asyncFunctions was passed incorrect argument. Should be array of $example"); 
		
		$callbacksArray_NEW =[]; // just add arguments
	
		foreach($callbacksArray as $eachPair){
			if (is_callable($eachPair))
			{
				$callbacksArray_NEW[] = [$eachPair, []];
			}
			elseif (is_array($eachPair))
			{
				$callback = $eachPair[0];
				array_shift($eachPair); //unset first one, as all other ones are arguments passed to call_user_func
				$args     = empty($eachPair)? [] : $eachPair;
				$callbacksArray_NEW[] = [$callback, $args];
			}
			else{
				throw new \Exception("asyncFunctions was passed incorrect array of callback. Each array child should be $example");
			}
		}
		
		//re-sort
		$func_name = 'asyncFunctions_helper_'.$method;

		call_user_func([$this, $func_name], $callbacksArray_NEW, $exception_handler);
		return;

		if ($blocking)
		{
			
		}
		else{
			//foreach($callbacksArray_NEW as $callbackAndArgPair){
			//	$this->asyncFunction($callbackAndArgPair, $which_method);
			//}
		}
	}


	// ##### PARALLEL (requires extension) #####
    public static function asyncFunctions_helper_parallel($callbacksArray, $exception_handler=null)
    {
		foreach( $callbacksArray as $callback_arg_pair){
			//\parallel\run($callback = $callback_arg_pair[0], $args = $callback_arg_pair[1] ); 
			//$r1 = new \parallel\Runtime();$r1->run
			self::asyncFunction_parallel($callback = $callback_arg_pair[0], $args = $callback_arg_pair[1]);
		}
    } 
    public static function asyncFunction_parallel($callback, $args=[])
    {
		\parallel\run($callback, $args); 
    } 


	// ##### SPATIE #####  [ https://github.com/spatie/async/issues/120 ]
    public static function asyncFunctions_helper_spatie($callbacksArray, $exception_handler=null)
    {
		if ( ! $this->spatieSupported()  ){
			$msg = "Spaties needed extensions are not enabled: pcntl & posix";
			//if ( !$this->is_localhost()) throw new \Exception($msg); else   
			$this->var_dump($msg . " ; However, continuing execution in synchronous mode");
		}
		$pool = \Spatie\Async\Pool::create();
		foreach( $callbacksArray as $callback_arg_pair){
			$pool->add( function() use($callback_arg_pair){  
				call_user_func_array($callback = $callback_arg_pair[0], $args = $callback_arg_pair[1]);
			} )->then(function ($output) {
				// Handle success
			})->catch(function (\Spatie\Async\Pool\Throwable $exception) {
				var_dump($exception);
			});
		}
		$pool->wait(); 
    }  public static function spatieSupported(){ return \Spatie\Async\Pool::isSupported(); } 


	
	// ##### SWOOLE (requires extension) #####
	// use advanced usage: https://www.swoole.co.uk/docs/modules/swoole-coroutine-run
    public static function asyncFunctions_helper_swoole($callbacksArray, $exception_handler=null)
    {
		// needs to be checked ( as: https://www.swoole.co.uk/docs/modules/swoole-coroutine-run )
		if (self::swoole_installed() && function_exists('\\Swoole\\Coroutine\\run') )
		{
			if ( !self::swoole_inside_coroutine() ) 
			{
				\Swoole\Coroutine\run(function() use ($callbacksArray,$exception_handler)  {
					self::asyncFunctions_helper_swooleGoTrigger($callbacksArray,$exception_handler);
				});
			}
			else{ 
				self::asyncFunctions_helper_swooleGoTrigger($callbacksArray,$exception_handler);
			} 
		}
		else{
			var_dump("Swoole not installed, running plain function");
			self::asyncFunctions_helper__no_async_($callbacksArray);
		}
    }  
    private static function asyncFunctions_helper_swooleGoTrigger($callbacksArray, $exception_handler=null)
	{
		//$wg = new \Swoole\Coroutine\WaitGroup();
		foreach( $callbacksArray as $callback_arg_pair){
			\go(function() use ($callback_arg_pair, $exception_handler) {
				try{
					//$wg->add(1); 
					call_user_func_array($callback = $callback_arg_pair[0], $args = $callback_arg_pair[1]);
					//$wg->done();
				}
				catch(\Exception $ex){
					if (is_callable($exception_handler))
						call_user_func($exception_handler, new \Exception($ex->getMessage()) );
					else 
						trigger_error($ex->getMessage());
				}
			});
		}
		//$wg->wait();
	}  
	public static function swoole_inside_coroutine(){ return (self::swoole_installed() && \Swoole\Coroutine::getCid()!=-1 && \Swoole\Coroutine::getPcid()!==false ); } // https://cloud.tencent.com/developer/article/1771756
	public static function swoole_inside_coroutine_run(){ return (self::swoole_installed() && \Swoole\Coroutine::getPcid()==-1); } 
	public static function swoole_inside_coroutine_go(){ return (self::swoole_installed() && is_numeric(\Swoole\Coroutine::getPcid()) && \Swoole\Coroutine::getPcid()>0); } 

	public static function swoole_installed(){ return class_exists('\\Swoole\\Coroutine'); }
	public static function helper_SwooleToggle($enable_or_disable ){
		// SWOOLE_HOOK_ALL;  // https://www.geeksforgeeks.org/php-bitwise-operators/ ::: swoole_hook_all is: 2147479551  | SWOOLE_HOOK_CURL : 2048  | SWOOLE_HOOK_NATIVE_CURL : 4096    \Swoole\Runtime::getHookFlags(); // $currentHooks ^ SWOOLE_HOOK_CURL  ^ SWOOLE_HOOK_NATIVE_CURL; <-- cannot enable both  |  \Swoole\Runtime::setHookFlags($final); // https://github.com/swoole/swoole-src/issues/4280
		if (!self::swoole_inside_coroutine())
			return;
		if($enable_or_disable)
			\Swoole\Runtime::setHookFlags(SWOOLE_HOOK_ALL);
		else
			\Swoole\Runtime::setHookFlags(0);
	}

	// ##### AMPHP #####
	public static function asyncFunctions_helper_reactphp($callbacksArray, $exception_handler=null){
		$loop = \React\EventLoop\Factory::create();
		$promise = new \React\Promise\Promise(function($resolve){
		});
		foreach($callbacksArray as $callback_arg_pair) { 
			$promise->then(function($v) use ($loop, $callback_arg_pair, $exception_handler) {
				$loop->run( function() use($callback_arg_pair, $exception_handler){
					try{
						call_user_func_array($callback = $callback_arg_pair[0], $args = $callback_arg_pair[1]);
					}
					catch(\Exception $ex){
						if (is_callable($exception_handler))
							call_user_func($exception_handler,$ex);
						else 
							trigger_error($ex->getMessage());
					}
				}  );
			});
		}
		$loop->run();		
	}


	// ##### AMPHP #####
	public static function asyncFunctions_helper_amphp($callbacksArray, $exception_handler=null){

		$promises = [];
		\Amp\Loop::run(function () use($callbacksArray) { 
			foreach($callbacksArray as $eachCallbackAndArg) { 
				\Amp\Loop::defer($eachCallbackAndArg[0]); 
			}
		});

		return;
		//...
			$promises[] = \Amp\call( function() use ($eachCallbackAndArg) {
				return call_user_func($eachCallbackAndArg[0],$eachCallbackAndArg[1]);
			} );  
		//\Amp\Loop::run();  \Amp\asyncCall
		\Amp\Promise\wait(\Amp\Promise\all($promises)); 

		return;
		// ..
			\Amp\asyncCall( function() use ($eachCallbackAndArg) {
				return call_user_func($eachCallbackAndArg[0],$eachCallbackAndArg[1]);
			} ); 
		\Amp\Loop::run();
	}

	// https://github.com/amphp/parallel-functions  && https://github.com/amphp/parallel-functions/issues/28
	public static function asyncFunctions_helper_amphp_parallel($callbacksArray, $exception_handler=null){
		foreach($callbacksArray as $callback_arg_pair) {
			//$pool = new \Amp\Parallel\Worker\DefaultPool();
			$callbacks[] = call_user_func_array(\Amp\ParallelFunctions\parallel($callback_arg_pair[0]), $callback_arg_pair[1]); //, $pool
		}
		$result = \Amp\Promise\wait(\Amp\Promise\all($callbacks));
	}



	// ##### EXEC #####
	// https://stackoverflow.com/questions/49592786/asynchronous-call-to-shell-exec-php
	// https://stackoverflow.com/questions/222414/asynchronous-shell-exec-in-php
	// https://stackoverflow.com/questions/45953/php-execute-a-background-process#45966
	// https://stackoverflow.com/questions/2212635/best-way-to-manage-long-running-php-script !!
	public static function asyncFunctions_helper_exec($command = null, $with_php=false){
		// moved to separate file, due to WP restrictions
		return self::asyncFunctions_helper_exec2($command, $with_php);
	}

	// no async, just default fallback
    public static function asyncFunctions_helper__no_async_($callbacksArray, $exception_handler=null)
    {		
		self::call_user_funcs($callbacksArray);
    } 
    public static function call_user_funcs($callbacksArray)
    {
		foreach($callbacksArray as $callbackAndArgsPair){
			call_user_func_array($callbackAndArgsPair[0], $callbackAndArgsPair[1]);
		}
    }
	#endregion


	


	public function resizeImage($imagePath, $width, $height=0, $auto_proportion=true, $filter=false, $blur=1)
	{
		$Imagick = new \Imagick();
		$Imagick->readImage($imagePath);
		//$Imagick->setImageFormat( $format = $this->arrayKeyValue($opts,'format','png') );
		$filter= !$filter ? \Imagick::FILTER_LANCZOS : $filter;  //FILTER_LANCZOS
		$Imagick->resizeImage($width, $height, $filter, $blur, $auto_proportion );
		$Imagick->writeImage($imagePath);
	}
	
	// get-timezones : pastebin_com/4tXjgY7B

	public function add_prefix_to_object_keys($object, $prefix){
		$new_object = new stdClass();
		foreach ($object as $k => $v) { 
			$new_object->{$prefix . $k} = $v;
		}
		return $new_object;
	}

	public function convertClockToSeconds($input="4h", $minute_symbol="m", $month_symbol="M")
	{
		$array=['s'=>1,'S'=>1,     'm'=>60,    'h'=>3600,'H'=>3600,     'd'=>86400,'D'=>86400,     'w'=>604800,'W'=>604800,     'M'=>2678400];//31days
		foreach($array as $key=>$value)
		{
			if ( strpos($input,$key)!==false ) { $input=str_replace($key,'', $input); $input=$input*$value; }
		}
		return $input;
	}
	
	public function dieMessage($txt){
		echo 
		'<div style="padding: 50px; margin:100px auto; width:50%; text-align:center; line-height: 1.4; display:flex; justify-content:center; flex-direction:column; font-family: cursive; font-size: 1.7em; box-shadow:0px 0px 10px gray; border-radius: 10px;">'.
			'<div><h3>'.$txt.'</h3></div>'.
		'</div>';
		exit;
	}


	public function Return_If_Isset($var){ if (isset($var)) { return $var; }    else { return false; }  }
	public function Return_If_Not_Empty($var){ if (!empty($var)) { return $var; }    else { return false; }  }
	public function Return_If_Array_Key($array, $keyname){ if (array_key_exists($keyname, $array)) { return $array[$keyname]; }    else { return false; }  }

	// custom always-loaded scripts 
	// my_script_url("css|js",  "public|admin")
	public function my_script_url($type="js|css", $kind="public|admin", $with_tag=false) 
	{
		if ($type=='js'){
			return ($with_tag? '<script type="text/javascript" src="':'') . $this->js_library() .'&vers='.$this->changeable_JS_CSS_version . ($with_tag? '"></script>':'');
		}
		elseif ($type=='css'){
			return ($with_tag? '<link rel="stylesheet" href="':'') . $this->baseScriptsUrl.'style-'.$kind.'.css?vers='.$this->changeable_JS_CSS_version. ($with_tag? '"	type="text/css" media="all" />':'');
		}
	}
	public function my_loader_css_js($css=true, $js=true)
	{  	
		$admin = function_exists('is_admin') ? is_admin() : false;
		if ($css)	echo $this->my_script_url('css', ( $admin ? 'admin':'public'), true);
		if ($js) 	echo $this->my_script_url('js',  '', true);
	}
	
	public function my_loader_css_js_trigger()
	{  	
		$screen= is_admin()? 'admin' : 'public';
		$this->my_loader_css_js($css=$this->load_styles['css'][$screen], $js=$css=$this->load_styles['js'][$screen]);
	}

	// ================================== STYLES ================================== //
	
	private $all_enqueue_scripts=[];
	public function init_loadscripts($override_array)
	{
	  $initial_scripts=
	  [
		//
		'my_javascript'	=> ['screen'=>['admin'=>0, 'public'=>0], 'urls'=>[
			'js' => $this->my_script_url('js','')
		]],
		'my_style_public'=> ['screen'=>['admin'=>0, 'public'=>0], 'urls'=>[
			'css' => $this->my_script_url('css','public')
		]],
		'my_style_admin'=> ['screen'=>['admin'=>0, 'public'=>0], 'urls'=>[
			'css' => $this->my_script_url('css','admin')
		]]
	  ];

	  $this->load_scripts_override = [];
	  if ( method_exists($this, 'define_load_links') ) $this->define_load_links();
	  $initial_scripts = array_merge($initial_scripts, $this->load_scripts_override);

	  $this->all_enqueue_scripts = array_replace_recursive($initial_scripts, $override_array);
	}
	
	
	public function my_styles_hook($pure_php=false) {
		$front_or_back = function_exists('is_admin') && is_admin() ? 'admin' : 'public';
		$current_screen = $front_or_back=='public' ? 'wp' : 'admin';  // gets: admin or public
		foreach ($this->all_enqueue_scripts as $name=>$block)
		{
			if($block['screen'][$front_or_back])
			{
				if (!empty($block['urls']))
				foreach ($block['urls'] as $JS_or_CSS=>$url)
				{
					$type_ = ($JS_or_CSS=="js") ? 'script' : ($JS_or_CSS=="css" ? 'style' : $JS_or_CSS);
					if ($pure_php===true) 
					{
						if ($type_=='style')
							echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="all" />';
						else {
							echo '<script src="'.$url.'"></script>';
						}
					}
					else
					{
						$this->register_stylescript($current_screen, $type_, $name, $url);
					}
				}
			}
		}
	}

	//example testmode : pastebin_com/bUncPcFD
	
	public function filedate($file){
		return date("Y-M-D--H-i-s", filemtime($file) ); 
	}

	public function TRANSLL($phraze,$LNG=false, $desired=array())	{ return apply_filters('MLSS', $phraze, ($LNG ? $LNG: (defined('LNG') ? LNG : '' )  ),  $desired    );   }

	public function MY_LANGSS(){
		if (!function_exists('LANGS__MLSS')){
			if(!empty($GLOBALS['my_custom_langs'])) return $GLOBALS['my_custom_langs'];
			if(defined('ERROR_SHOWN__MLSS') || DISABLE_MLSS_ERROR ) {return array();}	

			$xx344=debug_backtrace();
				echo '<script>alert(\'plugin "Multi-Language Site (basis)" seems not installed. please install it.\r\n\r\n\ File:'. $xx344[0]['file'] .' \r\n\ line:'.$xx344[0]['line'].'\');</script>';  
				if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {die('error_45y4e5ge4g'); }	 define('ERROR_SHOWN__MLSS',1);
		}
		else{  return LANGS__MLSS();  }
	}
	
	 
	//if ( !$this->above_version('5.4') ) { echo("php_version is ". PHP_VERSION ." (quite old). HIGHLY recomended to update to higher version, or this program might not funciton normally ". __FILE__ ); }
	public function above_version($version= "5.4"){
		return version_compare(phpversion(), $version, '>=');
	}

	public function noindex_meta_tag() { return '<meta name="robots" content="noindex, nofollow">'; }

	public function valueToString( $value ){
		return is_bool($value) ? ($value ? 'true' : 'false' ) : strip_tags(  $value ) ;
	}
	public function stringToValue( $value ){
		return is_bool($value) ? $value : ( !is_string($value) ?  $value : ( $value =='true' ? true : (  $value =='false' ? false : $value) ) );
	}

    public function randomId($divider="_"){ return "K".time() . $divider . rand(1,9).rand(0,9999999999999); }


	
	
	// #####################################
	//check either GET or argv (note, in argv, the parameters should be quoted, like: 
	//       * * * * *  /usr/bin/php -q /var/www/example/wp-cron.php "cron_cycle=3&param=1"	
	public static function Argv($key='', $default='')
	{
		global $argv;
		$array = [];
		if (!empty($argv[1])) {
			parse_str($argv[1], $array);
		}
		return empty($key) ? $array : ( array_key_exists($key, $array) ? $array[$key] : $default);
	}
	public function Get($key='', $default='')
	{
		return empty($key) ? $_GET : ( array_key_exists($key, $_GET) ? $_GET[$key] : $default);
	}
	// THIS IS ALWAYS SANITIZED IN IMPLEMENTATIONS
	public function Post($key='', $parsePhpInput=true){
		if (!empty($_POST)) {
			$array = $_POST;
		} else {
			$input = file_get_contents('php://input');
			if (!empty($input)) {
				if (!$parsePhpInput) return $input;
				$array= json_decode($input, true);
				if (is_null($array))
				{
					return $input;
				}
			} else{
				$array = [];
			}
		}
		return (!empty($key) ? $this->array_value($array,$key) : $array);
	}
	public function inputData(){
		return $this->POST();
	}
	
	public function ArgvGet($key='', $default=null)
	{
		global $argv;
		$array = [];
		if (!empty($argv[1])) {
			parse_str($argv[1], $array);
		}
		else {
			$array=$_GET;
		}
		return !empty($key) ? $this->array_value($array,$key,$default) : $array;
	}
	public function ArgvIsSet($key)
	{
		global $argv;
		$array = [];
		if (!empty($argv[1])) {
			parse_str($argv[1], $array);
		}
		return array_key_exists($key,$array);
	}
	public function ArgvGetIsSet($key)
	{
		global $argv;
		$array = [];
		if (!empty($argv[1])) {
			parse_str($argv[1], $array);
		}
		else {
			$array=$_GET;
		}
		return array_key_exists($key,$array);
	}

	public static function ArgvFile()
	{
		global $argv;
		return !empty($argv[0]) ? $argv[0] : '';
	}

	public static function IsCron(){ return self::IsCli(); }
	public static function IsCli(){
		try{
			$sapi_type = php_sapi_name();
			return (substr($sapi_type, 0, 3) == 'cli' || empty($_SERVER['REMOTE_ADDR']));
		}
		catch(\Exception $ex){
			return false;
		}
	}
	
	public static function array_to_argv($array=null)
	{
		$str = http_build_query($array);
		return $str;
	}

	//convert command line to $_GET
	public function argv_to_array($argv_=null,$index=1)
	{
		$array=[];
		if (!empty($argv_[$index])) parse_str($argv_[$index], $array);	
		return $array;
	}


	public static function php_path_current(){ return PHP_BINARY; }
	
	public static function command_current($addToParams=''){
		return self::php_path_current().' "'.self::ArgvFile().'" "'.self::array_to_argv(self::Argv()) .$addToParams.'"';
	}

	public static function is_simple_type($var){
		return ( is_string($var) || is_numeric($var) || is_bool($var) );
	}


	public function argvs_get_post($argv_,$index=1)
	{
		//$array=[];
		//if (!empty($argv_[$index])) parse_str($argv_[$index], $array);	
		return $array;
	}
	
	public function serialize_argv($argvs)
	{
		if(empty($argvs) || !is_array($argvs)) return $argvs;
	
		$new_ar=[];
		foreach($argvs as $key=>$value)
		{
			if(stripos($value,'=')===false)
			{
				$new_ar[$key] = $value;
			}
			else{
				parse_str($argvs[$key], $params);
				$key1=array_keys($params)[0];
				if(!empty($argvs) && is_array($params))
					$new_ar[$key1] =  $params[$key1];
			}
		}
		return $new_ar;
	}
	
	// #####################################

	public function array_fields($array, $parent="plugin_slug[sample][sub]", $pairs=false)
	{ 
		echo '<div class="inpHolder">';

		echo '<div class="inputsBlock">';
		if (is_array($array) && !empty($array)) 
		{
			foreach ($array as $optName=>$value)
			{
				echo $this->field_out_helper1($parent, $optName, $value, $pairs) ;
			}
		}

		$sample_field = $this->field_out_helper1($parent, "", "", $pairs);
		//echo $sample_field;
		echo '</div>';
		?>
				<?php $unique = $this->sanitize_nonoword($parent); ?>
		<a class="button" href="#" onclick="return <?php echo $unique;?>_addNewArrayField_k(this);" class="addNewArrayInput"><?php _e('Add New');?></a>
		<script>
		function <?php echo $unique;?>_addNewArrayField_k(el)
		{ 
			var targetEl = el.parentNode.parentNode.parentNode.getElementsByClassName("eachInputBlock")[0];
			var rand=  Math.random().toString(36).substring(2); 
			var newElString = targetEl.outerHTML.replace( /(inputKey_[\w]*)/g, "inputKey_"+rand).replace(/value="(.*?)"/g, 'value=""');
			targetEl.parentNode.insertAdjacentHTML("beforeend", newElString);
			return false;
		}
		</script>
		<?php
		echo '</div>';
	}

	public function field_out_helper1($parent, $optName, $value, $pairs)
	{
		$output='<div class="eachInputBlock">';
		$rand= "inputKey_".rand(1,999999)."_".rand(1,999999)."_".rand(1,999999);  
		if (!$pairs) { 
			$key = (!empty($optName) ? $optName : $rand);
			$output .= '<input name="'.$parent.'['.$key.']"  class="eachInput each_'.$key.' regular-text" type="text" value="'.$value.'"  placeholder="" />';
		} else {
			$output .= '<input name="'.$parent.'['.$rand.'][name]"  class="eachInput each_'.$rand.' medium-text _key" type="text" value="'. (!empty($optName) ? $optName : "").'"  placeholder="name" />';
			$output .= '<input name="'.$parent.'['.$rand.'][value]"  class="eachInput each_'.$rand.' medium-text _value" type="text" value="'.$value.'"  placeholder="value" />';
		}
		$output .='</div>';
		return $output;
	}



	// 	<!-- GEORGIAN automatic keyboard while typing in SEARCH --> <script type="text/javascript" src="'. $this->baseURL .'/library/js/geokbd.js"></script>

	public function arrayFieldsResort($ar)
	{
		$new=[];
		foreach($ar as $key=>$val)
		{
			$new[ $this->sanitize_text_field($val["name"]) ] = $this->sanitize_text_field($val["value"]);
		}
		return $new;
	}


	public function get_fb_name_regex($fb_url){
		preg_match('/'.preg_quote('^(?:https?://)?(?:www.|m.|touch.)?(?:facebook.com|fb(?:.me|.com))/(?!$)(?:(?:\w)#!/)?(?:pages/)?(?:[\w-]/)?(?:/)?(?:profile.php?id=)?([^/?\s])(?:/|&|?)?.*$/'), $fb_url, $n);
		return $n[1];
	}
	
	
	public function shapeSpace_allowed_html() {

		$allowed_tags = [
			'a' => [
				'class'=>[], 'href'=>[], 'rel'=>[], 'title'=>[],
			],
			'abbr' => [
				'title' => [],
			],
			'b' => [],
			'blockquote' => [
				'cite'  => [],
			],
			'cite' => [
				'title' => [],
			],
			'code' => [],
			'del' => [
				'datetime'=>[], 'title'=>[],
			],
			'dd' => [],
			'div' => [
				'class'=>[], 'title'=>[], 'style'=>[],
			],
			'dl' => [],
			'dt' => [],
			'em' => [],
			'h1' => [],
			'h2' => [],
			'h3' => [],
			'h4' => [],
			'h5' => [],
			'h6' => [],
			'i' => [],
			'img' => [
				'alt'=>[], 'class'=>[], 'height'=>[], 'src'=>[], 'width'=>[],
			],
			'li' => [
				'class'=>[],
			],
			'ol' => [
				'class'=>[],
			],
			'p' => [
				'class'=>[],
			],
			'q' => [
				'cite'=>[],'title'=>[],
			],
			'span' => [
				'class'=>[], 'title'=>[], 'style'=>[],
			],
			'strike' => [],
			'strong' => [],
			'ul' => [
				'class' => [],
			]
		];
		
		return $allowed_tags;
	}	
	
	public function display_errors()
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting( E_ALL );
	}
	
	public function log_errors($path=false, $callback=null)
	{
		//htaccess:
		//	php_flag log_errors on
		//	php_value error_log /home/FTP_username/public_html/error_log.txt
		ini_set("log_errors", 1);
		ini_set("error_log", $path ? $path : $_SERVER['DOCUMENT_ROOT']."/zzz___php-my-errors_".$this->my_site_variables__secret('rand_name', RandomString(11)).".log");
		//error_log( "Hello, errors!" );	
		if (!is_null($callback)) set_error_handler($callback);
	}
	 
	public function javascript_headers()
	{
		session_cache_limiter('none');
		// http://stackoverflow.com/a/1385982/2377343
		//Caching with "CACHE CONTROL"
		header('Cache-control: max-age='.($year=60*60*24*365) .', public');
		//Caching with "EXPIRES"  (no need of EXPIRES when CACHE-CONTROL enabled)
		//header('Expires: '.gmdate(DATE_RFC1123,time()+$year));
		//To get best cacheability, send Last-Modified header and ...
		header('Last-Modified: '.gmdate(DATE_RFC1123,filemtime(__file__)));  //i.e.  1467220550 [it's 30 june,2016]
		//reply using: status 304 (with empty body) if browser sends If-Modified-Since header.... This is cheating a bit (doesn't verify the date), but remove if you dont want to be cached forever:
		// if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {  header('HTTP/1.1 304 Not Modified');   die();	}
		header("Content-type: application/javascript;  charset=utf-8");
	}
	
	public function is_cli()
	{
		if( defined('STDIN') ) return true;  
		if( empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0)  return true; 
		return false;
	}
 
	public function init_defaults()
	{
		try{
			//some of this can be overwriten by init_module
			$this->ip				= $this->get_visitor_ip();
			$this->isMobile			= false;
			$this->isWP				= defined("ABSPATH");
			$this->is_cli			= $this->is_cli();
			$this->is_development 	= defined("_puvox_machine_") ;			// set only in devmachine (in "my_superglobals.php" and in "EnvVariables")
			//only web parts
			$this->is_https			= $this->is_cli ? false 	: ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT']==443) );
			$this->https			= $this->is_cli ? 'https://': ( $this->is_https ? 'https://' : 'http://');
			$this->domainCurrent	= $this->is_cli ? '' 		: (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			$this->domain			= $this->is_cli ? '' 		: $this->https . $this->domainCurrent;
			$this->requestURL		= $this->is_cli ? '' 		: $this->array_value($_SERVER,'REQUEST_URI'); $this->requestURI=$this->requestURL;
			$this->currentURL		= $this->is_cli ? '' 		: $this->domain.$this->requestURL; 
			$this->domainCurrentWithoutPort=$this->is_cli ? ''  : $this->array_value( parse_url($this->currentURL),'host');
			$this->is_localhost     = $this->is_cli ? false 	: $this->is_localhost();
			// others 
			$this->empty_image		= "data:image/svg+xml;utf8,&lt;svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'&gt;&lt;/svg&gt;";
			$this->extra_options_enabled=false;
		}
		catch(\Exception $ex){
			$this->var_dump($ex);
		}
	}

	// only when called explicitly, i.e. from plugin or module
	public function init_module($args=[])
	{
		// Because this is a class, we don't use "__FILE__" & "__DIR__" here, but "Reflection" to refer to caller file ####
		$isClass=(array_key_exists('class',$args) && !empty($args['class']));
		$reflection				= $isClass ? (new \ReflectionClass( $args['class'] )) : null; 
		$this->module_NAMESPACE	= $isClass ? $reflection->getNamespaceName() : (array_key_exists('NAMESPACE',$args) ? $args['NAMESPACE'] : "EXAMPLE"); 	// get parent's namespace name
		$this->moduleFILE		= $isClass ? $reflection->getFileName() : (array_key_exists('FILE',$args) ? $args['FILE'] : __FILE__); 		// set plugin's main file path
		$this->moduleDIR		= (array_key_exists('DIR',$args) ? $args['DIR'] : dirname($this->moduleFILE) ).DIRECTORY_SEPARATOR ;  	// set plugin's dir	path
		$this->prefix			= strtolower( preg_replace('![^A-Z]+!', '', $this->module_NAMESPACE) );// get prefix from current namespace initials of UpperCase characters (i.e. MyPluginNamespace-->MPN)
		$this->prefix_			= $this->prefix .'_';
		
		//$backtrace = debug_backtrace(); 	$this->_index_file_		= $backtrace[0]['file'];		$this->_index_dir_		= dirname($this->_index_file_);   
		// if this class is used just as a helper php library
		if (!$this->isWP || array_key_exists('homeFOLDER', $args))
		{
			$this->homeFOLDER 		= $args['homeFOLDER'];
			$this->homeURL 			= $this->domain.$this->homeFOLDER;
			$this->doc_root_real 	= $this->slashesForward(str_replace( $this->homeFOLDER,'',  $this->slashesForward($this->moduleDIR) )); // even for symlinked;
			$this->moduleURL		= str_replace($this->doc_root_real,'',  $this->slashesForward($this->moduleDIR)) ;
		}
		// else, if this class is used as plugin class (used mostly by Puvox.Software)
		else
		{
			$this->wpURL 			= network_home_url('/');						// WP installation home 
			$this->wpFOLDER 		= network_home_url('/', 'relative');			// WP folder 
			$this->homeURL			= home_url('/');								// current sub/site home url
			$this->homeFOLDER		= home_url('/', 'relative');					// current sub/site home folder
			$this->moduleURL		= plugin_dir_url($this->moduleFILE);			//
			$this->plugin_entryfile	= defined( $this->module_NAMESPACE.'\\PLUGIN_ENTRY_FILE') ? constant($this->module_NAMESPACE.'\\PLUGIN_ENTRY_FILE') : $this->moduleFILE;
		} 
		$this->httpsReal		= preg_replace('/(http(s|):\/\/)(.*)/i', '$1', $this->homeURL);
		$this->domainReal		= $this->getDomain($this->homeURL);  $this->domainNaked=$this->domainReal;
		$this->domain			= $this->httpsReal.$this->domainReal;
		$this->domain_schemeless= '//'.$this->domainReal;
		$this->siteslug			= str_ireplace('.','_',   $this->domainReal);
		$this->urlAfterHome		= substr($this->requestURL, strlen($this->homeFOLDER) );
		$this->pathAfterHome	= parse_url($this->urlAfterHome, PHP_URL_PATH);
		$this->homeUrlStripped	= $this->stripUrlPrefixes($this->homeURL); 

		$this->baseFILE			= $this->moduleFILE;								//
		$this->baseDIR			= $this->moduleDIR.'/';								//
		$this->baseURL			= property_exists($this, 'baseURL') ? $this->baseURL : $this->moduleURL; //( stripos(__FILE__, 'wp-content'.DIRECTORY_SEPARATOR.'themes') !== false ? themeURL ... 
		$this->baseScriptsFolder= property_exists($this, 'baseScriptsFolder') ? $this->baseScriptsFolder : '';
		$this->baseScriptsDir	= $this->baseDIR . $this->baseScriptsFolder; 
		$this->baseScriptsUrl	= $this->baseURL . $this->baseScriptsFolder; 
		$this->changeable_JS_CSS_version = ( file_exists($file = $this->baseScriptsDir.'/style-public.css') ? 'date_'.filemtime($file) : $this->sanitize_key($this->domainReal).date('m') );
		
		// others
		$this->is_development 	= defined("_puvox_machine_") ;			// set only in devmachine (in "my_superglobals.php" and in "EnvVariables")
		if ($this->is_development)
		{
			$this->display_errors();
			if (!property_exists($this,'triggered_dev_shutdown_hook'))
			{
				$this->triggered_dev_shutdown_hook=true;
				register_shutdown_function( function(){ if (substr(ob_get_contents(), -7)=='</html>') echo('<div data-debug-memory-limit="'. ini_get('memory_limit').'" data-debug-WP_MEMORY_LIMIT="'. (defined('WP_MEMORY_LIMIT') ? WP_MEMORY_LIMIT : '').'"></div>');} ); 
				$this->START_TIME1 = microtime(true);
				register_shutdown_function( function(){ if (substr(ob_get_contents(), -7)=='</html>') echo('<div data-debug-time-load="'. (microtime(true)-$this->START_TIME1).'"></div>');} );
			}
		} 
	} 
  }
} // class



// Only used if there exists any your custom 'initial_class'
if ( file_exists($extendfile=__DIR__.'/library_extra.php') || file_exists($extendfile=__DIR__.'/../library_extra.php') ) { include_once($extendfile); }
else { if (!class_exists('\\Puvox\\standard_php_library_') ) { 
	class standard_php_library_ extends standard_php_library{
		public function __construct()
		{
			parent::__construct();
		}
	} 
} }

//==========================================================================================================
//==========================================     ### PHP codes     =========================================
//==========================================================================================================