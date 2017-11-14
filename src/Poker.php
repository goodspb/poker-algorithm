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

    /**
     * @var array 玩家牌
     */
    protected $playerCards = [];

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
        //洗牌
        $this->shuffle();
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
     * 随机生成玩家牌
     * @param int $playerNumbers
     * @param int $perCardsNumber
     * @return array
     */
    public function generate($playerNumbers, $perCardsNumber = 5)
    {
        $beginAt = count($this->playerCards);
        for ($i = 1; $i <= $playerNumbers; $i++) {
            $this->playerCards[$beginAt + $i] = array_splice($this->round, 0, $perCardsNumber);
        }
        return $this->playerCards;
    }

    /**
     * 获取剩余的牌
     * @return array
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * 设置一张忽略的牌
     * @param array $card
     * @return array
     */
    public function setExclude(array $card)
    {
        foreach ($this->round as $key => $nowLeftCard) {
            if ($nowLeftCard == $card) {
                unset($this->round[$key]);
            }
        }
        return $this->round;
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
     * 获取玩家牌
     * @param string $player 玩家 key
     * @return array
     */
    public function getPlayersCards($player = null)
    {
        return is_null($player) ? $this->playerCards : (isset($this->playerCards[$player]) ? $this->playerCards[$player] : []);
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
     * @param string $player 玩家标识
     * @param array $cards
     */
    public function setPlayerCard($player, array $cards)
    {
        $playerCards = [];
        foreach ($cards as $card) {
            $hasThisCard = false;
            $unsetKey = null;
            foreach ($this->round as $nowLeftCardKey => $nowLeftCard) {
                if ($card == $nowLeftCard) {
                    $hasThisCard = true;
                    $unsetKey = $nowLeftCardKey;
                }
            }
            if ($hasThisCard) {
                $playerCards[] = $card;
                unset($this->round[$unsetKey]);
            }
        }
        $this->playerCards[$player] = $playerCards;
    }

    /**
     * 根据牌面，花色的大小从大到小排序
     * @param array  $cards 一手牌
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
     * 比较2张牌的大小，一次比较单牌的大小，如单牌牌面都相同，比较最大单牌的花色
     * @param $firstCards
     * @param $secondCards
     * @return int 返回 1 表示前者比后者大，返回 -1 表示前者比后者细
     */
    public function compareWithNumberAndColor($firstCards, $secondCards)
    {
        foreach ($firstCards as $key => $value) {
            if (($firstNumber = $this->getCardNumber($value)) < ($secondNumber = $this->getCardNumber($secondCards[$key]))) {
                return 1;
            } elseif ($firstNumber > $secondNumber) {
                return -1;
            }
        }
        //当所有的牌都是等于的时候，比较最大单牌的花色
        return $this->getCardColor($firstCards[0]) < $this->getCardColor($secondCards[0]) ? 1 : -1;
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
