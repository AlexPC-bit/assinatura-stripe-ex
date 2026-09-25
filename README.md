# AssinaturaStripe

Plataforma de assinaturas (SaaS B2C) com integração real de pagamentos via Stripe — checkout, cobrança recorrente e liberação de acesso controlada por webhook.

## Por que esse projeto

Depois de um tempo afastado do mercado, decidi reconstruir meu portfólio com projetos que realmente provam competência backend.

## Decisão sem Cashier, na mão

O Laravel tem um pacote oficial (Cashier) que abstrai praticamente toda a integração com Stripe. Decidi **não usar ele** — usei o SDK puro (`stripe/stripe-php`) e construí o fluxo de checkout e webhook manualmente.

O motivo: eu queria explicar cada peça do processo, não só "funcionar". Fazendo na mão.

🔗 [Ver projeto em produção](https://assinatura-stripe-ex.onrender.com) · 📄 [Documentação de regras de negócio](docs/regras-de-negocio.pdf)

## Stack

- Laravel 11 (PHP)
- Breeze para autenticação
- Stripe SDK oficial (`stripe/stripe-php`)
- Stripe CLI para testar webhooks localmente
- SQLite em desenvolvimento

## Rodando localmente

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=PlanSeeder
npm install && npm run build
php artisan serve
```

Configura as chaves de teste do Stripe no `.env`:

```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=
```

Pra testar o fluxo de webhook localmente, precisa do [Stripe CLI](https://stripe.com/docs/stripe-cli) rodando em paralelo, escutando os eventos e encaminhando pro seu ambiente local:

## O que eu faria diferente numa versão de produção

Documentando aqui porque acho importante deixar claro que sei que esse projeto tem escopo de portfólio, não de produção:

- Trataria mais tipos de evento de webhook (falha de pagamento, tentativa de cobrança recorrente que falhou, etc.)
- Adicionaria fila (queue) no processamento do webhook, pra não bloquear a resposta HTTP enquanto processa a lógica de negócio
- Implementaria testes automatizados cobrindo o fluxo de idempotência e o gate de acesso
- Adicionaria um plano de retry mais robusto caso a criação da assinatura falhe depois do pagamento confirmado.
