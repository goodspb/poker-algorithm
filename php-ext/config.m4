PHP_ARG_ENABLE(poker_algorithm, whether to enable poker_algorithm support,
[  --enable-poker_algorithm          Enable poker_algorithm support], no)

if test "$PHP_POKER_ALGORITHM" != "no"; then
  AC_DEFINE(HAVE_POKER_ALGORITHM, 1, [ Have poker_algorithm support ])
  PHP_NEW_EXTENSION(poker_algorithm, poker_algorithm.c, $ext_shared)
fi
