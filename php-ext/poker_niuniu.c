
#ifndef PHP_POKER_ALGORITHM_H
#include "php_poker_algorithm.h"
#endif

PHP_METHOD(poker_niuniu, __construct)
{
    zval *this_ptr;
    this_ptr = getThis();
    zval *cards = emalloc(sizeof(zval));
    bzero(cards, sizeof(zval));
    array_init(cards);
    zval *card = emalloc(sizeof(zval));
    for (int i = 0; i < sizeof(init_poker_cards) / sizeof(init_poker_cards[0]); ++i) {
        bzero(card, sizeof(zval));
        array_init(card);
        add_index_long(card, 0, init_poker_cards[i].number);
        add_index_long(card, 1, init_poker_cards[i].color);
        add_index_long(card, 2, init_poker_cards[i].value);
        add_index_zval(cards, i, card);
    }
    add_property_zval_ex(this_ptr, ZEND_STRL("cards"), cards);
}