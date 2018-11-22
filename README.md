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
| Parameter             |  Type   | Description                                                                                     | Default     |
| :-------------------- | ------- | :---------------------------------------------------------------------------------------------- |:----------- |
| class | String | The required class path to the NotificationSystemModule | 'cbtech\notification_system\NotificationSystemModule' | 
| controllerNamespace | String | The controller's namespace for where to find the Controller actions.  You may use the default NotificationsController provided or create your own custom controller. | 'cbtech\notification_system\controllers' |
| notificationClass | String | Point this to your own Notification class. See the "Declaring your notifications" section below. | 'common\models\Notification' |
| allowDuplicate | Boolean | Allow to have notifications with the same user_id, key, key_id | false |
| dbDateFormat | String | Allows custom date formatting in databse | 'Y-m-d H:i:s' |
| userId | callable/integer | This callable should return your logged in user Id | ``` function() { return \Yii::$app->user->id; } ``` |

### Declaring your notifications
Your custom Notification class must **extend** `cbtech\notification_system\models\NotificationBase`

An example is provided in [examples/Notification.php](examples/Notification.php)


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

| Parameter             |  Type   | Description                                                                                     | Default     |
| :-------------------- | ------- | :---------------------------------------------------------------------------------------------- |:----------- |
| pollUrl               | String  | The URL for the poll() for new notifications controller action                                  | '/notifications/notifications/poll'       |
| markAsReadUrl         | String  | The URL for the controller action that marks an individual notification as read                 | '/notifications/notifications/read'       |
| markAsUnreadUrl       | String  | The URL for the controller action that marks an individual notification as unread               | '/notifications/notifications/unread'     |
| flashUrl              | String  | The URL for the controller action that marks an individual notification as having been flashed  | '/notifications/notifications/flash'      |
| readAllUrl            | String  | The URL for the controller action that marks all notifications as read                          | '/notifications/notifications/read-all'   |
| unreadAllUrl          | String  | The URL for the controller action that marks all notifications as unread                        | '/notifications/notifications/unread-all' |
| delay                 | Integer | The time to leave the notification shown on screen                                              | 5000        |
| pollInterval          | Integer | The delay in milliseconds between polls                                                         | 5000        |
| xhrTimeout            | Integer | The XHR request timeout in milliseconds                                                         | 2000        |
| counters              | Array   | An array of jQuery selectors to update with the current notifications count                     | []          |
| markAllReadSelector   | String  | The jQuery selector for the Mark All as Read button                                             | null        |
| markAllUnreadSelector | String  | The jQuery selector for the Mark All as Unread button                                           | null        |
| viewAllSelector       | String  | The jQuery selector for your UI element that will holds the notification list                   | null        |
| viewUnreadSelector    | String  | The jQuery selector for the View Unread button                                                  | null        |
| headerSelector        | String  | The jQuery selector for the Notifications header view                                           | null        |
| headerTemplate        | String  | The header HTML template. You can provide your own HTML structure and use the following variables: `{title}`,`{readAllId}`,`{unreadAllId}` | See default example below. |
| headerTitle           | String  | The header title string                                                                         | "Notifications" |
| listSelector          | String  | The jQuery selector for the View All button                                                     | null        |
| listItemTemplate      | String  | The list item HTML template. You can provide your own HTML structure and use the following variables: `{id}`,`{title}`,`{body}`,`{read}`,`{unread}`,`{timeago}`,`{footer}` | See default example below. |

