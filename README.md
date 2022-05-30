# Payhook SDK PHP

Payhook Software Development Kit for PHP.

[API Documentation](https://docs.payhook.org)

## Installation

Requirements:

- PHP 7.4 or higher

```shell
composer require payhook/sdk
```

## Usage

```php
use Payhook\Sdk\Payhook;

$payhook = new Payhook('your_api_key');

$payhook->createPayment([
    'title' => 'Test payment',
    'currency' => 'USD',
    'amount' => Payhook::moneyToNanos('12.34'),
]);
```

### Available Methods

#### `createPayment(array $params): array`

Create a new payment.

#### `getPayment(int $id): array`

Get payment by id.

#### `deletePayment(int $id): void`

Delete payment by id.

#### `isWebhookValid(string $id, string $event, string $signature): bool`

Check whether webhook is not corrupted.

#### `generateSignature(string $id, string $event): string`

Generate webhook signature.

### Additional functions

#### `Payhook::moneyToNanos(string $money): string`

Convert money to nanos.

#### `Payhook::nanosToMoney(string $nanos): string`

Convert nanos to money.

## Licence

Copyright 2022 Payhook

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
