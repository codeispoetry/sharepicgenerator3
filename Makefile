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
	rsync -avz --delete --progress public/assets sharepic:httpdocs

create-pot:
	find src -name '*.php' | xargs xgettext --language=PHP -o languages/messages.pot

create-po-init:
	msginit -i languages/messages.pot -o languages/de.po -l de_DE.utf8

update-po:
	msgmerge -U languages/de.po languages/messages.pot

create-mo:
	msgfmt languages/de.po -o languages/de_DE/LC_MESSAGES/sg.mo

translation-prepare:
	make create-pot update-po && code languages/de.po

translate:
	make create-mo stop up

