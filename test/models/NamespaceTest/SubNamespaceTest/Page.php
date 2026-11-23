<?php

namespace NamespaceTest\SubNamespaceTest;

use ActiveRecord\Model;
use NamespaceTest\Book;

class Page extends Model
{
    public static $belong_to = [
        [
            'book',
            'class_name' => Book::class,
        ],
    ];
}
