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
	rsync -avz --delete assets node_modules/quill/dist index.php src tenants users vendor config.ini favicon.ico sharepic:/var/www/mint-develop.sharepicgenerator.de