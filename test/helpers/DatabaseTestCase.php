<?php

use ActiveRecord\Config;
use ActiveRecord\ConnectionManager;
use ActiveRecord\DatabaseException;
use ActiveRecord\Table;
use ActiveRecord\UndefinedPropertyException;

require_once __DIR__.'/DatabaseLoader.php';

class DatabaseTestCase extends SnakeCase_PHPUnit_Framework_TestCase
{
    protected $conn;

    public static $log = false;

    public static $db;

    protected $original_default_connection;

    protected $original_date_class;

    protected $connection_name;

    public function set_up($connection_name = null)
    {
        Table::clear_cache();

        $config = Config::instance();
        $this->original_default_connection = $config->get_default_connection();

        $this->original_date_class = $config->get_date_class();

        if ($connection_name) {
            $config->set_default_connection($connection_name);
        }

        if (
            $connection_name == 'sqlite' ||
            $config->get_default_connection() == 'sqlite'
        ) {
            // need to create the db. the adapter specifically does not create it for us.
            static::$db = substr(
                Config::instance()->get_connection('sqlite'),
                9,
            );
            new SQLite3(static::$db);
        }

        $this->connection_name = $connection_name;
        try {
            $this->conn = ConnectionManager::get_connection(
                $connection_name,
            );
        } catch (DatabaseException $e) {
            $this->mark_test_skipped(
                $connection_name.' failed to connect. '.$e->getMessage(),
            );
        }

        $GLOBALS['ACTIVERECORD_LOG'] = false;

        $loader = new DatabaseLoader($this->conn);
        $loader->reset_table_data();

        if (self::$log) {
            $GLOBALS['ACTIVERECORD_LOG'] = true;
        }
    }

    public function tear_down()
    {
        Config::instance()->set_date_class(
            $this->original_date_class,
        );
        if ($this->original_default_connection) {
            Config::instance()->set_default_connection(
                $this->original_default_connection,
            );
        }
    }

    public function assert_exception_message_contains($contains, $closure)
    {
        $message = '';

        try {
            $closure();
        } catch (UndefinedPropertyException $e) {
            $message = $e->getMessage();
        }

        $this->assertStringContainsString($contains, $message);
    }

    /**
     * Returns true if $regex matches $actual.
     *
     * Takes database specific quotes into account by removing them. So, this won't
     * work if you have actual quotes in your strings.
     */
    public function assert_sql_has($needle, $haystack)
    {
        $needle = str_replace(['"', '`'], '', $needle);
        $haystack = str_replace(['"', '`'], '', $haystack);

        return $this->assertStringContainsString($needle, $haystack);
    }

    public function assert_sql_doesnt_has($needle, $haystack)
    {
        $needle = str_replace(['"', '`'], '', $needle);
        $haystack = str_replace(['"', '`'], '', $haystack);

        return $this->assertStringNotContainsString($needle, $haystack);
    }

    public function test_true()
    {
        $this->assertTrue(true);
    }
}
