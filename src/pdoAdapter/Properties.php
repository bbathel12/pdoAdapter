<?php

namespace PDOAdapter;

/*
 * this a class that will hold methods to convert
 * properties that exist in specific adapter to
 * method calls on pdo object
 */
abstract class Properties{

    public abstract function get($propertyName, $object);
}