### Widget Usage
Below is an example of the widget with all possible parameters.  Optional values are indicated. This should be added at the top of your main layout template.
```php
NotificationsWidget::widget([
    'pollUrl' => '/notifications/notifications/poll', //Optional, default value
    'markAsReadUrl' => '/notifications/notifications/read', //Optional, default value
    'markAsUnreadUrl' => '/notifications/notifications/unread', //Optional, default value
    'flashUrl' => '/notifications/notifications/flash', //Optional, default value
    'readAllUrl' => '/notifications/notifications/read-all', //Optional, default value
    'unreadAllUrl' => '/notifications/notifications/unread-all', //Optional, default value
    'clientOptions' => [
        'location' => 'tr',
    ],
    'delay' => 5000,
	'xhrTimeout' => 2000,
    'pollInterval' => 5000,
    'counters' => [
        '.notifications-header-count',
        '.notifications-icon-count'
    ],
    'markAllReadSelector' => '#notification-read-all',
    'markAllUnreadSelector' => '#notification-unread-all',
    'listSelector' => '#notifications',
    'viewAllSelector' => '#viewAll',
    'viewUnreadSelector' => '#viewUnread',
    'headerSelector' => '#notifications-header',
    'headerTitle' => 'Notifications',
    'headerTemplate' => 
        '<div class="col-xs-12">' . 
            '<div class="pull-left" style="font-size:14px;font-weight:bold;margin-left:10px;">{title}</div>' . 
            '<button id="{readAllId}" class="btn btn-xs btn-link pull-right" style="color:#3399ff;" data-keepOpenOnClick>Read</button>' . 
            '<button id="{unreadAllId}" class="btn btn-xs btn-link pull-right" style="color:#3399ff;" data-keepOpenOnClick>Unread</button>' . 
            '<label style="font-size:12px;padding-top:1px;" class="pull-right">Mark All as </label>' .
        '</div>', //Optional, default value
    'listItemTemplate' => 
        '<div class="notificationRow" id="notification_{id}" data-keepOpenOnClick>' .
            '<div class="col-xs-11" onclick="goToRoute(\'{id}\');">' .
                '<div class="notification-title">{title}</div>' .
                '<div class="notification-body">{body}</div>' .
            '</div>' .
            '<div class="col-xs-1">' .
                '<div class="notification-actions pull-right">{read}{unread}</div>' .
            '</div>' .
            '<div class="clearfix"></div>' . 
            '<div class="col-xs-1">' .
                '<div class="notification-timeago">{timeago}</div>' .
            '</div>' .
            '<div class="col-xs-10">' .
                '<div class="notification-footer">{footer}</div>' .
            '</div>' .
            '<div class="clearfix"></div>' . 
        '</div>', //Optional, default value
]);
```

If you have provided a value for the `headerSelector` and/or the `listItemTemplate` you can include the notifications list view by adding the following to your navbar:
```php
$menuItems[] = '<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span style="font-size:18px;top: 5px;margin-right:3px;" class="glyphicon glyphicon-bell"></span>
                        <span class="badge notifications-icon-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li id="notifications-header" style="text-align:center;"></li>
                        <li id="notifications"></li>
                        <li style="text-align:center;border-bottom:1px #cccccc solid;padding:10px;">
                            <button class="btn btn-xs btn-primary" id="viewAll" data-keepOpenOnClick>View All</button> /
                            <button class="btn btn-xs btn-primary" id="viewUnread" data-keepOpenOnClick>View Unread</button>
                        </li>
                    </ul>
                </li>';
```

#### Notifications List View
![Notifications list view](https://raw.githubusercontent.com/cb-techservices/yii2-notification-system/master/images/NotificationsListView.png)

#### Tostr Notification
![Toastr notification](https://raw.githubusercontent.com/cb-techservices/yii2-notification-system/master/images/ToastrNotification.png)

Contributors
------------
Carl Burnstein https://github.com/carlb0329

Credits
------------
Inspired by [machour/yii2-notifications](https://github.com/machour/yii2-notifications)
Uses [CodeSeven/toastr](https://github.com/CodeSeven/toastr)

License
------------
Yii2 Notification System is licensed under MIT license - https://github.com/cb-techservices/yii2-notification-system/blob/master/LICENSE <br/>
Copyright (c) 2018 CB Tech Services