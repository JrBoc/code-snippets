<?php

/**
 * Helpers.
 * 
 * Loaded via composer file includes.
 */

 /**
  * 
  * @return \Illuminate\Contracts\Auth\Authenticatable|\Models\User|null
  * @throws \Illuminate\Contracts\Container\BindingResolutionException 
  */
function user()
{
    return auth()->user();
}

function student()
{
    return auth('student')->user();
}

student()->student_id;

user()->name;
