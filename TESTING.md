# Running tests

there are a few additional steps needed to get tests working. For every database you wish to test,
you need that database running on your local machine.

see [test/helpers/config.php](test/helpers/config.php) to see what DB connections the tests expect.
TLDR: they connect to localhost with name/pass root:foo.


The most important DB to us is MySQL, since we use the MySQL adapter (Mariadb) for our servers. Also,
it's the default connection that ActiveRecord expects, so the majority of tests will be skipped/broken
without it.

Sample install on MacOS:

```bash
brew install mysql
brew services start mysql
mysql_secure_installation
```

the last command will walk you through setup, this is where you can set the root password.
or, if you already have an existing DB with a different root password, set the `PHPAR_MYSQL`
environment variable to your database URL. Make sure not to delete the default test database,
as the tests expect it to be present.
