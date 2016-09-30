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
	openssl aes-256-cbc -K $encrypted_841ca0062694_key -iv $encrypted_841ca0062694_iv -in .blackfire.travis.ini.enc -out .blackfire.travis.ini -d
	curl -L https://blackfire.io/api/v1/releases/agent/linux/amd64 | tar zxpf -
	chmod 755 agent && ./agent --config=~/.blackfire.ini --socket=unix:///tmp/blackfire.sock &
fi
