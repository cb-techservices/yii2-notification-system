# Yii2 Notification System
This Yii2 extension provides a fully functional notification system backed with ActiveRecord and a customizable UI.

Installation
------------
##### Composer
```shell
php composer.phar require cbtech/yii2-notification-system "*"
```
or add
```json
"cbtech/yii2-notification-system": "*"
```
to the require section of your `composer.json` file.

Configuration
------------
Before using this module, you have to run its migrations scripts. This will add the notification table to your database.

Run this command from the root of your Yii project:
```bash
./yii migrate/up --migrationPath=vendor/cbtech/yii2-notification-system/migrations/
```

Add the following to the `modules` section of your Yii project config.
```php
'modules'=>[
    'notifications' => [
        'class' => 'cbtech\notification_system\NotificationSystemModule',
        //The controller's namespace for where to find the Controller actions.
        //You may use the default NotificationsController provided or create your own custom controller.
        'controllerNamespace' => 'cbtech\notification_system\controllers',
        // Point this to your own Notification class
        // See the "Declaring your notifications" section below
        'notificationClass' => 'common\models\Notification',
        // Allow to have notification with same (user_id, key, key_id)
        // Default to FALSE
        'allowDuplicate' => true,
        // Allow custom date formatting in database
        'dbDateFormat' => 'Y-m-d H:i:s',
		// This callable should return your logged in user Id
        'userId' => function() {
            return \Yii::$app->user->id;
        },
        'expirationTime'=>0
    ],
],
```
### Module Parameters

### Declaring your notifications
Your custom Notification class must **extend** `cbtech\notification_system\models\NotificationBase`

An example is provided in [/examples/Notification.php](/examples/Notification.php)


Usage
------------

### Triggering a notification


```php

// A connection request made by a user to the $recipient_id
Notification::notify(Notification::KEY_NEW_CONNECTION_REQUEST, $recipient_id, $connectionRequest->id);

// You may also use the following static methods to set the notification type:
Notification::warning(Notification::KEY_NEW_MESSAGE, $recipient_id, $message->id);
Notification::success(Notification::ORDER_PLACED, $admin_id, $order->id);
Notification::error(Notification::KEY_NO_DISK_SPACE, $admin_id);

```
          
### Listening and showing notifications in the UI
This extension comes with a `NotificationsWidget` that is used to regularly poll the server for new notifications.

### Widget Parameters

| Parameter             | Description                                                                                     | Default     |
| --------------------  | ----------------------------------------------------------------------------------------------- | -----------:|
| pollUrl               | The URL for the poll() for new notifications controller action                                  | false       |
| markAsReadUrl         | The URL for the controller action that marks an individual notification as read                 | false       |
| markAsUnreadUrl       | The URL for the controller action that marks an individual notification as unread               | false       |
| flashUrl              | The URL for the controller action that marks an individual notification as having been flashed  | false       |
| readAllUrl            | The URL for the controller action that marks all notifications as read                          | false       |
| unreadAllUrl          | The URL for the controller action that marks all notifications as unread                        | false       |
| delay                 | The time to leave the notification shown on screen                                              | 5000        |
| pollInterval          | The delay in milliseconds between polls                                                         | 5000        |
| xhrTimeout            | The XHR request timeout in milliseconds                                                         | 2000        |
| counters              | An array of jQuery selectors to update with the current notifications count                     | []          |
| markAllReadSelector   | The jQuery selector for the Mark All as Read button                                             | null        |
| markAllUnreadSelector | The jQuery selector for the Mark All as Unread button                                           | null        |
| viewAllSelector       | The jQuery selector for your UI element that will holds the notification list                   | null        |
| viewUnreadSelector    | The jQuery selector for the View Unread button                                                  | null        |
| headerSelector        | The jQuery selector for the Notifications header view                                           | null        |
| headerTemplate        | The header HTML template                                                                        | null        |
| headerTitle           | The header title string                                                                         | "Notifications" |
| listSelector          | The jQuery selector for the View All button                                                     | null        |
| listItemTemplate      | An optional template for the list item.                                                         | built-in    |
