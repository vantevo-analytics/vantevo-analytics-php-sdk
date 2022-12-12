# The analytics of your website, but simpler
 
**Vantevo Analytics** is the alternative platform to Google Analytics that respects privacy, because it does not need cookies not compliant with the GDPR. Easy to use, light and can be integrated into any website and back-end.
 
This is the official PHP client SDK for Vantevo Analytics. For more information visit the website  [vantevo.io](https://vantevo.io).

## Installation
 
You can install the SDK using Composer or by copying the `src/Client.php` directly.

`composer require vantevo-analytics/sdk`

## Usage 

To start tracking page views, events, and getting statistics, you need to initialize the client first:

| Option        | Type                  | Description                 |
| ------------- | --------------------- | ----------------------------|
| accessToken   | `string`  (required)  | To create an api key read our [guide](https://vantevo.io/docs/account/impostazioni).|
| domain        | `string`  (required)  | Enter the domain you want to use to collect statistics.The domain must not include http, https, or www. Example: example.com  |
| timeout       | `int`     (optional)  |  You can change the request timeout, you need to enter a number in seconds. Default `30`.|
| dev           | `boolean` (optional)  | Tracker will not send data to server, the client execute print the data. Default `false`. |


```php
require __DIR__ . '/vendor/autoload.php'; // or require_once 'src/Client.php';

$client = new Vantevo\Client('accessToken', 'domain', 'timeout' 'dev');
```

## Tracking page views and events

**Parameters**

| Option        | Type                  | Description                 |
| ------------- | --------------------- | ----------------------------|
| event  	    | `array`  (required)   | See event parameters. |

**parameters**
| Option        | Type                  | Description                 |
| ------------- | --------------------- | ----------------------------|
| event         | `string`  (required)  | Event name, remember that the name `pageview` will send a pageview event. |
| url           | `string`  (optional)  | Enter url you want to save in the statistics. Default is where the client is installed. |
| title         | `string`  (optional)  | You can insert a title of the page, if this field is not used vantevo will insert the pathname of the url used. |
| referrer      | `string`  (optional)  | In this field you can enter a referrer for your request.  Default `$_SERVER[HTTP_REFERER]` or the client checks query parameters: `ref, referrer, source, utm_source`. |
| width         | `string`  (optional)  | This field is used to save the screen size. Default: `0`.|
| height        | `string`  (optional)  | This field is used to save the screen size.  Default: `0`. |
| meta          | `array`   (optional)  | Enter the event values `meta_key` and` meta_value`, [read more how to create an event](https://vantevo.io/docs/come-creare-un-evento#evento)  Default: `array`. |

### Example pageview

```php
try {
    $data = array("event" => "pageview", "title" => "Eaxmple Page view");
	$client->event($data);
} catch (Exception $e) {
	// something went wrong...
}
```

### Example event

```php
try {
    $data = array("event" => "Download", "meta" => array("pdf": "Recipes"));
	$client->event($data);
} catch (Exception $e) {
	// something went wrong...
}
```

## Monitoring Ecommerce

In the ecommerce section of Vantevo you can monitor specific actions affecting your ecommerce website and the sources of traffic that lead to sales.

| Option        | Type                  | Description                 |
| ------------- | --------------------- | ----------------------------|
| event  	    | `array`  (required)   | See event parameters. |

**parameters**
| Option        | Type                  | Description                 |
| ------------- | --------------------- | ----------------------------|
| event         | `string`  (required)  | Event name, remember that the name `pageview` will send a pageview event. |
| url           | `string`  (optional)  | Enter url you want to save in the statistics. Default is where the client is installed. |
| title         | `string`  (optional)  | You can insert a title of the page, if this field is not used vantevo will insert the pathname of the url used. |
| referrer      | `string`  (optional)  | In this field you can enter a referrer for your request.  Default `$_SERVER[HTTP_REFERER]` or the client checks query parameters: `ref, referrer, source, utm_source`. |
| width         | `string`  (optional)  | This field is used to save the screen size. Default: `0`.|
| height        | `string`  (optional)  | This field is used to save the screen size.  Default: `0`. |
| total         | `float`   (optional)  | The total amount of the order Example: 1255.00 Default: `0`. |
| coupon        | `string`  (optional)  | The name of the coupon used. Example: newsletter |
| coupon_value  | `float`   (optional)  | The value of the coupon used. Example: 45.00 |
| payment_type  | `string`  (optional)  | The mode of payment. Example: Payal o Carta di credito. |
| items         | `array`   (required)  | The product information, [read more](https://vantevo.io/docs/ecommerce/index). |


### List events

These are the events to use to monitor your ecommerce:

| Event              | Description                                 |
| :----------------- | :------------------------------------------ |
| `add_to_wishlist`  | a user adds a product to the favorites list |
| `view_item`        | a user views a product                      |
| `remove_item_cart` | a user removes a product from the cart      |
| `add_item_cart`    | a user adds product to the cart             |
| `start_checkout`   | a user has started the checkout process     |
| `checkout_info`    | a user submits personal data                |
| `checkout_ship`    | a user submits shipments data               |
| `checkout_payment` | a user initiated the payment process        |
| `purchase`         | a user has completed a purchase             |


### Example

An example for how to use the `trackEcommerce` function.

```php
try {
    $data = array(
		"event" => "view_item",
		"items" => array(
			array(
				"item_id" => "SKU_123",
				"item_name" => "Samsung Galaxy",
				"currency" => "EUR",
				"quantity" => 0,
				"price" => 199.99,
				"discount" => 0,
				"position" => 1,
				"brand" => "Samsung",
				"category_1" => "Smartphone",
				"category_2" => "Samsung",
				"category_3" => "Galaxy",
				"category_4" => "",
				"category_5" => "",
				"variant_1" => "Black",
				"variant_2" => "5.5 inch",
				"variant_3" => ""
			)
		)
		);
	$client->trackEcommerce($data);
} catch (Exception $e) {
	// something went wrong...
}
```

Read our [guide](https://vantevo.io/docs/ecommerce/index/?utm_source=npm&utm_medium=vantevo-analytics-tracker) for more information of how to use the ecommerce tracking function.





## How to get the statistics

**Parameters**

| Option        | Type                  | Description                 |
| ------------- | --------------------- | ----------------------------|
| event         | `array`  (required)  | Check out our guide to see all the parameters you can use like, [click here](https://vantevo.io/docs/api-sdk/api-statistiche#parametri). |

### Example Statistics

```php
try {
    $data = array("source" => "pages", "period" => "1m");
	$stats = $client->stats($data);
} catch (Exception $e) {
	// something went wrong...
}
```

### Example Events

```php
try {
    $data = array("source" => "events", "period" => "1m");
	$events = $client->events($data);
} catch (Exception $e) {
	// something went wrong...
}
```

## Vantevo Analytics guide
 
To see all the features and settings of Vantevo Analytics we recommend that you read our complete guide [here](https://vantevo.io/docs?utm_source=npm&utm_medium=vantevo-analytics-tracker).
 
















