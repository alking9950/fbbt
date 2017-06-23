<?php
spl_autoload_register(function($class){
	require __DIR__ . "/handler/".str_replace("\\", "/", $class).".php";
});