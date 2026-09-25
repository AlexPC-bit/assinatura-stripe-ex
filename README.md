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
- Adicionaria um plano de retry mais robusto caso a criação da assinatura falhe depois do pagamento confirmado.<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
