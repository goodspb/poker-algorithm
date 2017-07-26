<?php

/**
 * 德州扑克
 * Class Texas
 */
class Texas
{
    protected static $cards = [
        //数字，花色
        [1, 4], [1, 3], [1, 2], [1, 1],
        [2, 4], [2, 3], [2, 2], [2, 1],
        [3, 4], [3, 3], [3, 2], [3, 1],
        [4, 4], [4, 3], [4, 2], [4, 1],
        [5, 4], [5, 3], [5, 2], [5, 1],
        [6, 4], [6, 3], [6, 2], [6, 1],
        [7, 4], [7, 3], [7, 2], [7, 1],
        [8, 4], [8, 3], [8, 2], [8, 1],
        [9, 4], [9, 3], [9, 2], [9, 1],
        [10, 4], [10, 3], [10, 2], [10, 1],
        [11, 4], [11, 3], [11, 2], [11, 1],
        [12, 4], [12, 3], [12, 2], [12, 1],
        [13, 4], [13, 3], [13, 2], [13, 1],
    ];

    /**
     * @var array 玩家牌
     */
    protected $playerCards = [];

    /**
     * @var array 公共牌
     */
    protected $publicCards;

    /**
     * @var array 剩余牌堆
     */
    protected $nowLeftCards;

    public function __construct()
    {
        $this->nowLeftCards = self::$cards;
        //洗牌
        shuffle($this->nowLeftCards);
    }

    /**
     * 获取牌面数字
     * @param $card
     * @return mixed
     */
    protected function getCardNumber($card)
    {
        return $card[0];
    }

    /**
     * 获取所有牌的牌面
     * @param $cards
     * @return array
     */
    protected function getCardNumbers($cards)
    {
        $numbers = [];
        foreach ($cards as $card) {
            $numbers[] = $this->getCardNumber($card);
        }
        return $numbers;
    }

    /**
     * 获取花色
     * @param $card
     * @return mixed
     */
    protected function getCardColor($card)
    {
        return $card[1];
    }

    /**
     * 获取所有牌的花色
     * @param $cards
     * @return array
     */
    protected function getCardColors($cards)
    {
        $colors = [];
        foreach ($cards as $card) {
            $colors[] = $this->getCardColor($card);
        }
        return $colors;
    }

    /**
     * 获取公共牌
     * @return array
     */
    public function getPublicCards()
    {
        return $this->publicCards;
    }

    /**
     * 设置公共牌
     * @param $publicCards
     * @return mixed
     */
    public function setPublicCards($publicCards)
    {
        return $this->publicCards = $publicCards;
    }

    /**
     * 获取玩家牌
     * @param string $player 玩家 key
     * @return array
     */
    public function getPlayersCards($player = null)
    {
        return is_null($player) ? $this->playerCards : (isset($this->playerCards[$player]) ? $this->playerCards[$player] : []);
    }

    /**
     * 随机生成玩家牌 & 公共牌
     * @param int  $playerNumbers 玩家数量
     * @param bool $needPublic 是否生成公共牌
     */
    public function generate($playerNumbers = 2, $needPublic = true)
    {
        $needPublic and $this->publicCards = array_splice($this->nowLeftCards, 0, 5);
        $beginAt = count($this->playerCards);
        for ($i = 1; $i <= $playerNumbers; $i++) {
            $player = "player_" . ($beginAt + $i);
            $this->playerCards[$player] = array_splice($this->nowLeftCards, 0, 2);
        }
    }

