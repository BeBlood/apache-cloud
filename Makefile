
stack = apache
apache_container_id = $(shell docker ps --filter name="$(stack)_apache" -q)

# Stack
.PHONY: build
build:
	docker build -t cours-apache/apache2 .docker/apache $(options)
	docker build -t cours-apache/php \
		-f .docker/php/Dockerfile-7.1 \
		--build-arg PHP_PORT=9000 \
		--build-arg HOST_UID=1000 \
		.docker/php;


.PHONY: start
start:
	docker stack deploy -c docker-compose.yml $(stack)

.PHONY: run
run: build
	docker run --rm -i -v `pwd`/apache2:/etc/apache2 -v `pwd`/html:/var/www/html -w /var/www/html -p 8888:80 --entrypoint="/opt/docker/apache.sh" cours-apache/apache2

.PHONY: stop
stop:
	docker stack rm $(stack)

.PHONY: logs
logs:
	docker service logs $(stack)_apache $(options)

.PHONY: ps
ps:
	docker service ps $(stack)_apache $(options)

# Utils

.PHONY: bash
bash:
	docker exec -it $(apache_container_id) bash

.PHONY: apache-reload
apache-reload:
	docker exec -it $(apache_container_id) service apache2 reload
