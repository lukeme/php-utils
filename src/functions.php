<?php

//调试打印php变量
if(!function_exists('dump')){
	function dump() {
		static $i = 1;
		if(func_num_args()>0){
			foreach(func_get_args() as $arg){
				$out[] = var_export($arg, true);
			}
			$tpl = str_repeat("\n%s",func_num_args());
			if(PHP_SAPI == 'cli'){
				echo vsprintf("#%s$tpl", array_merge([$i++], $out));
			}else{
				echo vsprintf("<pre><b>#%s</b>$tpl</pre>", array_merge([$i++], $out));
			}
		}else{
			die('call function dump without any params');
		}
	}
}

//是否是身份证
if(!function_exists('is_idcard')){
	function is_idcard($id)
	{
	    $id = strtoupper($id);
	    $regx = "/(^\d{15}$)|(^\d{17}([0-9X])$)/";
	    if (!preg_match($regx, $id)) {
		return false;
	    } else if (15 === strlen($id)) {
		$regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
		preg_match($regx, $id, $birthday);
		return !strtotime($birthday[1]); //检查生日日期
	    } else {
		$regx = "/^\d{6}(\d{8})\d{3}[0-9X]$/";
		preg_match($regx, $id, $birthday);
		if (!strtotime($birthday[1])) //检查生日日期是否正确
		{
		    return false;
		} else {
		    //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
		    $powerArr = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
		    $checkArr = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
		    $sign = 0;
		    for ($i = 0; $i < 17; $i++) {
			$b = (int)$id{$i};
			$w = $powerArr[$i];
			$sign += $b * $w;
		    }
		    return $checkArr[$sign % 11] === $id{17};
		}
	    }
	}
}
