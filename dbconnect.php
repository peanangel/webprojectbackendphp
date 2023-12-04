<?php
    $servername = 'localhost';
    $username = 'deliveryShop';
    $password = 'abc123';
    $dbname = 'deliverfood';

    $dbconnect = new mysqli($servername, $username, $password, $dbname);
    $dbconnect->set_charset("utf8");
