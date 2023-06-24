<?php


if (!function_exists('convertToBoolean')) {
    function convertToBoolean($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}