# 扑克游戏算法 for php

* [牛牛算法规则](/doc/niuniu.md)
* [德州扑克算法规则](/doc/texas.md)
* [三公算法规则](/doc/sangong.md)

## 使用

```php
<?php

include __DIR__ . 'vendor/autoload.php';


/*********  牛牛例子  *********/
//创建实例
$niu = new \Goodspb\PokerAlgorithm\Games\NiuNiu();
//随机生成3位玩家， 2种方式二选一，但是如果手工填入的话，需要自己管理随机性和牌的唯一性
$niu->generate(3);
//手工填入2位玩家
//$niu->setPlayerCard('player_1', [[1, 4, 1], [2, 4, 2], [3, 4, 3], [4, 4, 4], [5, 2, 5]]);
//$niu->setPlayerCard('player_2', [[1, 3, 1], [2, 3, 2], [3, 3, 3], [4, 3, 4], [5, 3, 5]]);

//执行计算
$result = $niu->execute();
$players = $niu->getPlayersCards();

//输出剩余的牌
//var_dump($niu->getLeftCards());

//输出玩家牌
var_dump($players);

//输出结果
echo '<pre>';
print_r($result);


/*********  德州扑克例子  *********/
$texas = new \Goodspb\PokerAlgorithm\Games\Texas();
//生成2个玩家的牌 和 公共牌
$texas->generate(2);
//输出玩家牌 和 公共牌
var_dump($texas->getPlayersCards(), $texas->getPublicCards());
//输出比较结果
echo "<pre>";
print_r($texas->comparePlayer($texas->getPlayersCards()['player_1'], $texas->getPlayersCards()['player_2']));
```

## 问题

欢迎 issues 哦。
