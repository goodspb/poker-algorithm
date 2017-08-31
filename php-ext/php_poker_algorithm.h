/* poker_algorithm extension for PHP */
#ifdef HAVE_CONFIG_H
# include "config.h"
#endif

#include <memory.h>
#include "php.h"

#include "ext/standard/info.h"


#ifndef PHP_POKER_ALGORITHM_H
# define PHP_POKER_ALGORITHM_H

extern zend_module_entry poker_algorithm_module_entry;
# define phpext_poker_algorithm_ptr &poker_algorithm_module_entry

# define PHP_POKER_ALGORITHM_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_POKER_ALGORITHM)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_POKER_ALGORITHM_H */

typedef struct _poker_cards {
    int number;
    int color;
    int value;
} poker_cards;

poker_cards init_poker_cards[52] = {
        {1, 1, 1}, {1, 2, 1}, {1, 3, 1}, {1, 4, 1},
        {2, 1, 2}, {2, 2, 2}, {2, 3, 2}, {2, 4, 2},
        {3, 1, 3}, {3, 2, 3}, {3, 3, 3}, {3, 4, 3},
        {4, 1, 4}, {4, 2, 4}, {4, 3, 4}, {4, 4, 4},
        {5, 1, 5}, {5, 2, 5}, {5, 3, 5}, {5, 4, 5},
        {6, 1, 6}, {6, 2, 6}, {6, 3, 6}, {6, 4, 6},
        {7, 1, 7}, {7, 2, 7}, {7, 3, 7}, {7, 4, 7},
        {8, 1, 8}, {8, 2, 8}, {8, 3, 8}, {8, 4, 8},
        {9, 1, 9}, {9, 2, 9}, {9, 3, 9}, {9, 4, 9},
        {10, 1, 10}, {10, 2, 10}, {10, 3, 10}, {10, 4, 10},
        {11, 1, 10}, {11, 2, 10}, {11, 3, 10}, {11, 4, 10},
        {12, 1, 10}, {12, 2, 10}, {12, 3, 10}, {12, 4, 10},
        {13, 1, 10}, {13, 2, 10}, {13, 3, 10}, {13, 4, 10},
};

PHP_METHOD(poker_niuniu, __construct);