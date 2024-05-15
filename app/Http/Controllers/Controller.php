<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function validateSort($sort)
    {
        $sort = explode(' ', $sort);

        if (is_array($sort) && count($sort) >= 2) {
            $attribute = in_array($sort[0], ['date_published', 'author', 'title']) ? $sort[0] : 'date_published';
            $direction = in_array($sort[1], ['asc', 'desc']) ? $sort[1] : 'desc';
        } 
        else {
            $attribute = 'date_published';
            $direction = 'desc';
        }

        return [$attribute, $direction];
    }
}
