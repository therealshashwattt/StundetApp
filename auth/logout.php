<?php
session_start();

// पूरा session खत्म
session_unset();
session_destroy();

// सीधे login पर भेजो
header("Location: login.php");
exit;
