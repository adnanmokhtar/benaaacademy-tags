<?php

namespace Benaaacademy\Tags;

use Illuminate\Support\Facades\Auth;
use Navigation;
use URL;

class Tags extends \Benaaacademy\Platform\Plugin
{

    protected $permissions = [
        "manage"
    ];

    function boot()
    {

        parent::boot();

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("tags.manage")) {
                $menu->item('tags', trans("tags::tags.tags"), route("admin.tags.show"))->icon("fa-tags")->order(3);
            }
        });
    }
}
