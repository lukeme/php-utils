<?php

# 要同步xx日期的数据
$day = '2021-10-12';
$s = $day . ' 10:23:00';
$e = date('Y-m-d 00:00:00', strtotime($day)+86400);

# 要同步的表
$tables = ['xx', 'xx2'];

#步长与步数
$size = 600;
$step = ceil((strtotime($e) - strtotime($s))/$size);

foreach ($tables as $tbl) {
    for ($i = 0, $ss = $s; $i < $step; $i++) {
        $ss = strtotime($ss);
        $s2 = date('Y-m-d H:i:s', $ss);
        $e2 = date('Y-m-d H:i:s', $ss + $size);
        if ($e2 > $e) {
            $e2 = $e;
        }
        $ss = $e2;
        echo $s2,' ',$e2,PHP_EOL;
        $sql = sprintf("select * from %s where created_at>='%s' and created_at<'%s'", $tbl, $s2, $e2);

        echo $sql, PHP_EOL;
        if($s2 >= $e2){
          break;
        }
    }
}
