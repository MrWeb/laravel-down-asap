# laravel-down-when-possible

Put Laravel "down" (maintenance mode) as soon as the DB is not used for the selected amount of minutes avoiding users to be interrupted while doing some other actions.

### Basic Usage:

`art down:asap`

### Options:

You can specify how much the DB idle time has to be (in minutes) by setting the `idle` flag. By default the idle time is 1 minute: `art down:asap --idle:10`

You can specify after how many seconds the system has to retry the command by setting the `retry` flag. By default the retry time is 30 seconds: `art down:asap --retry:30`

