define([
    'jquery',
    'domReady',
], function ($,dom) {
    'use strict';
    return function(config) {
        console.log(config);
        console.log(config.param1);
        console.log(config.param2);
        alert("Add Init Custom Js");
    }
});