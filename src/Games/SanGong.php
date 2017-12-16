<?php
namespace Goodspb\PokerAlgorithm\Games;

use Goodspb\PokerAlgorithm\Poker;
use Goodspb\PokerAlgorithm\Traits\CardWithValue;

/**
 * 三公
 * Class SanGong
 * @package Goodspb\PokerAlgorithm\Games
 */
class SanGong extends Poker
{
    use CardWithValue;

    const SHAPE_TOP_NINE = 12;
    const SHAPE_FULL_HOUSE = 11;
    const SHAPE_THREE = 10;
    const SHAPE_POINT_9 = 9;
    const SHAPE_POINT_8 = 8;
    const SHAPE_POINT_7 = 7;
    const SHAPE_POINT_6 = 6;
    const SHAPE_POINT_5 = 5;
    const SHAPE_POINT_4 = 4;
    const SHAPE_POINT_3 = 3;
    const SHAPE_POINT_2 = 2;
    const SHAPE_POINT_1 = 1;
    const SHAPE_POINT_0 = 0;

    public static $shapes = [
        self::SHAPE_TOP_NINE => '至尊九',
        self::SHAPE_FULL_HOUSE => '三条',
        self::SHAPE_THREE => '三公',
        self::SHAPE_POINT_9 => '9点',
        self::SHAPE_POINT_8 => '8点',
        self::SHAPE_POINT_7 => '7点',
        self::SHAPE_POINT_6 => '6点',
        self::SHAPE_POINT_5 => '5点',
        self::SHAPE_POINT_4 => '4点',
        self::SHAPE_POINT_3 => '3点',
        self::SHAPE_POINT_2 => '2点',
        self::SHAPE_POINT_1 => '1点',
        self::SHAPE_POINT_0 => '0点',
    ];

    public function execute($playerCards = [])
    {
        $playerCards = empty($playerCards) ? $this->getPlayersCards() : $playerCards;
        $result = [];
        foreach ($playerCards as $player => &$playerCard) {
            //手牌中按照大小来排序
            $this->sortCard($playerCard);
            $result[] = [
                'player' => $player,                    //用户手牌
                'cards' => $playerCard,                 //排序过后的手牌
                'shape' => $this->judge($playerCard),   //判断牌型
            ];
        }
        $this->sortResult($result);
        return $result;
    }

    public function sortResult(&$result)
    {
        usort($result, function($value, $next){
            if ($value['shape'] == $next['shape']) {
                return $this->compareWithNumberAndColor($value['cards'], $next['cards']);
            }
            return $value['shape'] < $next['shape'] ? 1 : -1;
        });
    }

    /**
     * 判断牌型
     * @param array $cards
     * @return int
     */
    public function judge(array $cards)
    {
        //获取所有Numbers
        $numbers = $this->getCardNumbers($cards);
        //三条
        if (1 === count($uniqueNumber = array_unique($numbers))) {
            //至尊九牌型
            if (3 == $uniqueNumber) {
                return self::SHAPE_TOP_NINE;
            }
            return self::SHAPE_FULL_HOUSE;
        }
        $values = $this->getCardValues($cards);
        //值都是10以上
        if ([10] == array_unique($values)) {
            //是否数字全部都是公仔
            $allAboveTen = true;
            foreach($numbers as $number) {
                $number == 10 and $allAboveTen = false;
            }
            if ($allAboveTen) {
                return self::SHAPE_THREE;
            }
        }
        //返回点数
        return array_sum($values) % 10;
    }



}
