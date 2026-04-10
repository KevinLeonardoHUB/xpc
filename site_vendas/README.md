# XPC Informática - Loja em PHP

## O que está funcional
- login
- registo
- carrinho
- checkout com criação de pedido
- área do cliente
- painel admin
- gestão de categorias
- gestão de produtos
- gestão de pedidos
- utilizadores

## Conta admin inicial
- Email: `kevinleonardomail@hotmail.com`
- Password: `Admin@123456`

## Como instalar
1. Extraia o projeto.
2. No terminal, dentro da pasta do projeto:
   ```bash
   composer install
   cp .env.example .env
   ```
3. Edite o ficheiro `.env` e preencha os dados SMTP reais do Hotmail/Outlook caso queira manter o envio de emails de orçamento.
4. Inicie localmente:
   ```bash
   php -S localhost:8000
   ```
5. Abra no navegador:
   ```bash
   http://localhost:8000
   ```

## Estrutura
A estrutura foi mantida simples:
- raiz com páginas principais
- `inc/` para ligação à base de dados, funções e layout
- `admin/` para o painel
- `storage/database.json` usado como base de dados simples

## Compra
A compra funciona com:
- adicionar ao carrinho
- atualizar quantidades
- checkout
- criação de pedido na base de dados
- controlo de stock

Não há gateway de pagamento online nesta versão. O fluxo fecha o pedido internamente no sistema.
