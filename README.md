[![Build Status](https://travis-ci.org/rafaelzorn/forum.svg?branch=master)](https://travis-ci.org/rafaelzorn/forum)
[![Test Coverage](https://img.shields.io/codecov/c/github/rafaelzorn/forum/master.svg)](https://codecov.io/github/rafaelzorn/forum?branch=master)

# FORUM

Projeto para estudo de TDD (Test Driven Development) utilizando framework Laravel ❤.

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
```

Execute os testes

```
phpunit
```

## Screenshots

![Screenshot 1](https://image.ibb.co/jzEa50/home.png)
![Screenshot 2](https://image.ibb.co/gaDJQ0/show.png)
![Screenshot 3](https://image.ibb.co/duo7JL/login.png)
![Screenshot 4](https://image.ibb.co/dxeQ50/topics.png)
![Screenshot 5](https://image.ibb.co/mk6PXf/topics-form.png)

## Construído com

-   [Laravel 5.6](https://laravel.com)
