cmake_minimum_required(VERSION 3.5)

# Project name
project(poker_algorithm)
# Use C99
set(CMAKE_C_STANDARD 11)

set(SOURCE_FILES poker_algorithm.c php_poker_algorithm.h poker_niuniu.c)

add_executable(poker_algorithm ${SOURCE_FILES})

# Define path to php-src, change accordingly
set(PHP_SOURCE ../../../cpp/php-src)
# Import php sources
include_directories(
        ${PHP_SOURCE}
        ${PHP_SOURCE}/main
        ${PHP_SOURCE}/Zend
        ${PHP_SOURCE}/TSRM
        ${PHP_SOURCE}/ext
        ${PHP_SOURCE}/sapi
        ${PHP_SOURCE}/pear
)