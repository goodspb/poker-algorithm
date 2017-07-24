<?php

class NiuNiu
{
    public static $cards = [
        //数字，花色，牛仔中的大小
        [1, 4, 1], [1, 3, 1], [1, 2, 1], [1, 1, 1],
        [2, 4, 2], [2, 3, 2], [2, 2, 2], [2, 1, 2],
        [3, 4, 3], [3, 3, 3], [3, 2, 3], [3, 1, 3],
        [4, 4, 4], [4, 3, 4], [4, 2, 4], [4, 1, 4],
        [5, 4, 5], [5, 3, 5], [5, 2, 5], [5, 1, 5],
        [6, 4, 6], [6, 3, 6], [6, 2, 6], [6, 1, 6],
        [7, 4, 7], [7, 3, 7], [7, 2, 7], [7, 1, 7],
        [8, 4, 8], [8, 3, 8], [8, 2, 8], [8, 1, 8],
        [9, 4, 9], [9, 3, 9], [9, 2, 9], [9, 1, 9],
        [10, 4, 10], [10, 3, 10], [10, 2, 10], [10, 1, 10],
        [11, 4, 10], [11, 3, 10], [11, 2, 10], [11, 1, 10],
        [12, 4, 10], [12, 3, 10], [12, 2, 10], [12, 1, 10],
        [13, 4, 10], [13, 3, 10], [13, 2, 10], [13, 1, 10],
    ];

    protected $nowLeftCards;
    protected $playersNumber;
    protected $playerCards = [];

