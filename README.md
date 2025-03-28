![alt tag](https://www.gstatic.com/devrel-devsite/prod/v5ab6fd0ad9c02b131b4d387b5751ac2c3616478c6dd65b5e931f0805efa1009c/firebase/images/lockup.svg)

# elmogy/fcm
A Laravel package for FCM (Firebase Cloud Messaging) integration . 

# Features
* Easy integration with Laravel applications

**Requirements**
* PHP 8.0 
* apiclient ^2.0

## Installation
```bash
composer require elmogy/fcm
```


**Configuration:** 

_Copy the Thawani Pay configuration snippet you provided and paste it into your .env file. However,The THAWANI_MODE should be set to live instead of test for a production environment :_
```
# Elmogy Fcm Configuration for Production Environment
FIREBASE_PROJECT_ID=YOUR_PROJECT_ID
FIREBASE_FILE=FIREBASE_FILE

```

## Instantiating FCM Class

To begin using the FCM functionality, you need to instantiate the `elmogy/fcm` class. Follow these steps:

### Step 1: Import FCM Class

Before you can create an instance of the `elmogy/fcm` class, ensure that you import it into your PHP file using the `use` statement:

```php
use elmogy\fcm\FCMService;
```

### Step 2: Instantiate FCM Class
Once the class is imported, you can instantiate it using the following code:
```php
 $fcmService = new FCMService();
```
This creates an instance of the FCM class, allowing you to utilize its methods and properties for handling FCM within your Laravel application.


# How to use :
## 1 - checkout & session

- Create session :
```php
    use elmogy\fcm\FCMService;

    $fcmService = new FCMService();

    $fcmToken = "DEVICE_TOKEN"; // Replace with the actual FCM token
    $title = "Test Notification";
    $body = "This is a test message.";
    $data = " ['key' => 'value']"; // Optional

    $response = $fcmService->sendFCM($fcmToken, $title, $body , $data);
   

```
