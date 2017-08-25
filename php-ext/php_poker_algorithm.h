/* poker_algorithm extension for PHP */

#ifndef PHP_POKER_ALGORITHM_H
# define PHP_POKER_ALGORITHM_H

extern zend_module_entry poker_algorithm_module_entry;
# define phpext_poker_algorithm_ptr &poker_algorithm_module_entry

# define PHP_POKER_ALGORITHM_VERSION "0.1.0"

# if defined(ZTS) && defined(COMPILE_DL_POKER_ALGORITHM)
ZEND_TSRMLS_CACHE_EXTERN()
# endif

#endif	/* PHP_POKER_ALGORITHM_H */
