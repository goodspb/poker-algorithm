/* poker_algorithm extension for PHP */

#ifdef HAVE_CONFIG_H
# include "config.h"
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "php_poker_algorithm.h"

/* {{{ void poker_algorithm_test1()
 */
PHP_FUNCTION(poker_algorithm_test1)
{
	ZEND_PARSE_PARAMETERS_NONE();

	php_printf("The extension %s is loaded and working!\r\n", "poker_algorithm");
}
/* }}} */

/* {{{ string poker_algorithm_test2( [ string $var ] )
 */
PHP_FUNCTION(poker_algorithm_test2)
{
	char *var = "World";
	size_t var_len = sizeof("World") - 1;
	zend_string *retval;

	ZEND_PARSE_PARAMETERS_START(0, 1)
		Z_PARAM_OPTIONAL
		Z_PARAM_STRING(var, var_len)
	ZEND_PARSE_PARAMETERS_END();

	retval = strpprintf(0, "Hello %s", var);

	RETURN_STR(retval);
}
/* }}}*/

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

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(poker_algorithm)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "poker_algorithm support", "enabled");
	php_info_print_table_end();
}
/* }}} */

/* {{{ arginfo
 */
ZEND_BEGIN_ARG_INFO(arginfo_poker_algorithm_test1, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO(arginfo_poker_algorithm_test2, 0)
	ZEND_ARG_INFO(0, str)
ZEND_END_ARG_INFO()
/* }}} */

/* {{{ poker_algorithm_functions[]
 */
const zend_function_entry poker_algorithm_functions[] = {
	PHP_FE(poker_algorithm_test1,		arginfo_poker_algorithm_test1)
	PHP_FE(poker_algorithm_test2,		arginfo_poker_algorithm_test2)
	PHP_FE_END
};
/* }}} */

/* {{{ poker_algorithm_module_entry
 */
zend_module_entry poker_algorithm_module_entry = {
	STANDARD_MODULE_HEADER,
	"poker_algorithm",					/* Extension name */
	poker_algorithm_functions,			/* zend_function_entry */
	NULL,							/* PHP_MINIT - Module initialization */
	NULL,							/* PHP_MSHUTDOWN - Module shutdown */
	PHP_RINIT(poker_algorithm),			/* PHP_RINIT - Request initialization */
	NULL,							/* PHP_RSHUTDOWN - Request shutdown */
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
