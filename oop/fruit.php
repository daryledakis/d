<?php

abstract class Fruit {
    private $color;
    public $action;

    public function eat() {
        $this->action = "Chew";
        return $this->action;
    }

    public function setColor($c) {
        $this->color = $c;
    }
}

class Apple extends Fruit {
    public function eat() {
        return parent::eat()." until core";
    }
}

class Orange extends Fruit {
    public function eat() {
        return parent::eat()." but peel first.";
    }
}

$apple = new Apple();
echo $apple->eat().PHP_EOL;

$orange = new Orange();
echo $orange->eat().PHP_EOL;

    