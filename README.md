![alt tag](https://thawani.om/wp-content/uploads/2022/12/Logo-Thawani.png)

# jkbroot/thawani
This Laravel package facilitates easy integration with the Thawani payment gateway, offering a straightforward and intuitive set of functionalities for payment session creation, customer management, and transaction handling, all within your Laravel application.

# Features
* Easy integration with Laravel applications
* Support for creating and managing Thawani checkout sessions
* Customer management utilities
* Payment and refund handling
* Support for payment intents

**Requirements**
* PHP 7.3 or higher
* Laravel 6.0 or higher

## Installation
```bash
composer require jkbroot/thawani
```
This package supports Package Auto-Discovery, which was introduced in Laravel 5.5. Therefore, it doesn't require manual registration of the ServiceProvider in most cases.

If you're using a version of Laravel where auto-discovery is not supported or have specifically disabled it, you need to register the ServiceProvider manually.

Add the ServiceProvider to the providers array in config/app.php:

```php

'providers' => [
    // Other Service Providers

    Jkbroot\Thawani\ThawaniServiceProvider::class,
],

```

**Configuration:** 

Publish the configuration file by running the following Artisan command. This will publish the Thawani configuration file to config/thawani.php, where you can customize your settings.

```bash
php artisan vendor:publish --tag=config
```
_Copy the Thawani Pay configuration snippet you provided and paste it into your .env file. However,The THAWANI_MODE should be set to live instead of test for a production environment :_
```
#Thawani Pay Configuration for Production Environment
THAWANI_LIVE_SECRET_KEY=your_thawani_live_secret_key
THAWANI_LIVE_PUBLISHABLE_KEY=your_thawani_live_publishable_key
#Thawani mode change to (live) for production.
THAWANI_MODE=test
```
Replace your_thawani_live_secret_key and your_thawani_live_publishable_key with your actual Thawani live secret and publishable keys.

## Instantiating ThawaniPay Class

To begin using the Thawani payment functionality, you need to instantiate the `ThawaniPay` class. Follow these steps:

### Step 1: Import ThawaniPay Class

Before you can create an instance of the `ThawaniPay` class, ensure that you import it into your PHP file using the `use` statement:

```php
use Jkbroot\Thawani\ThawaniPay;
```

### Step 2: Instantiate ThawaniPay Class
Once the class is imported, you can instantiate it using the following code:
```php
$thawani = new ThawaniPay();
```
This creates an instance of the ThawaniPay class, allowing you to utilize its methods and properties for handling Thawani payments within your Laravel application.

**Additional Notes**
* Ensure that you have installed the jkbroot/thawani package via Composer before attempting to use it.
* Review the package documentation for any specific configurations, requirements, or additional functionality provided by the package.


# How to use :
## 1 - checkout & session

- Create session :
```php
use Jkbroot\Thawani\ThawaniPay;

$thawani = new ThawaniPay();


$data = [
    "client_reference_id" => "123412",
    "mode" => "payment",
    "products" => [
        [
            "name" => "product 1",
            "quantity" => 1,
            "unit_amount" => 100,
        ],
    ],
    "success_url" => "https://company.com/success",
    "cancel_url" => "https://company.com/cancel",
    "metadata" => [
        "Customer name" => "somename",
        "order id" => 0,
    ],
];

$session = $thawani->checkoutSessions->create($data);

```
Create session Response : 

```php
[
    "success" => true
    "code" => 2004
    "description" => "Session generated successfully"
    "data" => array:16 [
        "mode" => "payment"
        "session_id" => "checkout_0Cp4idrQaNFMws8YFJH020FJA3I4ITwkvmk2RxUc9Les5kY3ym"
        "client_reference_id" => "123412"
        "customer_id" => null
        "products" => [
          0 => [
            "name" => "product 1"
            "unit_amount" => 100
            "quantity" => 1
          ]
        ]
        "total_amount" => 100
        "currency" => "OMR"
        "success_url" => "https://company.com/success"
        "cancel_url" => "https://company.com/cancel"
        "return_url" => null
        "payment_status" => "unpaid"
        "invoice" => "20240228270008"
        "save_card_on_success" => false
        "metadata" => [
          "Customer name" => "somename"
          "order id" => "0"
        ]
        "created_at" => "2024-02-28T15:19:35.2937785Z"
        "expire_at" => "2024-02-29T15:19:35.2204113Z"
    ]
]
```


- Retrieve session :
```php
$session_id = "checkout_0Cp4idrQaNFMws8YFJH020FJA3I4ITwkvmk2RxUc9Les5kY3ym";
$session = $thawani->checkoutSessions->retrieve($session_id);
```
Retrieve session Response : 
```php
[
  "success" => true
  "code" => 2000
  "description" => "session retrieved successfully"
  "data" =>  [
    "mode" => "payment"
    "session_id" => "checkout_0Cp4idrQaNFMws8YFJH020FJA3I4ITwkvmk2RxUc9Les5kY3ym"
    "client_reference_id" => "123412"
    "customer_id" => null
    "products" => [
      0 => [
        "name" => "product 1"
        "unit_amount" => 100
        "quantity" => 1
      ]
    ]
    "total_amount" => 100
    "currency" => "OMR"
    "success_url" => "https://company.com/success"
    "cancel_url" => "https://company.com/cancel"
    "return_url" => null
    "payment_status" => "unpaid"
    "invoice" => "20240228270008"
    "save_card_on_success" => false
    "metadata" =>[
      "Customer name" => "somename"
      "order id" => "0"
    ]
    "created_at" => "2024-02-28T15:19:35.2937785"
    "expire_at" => "2024-02-29T15:19:35.2204113"
  ]
]
```

- Cancel session :

```php
$session_id = "checkout_0Cp4idrQaNFMws8YFJH020FJA3I4ITwkvmk2RxUc9Les5kY3ym";
$session = $thawani->checkoutSessions->cancel($session_id);
```
Cancel session Response : 
```php
[
  "success" => true
  "code" => 2105
  "description" => "Checkout session cancelled successfully"
]
```
- list Sessions :

```php
$sessions = $thawani->checkoutSessions->list();

//for limit the lists and paginate between lists (limit,skip)
$sessions = $thawani->checkoutSessions->list(10,0);

```

list Sessions Response : 
```php
[
  "success" => true
  "code" => 2000
  "description" => "sessions retrieved successfully"
  "data" => [/*'array off Sessions'*/]
]
```

- create Checkout Url :

```php
$data = [
    "client_reference_id" => "123412",
    "mode" => "payment",
    "products" => [
        [
            "name" => "product 1",
            "quantity" => 1,
            "unit_amount" => 100,
        ],
    ],
    "success_url" => "https://company.com/success",
    "cancel_url" => "https://company.com/cancel",
    "metadata" => [
        "Customer name" => "somename",
        "order id" => 0,
    ],
];

$checkoutUrl = $thawani->checkoutSessions->createCheckoutUrl($data);

```
- create Checkout Url Response:

```php
[
  "session_id" => "checkout_5Q3IPYhiWaOIDotTqHJMjtaklHoHeWTjT6iTPaT0kQSTSZF3Za"
  "redirect_url" => "https://uatcheckout.thawani.om/pay/checkout_5Q3IPYhiWaOIDotTqHJMjtaklHoHeWTjT6iTPaT0kQSTSZF3Za?key=HGvTMLDssJghr9tlN9gr4DVYt0qyBy"
]
```
- session_id: A unique identifier for the created checkout session.
- redirect_url: The URL to which you should redirect your customers to complete the payment process. This URL leads to Thawani's payment gateway, where the customer can enter their payment details securely.


## 2 - Customers

- create customer :
```php
$data = [
        "client_customer_id" => "customer@example.com"
    ];
    
$customer = $thawani->customers->create($data);
```
- create customer Response:

```php
[
  "success" => true
  "code" => 2001
  "description" => "Customer added successfully"
  "data" =>[
    "id" => "cus_pbW3HE7eG81zRvJX"
    "customer_client_id" => "customer@example.com"
  ]
]
```

- retrieve customer :

```php
$customerId = 'cus_pbW3HE7eG81zRvJX';
$customer = $thawani->customers->retrieve($customerId);
```

- retrieve customer Response:

```php
[
  "success" => true
  "code" => 2000
  "description" => "Customers retrieved successfully"
  "data" => [
    "id" => "cus_pbW3HE7eG81zRvJX"
    "customer_client_id" => "customer@example.com"
  ]
]
```

- delete customer :
```php
$customerId = 'cus_pbW3HE7eG81zRvJX';
$customer = $thawani->customers->delete($customerId);
```
- list customers :
````php
$customers = $thawani->customers->list();

//for limit the lists and paginate between lists (limit,skip)
$customers = $thawani->customers->list(10,0);
````


## 3 - Pyament Intents

- list payment intent :

```php
$paymentIntents = $thawani->paymentIntents->list();

//for limit the lists and paginate between lists (limit,skip)

$paymentIntents = $thawani->paymentIntents->list(10,0);
```
- list payment intent Response:

```php
[
  "success" => true
  "code" => 2000
  "description" => "sessions retrieved successfully"
  "data" => [/*'array off Payment intents'*/]
]
```

- create payment intent :

```php
$data = [
    "amount" => 100,
    "payment_method" => "card_zK5a7sd98wdwe78TbiSUyLUjann6xFx",
    "description" => "Payment for order 123412",
    "client_reference_id" => "123412",
    "return_url" => "https://thw.om/success",
    "metadata" => [
        "Customer name" => "somename",
    ],
];

$paymentIntent = $thawani->paymentIntents->create($data);
```
- create payment intent Response:

```php
{
  "success": true,
  "code": 0,
  "description": "string",
  "data": {
    "id": "string",
    "client_reference_id": "string",
    "amount": 0,
    "currency": "string",
    "payment_method": "string",
    "next_action": {
      "url": "string",
      "return_url": "string"
    },
    "status": "requires_payment_method",
    "metadata": {},
    "created_at": "2019-08-24T14:15:22Z",
    "expire_at": "2019-08-24T14:15:22Z"
  }
}
```

# Here's a concise explanation for each method in the supported classes of your Laravel package for integrating the Thawani payment gateway:

### CheckoutSessions
- `create`: Initializes a new payment session.
- `retrieve`: Fetches details of a specific session.
- `cancel`: Cancels an existing session.
- `list`: Lists all sessions with optional pagination.
- `createCheckoutUrl`: Generates a URL for the payment checkout page.
- `retrieveByClientReference`: Retrieves a session using a client-provided reference ID.
- `retrieveByInvoice`: Retrieves a session using an invoice number.

### Customers
- `create`: Registers a new customer in the payment system.
- `retrieve`: Retrieves information about a specific customer.
- `delete`: Removes a customer from the system.
- `list`: Lists all customers with optional pagination.

### PaymentIntents
- `create`: Creates a payment intent to initiate a transaction.
- `retrieve`: Fetches details of a specific payment intent.
- `cancel`: Cancels an unresolved payment intent.
- `list`: Lists payment intents with optional pagination.
- `confirm`: Confirms a payment intent, attempting to finalize the transaction.


### PaymentMethods
- `list`: Lists all payment methods associated with a given customer ID.
- `delete`: Deletes a specified payment method from a customer's account.

### Refunds
- `create`: Initiates a refund for a specific transaction.
- `retrieve`: Retrieves details of a specific refund.
- `list`: Lists all refunds with optional pagination.

This overview provides a quick guide to the functionalities available in your Laravel package for integrating Thawani payment services.
