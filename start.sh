#!/usr/bin/env bash
touch docker_server.env
echo 'UID_USER='$UID >> docker_server.env
echo 'MYSQL_ROOT_PASSWORD=digico' >> docker_server.env
echo 'MYSQL_DATABASE=digico' >> docker_server.env

