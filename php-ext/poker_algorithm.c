

#include "php_poker_algorithm.h"

zend_class_entry poker_niuniu_class_ce;
zend_class_entry *poker_niuniu_class_ce_ptr;

ZEND_BEGIN_ARG_INFO_EX(arginfo_poker_niuniu__construct, 0, 0, 0)
ZEND_END_ARG_INFO()

static zend_function_entry poker_niuniu_class_methods[] = {
    PHP_ME(poker_niuniu, __construct, arginfo_poker_niuniu__construct, ZEND_ACC_PUBLIC | ZEND_ACC_CTOR)
    { NULL, NULL, NULL }
};

/* {{{ PHP_RINIT_FUNCTION
 */
PHP_RINIT_FUNCTION(poker_algorithm)
{
#if defined(ZTS) && defined(COMPILE_DL_POKER_ALGORITHM)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif

	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(poker_algorithm)
{
    INIT_CLASS_ENTRY(poker_niuniu_class_ce, "poker_niuniu", poker_niuniu_class_methods);
    poker_niuniu_class_ce_ptr = zend_register_internal_class(&poker_niuniu_class_ce TSRMLS_CC);
    return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(poker_algorithm)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "poker_algorithm support", "enabled");
	php_info_print_table_row(2, "version", "0.1.0");
	php_info_print_table_row(2, "include games", "Niuniu, Texas");
	php_info_print_table_end();
}
/* }}} */

/* {{{ poker_algorithm_functions[]
 */
const zend_function_entry poker_algorithm_functions[] = {
	PHP_FE_END
};
/* }}} */

/* {{{ poker_algorithm_module_entry
 */
zend_module_entry poker_algorithm_module_entry = {
	STANDARD_MODULE_HEADER,
	"poker_algorithm",					/* Extension name */
	poker_algorithm_functions,			/* zend_function_entry */
	PHP_MINIT(poker_algorithm),			/* PHP_MINIT - Module initialization */
	NULL,							    /* PHP_MSHUTDOWN - Module shutdown */
	PHP_RINIT(poker_algorithm),			/* PHP_RINIT - Request initialization */
	NULL,							    /* PHP_RSHUTDOWN - Request shutdown */
	PHP_MINFO(poker_algorithm),			/* PHP_MINFO - Module info */
	PHP_POKER_ALGORITHM_VERSION,		/* Version */
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_POKER_ALGORITHM
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(poker_algorithm)
#endif
