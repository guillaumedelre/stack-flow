# Stack Flow

## Getting start

A symfony dashboard application revealing the mezzo team gitlab state.

The purpose is to experiment latest `Mercure` and `HTTP3` protocols.

### First install ?

`make dcb`

### Setup symfony app

`make setup`

### Run webpack

`make watch`

## Makefile

`make dcb` when you need to build your docker image

`make dcup` when you need to start the composition

`make dcd` when you want to stop your composition and clean services

`make dcps` when you need to list the state of your container

`make setup` build the app

`make watch` Run webpack

## Supervisor

```
[program:flow-update]
user=gdelre
directory=/home/gdelre/sources/stack/flow/
command=docker-compose exec -T php bin/console flow:update -vvv
numprocs=1
startsecs=1
autorestart=true
autostart=true
logfile = /tmp/program:flow-update.log
stderr_logfile=/tmp/program:flow-update.err.log
stdout_logfile=/tmp/program:flow-update.out.log
process_name=%(program_name)s_%(process_num)02d
```

## Reference

Made with [Symfony Docker](https://github.com/dunglas/symfony-docker).
