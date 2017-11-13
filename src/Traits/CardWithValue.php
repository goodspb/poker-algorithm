<?php
namespace Goodspb\PokerAlgorithm\Traits;

/**
 * 排当中出现值，且规则如下
 * 1-10 对应的值为 10
 * JQK 也对应为 10
 *
 * @package Goodspb\PokerAlgorithm\Traits
 * @method getCardNumber($card)
 */
trait CardWithValue
{
    /**
     * 获取数值
     * @param $card
     * @return mixed
     */
    protected function getCardValue($card)
    {
        $cardNumber = $this->getCardNumber($card);
        return $cardNumber < 10 ? $cardNumber : 10;
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
}
