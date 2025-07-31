<?php
require_once __DIR__ . '/../../functions/helpers.php';
$settings = get_settings($pdo);
?>
<style type="text/css" scoped>
  /* Footer Base Styles */
  footer {
    position: fixed;
    align-self: center;
    bottom: 0;
    width: 100%;
    height: 750px;
    max-width: 1920px;
    z-index: 1;
    display: flex;
    flex-direction: column;
  }

  .footer-nav {
    height: 100%;
    width: 100%;
    padding: 1rem 10vw;
    box-sizing: border-box;
    background: url("public/img/footer.svg") no-repeat;
    background-position: center bottom;
    background-size: 99%;
  }

  /* Copyright Section */
  .footer-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 80px;
    width: 100%;
    padding: 0 1%;
  }

  .footer-cr {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
  }

  /* Responsive Design */
  @media (max-width: 1024px) {
    .footer-nav {
      padding: 2rem 5%;
    }
  }

  @media (max-width: 768px) {
    .footer-row {
      flex-direction: column;
      gap: 0.5rem;
      align-items: center;
      text-align: center;
    }
  }
</style>

<footer id="footer">
  <div class="footer-nav"></div>
  <div class="footer-row">
    <a class="footer-cr">Nevyllo Zamuel Kalangi © <span id="year"></span> - Privacy Policy</a>
    <a class="footer-cr"><?= htmlspecialchars($settings['location']) ?></a>
  </div>
</footer>

<script>
  document.getElementById("year").innerHTML = new Date().getFullYear();
</script>