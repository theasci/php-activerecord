<?php

namespace NamespaceTest;

use ActiveRecord\Model;
use NamespaceTest\SubNamespaceTest\Page;

class Book extends Model
{
    public static $belongs_to = [
        [
            'parent_book',
            'class_name' => self::class,
        ],
        [
            'parent_book_2',
            'class_name' => 'Book',
        ],
        [
            'parent_book_3',
            'class_name' => '\Book',
        ],
    ];

    public static $has_many = [
        [
            'pages',
            'class_name' => Page::class,
        ],
        [
            'pages_2',
            'class_name' => 'SubNamespaceTest\Page',
        ],
    ];
}
