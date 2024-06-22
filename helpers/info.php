<?php

defined("C5_EXECUTE") or die("Access Denied.");
class info
{
    public function __construct()
    {
        $this->js = Loader::helper("json");
        $this->cachelifetime = 600; //cache lifetime in seconds
    }
}
