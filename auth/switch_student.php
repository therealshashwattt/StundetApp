<?php
session_start();

// student session clear
unset($_SESSION['student_id']);
unset($_SESSION['class_id']);
unset($_SESSION['section_id']);
unset($_SESSION['session_id']);

// अब school_db पहले से session में है
$school = $_SESSION['school_db'] ?? '';
$last_mobile = $_SESSION['last_mobile'] ?? $_COOKIE['last_mobile'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Switching...</title>
</head>
<body>

<form id="autoForm" method="post" action="check_student.php">
  <input type="hidden" name="school_db" value="<?= htmlspecialchars($school) ?>">
  <input type="hidden" name="mobile" value="<?= htmlspecialchars($last_mobile) ?>">
</form>

<script>
document.getElementById("autoForm").submit();
</script>

</body>
</html>


 