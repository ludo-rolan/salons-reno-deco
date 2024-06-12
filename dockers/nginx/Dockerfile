FROM nginx:latest

RUN apt-get update
RUN apt-get install -y nginx-extras
RUN apt-get install nano
RUN apt-get install net-tools
RUN mkdir -p /run/shm/fastcgi_cache
RUN mkdir -p /gounane