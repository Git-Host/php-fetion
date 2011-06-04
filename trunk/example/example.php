<?php
require '../lib/PHPFetion.php';

$// 飞信手机号、飞信密码
fetion = new PHPFetion('13500001111', '123123');

// 接收人手机号、飞信内容
echo $fetion->send('13500001111', 'Hello!'); 
