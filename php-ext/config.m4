PHP_ARG_ENABLE(poker_algorithm, whether to enable poker_algorithm support,
[  --enable-poker_algorithm          Enable poker_algorithm support], no)

if test "$PHP_POKER_ALGORITHM" != "no"; then

  AC_DEFINE(HAVE_POKER_ALGORITHM, 1, [ Have poker_algorithm support ])

  poker_algorithm_source_files="poker_algorithm.c \
            poker_niuniu.c"

  PHP_NEW_EXTENSION(poker_algorithm, $poker_algorithm_source_files, $ext_shared)

fi
