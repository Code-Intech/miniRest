<?php

function dd(mixed $value, mixed ...$values): void
{
    var_dump($value, ...$values);
    die();
}