<?php
namespace Goodspb\PokerAlgorithm;

abstract class Poker
{
    const COLOR_SPADE = 4;      //黑桃
    const COLOR_HEART = 3;      //红桃
    const COLOR_CLUB = 2;       //梅花
    const COLOR_DIAMOND = 1;    //方块

    public static $colors = [
        self::COLOR_SPADE,
        self::COLOR_HEART,
        self::COLOR_CLUB,
        self::COLOR_DIAMOND,
    ];

    const NUMBER_K = 13;
    const NUMBER_Q = 12;
    const NUMBER_J = 11;
    const NUMBER_10 = 10;
    const NUMBER_9 = 9;
    const NUMBER_8 = 8;
    const NUMBER_7 = 7;
    const NUMBER_6 = 6;
    const NUMBER_5 = 5;
    const NUMBER_4 = 4;
    const NUMBER_3 = 3;
    const NUMBER_2 = 2;
    const NUMBER_A = 1;

    public static $numbers = [
        self::NUMBER_K,
        self::NUMBER_Q,
        self::NUMBER_J,
        self::NUMBER_10,
        self::NUMBER_9,
        self::NUMBER_8,
        self::NUMBER_7,
        self::NUMBER_6,
        self::NUMBER_5,
        self::NUMBER_4,
        self::NUMBER_3,
        self::NUMBER_2,
        self::NUMBER_A,
    ];

    /**
     * 一副牌
     * @var array
     */
    protected $pack;

    /**
     * 当前牌堆
     * @var array
     */
    protected $round = null;

    public function __construct($autoBegin = true)
    {
        $this->pack = $this->generatePack();
        if ($autoBegin) {
            $this->begin();
        }
    }

    /**
     * 开始一局
     */
    public function begin()
    {
        if (is_null($this->round)) {
            $this->round = $this->pack;
        }
    }

    /**
     * 创建一副牌
     * @return array
     */
    protected function generatePack()
    {
        $pack = [];
        foreach(self::$numbers as $number) {
            foreach (self::$colors as $color) {
                $pack[] = [
                    'number' => $number,
                    'color' => $color
                ];
            }
        }
        return $pack;
    }

    /**
     * 洗牌
     */
    public function shuffle()
    {
        shuffle($this->round);
    }

    /**
     * 获取牌面数字
     * @param $card
     * @return mixed
     */
    protected function getCardNumber($card)
    {
        return $card['number'];
    }

    /**
     * 获取花色
     * @param $card
     * @return mixed
     */
    protected function getCardColor($card)
    {
        return $card['color'];
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
