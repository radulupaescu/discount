# Discount microservice based on lumen

## Short description

This repository defines a microservice that computes and applies discounts to orders.

It has two routes:
- POST /api/discounts/get
- POST /api/discounts/apply

After cloning this this repository you must run composer to fetch all dependencies.

The microservice will be available at:
[your-installation-path]/public/index.php?[api-route]

URL rewriting is not provided as this is dependent on the container/web server configuration, but should be trivial.

In the *example-orders* folder you'll find some orders in JSON format to be passed to a REST client of your choosing.

## Microservice data flow

### Before entering the controller

This is an order example:

```json
{
  "id": "1",
  "customer-id": "1",
  "items": [
    {
      "product-id": "B102",
      "quantity": "10",
      "unit-price": "4.99",
      "total": "49.90"
    }
  ],
  "total": "49.90"
}
```

When the service is called with an order, the following steps happen before entering the controller:

1. Caller authorization (HeaderAuthorizationMiddleware)
2. Data sanitization (SanitizationMiddleware)
3. Order Parsing (ParseOrderMiddleware)

The first two middlewares are not implemented, their purpose is to provide a place to plugin this functionality.

*ParseOrderMiddleware* receives the order, fetches all the necessary data from the repositories and attempts to build an OrderModel.

If building is successful, an OrderModel is put on the container to be used by the subsequent steps.

### In the controller

Depending on the route called, a specific controller method will be accessed. These methods will use the *DiscountService* implemented to cycle through the defined discounts and compile and/or apply these discounts on the order.

After receiving a response from the service, the controller formats a response and returns it to the caller.

### In the service

The discount service has a property called *$availableDiscounts*.

```php
protected $availableDiscounts = [
    TwentyPercentOnCheapestThirdTool::class,
    SixthForFree::class,
    GoldCustomerDiscount::class
];
```  

This property is an array holding the FQCN of each discount model implemented in the service. When testing if an order is eligible for a specific discount, the service cycles through this array and instantiates each model.

### The Discount model

If you'd like to implement a new discount to be used by this service, you should extend the AbstractDiscount class. The two methods that your discount needs to implement are:

- isOrderEligible
- getApplicableDiscountItems

```php
/**
 * @return bool
 */
public function isOrderEligible()
{
    // ...
}

/**
 * @return OrderDiscountModel[]
 */
public function getApplicableDiscountItems()
{
    // ...
}
```

AbstractDiscount class has a helper method called *buildDiscountItem* that offers a nice way to return an OrderDiscountModel to be put on the order, with the following signature: 

```php
protected function buildDiscountItem(float $price, int $quantity){}
```

## The responses

### POST /api/discounts/get

This call will return a JSON formatted response containing the list of discounts and/or extra items the original order is eligible for.

Example response for the above order given:

```json
[
  {
    "discount-name":"Sixth switch for free",
    "discount-code":"SFF",
    "quantity":1,
    "unit-price":-4.99,
    "total":-4.99
  }
]
```

### POST /api/discounts/apply

This call will return a JSON formatted response containing the original order with all the discounts applied and a few more properties detailing the applied discounts.

Example response for the above order given:

```json
{
  "id":1,
  "customer-id":1,
  "items":[
    {
      "product-id":"B102",
      "quantity":10,
      "unit-price":4.99,
      "total":49.9
    }
  ],
  "discounted-items":[
    {
      "discount-name":"Sixth switch for free",
      "discount-code":"SFF",
      "quantity":1,
      "unit-price":-4.99,
      "total":-4.99
    }
  ],
  "total":49.9,
  "discount":-4.99,
  "discounted-total":44.91
}
```

## What's next?

Not necessarily in this order, but the following issues should be attended:

- repository abstraction so it could be configured in the .env file
- more clear docblocks (to be able to use a documentation generator tool)
- docblocks for tests with short description of what is being tested
- test the exceptions (or to be more precise, test that the correct exceptions are thrown and caught)
- implement default header authorization mechanism
- implement default sanitization
- richer exception reporting to the callers
- some exceptions can be just logged not necessarily reported (for example wrong totals on order could trigger recalculation and logging of bad input data)
