# laravel-down-when-possible

Put Laravel "down" (maintenance mode) as soon as the DB (MySQL) is not used for the selected amount of minutes avoiding users to be interrupted while doing some other actions.

### Installation:

`composer require mrweb/laravel-down-asap`

### Basic Usage:

`php artisan down:asap`

### Options:

You can specify how much the DB (MySQL) idle time has to be (in minutes) by setting the `idle` flag. By default the idle time is 3 minutes: `php artisan down:asap --idle:10`

You can specify after how many seconds the system has to retry the command by setting the `retry` flag. By default the retry time is 30 seconds: `php artisan down:asap --retry:30`

