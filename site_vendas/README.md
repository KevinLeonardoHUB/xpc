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
- Email: `admin@admin.com`
- Password: `Admin@123456`


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
