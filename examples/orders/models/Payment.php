<?php

use ActiveRecord\Model;

class Payment extends Model
{
    // payment belongs to a person
    public static $belongs_to = [
        ['person'],
        ['order']];
}
