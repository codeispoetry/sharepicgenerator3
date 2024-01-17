up:
	docker-compose up -d
	@echo "Port: 9500"
	google-chrome http://localhost:9500

stop:
	docker-compose stop

build:
	docker-compose build

shell:
	docker-compose run webserver bash

log-empty:
	rm logs/*.log

png:
	chromium-browser --headless --disable-gpu --screenshot=output.png --window-size=800,600 input.svg

woff:
	woff2_compress Pacifico.ttf 

deploy-develop:
	rsync -avz --delete assets node_modules/quill/dist index.php cli.php languages src templates vendor favicon.ico sharepic:/var/www/mint-develop.sharepicgenerator.de

deploy-gruene-develop:
	rsync -avz --delete assets node_modules/quill/dist index.php cli.php languages src templates vendor favicon.ico install.php sharepic:/var/www/develop.sharepicgenerator.de/version3

deploy-gruene:
	rsync -avz --delete assets node_modules/quill/dist index.php cli.php languages src templates vendor favicon.ico install.php sharepic:/var/www/sharepicgenerator.de/version3

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
	make create-mo

