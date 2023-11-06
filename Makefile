up:
	docker-compose up -d
	@echo "Port: 9500"

stop:
	docker-compose stop

build:
	docker-compose build

shell:
	docker-compose run webserver bash

png:
	chromium-browser --headless --disable-gpu --screenshot=output.png --window-size=800,600 input.svg