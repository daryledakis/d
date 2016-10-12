<?php

interface template1
{

    public function f1();
}

interface template2 extends template1
{

    public function f2();
}

class abc implements template2
{

    public function f1()
    {
        //Your function body
    }

    public function f2()
    {
        //your function body
    }

}

echo PHP_EOL;

$abc = new abc();

echo PHP_EOL;
