<?php

class Beverage
{
    public function cost()
    {
        return 5;
    }
}

abstract class BeverageDecorator extends Beverage
{
    /**
     * @var Beverage
     */
    protected $beverage;
    
    public function __construct(Beverage $b)
    {
        $this->beverage = $b;
    }
}

class BrewedCoffee extends BeverageDecorator
{
    public function cost()
    {
        return 10 + $this->beverage->cost();
    }
}

class CafeMocha extends BeverageDecorator
{
    public function cost()
    {
        return 15 + parent::cost();
    }
}

echo PHP_EOL;
$b = new Beverage();

$bc = new BrewedCoffee($b);

$cm = new CafeMocha($bc);

echo $cm->cost();

echo PHP_EOL;