    public function __construct()
    {
        //创建剩余的牌
        $this->nowLeftCards = self::$cards;
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
     * 获取花色
     * @param $card
     * @return mixed
     */
    protected function getCardColor($card)
    {
        return $card[1];
    }

    /**
     * 获取牛牛中数值
     * @param $card
     * @return mixed
     */
    protected function getCardValue($card)
    {
        return $card[2];
    }

    /**
     * 获取剩余的牌
     * @return array
     */
    public function getLeftCards()
    {
        return $this->nowLeftCards;
    }

    /**
     * 获取玩家的牌
     * @return array
     */
    public function getPlayersCards()
    {
        return $this->playerCards;
    }

    /**
     * 批量设置玩家手牌
     * @param array $cards
     * @return array
     */
    public function setPlayersCards(array $cards)
    {
        foreach ($cards as $player => $card) {
            $this->setPlayerCard($player, $card);
        }
        return $this->playerCards;
    }

    /**
     * 设置玩家手牌
     * @param string $playerName
     * @param array $cards
     */
    public function setPlayerCard($playerName, array $cards)
    {
        $playerCards = [];
        foreach ($cards as $card) {
            $hasThisCard = false;
            $unsetKey = null;
            foreach ($this->nowLeftCards as $nowLeftCardKey => $nowLeftCard) {
                if ($card == $nowLeftCard) {
                    $hasThisCard = true;
                    $unsetKey = $nowLeftCardKey;
                }
            }
            if ($hasThisCard) {
                $playerCards[] = $card;
                unset($this->nowLeftCards[$unsetKey]);
            }
        }
        $this->playerCards[$playerName] = $playerCards;
    }

    /**
     * 设置忽略的牌
     * @param array $value
     * @return array
     */
    public function setExclude(array $value)
    {
        foreach ($this->nowLeftCards as $key => $nowLeftCard) {
            if ($nowLeftCard == $value) {
                unset($this->nowLeftCards[$key]);
            }
        }
        return $this->nowLeftCards;
    }

    /**
     * 随机生成玩家 & 牌
     * @param int $playerNumbers
     * @return array
     */
    public function generate($playerNumbers = 3)
    {
        $this->playerCards = [];
        //洗牌
        shuffle($this->nowLeftCards);
        for ($i = 1; $i <= $playerNumbers; $i++) {
            $needToRand = 5;
            $this->playerCards["player_{$i}"] = array_splice($this->nowLeftCards, 0, $needToRand);
        }
        return $this->playerCards;
    }

    /**
     * 执行计算
     * @return array
     */
    public function execute()
    {
        $result = [];
        foreach ($this->playerCards as $player => &$playerCard) {
            //按照从大到小排序
            $this->cardsSort($playerCard);
            $result[] = [
                'name' => $player,
                'shape' => $this->judge($playerCard),
                'cards' => $playerCard,
            ];
        }
        //计算结果
        $this->sortResult($result);
        return $result;
    }

    /**
     * 计算结果
     * @param $result
     */
    public function sortResult(&$result)
    {
        usort($result, function($value, $next) {
            //当牌型相同的时候，比较牌的大小和卡
            if ($value['shape'] == $next['shape']) {
                return $this->compareNumberAndColor($value['cards'], $next['cards']);
            }
            return $value['shape'] < $next['shape'] ? 1 : -1;
        });
    }

    /**
     * 比较2张牌的大小，一次比较单牌的大小，如单牌牌面都相同，比较最大单牌的花色
     * @param $first
     * @param $second
     * @return bool|int
     */
    public function compareNumberAndColor($first, $second)
    {
        foreach ($first as $key => $value) {
            if (($firstNumber = $this->getCardNumber($value)) < ($secondNumber = $this->getCardNumber($second[$key]))) {
                return 1;
            } elseif ($firstNumber > $secondNumber) {
                return -1;
            }
        }
        //当所有的牌都是等于的时候，比较最大单牌的花色
        return $this->getCardColor($first[0]) < $this->getCardColor($second[0]) ? 1 : -1;
    }

    /**
     * 判断牌型
     * @param $cards
     * @return int 0：没有牛 | 牛1~9：1~9 |  10：牛牛 |  11：4花牛 | 12： 5花牛 | 13：炸弹 | 14：五小牛
     */
    public function judge($cards)
    {
        $numbers = [];
        $smallerThanFive = 0;
        foreach ($cards as $card) {
            $numbers[] = $cardNumber = $this->getCardNumber($card);
            if ($cardNumber < 5) {
                $smallerThanFive++;
            }
        }
        // 5小牛
        if (array_sum($numbers) < 10 && $smallerThanFive == 5) {
            return 14;
        }
        // 炸弹， 4张牌进行排列
        $fourCardArrangements = $this->arrangement($numbers, 4);
        foreach ($fourCardArrangements as $fourCardArrangement) {
            //去重值, 如果只剩下1个, 证明4张牌相同
            if (count(array_unique($fourCardArrangement)) == 1) {
                return 13;
            }
        }
        //所有卡牌的数字总和
        $allCardSum = $this->cardsSum($cards);
        //计算牛牛，3张牌一组进行排列
        $arrangements = $this->arrangement($cards, 3);
        //初始化结果，没有牛
        $result = 0;
        foreach ($arrangements as $arrangement) {
            $sum = $this->cardsSum($arrangement);
            //有牛
            if ($sum % 10 ==  0) {
                $left = ($allCardSum - $sum) % 10;
                //牛牛
                if ($left == 0 ) {
                    $biggerThanEleven = 0;
                    $biggerThanTen = 0;
                    //所有牌的牌面数字
                    $numbers = [];
                    foreach ($cards as $card) {
                        $numbers[] = $cardNumber = $this->getCardNumber($card);
                        if ($cardNumber >= 11) {
                            $biggerThanEleven++;
                            $biggerThanTen++;
                        } elseif($cardNumber == 10) {
                            $biggerThanTen++;
                        }
                    }
                    // 5花牛
                    if ($biggerThanEleven == 5) {
                        return 12;
                    }
                    // 4花牛
                    if ($biggerThanTen == 5) {
                        return 11;
                    }
                    //普通牛牛
                    return 10;
                }
                //牛1~9
                else {
                    //当前值大于另外的组合的值，则代替
                    if ($left >= $result) {
                        $result = $left;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 卡牌求和
     * @param $cards
     * @return int
     */
    protected function cardsSum($cards)
    {
        $sum = 0;
        foreach ($cards as $card) {
            $sum += $this->getCardValue($card);
        }
        return $sum;
    }

    /**
     * 从大到小的排序，包括牌面、花色
     * @param $cards
     */
    protected function cardsSort(&$cards)
    {
        usort($cards, function($value, $next) {
            //先判断牌面, 当牌面相同，再判断花色
            if ($value[0] == $next[0]) {
                return $value[1] == $next[1] ? 0 : ($value[1] < $next[1] ? 1 : -1);
            }
            return $value[0] < $next[0] ? 1 : -1;
        });
    }

    /**
     * 排列
     * @param $array
     * @param $number
     * @return array
     */
    protected function arrangement($array, $number = 3)
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
