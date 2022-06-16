phpstan:
	vendor/bin/phpstan analyse src tests --level 4

beautify:
	vendor/bin/phpcbf --standard=PSR12 src tests -v

code-sniffer:
	vendor/bin/phpcs --standard=PSR12 src tests

test:
	vendor/bin/phpunit tests

all: test phpstan beautify

phpstanw:
	.\vendor\bin\phpstan analyse src tests --level 4

beautifyw:
	.\vendor\bin\phpcbf --standard=PSR12 src tests -v

code-snifferw:
	.\vendor\bin\phpcs --standard=PSR12 src tests

testw:
	.\vendor\bin\phpunit tests

allw: testw phpstanw beautifyw