    /**
     * @param array $player1
     * @param array $player2
     * @return array 'result' = 0 相同，1 前者小，-1 后者小
     */
    public function comparePlayer(array $player1, array $player2)
    {
        $player1Biggest = $this->getBiggestCardFromPlayerAndPublic($player1);
        $player2Biggest = $this->getBiggestCardFromPlayerAndPublic($player2);
        $type1 = $this->judge($player1Biggest);
        $type2 = $this->judge($player2Biggest);
        $result = $type1 < $type2 ? 1 : -1;
        if ($type1 == $type2) {
            $result = $this->compareTwoEqualType($type1, $player1Biggest, $player2Biggest);
        }
        return [
            'result' => $result,
            'player_1' => [
                'cards' => $player1Biggest,
                'type' => $type1
            ],
            'player_2' => [
                'cards' => $player2Biggest,
                'type' => $type2
            ],
        ];
    }

    /**
     * 从玩家和公共牌的组合中选出最优解
     * @param array $player
     * @param array $public
     * @return mixed
     */
    public function getBiggestCardFromPlayerAndPublic(array $player, array $public = [])
    {
        $public = empty($public) ? $this->publicCards : $public;
        $combinations = $this->getUniqueCombinationWithSort(array_merge($player, $public));
        usort($combinations, function ($first, $next) {
            //先比较牌型
            $firstType = $this->judge($first);
            $nextType = $this->judge($next);
            if ($firstType == $nextType) {
                return $this->compareTwoEqualType($firstType, $first, $next);
            }
            return $firstType < $nextType ? 1 : -1;
        });
        return reset($combinations);
    }

    /**
     * 相同牌型的比较的牌的大小
     * @param int $type
     * @param array $cards1
     * @param array $cards2
     * @return int 0 相同，1 前者小，-1 后者小
     */
    public function compareTwoEqualType($type, array $cards1, array $cards2)
    {
        switch ($type) {
            case 10://皇家同花顺
                return 0;
            case 9://同花顺
            case 5://顺子
                return $this->compareFlushNumber($this->getCardNumbers($cards1), $this->getCardNumbers($cards2));
            case 8://四条
                $same = 4;
            case 7://葫芦
                $same = isset($same) ? $same : 3;
                $left = 5 - $same;
                $cards1SameCount = $cards2SameCount = [];
                $this->checkSame($this->getCardNumbers($cards1), $same, $cards1SameCount);
                $this->checkSame($this->getCardNumbers($cards2), $same, $cards2SameCount);
                $_temp1 = array_flip($cards1SameCount);
                $_temp2 = array_flip($cards2SameCount);
                //先比较4/3张牌是否相同，如果相同，就比较最后1/2张牌的大小
                if ($_temp1[$same] == $_temp2[$same]) {
                    return $_temp1[$left] == $_temp2[$left] ? 0 : ($_temp1[$left] < $_temp2[$left] ? 1 : -1);
                }
                return $_temp1[$same] < $_temp2[$same] ? 1 : -1;
            case 6://同花
            case 1://高牌
                return $this->compareNumber($this->getCardNumbers($cards1), $this->getCardNumbers($cards2));
            case 4://三条
                $same = 3;
            case 2://一对
                $same = isset($same) ? $same : 2;
                $cards1SameCount = $cards2SameCount = [];
                $this->checkSame($this->getCardNumbers($cards1), $same, $cards1SameCount);
                $this->checkSame($this->getCardNumbers($cards2), $same, $cards2SameCount);
                $_temp1 = array_flip($cards1SameCount);
                $_temp2 = array_flip($cards2SameCount);
                //先比较3/2张牌是否相同，如果相同，就比较最后2张牌的大小
                if ($_temp1[$same] == $_temp2[$same]) {
                    return $this->compareNumber(array_unique($this->getCardNumbers($cards1)), array_unique($this->getCardNumbers($cards2)));
                }
                return $_temp1[$same] < $_temp2[$same] ? 1 : -1;
            case 3://两对
                $cards1SameCount = $cards2SameCount = [];
                $this->checkSame($this->getCardNumbers($cards1), 2, $cards1SameCount);
                $this->checkSame($this->getCardNumbers($cards2), 2, $cards2SameCount);
                $two1 = $two2 = [];
                $one1 = $one2 = [];
                foreach ($cards1SameCount as $key1 => $value1) {
                    if ($value1 == 1){
                        $one1[] = $key1;
                    } elseif ($value1 == 2) {
                        $two1[] = $key1;
                    }
                }
                foreach ($cards2SameCount as $key2 => $value2) {
                    if ($value2 == 1){
                        $one2[] = $key2;
                    } elseif ($value2 == 2) {
                        $two2[] = $key2;
                    }
                }
                //先比较2对的大小
                if (0 == $compare2Res = $this->compareNumber($two1, $two2)) {
                    //如果相同，再比较单牌的大小
                    return $this->compareNumber($one1, $one2);
                }
                return $compare2Res;
        }
    }

