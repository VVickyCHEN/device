<?php

// +----------------------------------------------------------------------
// | 后台函数文件
// +----------------------------------------------------------------------

/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */

function encrypt_password($password, $encrypt = '')
{
    $pwd = array();
    $pwd['encrypt'] = $encrypt ? $encrypt : genRandomString();
    $pwd['password'] = md5(trim($password) . $pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}
/**
p
 */
function p($val)
{
   print_r('<pre>');
   print_r($val);
   print_r('</pre>');
}
//获取字符串里面的数字
function ast($str){
return preg_match('/([0-9]{8})/',$str,$a) ? $a[1] : 0;
}

//判断奇数偶数
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}
//校检手机号
function check_phone($phone){if(preg_match('/^1[34578]\d{9}$/', $phone)){return true;}else{return false;}}
/**
通过id获取手机号
 */
function gets_phones($id){
   return db('member')->where('id',$id)->value('phone');
}
/**
通过商品分类id获取分类名
 */
function gettypename($cid){
   $name = db('goods_class')->where('id',$cid)->value('name');
   if($name){
       return $name;
   }else{
       return '未找到';
   }
}
/**
通过id获取会员名
 */
function getmembername($mid){
   return db('member')->where('id',$mid)->value('name');

}
/**
/**
返回json
 */
function Result($status, $msg,$data='') {
         $result['status'] = $status;
         $result['msg'] = $msg;
         $result['data'] = $data;
         exit(json_encode($result));
    }

/**
返回树形结构json
 */
function TreeResult($code, $msg,$data='',$count='') {
         $result['code'] = $code;
         $result['msg'] = $msg;
         $result['data'] = $data;
         $result['count'] = $count;
         exit(json_encode($result));
}
    //获取整条字符串所有汉字拼音首字母
    function pinyin_long($zh){
        $ret = "";
        $s1 = iconv("UTF-8","GBK//IGNORE", $zh);
        $s2 = iconv("GBK","UTF-8", $s1);
        if($s2 == $zh){$zh = $s1;}
        for($i = 0; $i < strlen($zh); $i++){
            $s1 = substr($zh,$i,1);
            $p = ord($s1);
            if($p > 160){
                $s2 = substr($zh,$i++,2);
                $ret .= getfirstchar($s2);
            }else{
                $ret .= $s1;
            }
        }
        return $ret;
    }

    function tranTime($time){
            $rtime = date("m-d H:i",$time);
            $htime = date("H:i",$time);
                  
            $time = time() - $time;
                  
            if ($time < 60)
            {
                $str = '刚刚';
            }
            elseif ($time < 60 * 60)
            {
                $min = floor($time/60);
                $str = $min.'分钟前';
            }
            elseif ($time < 60 * 60 * 24)
            {
                $h = floor($time/(60*60));
                $str = $h.'小时前 '.$htime;
            }
            elseif ($time < 60 * 60 * 24 * 3)
            {
                $d = floor($time/(60*60*24));
                if($d==1)
                    $str = '昨天 '.$rtime;
                else
                    $str = '前天 '.$rtime;
            }
            else
            {
                $str = $rtime;
            }
            return $str;
        }

    //获取单个汉字拼音首字母。注意:此处不要纠结。汉字拼音是没有以U和V开头的
/**
 * 取汉字的第一个字的首字母
 * @param string $str
 * @return string|null
 */
function getFirstChar($str) {
    if (empty($str)) {
        return '';
    }
 
    $fir = $fchar = ord($str[0]);
    if ($fchar >= ord('A') && $fchar <= ord('z')) {
        return strtoupper($str[0]);
    }
 
    $s1 = @iconv('UTF-8', 'gb2312//IGNORE', $str);
    $s2 = @iconv('gb2312', 'UTF-8', $s1);
    $s = $s2 == $str ? $s1 : $str;
    if (!isset($s[0]) || !isset($s[1])) {
        return '';
    }
 
    $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
 
    if (is_numeric($str)) {
        return $str;
    }
 
    if (($asc >= -20319 && $asc <= -20284) || $fir == 'A') {
        return 'a';
    }
    if (($asc >= -20283 && $asc <= -19776) || $fir == 'B') {
        return 'b';
    }
    if (($asc >= -19775 && $asc <= -19219) || $fir == 'C') {
        return 'c';
    }
    if (($asc >= -19218 && $asc <= -18711) || $fir == 'D') {
        return 'd';
    }
    if (($asc >= -18710 && $asc <= -18527) || $fir == 'E') {
        return 'e';
    }
    if (($asc >= -18526 && $asc <= -18240) || $fir == 'F') {
        return 'f';
    }
    if (($asc >= -18239 && $asc <= -17923) || $fir == 'G') {
        return 'g';
    }
    if (($asc >= -17922 && $asc <= -17418) || $fir == 'H') {
        return 'h';
    }
    if (($asc >= -17417 && $asc <= -16475) || $fir == 'J') {
        return 'j';
    }
    if (($asc >= -16474 && $asc <= -16213) || $fir == 'K') {
        return 'k';
    }
    if (($asc >= -16212 && $asc <= -15641) || $fir == 'L') {
        return 'l';
    }
    if (($asc >= -15640 && $asc <= -15166) || $fir == 'M') {
        return 'm';
    }
    if (($asc >= -15165 && $asc <= -14923) || $fir == 'N') {
        return 'n';
    }
    if (($asc >= -14922 && $asc <= -14915) || $fir == 'O') {
        return 'o';
    }
    if (($asc >= -14914 && $asc <= -14631) || $fir == 'P') {
        return 'p';
    }
    if (($asc >= -14630 && $asc <= -14150) || $fir == 'Q') {
        return 'q';
    }
    if (($asc >= -14149 && $asc <= -14091) || $fir == 'R') {
        return 'r';
    }
    if (($asc >= -14090 && $asc <= -13319) || $fir == 'S') {
        return 's';
    }
    if (($asc >= -13318 && $asc <= -12839) || $fir == 'T') {
        return 't';
    }
    if (($asc >= -12838 && $asc <= -12557) || $fir == 'W') {
        return 'w';
    }
    if (($asc >= -12556 && $asc <= -11848) || $fir == 'X') {
        return 'x';
    }
    if (($asc >= -11847 && $asc <= -11056) || $fir == 'Y') {
        return 'y';
    }
    if (($asc >= -11055 && $asc <= -10247) || $fir == 'Z') {
        return 'z';
    }
 
    return '';
}
// function getFirstChars($str) {
//     if (empty($str)) {
//         return '';
//     }
 
