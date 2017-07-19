<?php

include __DIR__ . 'NiuNiu.php';

$time = microtime(true);

//创建实例
$niu = new NiuNiu();

//随机生成3位玩家， 2种方式二选一，但是如果手工填入的话，需要自己管理随机性和牌的唯一性
$niu->generate(3);
//手工填入2位玩家
//$niu->setPlayerCard('player_1', [[1, 4, 1], [2, 4, 2], [3, 4, 3], [4, 4, 4], [5, 2, 5]]);
//$niu->setPlayerCard('player_2', [[1, 3, 1], [2, 3, 2], [3, 3, 3], [4, 3, 4], [5, 3, 5]]);

//执行计算
$result = $niu->execute();
$players = $niu->getPlayersCards();
$time2 = microtime(true);

//输出剩余的牌
//var_dump($niu->getLeftCards());

//输出玩家牌
var_dump($players);

//输出结果
echo '<pre>';
print_r($result);

//计算执行时间
var_dump(round($time2 - $time, 4) . '秒');

//计算占用内存
var_dump(round(memory_get_usage() / (1024 * 1024), 4) . 'MB');
