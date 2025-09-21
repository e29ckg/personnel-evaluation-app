<?php
// ล้าง token ที่ฝั่ง client ด้วย JavaScript
echo '<script>
  localStorage.removeItem("access_token");
  localStorage.removeItem("role_id");
  window.location.href = "login.php";
</script>';
?>