//     $fir = $fchar = ord($str[0]);
//     if ($fchar >= ord('A') && $fchar <= ord('z')) {
//         return strtoupper($str[0]);
//     }
 
//     $s1 = @iconv('UTF-8', 'gb2312', $str);
//     $s2 = @iconv('gb2312', 'UTF-8', $s1);
//     $s = $s2 == $str ? $s1 : $str;
//     if (!isset($s[0]) || !isset($s[1])) {
//         return '';
//     }
 
//     $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
 
//     if (is_numeric($str)) {
//         return $str;
//     }
 
//     if (($asc >= -20319 && $asc <= -20284) || $fir == 'A') {
//         return 'A';
//     }
//     if (($asc >= -20283 && $asc <= -19776) || $fir == 'B') {
//         return 'B';
//     }
//     if (($asc >= -19775 && $asc <= -19219) || $fir == 'C') {
//         return 'C';
//     }
//     if (($asc >= -19218 && $asc <= -18711) || $fir == 'D') {
//         return 'D';
//     }
//     if (($asc >= -18710 && $asc <= -18527) || $fir == 'E') {
//         return 'E';
//     }
//     if (($asc >= -18526 && $asc <= -18240) || $fir == 'F') {
//         return 'F';
//     }
//     if (($asc >= -18239 && $asc <= -17923) || $fir == 'G') {
//         return 'G';
//     }
//     if (($asc >= -17922 && $asc <= -17418) || $fir == 'H') {
//         return 'H';
//     }
//     if (($asc >= -17417 && $asc <= -16475) || $fir == 'J') {
//         return 'J';
//     }
//     if (($asc >= -16474 && $asc <= -16213) || $fir == 'K') {
//         return 'K';
//     }
//     if (($asc >= -16212 && $asc <= -15641) || $fir == 'L') {
//         return 'L';
//     }
//     if (($asc >= -15640 && $asc <= -15166) || $fir == 'M') {
//         return 'M';
//     }
//     if (($asc >= -15165 && $asc <= -14923) || $fir == 'N') {
//         return 'N';
//     }
//     if (($asc >= -14922 && $asc <= -14915) || $fir == 'O') {
//         return 'O';
//     }
//     if (($asc >= -14914 && $asc <= -14631) || $fir == 'P') {
//         return 'P';
//     }
//     if (($asc >= -14630 && $asc <= -14150) || $fir == 'Q') {
//         return 'Q';
//     }
//     if (($asc >= -14149 && $asc <= -14091) || $fir == 'R') {
//         return 'R';
//     }
//     if (($asc >= -14090 && $asc <= -13319) || $fir == 'S') {
//         return 'S';
//     }
//     if (($asc >= -13318 && $asc <= -12839) || $fir == 'T') {
//         return 'T';
//     }
//     if (($asc >= -12838 && $asc <= -12557) || $fir == 'W') {
//         return 'W';
//     }
//     if (($asc >= -12556 && $asc <= -11848) || $fir == 'X') {
//         return 'X';
//     }
//     if (($asc >= -11847 && $asc <= -11056) || $fir == 'Y') {
//         return 'Y';
//     }
//     if (($asc >= -11055 && $asc <= -10247) || $fir == 'Z') {
//         return 'Z';
//     }
 
//     return '';
// }

/**
 * 产生一个指定长度的随机字符串,并返回给用户
 * @param type $len 产生字符串的长度
 * @return string 随机字符串
 */
function genRandomString($len = 6)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9",
    );
    $charsLen = count($chars) - 1;
    // 将数组打乱
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}