    /**
     * 比较非顺子以外牌型的 2个牌面的大小
     * @param array $numbers1
     * @param array $numbers2
     * @return int  0 相同，1 前者小，-1 后者小
     */
    public function compareNumber(array $numbers1, array $numbers2)
    {
        //做一个大小的映射, A最大，2最小
        $order = array_flip([2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 1]);
        //排序
        $orderClosure = function ($first, $second) use ($order) {
            return $order[$first] == $order[$second] ? 0 : ($order[$first] < $order[$second] ? 1 : -1);
        };
        usort($numbers1, $orderClosure);
        usort($numbers2, $orderClosure);
        foreach ($numbers1 as $key => $value) {
            if ($order[$value] < $order[$numbers2[$key]]) {
                return 1;
            } elseif ($order[$value] > $order[$numbers2[$key]]) {
                return -1;
            }
        }
        return 0;
    }

    /**
     * 比较顺子的 2个牌面的大小
     * @param array $numbers1
     * @param array $numbers2
     * @return int  0 相同，1 前者小，-1 后者小
     *
     * A在做顺子牌型的时候，只会出现在顺首和顺尾，并且会有截然不同的效果。比如下面的例子：
     * A、K、Q、J、10 在此牌型中“A”为顺尾，它作为最大的牌使用。
     * 5、4、3、2、A 在此牌型中“A”为顺首，它作为最小的牌使用。
     * 比如： 5、4、3、2、A 就比 6、5、4、3、2 要小。
     *
     */
    public function compareFlushNumber(array $numbers1, array $numbers2)
    {
        $orderClosure = function ($first, $second) {
            return $first == $second ? 0 : ($first < $second ? 1 : -1);
        };
        usort($numbers1, $orderClosure);
        $numbers1 == [13, 12, 11, 10, 1] and $numbers1 = [14, 13, 12, 11, 10];
        usort($numbers2, $orderClosure);
        $numbers2 == [13, 12, 11, 10, 1] and $numbers2 = [14, 13, 12, 11, 10];
        foreach ($numbers1 as $key => $value) {
            if ($value < $numbers2[$key]) {
                return 1;
            } elseif ($value > $numbers2[$key]) {
                return -1;
            }
        }
        return 0;
    }

    /**
     * 判断牌型
     * @param array $cards 从大到小排序过的牌
     * @return int 10:皇家同花顺 | 9:同花顺 | 8:四条 | 7:葫芦 | 6:同花 | 5:顺子 | 4:三条 | 3:两对 | 2:一对 | 1:高牌
     */
    public function judge(array $cards)
    {
        $numbers = $this->getCardNumbers($cards);
        $color = $this->getCardColors($cards);
        //皇家同花顺
        if ($numbers == [13, 12, 11, 10, 1]) {
            return 10;
        }
        //同花顺
        if ($this->checkStraight($numbers) && $this->checkFlush($color)) {
            return 9;
        }
        //四条
        if ($this->checkSame($numbers, 4) == 1) {
            return 8;
        }
        //葫芦, 有1个3条 和 2个2条
        if ($this->checkSame($numbers, 3) == 1 && $this->checkSame($numbers, 2) == 2) {
            return 7;
        }
        //同化
        if ($this->checkFlush($color)) {
            return 6;
        }
        //顺子
        if ($this->checkStraight($numbers)) {
            return 5;
        }
        //三条
        if ($this->checkSame($numbers, 3) == 1) {
            return 4;
        }
        //两对
        if ($this->checkSame($numbers, 2) == 2) {
            return 3;
        }
        //一对
        if ($this->checkSame($numbers, 2) == 1) {
            return 2;
        }
        //高牌
        return 1;
    }

