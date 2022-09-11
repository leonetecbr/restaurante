# Restaurante

## Instalação 

### Requisitos

<ul>
    <li>PHP 8.0.1</li>
    <li>Composer</li>
</ul>

#### Recomendado

<ul>
    <li>NPM</li>
</ul>

### Passos

<ol>
    <li>Clone o repositório <code>git clone https://github.com/leonetecbr/restaurante</code></li>
    <li>Acesse o diretório <code>cd restaurante</code></li>
    <li>Instale as dependências <code>composer install</code></li>
    <li>Modifique o aquivo <code>.env</code> conforme seu ambiente</li>
    <li>Realize a migração do banco de dados <code>php artisan migrate</code></li>
    <li>Inicie o servidor <code>php artisan serve --port=80</code></li>
    <li>Acesse <code>http://localhost</code></li>
</ol>

### Autenticação

#### Versão garçom

**Email:** `garcom@localhost`
**Senha:** `12345678`

#### Versão admin

**Email:** `admin@localhost`
**Senha:** `12345678`

Caso `APP_DOMAIN` difira de `localhost` alterar o domínio no e-mail também.
