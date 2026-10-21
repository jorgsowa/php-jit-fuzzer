#!/bin/sh
# Usage: build-php.sh <php-src dir> <install prefix>
set -eu
cd "$1"
./buildconf --force
./configure --prefix="$2" --disable-all --enable-debug --enable-opcache --disable-cgi --disable-phpdbg
make -j"$(nproc)"
make install
