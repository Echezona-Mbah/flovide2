<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

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

## Swappr API keys

Swappr uses a single Bearer API key per environment; it does not use a separate public key. Add both keys to the server environment (do not commit their values):

```dotenv
SWAPPR_BASE_URL=https://api.swappr.me/api/v1
SWAPPR_TEST_PUBLIC_KEY=
SWAPPR_TEST_API_KEY=sk_test_...
SWAPPR_LIVE_PUBLIC_KEY=
SWAPPR_LIVE_API_KEY=sk_live_...
SWAPPR_CAD_INTERAC_EMAIL=

SWAPPR_SENDER_FIRST_NAME=
SWAPPR_SENDER_LAST_NAME=
SWAPPR_SENDER_ID_TYPE=
SWAPPR_SENDER_ID_NUMBER=
SWAPPR_SENDER_ID_EXPIRY=
SWAPPR_SENDER_DATE_OF_BIRTH=
SWAPPR_SENDER_COUNTRY=
```

The public-key variables are optional reference-only settings; Swappr requests authenticate only with the corresponding secret API key. Payouts and account enquiries use the key matching the request environment. NGN bank synchronization defaults to the sandbox key; use `php artisan swappr:sync-banks NGN --mode=live` to use the live key. The existing `SWAPPR_API_KEY` setting remains supported only when its key prefix matches the selected environment.

CAD Interac collections can be routed between Blaaiz and Swappr using the separate CAD collection switches on the Payment Providers admin page. The first enabled collection provider by priority is selected; payment/payout routing is unaffected. Blaaiz CAD collections remain enabled by default, while Swappr CAD collections must be enabled manually. Set `SWAPPR_CAD_INTERAC_EMAIL` to the registered Swappr recipient address before enabling Swappr. Swappr uses a shared merchant address rather than creating an individual payment request; these deposits remain pending for manual reconciliation and are not automatically credited to a Flovide wallet. The generated transaction reference should be included in the payer's Interac message where supported.

For NGN account-name lookups, the existing `/account-inquiry` route uses Swappr's `POST /name-enquiry` endpoint when Swappr is the selected provider. The local Swappr bank list must include the submitted bank code; synchronize banks before using the lookup.

Some Swappr merchant accounts require sender identity on every payout. For those accounts, include a `sender` object in the Flovide payout request. Supply the actual originator's verified details; dates must be `DDMMYYYY`, country must be a two-letter uppercase ISO code, and `id_type` must be `Passport`, `Driver's License`, `National-ID`, or `Residence Card`. Do not guess or use placeholder identity data.

```json
{
  "sender": {
    "first_name": "ACTUAL LEGAL FIRST NAME",
    "last_name": "ACTUAL LEGAL LAST NAME",
    "id_type": "National-ID",
    "id_number": "ACTUAL ID NUMBER",
    "id_expiry": "31122030",
    "date_of_birth": "15081990",
    "country": "NG"
  }
}
```

The sender block is forwarded on business, personal, and versioned API payout flows. It is optional for accounts that do not have this Swappr compliance requirement. Treat the identity values as sensitive personal data and send them only over HTTPS.

For this merchant's NGN payouts, configure the seven `SWAPPR_SENDER_*` values in the server environment using the real originator's details. The app inserts this sender object into each NGN Swappr payout automatically. Dates must use `DDMMYYYY`, `SWAPPR_SENDER_COUNTRY` must be a two-letter uppercase ISO code, and the ID type must be `Passport`, `Driver's License`, `National-ID`, or `Residence Card`. Payouts are rejected before reaching Swappr if these values are missing or invalid; no placeholder identity data is sent.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
