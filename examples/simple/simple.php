<?php

use ActiveRecord\Config;
use ActiveRecord\Model;

require_once __DIR__.'/../../ActiveRecord.php';

// assumes a table named "books" with a pk named "id"
// see simple.sql
class Book extends Model {}

// initialize ActiveRecord
// change the connection settings to whatever is appropriate for your mysql server
Config::initialize(function ($cfg): void {
    $cfg->set_model_directory('.');
    $cfg->set_connections([
        'development' => 'mysql://test:test@127.0.0.1/test',
    ]);
});

print_r(Book::first()->attributes());
