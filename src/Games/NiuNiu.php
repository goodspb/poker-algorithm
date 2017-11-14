<?php
namespace Goodspb\PokerAlgorithm\Games;

use Goodspb\PokerAlgorithm\Poker;
use Goodspb\PokerAlgorithm\Traits\CardWithValue;

/**
 * 牛牛
 * Class NiuNiu
 */
class NiuNiu extends Poker
{
    use CardWithValue;

    /**
     * 执行计算
     * @return array
     */
    public function execute()
    {
        $result = [];
        foreach ($this->playerCards as $player => &$playerCard) {
            //按照从大到小排序
            $this->sortCard($playerCard);
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
                return $this->compareWithNumberAndColor($value['cards'], $next['cards']);
            }
            return $value['shape'] < $next['shape'] ? 1 : -1;
        });
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
}
