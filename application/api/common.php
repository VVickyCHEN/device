<?php
/**
 * where in 数组为空时返回不存在的字符例如(-10000000000)
 * @param $value
 * @return string
 */
function in_parse_str($value)
{
    if (!$value) {
        $value = '-10000000000';
    }
    return $value;
}
 

/**
 * 功能：生成二维码
 * @param string $qrData 手机扫描后要跳转的网址
 * @param string $qrLevel 默认纠错比例 分为L、M、Q、H四个等级，H代表最高纠错能力
 * @param string $qrSize 二维码图大小，1－10可选，数字越大图片尺寸越大
 * @param string $savePath 图片存储路径
 * @param string $savePrefix 图片名称前缀
 */
function createQRcode($savePath, $qrData = 'PHP QR Code :)', $qrLevel = 'L', $qrSize = 4, $savePrefix = 'qrcode')
{
    if (!isset($savePath)) return '';
    //设置生成png图片的路径
    $PNG_TEMP_DIR = $savePath;
 
    //检测并创建生成文件夹
    if (!file_exists($PNG_TEMP_DIR)) {
        mkdir($PNG_TEMP_DIR);
    }
    $filename = $PNG_TEMP_DIR . 'test.png';
    $errorCorrectionLevel = 'L';
    if (isset($qrLevel) && in_array($qrLevel, ['L', 'M', 'Q', 'H'])) {
        $errorCorrectionLevel = $qrLevel;
    }
    $matrixPointSize = 4;
    if (isset($qrSize)) {
        $matrixPointSize = min(max((int)$qrSize, 1), 10);
    }
    if (isset($qrData)) {
        if (trim($qrData) == '') {
            die('data cannot be empty!');
        }
        //生成文件名 文件路径+图片名字前缀+md5(名称)+.png
        $filename = $PNG_TEMP_DIR . $savePrefix . md5($qrData . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
        //开始生成
        \QRcode::png($qrData, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    } else {
        //默认生成
        \QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    }
    if (file_exists($PNG_TEMP_DIR . basename($filename)))
        return basename($filename);
    else
        return FALSE;
}

 /**
返回json
 */
function Result($status, $msg,$data='') {
         $result['status'] = $status;
         $result['msg'] = $msg;
         $result['data'] = $data;
         exit(json_encode($result));
    }


//校检手机号
function check_phone($phone){if(preg_match('/^1[34578]\d{9}$/', $phone)){return true;}else{return false;}}

/**
通过id获取分类名字
 */
function getcateid($cateid){
    $res = Db::name('article_tree')->where(['id'=>$cateid])->find();
    if($res){
      return $res['name'];
    }else{
      return 'error not find';
    } 
}

/**
通过分类名获取id
 */
function getnames($name){
    $res = Db::name('article_tree')->where(['name'=>$name])->find();
    if($res){
      return $res['id'];
    }else{
      return 'error not find';
    } 
}
/**
通过userid获取组织架构id
 */
function getfids($userid){
    $res = Db::name('admin')->where(['userid'=>$userid])->find();
    if($res){
      return $res['companyid'];
    }else{
      return 'error not find';
    } 
}


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

