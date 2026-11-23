<?php

use ActiveRecord\Model;

class Person extends Model
{
    // a person can have many orders and payments
    public static $has_many = [
        ['orders'],
        ['payments']];

    // must have a name and a state
    public static $validates_presence_of = [
        ['name'], ['state']];
}
