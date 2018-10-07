run docker:
$ cd devenv
$ docker-compose up -d

enter to container:
$ docker exec -ti container-name bash

view all run container:
$ docker ps

note:
- check mysql port or stop mysql(sudo service mysql stop)

for access rabbitMQ: 
http://fitness.notification.local:15672
login: rabbitmq
password: rabbitmq

todo:
- rabbitmq
- notify service +
- subscription to classes
