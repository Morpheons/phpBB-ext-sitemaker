#!/bin/bash
#
# Collapsible Categories extension for the phpBB Forum Software package.
#
# @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
# @license GNU General Public License, version 2 (GPL-2.0)
#
set -e
set -x

DB=$1
TRAVIS_PHP_VERSION=$2

if [ "$TRAVIS_PHP_VERSION" == "5.6" -a "$DB" == "mysqli" ]
then
    phpenv config-rm xdebug.ini || true
	curl -L https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$(php -r "echo PHP_MAJOR_VERSION . PHP_MINOR_VERSION;")-zts | tar zxpf -
	echo "extension=$(pwd)/$(ls blackfire-*.so | tr -d '[[:space:]]')" > ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/blackfire.ini
	echo "blackfire.agent_socket=unix:///tmp/blackfire.sock" >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/blackfire.ini
fi
