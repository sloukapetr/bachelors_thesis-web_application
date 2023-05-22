<?php

return [
    'app_key' => 'XYgXUjEmS1ZjwCZ5fMDQBSc3fapU07ji', //key of this app for authorize URL requests from sensors
    'required_water_temp' => '32', //required water temperature in heating system
    'weather_api_address' => 'Brno, Czech', //location for weather API
    'weather_api_key' => '8CE9QGZ825NL9KF2G4V59SS3R', //weather API key from https://weather.visualcrossing.com/
    'show_floors' => (bool) false, //if in the hause there are floors
    'show_admin_messages' => (bool) false, //if messages are shown to the admin user
    'sensor_last_update' => 900, // limit, when the notification show last update in seconds
];
