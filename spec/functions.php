<?php

function d(...$vars)
{
    foreach ($vars as $var) {
        var_dump($var);
    }
}

function dd(...$vars)
{
    d(...$vars);
    die();
}

function dw(...$vars)
{
    d(...array_map(function ($var) {
        return $var->getWrappedObject();
    }, $vars));
}

function ddw(...$vars)
{
    dw(...$vars);
    die();
}