    /**
     * 检查是否相同牌面，可判断 4条，3条，2条
     * @param array $numbers
     * @param int   $same
     * @param array $sameCounts 相同的结果
     * @return int
     */
    public function checkSame(array $numbers, $same = 4, array &$sameCounts = [])
    {
        // 桶方法
        foreach ($numbers as $number) {
            if (!isset($sameCounts[$number])) {
                $sameCounts[$number] = 1;
            } else {
                $sameCounts[$number]++;
            }
        }
        $sameNumber = 0;
        foreach ($sameCounts as $key => $sameCount) {
            if (($ceil = (int)($sameCount / $same)) > 0) {
                $sameNumber += $ceil;
            }
        }
        return $sameNumber;
    }

    /**
     * 检查是不是 顺子
     * @param $numbers
     * @return bool
     */
    public function checkStraight($numbers)
    {
        $isStraight = true;
        foreach ($numbers as $key => $number) {
            $nextKey = $key + 1;
            if (isset($numbers[$nextKey]) && ($number - $numbers[$nextKey] != 1)) {
                $isStraight = false;
                break;
            }
        }
        return $isStraight;
    }

    /**
     * 检查是不是 同花
     * @param $color
     * @return bool
     */
    public function checkFlush($color)
    {
        return count(array_unique($color)) == 1;
    }

    /**
     * 获取唯一的排列
     * @param array $cards
     * @param int   $number
     * @return array
     */
    public function getUniqueCombinationWithSort(array $cards, $number = 5)
    {
        $combinations = $this->arrangement($cards, $number);
        $result = [];
        foreach ($combinations as $combination) {
            $unique = true;
            $this->sortCard($combination);
            foreach ($result as $item) {
                if ($item == $combination) {
                    $unique = false;
                    break;
                }
            }
            if ($unique) {
                $result[] = $combination;
            }
        }
        return $result;
    }

    /**
     * 根据牌面，花色的大小从大到小排序
     * @param array  $cards 牌
     * @param bool   $sortColor 是否比较花色
     * @param string $sort 降序（desc)，还是升序(asc)
     */
    protected function sortCard(array &$cards, $sortColor = true, $sort = 'desc')
    {
        $desc = strtolower($sort) == 'desc';
        usort($cards, function ($value, $next) use ($sortColor, $desc) {
            $number = $this->getCardNumber($value);
            $nextNumber = $this->getCardNumber($next);
            if ($number == $nextNumber) {
                // 比较花色
                if ($sortColor) {
                    return $this->getCardColor($value) < $this->getCardColor($next) ? ($desc ? 1 : -1) : ($desc ? -1 : 1);
                }
                return 0;
            }
            return $number < $nextNumber ? ($desc ? 1 : -1) : ($desc ? -1 : 1);
        });
    }

    /**
     * 排列
     * @param $array
     * @param $number
     * @return array
     */
    public function arrangement($array, $number = 5)
    {
        $result = [];
        $count = count($array);
        if ($number <= 0 || $number > $count) {
            return $result;
        }
        for ($i = 0; $i < $count; $i++) {
            $_temp = $array;
            //每次取一个数字，并从数组中删除
            $single = array_splice($_temp, $i, 1);
            if ($number == 1) {
                $result[] = $single;
            } else {
                $deep = $this->arrangement($_temp, $number - 1);
                foreach ($deep as $deepItem) {
                    $result[] = array_merge($single, $deepItem);
                }
            }
        }
        return $result;
    }
}
