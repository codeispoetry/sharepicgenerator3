up:
	docker-compose up -d
	@echo "Port: 9500"
	google-chrome http://localhost:9500

stop:
	docker-compose stop

build:
	docker-compose build

shell:
	docker-compose exec webserver bash

woff:
	woff2_compress Pacifico.ttf 

fonts_write_css_file:
	cd httpdocs && php -r "require 'src/Controllers/Fonts.php'; sharepicgenerator\Controllers\Fonts::write_css_file();"

deploy-gruene:
	ssh sharepic-verdigado 'sudo chown -R tr:tr /srv/sharepic'
	rsync -avzr --no-o --no-g --no-p --delete --progress --files-from=rsync-list.txt ./ sharepic-verdigado:/srv/sharepic || true
	ssh sharepic-verdigado 'sudo chown -R www-data:www-data /srv/sharepic'

deploy-mint-develop:
	rsync -avzr --no-o --no-g --no-p --delete --progress --files-from=rsync-list.txt ./ sharepic:/var/www/vhosts/sharepicgenerator.de/mint.sharepicgenerator.de

deploy-mint-live:
	rsync -avzr --no-o --no-g --no-p --delete --progress --files-from=rsync-list.txt ./ mint:/var/www/vhosts/mint-vernetzt.de/sharepic.mint-vernetzt.de

deploy-open:
	rsync -avzr --no-o --no-g --no-p --delete --progress --files-from=rsync-list.txt ./ sharepic:/var/www/vhosts/sharepicgenerator.de/open.sharepicgenerator.de

deploy-develop:
	rsync -avzr --no-o --no-g --no-p --delete --progress --files-from=rsync-list.txt ./ sharepic:/var/www/vhosts/sharepicgenerator.de/develop.sharepicgenerator.de

create-pot:
	find httpdocs/src -name '*.php' | xargs xgettext --language=PHP -o httpdocs/languages/messages.pot

create-po-init:
	msginit -i httpdocs/languages/messages.pot -o httpdocs/languages/de.po -l de_DE.utf8

update-po:
	msgmerge -U httpdocs/languages/de.po httpdocs/languages/messages.pot

create-mo:
	msgfmt httpdocs/languages/de.po -o httpdocs/languages/de_DE/LC_MESSAGES/sg.mo

translation-prepare:
	make create-pot update-po && code httpdocs/languages/de.po && poedit httpdocs/languages/de.po

translate:
	make create-mo stop up

download-bugs:
	rm logfiles/*.log
	rsync -avzr sharepic-verdigado:/srv/sharepic/logfiles/*.log logfiles/
	chmod 777 logfiles/*.log
	code logfiles/bugs.log
	./scripts/download-bugs.sh

log-downloads:
	grep -c 'created sharepic' logfiles/usage.log

log-users:
	cut -f2 logfiles/usage.log | sort | uniq -c | wc -l

log-power-users:
	cut -f2 logfiles/usage.log | sort | uniq -c | sort -nr | head -n 20

log-savings:
	for dir in $(shell find users -type d -name "save"); do echo "$$dir has $$(find $$dir -mindepth 1 -maxdepth 1 -type d | wc -l) savings"; done	
