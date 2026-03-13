<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.gc_maxlifetime', 86400);
    session_set_cookie_params(86400);
    session_start();
}
include __DIR__ . "/dbs.php";

if (!isset($_SESSION['school_db'])) {
    header("Location: login.php");
    exit;
}

$dbs    = getAllSlaveDatabases();
$school = $_SESSION['school_db'];

$con = null;
$ROOT = '';
$BASE = '';   // base url

foreach ($dbs as $d) {
    if ($d['db'] === $school) {

        // DB connection
        $con = new mysqli("localhost", $d['user'], $d['pass'], $d['db']);

        // Root path
        $ROOT = rtrim($d['root'] ?? '', '/');

        // Base URL
        $BASE = rtrim($d['base'] ?? '', '/');

        break;
    }
}

if (!$con || $con->connect_error) {
    die("Database connection failed");
}

// Global constants
define("SCHOOL_ROOT", $ROOT);
define("SCHOOL_BASE", $BASE);

// Optional helpers
$software_direct = "../" . SCHOOL_ROOT;
