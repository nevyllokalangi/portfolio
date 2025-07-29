<?php

function getCommonStyles()
{
  return <<<CSS
    /* Common Fonts */
    /* Regular */
    @font-face {
        font-family: "Poppins";
        font-style: normal;
        font-weight: 400;
        src: url("https://fonts.gstatic.com/s/poppins/v20/pxiEyp8kv8JHgFVrJJfecg.woff2") format("woff2");
    }

    /* Common Reset */
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

    /* Common Scrollbar */
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
    CSS;
}

function getVariables()
{
  return <<<CSS
      :root {
      /* === Background Colors === */
      --color-bg-primary: #0A0A12;         /* Deep black base */
      --color-bg-secondary: #13131F;       /* Slightly lighter section bg */
      --color-bg-surface: #1A1A2E;         /* Cards, nav, modals */
      --color-bg-navbar: #0D0D18;          /* Slight contrast navbar */
      --color-bg-card: #1A1A2E;

      /* === Text Colors === */
      --color-text-primary: #E0E0E0;       /* Main readable white */
      --color-text-secondary: #AAAAAA;     /* Subtle supporting text */
      --color-text-muted: #666666;         /* Placeholder or disabled text */
      --color-text-link: #00CFFD;          /* Cool blue-cyan links */
      --color-text-success: #4FFFB0;       /* Teal green success */
      --color-text-error: #FF3C38;         /* Bright red error */

      /* === Accent & Brand Colors === */
      --color-accent-primary: #00CFFD;     /* Primary accent: neon blue */
      --color-accent-hover: #14E4FF;       /* Hover glow: brighter cyan */
      --color-accent-secondary: #537FE7;   /* Soft space-blue for UI elements */
      --color-accent-tertiary: #41EAD4;    /* Teal glow (optional alt accent) */

      /* === Button Colors === */
      --color-button-primary-bg: #00CFFD;
      --color-button-primary-text: #0A0A12;
      --color-button-primary-hover: #14E4FF;

      --color-button-secondary-bg: #1A1A2E;
      --color-button-secondary-text: #00CFFD;
      --color-button-secondary-hover: #2B2B40;

      /* === Border & Outline === */
      --color-border: #29293F;

      /* === Status Colors === */
      --color-success: #4FFFB0;
      --color-error: #FF3C38;

      /* === Glow Effects === */
      --color-glow-primary: rgba(0, 207, 253, 0.4);
      --color-glow-link: rgba(0, 207, 253, 0.3);
      --color-shadow-card: rgba(0, 0, 0, 0.4);
    }
    CSS;
}

function getAdminLayoutSpecific()
{
  return <<<CSS
    .content {
        position: relative;
        display: flex;
        flex-direction: column;
        align-self: center;
        width: 100vw;
        max-width: 1920px;
        min-height: 100vh;
        z-index: 10;
        padding: 0 5%;
        background-color: var(--color-bg-primary);
    }

    /* Mobile/Tablet Restriction */
    .mobile-restriction {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background-color: var(--color-bg-primary);
        z-index: 9999;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: clamp(1rem, 4vw, 2rem);
    }

    .mobile-restriction-icon {
        font-size: clamp(3rem, 8vw, 4rem);
        margin-bottom: clamp(1.5rem, 4vw, 2rem);
        color: var(--accentBlue);
    }

    .mobile-restriction h1 {
        font-size: clamp(1.5rem, 4vw, 2rem);
        font-weight: 700;
        color: var(--color-text-primary);
        margin-bottom: clamp(0.75rem, 2vw, 1rem);
        line-height: 1.2;
    }

    .mobile-restriction p {
        font-size: 1rem;
        color: var(--color-text-secondary);
        max-width: min(500px, 85vw);
        line-height: 1.5;
        margin: 0 auto;
        hyphens: auto;
        text-align: center;
        white-space: initial;
    }

    .mobile-restriction .back-btn {
        margin-top: clamp(1.5rem, 4vw, 2rem);
        padding: clamp(0.75rem, 2vw, 1rem) clamp(1.5rem, 4vw, 2rem);
        background-color: var(--accentBlue);
        color: var(--primaryFont);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: clamp(0.875rem, 2vw, 1rem);
        transition: var(--transition);
        white-space: nowrap;
    }

    .mobile-restriction .back-btn:hover {
        background-color: var(--accentPurple);
        transform: translateY(-2px);
    }

    /* Responsive Breakpoints */
    @media (max-width: 1024px) {
        .admin-layout {
            display: none;
        }

        .mobile-restriction {
            display: flex;
        }
    }

    @media (min-width: 1025px) {
        .mobile-restriction {
            display: none;
        }

        .admin-layout {
            display: flex;
        }
    }
    CSS;
}
function getRegularLayoutSpecific()
{
  return <<<CSS
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
  CSS;
}