<?php
namespace Goodspb\PokerAlgorithm;

abstract class Poker
{
    const COLOR_SPADE = 4;      //黑桃
    const COLOR_HEART = 3;      //红桃
    const COLOR_CLUB = 2;       //梅花
    const COLOR_DIAMOND = 1;    //方块

    const CARD_K = 13;
    const CARD_Q = 12;
    const CARD_J = 11;
    const CARD_10 = 10;
    const CARD_9 = 9;
    const CARD_8 = 8;
    const CARD_7 = 7;
    const CARD_6 = 6;
    const CARD_5 = 5;
    const CARD_4 = 4;
    const CARD_3 = 3;
    const CARD_2 = 2;
    const CARD_A = 1;

    protected static $cards = [
        //数字，花色
        [self::CARD_A, self::COLOR_SPADE], [self::CARD_A, self::COLOR_HEART], [self::CARD_A, self::COLOR_CLUB], [self::CARD_A, self::COLOR_DIAMOND],
        [self::CARD_2, self::COLOR_SPADE], [self::CARD_2, self::COLOR_HEART], [self::CARD_2, self::COLOR_CLUB], [self::CARD_2, self::COLOR_DIAMOND],
        [self::CARD_3, self::COLOR_SPADE], [self::CARD_3, self::COLOR_HEART], [self::CARD_3, self::COLOR_CLUB], [self::CARD_3, self::COLOR_DIAMOND],
        [self::CARD_4, self::COLOR_SPADE], [self::CARD_4, self::COLOR_HEART], [self::CARD_4, self::COLOR_CLUB], [self::CARD_4, self::COLOR_DIAMOND],
        [self::CARD_5, self::COLOR_SPADE], [self::CARD_5, self::COLOR_HEART], [self::CARD_5, self::COLOR_CLUB], [self::CARD_5, self::COLOR_DIAMOND],
        [self::CARD_6, self::COLOR_SPADE], [self::CARD_6, self::COLOR_HEART], [self::CARD_6, self::COLOR_CLUB], [self::CARD_6, self::COLOR_DIAMOND],
        [self::CARD_7, self::COLOR_SPADE], [self::CARD_7, self::COLOR_HEART], [self::CARD_7, self::COLOR_CLUB], [self::CARD_7, self::COLOR_DIAMOND],
        [self::CARD_8, self::COLOR_SPADE], [self::CARD_8, self::COLOR_HEART], [self::CARD_8, self::COLOR_CLUB], [self::CARD_8, self::COLOR_DIAMOND],
        [self::CARD_9, self::COLOR_SPADE], [self::CARD_9, self::COLOR_HEART], [self::CARD_9, self::COLOR_CLUB], [self::CARD_9, self::COLOR_DIAMOND],
        [self::CARD_10, self::COLOR_SPADE], [self::CARD_10, self::COLOR_HEART], [self::CARD_10, self::COLOR_CLUB], [self::CARD_10, self::COLOR_DIAMOND],
        [self::CARD_J, self::COLOR_SPADE], [self::CARD_J, self::COLOR_HEART], [self::CARD_J, self::COLOR_CLUB], [self::CARD_J, self::COLOR_DIAMOND],
        [self::CARD_Q, self::COLOR_SPADE], [self::CARD_Q, self::COLOR_HEART], [self::CARD_Q, self::COLOR_CLUB], [self::CARD_Q, self::COLOR_DIAMOND],
        [self::CARD_K, self::COLOR_SPADE], [self::CARD_K, self::COLOR_HEART], [self::CARD_K, self::COLOR_CLUB], [self::CARD_K, self::COLOR_DIAMOND],
    ];

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
