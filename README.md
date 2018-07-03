[![Build Status](https://travis-ci.org/rafaelzorn/forum.svg?branch=master)](https://travis-ci.org/rafaelzorn/forum)
[![Test Coverage](https://img.shields.io/codecov/c/github/rafaelzorn/forum/master.svg)](https://codecov.io/github/rafaelzorn/forum?branch=master)

# FORUM

Projeto para estudo de TDD (Test Driven Development).

Primeiro você tem que usar o seu Terminal para chegar ao diretório que você deseja armazenar o projeto. Então você executa:

```
git clone git@github.com:rafaelzorn/forum.git
```

Acesse o diretório criado e você estará no diretório-raiz do projeto:

```
cd forum
```

Instalar as dependências do projeto:

```
composer install
```

Configure o .env

```
cp .env.example .env para configurar a instalação
```

Gere a chave unica

```
php artisan key:generate
```

Execute as migrations

```
php artisan migrate
```

Execute as seeders

```
php artisan db:seed
````

## Screenshots

## Construído com

* [Laravel 5.6](https://laravel.com)
