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

## Todo

Handles element creation with List vue component
 
Make some tests

## Reference

Made with [Symfony Docker](https://github.com/dunglas/symfony-docker).
