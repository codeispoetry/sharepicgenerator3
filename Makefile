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

deploy-gruene:
	rsync -avzr --no-o --no-g --no-p --delete --progress --files-from=rsync-list.txt ./ sharepic-verdigado:/srv/sharepic || true
	ssh sharepic-verdigado 'sudo chown -R www-data:www-data /srv/sharepic'

create-pot:
	find httpdocs/src -name '*.php' | xargs xgettext --language=PHP -o httpdocs/languages/messages.pot

create-po-init:
	msginit -i httpdocs/languages/messages.pot -o httpdocs/languages/de.po -l de_DE.utf8

update-po:
	msgmerge -U httpdocs/languages/de.po httpdocs/languages/messages.pot

create-mo:
	msgfmt httpdocs/languages/de.po -o httpdocs/languages/de_DE/LC_MESSAGES/sg.mo

translation-prepare:
	make create-pot update-po && code httpdocs/languages/de.po

translate:
	make create-mo stop up

