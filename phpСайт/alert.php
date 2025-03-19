<?php
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success fixed-top' style='top: 50px; left: 0; right: 0; z-index: 1029; pointer-events: none;'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger fixed-top' style='top: 50px; left: 0; right: 0; z-index: 1029; pointer-events: none;'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}

?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });
});
</script>