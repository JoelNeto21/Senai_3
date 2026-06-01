<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Sobre o Projeto

Sistema de gestão desenvolvido com Laravel + Filament para gerenciamento de pedidos, produtos, clientes, fornecedores, insumos e movimentação de estoque.

## Requisitos

- PHP 8.3+
- Composer
- Node.js + NPM
- SQLite (desenvolvimento) / MySQL (produção)
- Mailpit (desenvolvimento - envio de emails)

## Instalação

```bash
# Clonar o repositório
git clone <url-do-repositorio>
cd filaModel

# Instalar dependências PHP
composer install

# Configurar ambiente
cp .env.example .env
php artisan key:generate

# Configurar banco de dados SQLite
touch database/database.sqlite

# Executar migrations e seeders
php artisan migrate --seed

# Instalar dependências front-end
npm install
npm run build
```

## Execução

```bash
# Iniciar servidor de desenvolvimento
composer dev
```

## Mailpit

Mailpit é uma ferramenta de captura de emails para desenvolvimento. Com ela, você pode visualizar emails enviados pela aplicação sem precisar de um servidor SMTP real.

### Instalação

**Windows (via Chocolatey):**
```bash
choco install mailpit
```

**macOS (via Homebrew):**
```bash
brew install mailpit
```

**Linux:**
```bash
# Download do binário mais recente
curl -sSL https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64.tar.gz | tar xz
sudo mv mailpit /usr/local/bin/
```

### Execução

```bash
mailpit
```

O servidor SMTP será iniciado na porta **1025** e a interface web na porta **8025**.

### Acessando a Interface

Após iniciar o Mailpit, acesse:

```
http://localhost:8025
```

### Configuração

As variáveis de ambiente já estão configuradas no arquivo `.env.example`:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="sistema@filamodel.local"
MAIL_FROM_NAME="FilaModel"
```

### Fluxo de Teste

1. Inicie o Mailpit executando `mailpit` no terminal
2. Inicie o servidor de desenvolvimento: `php artisan serve`
3. Acesse `http://localhost:8025` para abrir a interface do Mailpit
4. Crie um pedido no sistema
5. Verifique o email recebido na interface do Mailpit

## Funcionalidades

- **Clientes**: Cadastro completo com validação de CPF e email
- **Fornecedores**: Cadastro com validação de CNPJ/CPF e email
- **Produtos**: Gerenciamento de produtos com controle de estoque
- **Insumos**: Gerenciamento de insumos com unidade de medida
- **Pedidos**: Criação de pedidos com itens e cálculo automático de total
- **Movimentação de Estoque**: Controle de entrada e saída de produtos
- **Notificações por Email**: Envio automático de email ao criar/atualizar pedidos
- **Usuários e Permissões**: Gerenciamento via Spatie Permission + Filament

## Licença

Este projeto é open-sourced sob a licença [MIT license](https://opensource.org/licenses/MIT).