<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../config.php';
?>
<!DOCTYPE html>
<html>

<head lang="en">
  <!-- Layout Config -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">

  <link rel="icon" type="image/x-icon" media="(prefers-color-scheme: dark)" href="/public/img/favicon.png" />
  <link rel="icon" type="image/x-icon" media="(prefers-color-scheme: light)" href="/public/img/favicon.png" />

  <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet" />
  <!-- Layout Config -->

  <!-- Page Config -->
  <meta name="description" content="<?php echo htmlspecialchars($pageTitle); ?>" />
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Nevyllo Corp.' : 'Nevyllo Corp.'; ?></title>
  <link rel="stylesheet" href="<?php echo htmlspecialchars($pageCSS); ?>" media="print" onload="this.media='all'">
  <?php if (isset($pageJS) && $pageJS): ?>
    <script type="text/javascript" src="<?php echo htmlspecialchars($pageJS); ?>" defer></script>
  <?php endif; ?>
  <!-- Page Config -->
</head>

<style type="text/css" scoped>
  /* Config */
  :root {
    --primaryFont: #ffffe3;
    --secondaryFont: #ccccb5;
    --tertiaryFont: #e21d48;

    --primary: #0d0d0d;
    --secondary: #1a1a1a;
    --tertiary: #252525;

    --accentBlue: #4dabf7;
    --accentPink: #ff8787;
    --accentOrange: #ff9f43;
    --accentPurple: #9775fa;
    --accentTeal: #20c997;

    --borderColor: #2e2e2e;
    --hoverColor: #333333;

    --transition: all 0.3s ease;
  }

  /* Regular */
  @font-face {
    font-family: "Poppins";
    font-style: normal;
    font-weight: 400;
    src: url("https://fonts.gstatic.com/s/poppins/v20/pxiEyp8kv8JHgFVrJJfecg.woff2") format("woff2");
  }

  /* Bold */
  @font-face {
    font-family: "Poppins";
    font-style: normal;
    font-weight: 700;
    src: url("https://fonts.gstatic.com/s/poppins/v20/pxiByp8kv8JHgFVrLDz8Z1xlEA.woff2") format("woff2");
  }

  /* Extra Bold */
  @font-face {
    font-family: "Poppins";
    font-style: normal;
    font-weight: 800;
    src: url("https://fonts.gstatic.com/s/poppins/v20/pxiByp8kv8JHgFVrLGT9Z1xlEA.woff2") format("woff2");
  }

  *,
  *::after,
  *::before {
    box-sizing: border-box;
  }

  * {
    margin: 0;
    padding: 0;
    font-family: "Poppins", sans-serif;
  }

  img,
  video,
  picture,
  svg {
    display: block;
    user-select: none;
  }

  html {
    color-scheme: dark light;
    scroll-behavior: smooth;
  }

  body {
    white-space: nowrap;
    overflow-x: hidden;
    min-height: 100%;
    min-width: 480px;
    color: var(--primaryFont);
  }

  .seperator {
    height: 100px;
  }

  .layout {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .content {
    position: relative;
    display: flex;
    flex-direction: column;
    align-self: center;
    top: -70px;
    width: 100vw;
    max-width: 1920px;
    min-height: 100vh;
    margin-bottom: 330px;
    z-index: 10;
    background-color: var(--primary);
  }

  /* Config */
  /* Scrollbar */
  ::-webkit-scrollbar {
    width: 9px;
  }

  ::-webkit-scrollbar-track {
    margin: 8px 0;
    background-color: transparent;
  }

  ::-webkit-scrollbar-thumb {
    border-radius: 20px;
    background-color: #888;
  }

  ::-webkit-scrollbar-thumb:hover {
    background-color: #bbb;
  }

  /* Scrollbar */
</style>

<body>
  <div class="layout">
    <!-- Include header -->
    <?php include __DIR__ . '/../partials/header.php'; ?>
    <?php echo isset($content) ? $content : ''; ?>
  </div>
</body>

</html>