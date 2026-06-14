<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA Cihuy — Sekolah Menengah Atas Unggulan</title>
    <meta name="description" content="SMA Cihuy adalah sekolah menengah atas unggulan yang berfokus pada pendidikan berkarakter, inovatif, dan berkelas internasional. Akses portal SIAKAD untuk informasi akademik lengkap.">
    <meta name="keywords" content="SMA Cihuy, sekolah unggulan, pendidikan, SIAKAD, akademik">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== WELCOME PAGE EXCLUSIVE STYLES ===== */

        :root {
            --cihuy-navy:    #0d1b3e;
            --cihuy-blue:    #1a3a6e;
            --cihuy-gold:    #c8992b;
            --cihuy-gold-lt: #f0c040;
            --cihuy-cream:   #fdfaf4;
            --cihuy-white:   #ffffff;
        }

        html { scroll-behavior: smooth; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--cihuy-navy); color: #fff; overflow-x: hidden; }

        /* ---------- NAV ---------- */
        .lp-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            padding: 0 32px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.4s ease, box-shadow 0.4s ease;
        }
        .lp-nav.scrolled {
            background: rgba(13,27,62,0.97);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 32px rgba(0,0,0,0.4);
            border-bottom: 1px solid rgba(200,153,43,0.15);
        }
        .lp-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .lp-logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--cihuy-gold), #e8b340);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(200,153,43,0.4);
        }
        .lp-logo-text { line-height: 1; }
        .lp-logo-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.02em;
        }
        .lp-logo-tag {
            font-size: 0.62rem;
            color: var(--cihuy-gold-lt);
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .lp-nav-links {
            display: flex;
            align-items: center;
            gap: 36px;
            list-style: none;
        }
        .lp-nav-links a {
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: color 0.2s;
            position: relative;
            padding-bottom: 2px;
        }
        .lp-nav-links a::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0;
            width: 0; height: 2px;
            background: var(--cihuy-gold);
            border-radius: 2px;
            transition: width 0.3s ease;
        }
        .lp-nav-links a:hover { color: #fff; }
        .lp-nav-links a:hover::after { width: 100%; }

        .btn-nav-login {
            background: linear-gradient(135deg, var(--cihuy-gold), #b8851e);
            color: #fff;
            padding: 9px 22px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 0.04em;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 14px rgba(200,153,43,0.3);
        }
        .btn-nav-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(200,153,43,0.5);
        }
        .btn-nav-secondary {
            background: transparent;
            color: rgba(255,255,255,0.8);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-nav-secondary:hover {
            border-color: rgba(255,255,255,0.5);
            color: #fff;
        }

        /* ---------- HAMBURGER ---------- */
        .lp-hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 4px; }
        .lp-hamburger span { width: 24px; height: 2px; background: #fff; border-radius: 2px; transition: all 0.3s; }

        /* ---------- HERO ---------- */
        .lp-hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .lp-hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('/images/school_hero.jpg');
            background-size: cover;
            background-position: center 20%;
            background-attachment: fixed;
        }
        .lp-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(13,27,62,0.94) 0%,
                rgba(13,27,62,0.82) 50%,
                rgba(13,27,62,0.65) 100%
            );
        }
        .lp-hero-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.15;
            animation: particle-float linear infinite;
        }
        @keyframes particle-float {
            0%   { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10%  { opacity: 0.15; }
            90%  { opacity: 0.15; }
            100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
        }

        .lp-hero-content {
            position: relative;
            z-index: 10;
            max-width: 1280px;
            margin: 0 auto;
            padding: 120px 32px 80px;
            width: 100%;
        }
        .lp-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 18px;
            border-radius: 50px;
            border: 1px solid rgba(200,153,43,0.4);
            background: rgba(200,153,43,0.12);
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--cihuy-gold-lt);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 24px;
            animation: fadeInDown 0.7s ease forwards;
        }
        .lp-hero-badge .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34,197,94,0.5);
            animation: ping-green 1.5s ease-out infinite;
        }
        @keyframes ping-green {
            0%   { box-shadow: 0 0 0 0 rgba(34,197,94,0.5); }
            70%  { box-shadow: 0 0 0 8px rgba(34,197,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        }

        .lp-hero-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(2.6rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.1;
            color: #fff;
            margin-bottom: 24px;
            animation: fadeInUp 0.7s ease 0.1s both;
        }
        .lp-hero-title .gold { color: var(--cihuy-gold-lt); }
        .lp-hero-title .outline {
            -webkit-text-stroke: 2px var(--cihuy-gold-lt);
            color: transparent;
        }

        .lp-hero-desc {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.72);
            max-width: 540px;
            line-height: 1.8;
            margin-bottom: 40px;
            animation: fadeInUp 0.7s ease 0.2s both;
        }
        .lp-hero-cta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            animation: fadeInUp 0.7s ease 0.3s both;
        }
        .btn-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--cihuy-gold), #b8851e);
            color: #fff;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 0.03em;
            transition: all 0.3s ease;
            box-shadow: 0 6px 24px rgba(200,153,43,0.4);
        }
        .btn-cta-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 36px rgba(200,153,43,0.6);
        }
        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 32px;
            border-radius: 50px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            color: #fff;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.25);
            transition: all 0.3s ease;
        }
        .btn-cta-secondary:hover {
            background: rgba(255,255,255,0.18);
            border-color: rgba(255,255,255,0.45);
            transform: translateY(-2px);
        }

        .lp-hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 64px;
            animation: fadeInUp 0.7s ease 0.4s both;
        }
        .lp-hero-stat-item { text-align: center; }
        .lp-hero-stat-num {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--cihuy-gold-lt);
        }
        .lp-hero-stat-label {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.5);
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .lp-hero-stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.15);
            align-self: stretch;
        }

        /* Hero right card */
        .lp-hero-card {
            position: absolute;
            right: 6%;
            top: 50%;
            transform: translateY(-50%);
            width: 320px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 24px;
            padding: 28px;
            animation: fadeInRight 0.8s ease 0.5s both;
            box-shadow: 0 24px 64px rgba(0,0,0,0.4);
        }
        @keyframes fadeInRight { from { opacity:0; transform: translate(30px, -50%); } to { opacity:1; transform: translate(0, -50%); } }
        @keyframes fadeInDown { from { opacity:0; transform: translateY(-16px); } to { opacity:1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }

        .portal-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .portal-card-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .portal-card-badge {
            padding: 3px 10px;
            border-radius: 50px;
            background: rgba(34,197,94,0.15);
            border: 1px solid rgba(34,197,94,0.3);
            color: #4ade80;
            font-size: 0.66rem;
            font-weight: 700;
        }
        .portal-stat-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .portal-stat-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 14px 16px;
        }
        .portal-stat-box .label { font-size: 0.68rem; color: rgba(255,255,255,0.45); margin-bottom: 4px; }
        .portal-stat-box .value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--cihuy-gold-lt);
        }
        .portal-stat-box .sub {
            font-size: 0.68rem;
            color: rgba(255,255,255,0.4);
            margin-top: 4px;
        }
        .portal-schedule-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .portal-schedule-item:last-child { border-bottom: none; }
        .portal-schedule-item .subj { font-size: 0.78rem; color: rgba(255,255,255,0.8); font-weight: 500; }
        .portal-schedule-item .time { font-size: 0.72rem; color: var(--cihuy-gold); font-weight: 600; }

        .lp-hero-scroll-hint {
            position: absolute;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.4);
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            animation: bounce-y 2s ease-in-out infinite;
            z-index: 10;
        }
        @keyframes bounce-y { 0%,100%{transform:translate(-50%,0)} 50%{transform:translate(-50%,8px)} }

        /* ---------- ANNOUNCEMENT TICKER ---------- */
        .lp-ticker {
            background: linear-gradient(90deg, var(--cihuy-gold), #b8851e);
            padding: 10px 0;
            overflow: hidden;
            position: relative;
            z-index: 10;
        }
        .lp-ticker-track {
            display: flex;
            gap: 80px;
            white-space: nowrap;
            animation: ticker-scroll 30s linear infinite;
        }
        .lp-ticker-track span {
            font-size: 0.78rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.04em;
        }
        @keyframes ticker-scroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }

        /* ---------- SECTION COMMONS ---------- */
        .lp-section { padding: 96px 32px; }
        .lp-container { max-width: 1280px; margin: 0 auto; }
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .section-badge.gold {
            background: rgba(200,153,43,0.12);
            border: 1px solid rgba(200,153,43,0.3);
            color: var(--cihuy-gold-lt);
        }
        .section-badge.blue {
            background: rgba(59,130,246,0.12);
            border: 1px solid rgba(59,130,246,0.3);
            color: #93c5fd;
        }
        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 16px;
        }
        .section-title .gold { color: var(--cihuy-gold-lt); }
        .section-desc { font-size: 1rem; color: rgba(255,255,255,0.55); line-height: 1.8; max-width: 520px; }

        /* ---------- ABOUT SECTION ---------- */
        .lp-about { background: linear-gradient(180deg, #0d1b3e 0%, #0f2050 100%); }
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }
        .about-image-wrap {
            position: relative;
        }
        .about-img-main {
            width: 100%;
            border-radius: 24px;
            object-fit: cover;
            height: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.5);
        }
        .about-img-secondary {
            position: absolute;
            bottom: -32px;
            right: -32px;
            width: 56%;
            border-radius: 18px;
            object-fit: cover;
            height: 200px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.5);
            border: 4px solid #0d1b3e;
        }
        .about-badge-float {
            position: absolute;
            top: -20px;
            left: -20px;
            background: linear-gradient(135deg, var(--cihuy-gold), #b8851e);
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 12px 32px rgba(200,153,43,0.4);
            text-align: center;
        }
        .about-badge-float .big { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 900; color: #fff; }
        .about-badge-float .small { font-size: 0.66rem; color: rgba(255,255,255,0.8); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; }

        .about-visi-list { display: flex; flex-direction: column; gap: 16px; margin-top: 32px; }
        .about-visi-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 20px;
            border-radius: 16px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s;
        }
        .about-visi-item:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(200,153,43,0.3);
            transform: translateX(6px);
        }
        .about-visi-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }
        .about-visi-text h4 { font-size: 0.9rem; font-weight: 700; color: #fff; margin-bottom: 4px; }
        .about-visi-text p { font-size: 0.8rem; color: rgba(255,255,255,0.5); line-height: 1.6; }

        /* ---------- FITUR SECTION ---------- */
        .lp-fitur {
            background: linear-gradient(180deg, #0f2050 0%, #0d1b3e 100%);
            position: relative;
            overflow: hidden;
        }
        .lp-fitur::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200,153,43,0.07), transparent 70%);
            pointer-events: none;
        }
        .fitur-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 56px;
        }
        .fitur-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 22px;
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: default;
        }
        .fitur-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 22px 22px 0 0;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .fitur-card:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(200,153,43,0.25);
            transform: translateY(-8px);
            box-shadow: 0 24px 64px rgba(0,0,0,0.3);
        }
        .fitur-card:hover::before { opacity: 1; }
        .fitur-card.c1::before { background: linear-gradient(90deg,#3b82f6,#60a5fa); }
        .fitur-card.c2::before { background: linear-gradient(90deg,var(--cihuy-gold),#f0c040); }
        .fitur-card.c3::before { background: linear-gradient(90deg,#10b981,#34d399); }
        .fitur-card.c4::before { background: linear-gradient(90deg,#8b5cf6,#a78bfa); }
        .fitur-card.c5::before { background: linear-gradient(90deg,#ec4899,#f472b6); }
        .fitur-card.c6::before { background: linear-gradient(90deg,#06b6d4,#22d3ee); }

        .fitur-icon {
            width: 54px; height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.4rem;
        }
        .fi-blue   { background: rgba(59,130,246,0.12); }
        .fi-gold   { background: rgba(200,153,43,0.12); }
        .fi-green  { background: rgba(16,185,129,0.12); }
        .fi-purple { background: rgba(139,92,246,0.12); }
        .fi-pink   { background: rgba(236,72,153,0.12); }
        .fi-cyan   { background: rgba(6,182,212,0.12); }

        .fitur-card h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
        }
        .fitur-card p {
            font-size: 0.84rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
        }
        .fitur-card-num {
            position: absolute;
            bottom: 20px; right: 24px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: rgba(255,255,255,0.03);
            line-height: 1;
        }

        /* ---------- GALLERY / PHOTO SECTION ---------- */
        .lp-gallery { background: #0a1530; }
        .gallery-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: 240px 240px;
            gap: 16px;
            border-radius: 24px;
            overflow: hidden;
            margin-top: 48px;
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        .gallery-item.main { grid-row: 1 / 3; }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .gallery-item:hover img { transform: scale(1.07); }
        .gallery-item-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(13,27,62,0.8) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: flex-end;
            padding: 16px;
        }
        .gallery-item:hover .gallery-item-overlay { opacity: 1; }
        .gallery-item-overlay span {
            font-size: 0.8rem;
            font-weight: 600;
            color: #fff;
        }
        .gallery-placeholder {
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }
        .gallery-placeholder.bg1 { background: linear-gradient(135deg, #1a3a6e, #0f2050); }
        .gallery-placeholder.bg2 { background: linear-gradient(135deg, #1a4a2e, #0f3020); }
        .gallery-placeholder.bg3 { background: linear-gradient(135deg, #3a1a6e, #200f50); }
        .gallery-placeholder.bg4 { background: linear-gradient(135deg, #6e1a1a, #500f0f); }

        /* ---------- STATS SECTION ---------- */
        .lp-stats {
            background: linear-gradient(135deg, var(--cihuy-gold) 0%, #b8851e 100%);
            padding: 72px 32px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            text-align: center;
        }
        .stat-item .number {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(2.5rem, 4vw, 4rem);
            font-weight: 900;
            color: #fff;
            line-height: 1;
        }
        .stat-item .unit { font-size: 1.5rem; font-weight: 900; color: rgba(255,255,255,0.6); }
        .stat-item .desc {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.7);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 8px;
        }
        .stats-divider { width: 1px; background: rgba(255,255,255,0.2); align-self: stretch; }

        /* ---------- PROGRAM / JURUSAN ---------- */
        .lp-program { background: linear-gradient(180deg, #0d1b3e 0%, #0f2050 100%); }
        .program-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 52px;
        }
        .program-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            height: 340px;
            cursor: pointer;
            transition: transform 0.4s ease;
        }
        .program-card:hover { transform: translateY(-8px); }
        .program-bg {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
        }
        .program-bg.ipa   { background: linear-gradient(135deg, #0f3a6e, #1a5aae); }
        .program-bg.ips   { background: linear-gradient(135deg, #3a1a6e, #5a2aae); }
        .program-bg.bahasa { background: linear-gradient(135deg, #0f5a3a, #1a8a5a); }
        .program-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 60%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 28px;
        }
        .program-tag {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .program-tag.gold { background: rgba(200,153,43,0.3); color: var(--cihuy-gold-lt); border: 1px solid rgba(200,153,43,0.5); }
        .program-tag.purple { background: rgba(139,92,246,0.3); color: #c4b5fd; border: 1px solid rgba(139,92,246,0.5); }
        .program-tag.green { background: rgba(16,185,129,0.3); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.5); }
        .program-overlay h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.3rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 6px;
        }
        .program-overlay p { font-size: 0.8rem; color: rgba(255,255,255,0.6); line-height: 1.6; }

        /* ---------- TESTIMONY / QUOTES ---------- */
        .lp-testimony { background: #0a1530; }
        .testimony-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 52px;
        }
        .testimony-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 28px;
            transition: all 0.3s;
        }
        .testimony-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(200,153,43,0.2);
            transform: translateY(-4px);
        }
        .testimony-stars { color: var(--cihuy-gold-lt); font-size: 0.9rem; margin-bottom: 14px; }
        .testimony-text { font-size: 0.88rem; color: rgba(255,255,255,0.65); line-height: 1.75; font-style: italic; margin-bottom: 20px; }
        .testimony-author { display: flex; align-items: center; gap: 12px; }
        .testimony-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .ta1 { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .ta2 { background: linear-gradient(135deg, #10b981, #047857); }
        .ta3 { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
        .testimony-name { font-size: 0.88rem; font-weight: 700; color: #fff; }
        .testimony-role { font-size: 0.72rem; color: rgba(255,255,255,0.4); font-weight: 500; margin-top: 2px; }

        /* ---------- CTA SECTION ---------- */
        .lp-cta {
            background: linear-gradient(180deg, #0f2050 0%, #0d1b3e 100%);
            padding: 96px 32px;
        }
        .cta-box {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(200,153,43,0.2);
            border-radius: 32px;
            padding: 72px 56px;
            position: relative;
            overflow: hidden;
        }
        .cta-box::before {
            content: '';
            position: absolute;
            top: -60px; left: 50%;
            transform: translateX(-50%);
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200,153,43,0.12), transparent 70%);
            pointer-events: none;
        }
        .cta-box h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            color: #fff;
            margin-bottom: 20px;
            position: relative;
        }
        .cta-box p { font-size: 0.98rem; color: rgba(255,255,255,0.55); line-height: 1.8; max-width: 500px; margin: 0 auto 40px; }
        .cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

        /* ---------- FOOTER ---------- */
        .lp-footer {
            background: #060e22;
            padding: 72px 32px 32px;
            border-top: 1px solid rgba(200,153,43,0.1);
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 56px;
        }
        .footer-brand .footer-logo { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.2rem; font-weight: 900; color: #fff; margin-bottom: 4px; }
        .footer-brand .footer-tagline { font-size: 0.72rem; color: var(--cihuy-gold); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 16px; }
        .footer-brand p { font-size: 0.84rem; color: rgba(255,255,255,0.45); line-height: 1.75; max-width: 280px; }
        .footer-contact { margin-top: 20px; display: flex; flex-direction: column; gap: 8px; }
        .footer-contact a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-contact a:hover { color: var(--cihuy-gold-lt); }

        .footer-heading {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.3);
            margin-bottom: 20px;
        }
        .footer-links { display: flex; flex-direction: column; gap: 10px; }
        .footer-links a {
            font-size: 0.84rem;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: #fff; }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 28px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .footer-bottom p { font-size: 0.78rem; color: rgba(255,255,255,0.3); }
        .footer-bottom .accent { color: var(--cihuy-gold); }
        .footer-social { display: flex; gap: 12px; }
        .footer-social a {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: all 0.2s;
        }
        .footer-social a:hover { background: rgba(200,153,43,0.2); border-color: rgba(200,153,43,0.4); color: var(--cihuy-gold-lt); }

        /* ---------- ACCREDITATION BANNER ---------- */
        .accred-banner {
            background: rgba(255,255,255,0.03);
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 20px 32px;
        }
        .accred-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 48px;
            flex-wrap: wrap;
        }
        .accred-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }
        .accred-item .dot { color: var(--cihuy-gold); font-size: 1rem; }

        /* ---------- MOBILE ---------- */
        @media (max-width: 1024px) {
            .lp-hero-card { display: none; }
            .about-grid { grid-template-columns: 1fr; gap: 48px; }
            .about-img-secondary { display: none; }
            .about-badge-float { display: none; }
            .fitur-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
            .stats-divider { display: none; }
            .program-grid { grid-template-columns: 1fr; }
            .testimony-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
            .gallery-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
            .gallery-item.main { grid-row: auto; }
        }
        @media (max-width: 768px) {
            .lp-nav-links { display: none; }
            .lp-hamburger { display: flex; }
            .lp-section { padding: 64px 20px; }
            .fitur-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .cta-box { padding: 48px 28px; }
            .lp-hero-stats { gap: 24px; }
            .lp-hero-content { padding: 100px 20px 60px; }
        }
    </style>
</head>
<body>

    {{-- ============================================================ --}}
    {{-- NAV --}}
    {{-- ============================================================ --}}
    <nav class="lp-nav" id="mainNav">
        <a href="/" class="lp-logo">
            <div class="lp-logo-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7l10 5 10-5-10-5z" fill="white" opacity="0.9"/>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.7"/>
                </svg>
            </div>
            <div class="lp-logo-text">
                <div class="lp-logo-name">SMA Cihuy</div>
                <div class="lp-logo-tag">Excellence · Character · Innovation</div>
            </div>
        </a>

        <ul class="lp-nav-links">
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#fitur">Layanan</a></li>
            <li><a href="#program">Program</a></li>
            <li><a href="#galeri">Galeri</a></li>
            <li><a href="#kontak">Kontak</a></li>
        </ul>

        <div style="display:flex; align-items:center; gap:10px;">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn-nav-login">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Dashboard Admin
                    </a>
                @elseif(auth()->user()->role === 'guru')
                    <a href="{{ route('guru.dashboard') }}" class="btn-nav-login">Dashboard Guru</a>
                @else
                    <a href="{{ route('siswa.dashboard') }}" class="btn-nav-login">Dashboard Siswa</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-nav-secondary" style="background:transparent; border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.7); cursor:pointer; padding:9px 18px; border-radius:50px; font-size:0.78rem; font-weight:600; transition:all 0.2s;">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-nav-secondary">Masuk Portal</a>
                <a href="{{ route('login') }}" class="btn-nav-login">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Login Akademik
                </a>
            @endauth
            <div class="lp-hamburger" id="hamburger" onclick="toggleMobileMenu()">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    {{-- ============================================================ --}}
    {{-- HERO --}}
    {{-- ============================================================ --}}
    <section class="lp-hero" id="beranda">
        <div class="lp-hero-bg"></div>
        <div class="lp-hero-overlay"></div>

        {{-- Floating particles --}}
        <div class="lp-hero-particles" id="particles"></div>

        <div class="lp-hero-content">
            <div class="lp-hero-badge">
                <span class="dot"></span>
                Portal Akademik · Tahun Pelajaran 2024/2025 Aktif
            </div>

            <h1 class="lp-hero-title">
                Membentuk Generasi<br>
                <span class="gold">Unggul</span> &amp; <span class="outline">Berkarakter</span><br>
                Masa Depan
            </h1>

            <p class="lp-hero-desc">
                SMA Cihuy adalah sekolah menengah atas unggulan yang mengintegrasikan kurikulum nasional berkelas internasional dengan pembinaan karakter dan teknologi terkini untuk mencetak generasi emas Indonesia.
            </p>

            <div class="lp-hero-cta">
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn-cta-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Masuk ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-cta-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Login Portal Akademik
                    </a>
                    <a href="#tentang" class="btn-cta-secondary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pelajari Lebih Lanjut
                    </a>
                @endauth
            </div>

            <div class="lp-hero-stats">
                <div class="lp-hero-stat-item">
                    <div class="lp-hero-stat-num">1.200<span style="font-size:1.2rem">+</span></div>
                    <div class="lp-hero-stat-label">Siswa Aktif</div>
                </div>
                <div class="lp-hero-stat-divider"></div>
                <div class="lp-hero-stat-item">
                    <div class="lp-hero-stat-num">85<span style="font-size:1.2rem">+</span></div>
                    <div class="lp-hero-stat-label">Tenaga Pengajar</div>
                </div>
                <div class="lp-hero-stat-divider"></div>
                <div class="lp-hero-stat-item">
                    <div class="lp-hero-stat-num">A</div>
                    <div class="lp-hero-stat-label">Akreditasi BAN-S/M</div>
                </div>
                <div class="lp-hero-stat-divider"></div>
                <div class="lp-hero-stat-item">
                    <div class="lp-hero-stat-num">20<span style="font-size:1.2rem">+</span></div>
                    <div class="lp-hero-stat-label">Tahun Berdiri</div>
                </div>
            </div>
        </div>

        {{-- Right Card --}}
        <div class="lp-hero-card" id="heroCard">
            <div class="portal-card-header">
                <span class="portal-card-title">SIAKAD Preview</span>
                <span class="portal-card-badge">● Live</span>
            </div>
            <div class="portal-stat-row">
                <div class="portal-stat-box">
                    <div class="label">Status Kehadiran Semester Ini</div>
                    <div class="value">98.4%</div>
                    <div class="sub">↑ Naik 1.2% dari semester lalu</div>
                </div>
                <div class="portal-stat-box">
                    <div class="label">Rata-rata Nilai Rapor</div>
                    <div class="value" style="color:#4ade80;">88.5 <span style="font-size:0.75rem; color:rgba(255,255,255,0.4);">— Predikat A</span></div>
                </div>
                <div class="portal-stat-box">
                    <div class="label">Jadwal Hari Ini</div>
                    <div style="margin-top:8px;">
                        <div class="portal-schedule-item">
                            <span class="subj">Matematika Wajib</span>
                            <span class="time">07:30</span>
                        </div>
                        <div class="portal-schedule-item">
                            <span class="subj">Fisika</span>
                            <span class="time">09:15</span>
                        </div>
                        <div class="portal-schedule-item">
                            <span class="subj">B. Indonesia</span>
                            <span class="time">11:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lp-hero-scroll-hint">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            Scroll
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- TICKER --}}
    {{-- ============================================================ --}}
    <div class="lp-ticker">
        <div class="lp-ticker-track" id="tickerTrack">
            <span>🏆 SMA Cihuy meraih Juara 1 Olimpiade Sains Nasional 2024</span>
            <span>📅 Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026 segera dibuka</span>
            <span>🎓 100% Lulusan 2024 diterima di Perguruan Tinggi Favorit</span>
            <span>🌟 Akreditasi A dengan nilai tertinggi di Kabupaten</span>
            <span>📚 Program Beasiswa Prestasi tersedia untuk siswa berprestasi</span>
            <span>🏆 SMA Cihuy meraih Juara 1 Olimpiade Sains Nasional 2024</span>
            <span>📅 Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026 segera dibuka</span>
            <span>🎓 100% Lulusan 2024 diterima di Perguruan Tinggi Favorit</span>
            <span>🌟 Akreditasi A dengan nilai tertinggi di Kabupaten</span>
            <span>📚 Program Beasiswa Prestasi tersedia untuk siswa berprestasi</span>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ABOUT --}}
    {{-- ============================================================ --}}
    <section class="lp-section lp-about" id="tentang">
        <div class="lp-container">
            <div class="about-grid">
                <div class="about-image-wrap">
                    <div class="about-badge-float">
                        <div class="big">A+</div>
                        <div class="small">Akreditasi<br>BAN-S/M</div>
                    </div>
                    <img src="/images/school_activity.jpg" alt="Kegiatan Siswa SMA Cihuy" class="about-img-main">
                    <img src="/images/school_lab.jpg" alt="Lab SMA Cihuy" class="about-img-secondary">
                </div>
                <div>
                    <div class="section-badge gold">🏫 Tentang Kami</div>
                    <h2 class="section-title">
                        Sekolah Unggulan<br>dengan Standar <span class="gold">Internasional</span>
                    </h2>
                    <p class="section-desc">
                        SMA Cihuy berdiri sejak 2004 dengan visi mencetak generasi penerus bangsa yang cerdas, berkarakter, dan siap bersaing di era global. Kami menggabungkan kurikulum Merdeka Belajar dengan pendekatan pembelajaran berstandar internasional.
                    </p>

                    <div class="about-visi-list">
                        <div class="about-visi-item">
                            <div class="about-visi-icon fi-blue">🎯</div>
                            <div class="about-visi-text">
                                <h4>Visi</h4>
                                <p>Menjadi sekolah menengah atas terkemuka yang menghasilkan lulusan berintegritas, berprestasi, dan berwawasan global.</p>
                            </div>
                        </div>
                        <div class="about-visi-item">
                            <div class="about-visi-icon fi-gold">🚀</div>
                            <div class="about-visi-text">
                                <h4>Misi</h4>
                                <p>Menyelenggarakan pendidikan berkualitas dengan metode inovatif, pembinaan karakter, dan teknologi digital terkini.</p>
                            </div>
                        </div>
                        <div class="about-visi-item">
                            <div class="about-visi-icon fi-green">🏆</div>
                            <div class="about-visi-text">
                                <h4>Prestasi</h4>
                                <p>Juara olimpiade sains, seni, olahraga tingkat nasional. 100% lulusan diterima di universitas negeri dan swasta terkemuka.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- AKREDITASI / TRUST STRIP --}}
    {{-- ============================================================ --}}
    <div class="accred-banner">
        <div class="accred-inner">
            <div class="accred-item"><span class="dot">★</span> Akreditasi A — BAN-S/M</div>
            <div class="accred-item"><span class="dot">★</span> Kurikulum Merdeka Belajar</div>
            <div class="accred-item"><span class="dot">★</span> ISO 9001:2015 Certified</div>
            <div class="accred-item"><span class="dot">★</span> SIAKAD Terintegrasi Digital</div>
            <div class="accred-item"><span class="dot">★</span> Sekolah Penggerak Kemendikbud</div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FITUR SIAKAD --}}
    {{-- ============================================================ --}}
    <section class="lp-section lp-fitur" id="fitur">
        <div class="lp-container">
            <div style="text-align:center; max-width:600px; margin:0 auto;">
                <div class="section-badge blue">💻 Platform SIAKAD</div>
                <h2 class="section-title">Layanan Akademik Digital<br><span class="gold">Terintegrasi &amp; Modern</span></h2>
                <p class="section-desc" style="margin: 0 auto;">Sistem informasi akademik terpadu yang menghubungkan guru, siswa, dan administrator dalam satu platform digital yang canggih dan mudah digunakan.</p>
            </div>

            <div class="fitur-grid">
                <div class="fitur-card c1">
                    <div class="fitur-icon fi-blue">🏫</div>
                    <h3>Portal Kelas &amp; Rombel</h3>
                    <p>Manajemen rombongan belajar yang terstruktur dengan distribusi siswa per kelas, jurusan, dan jenjang secara sistematis dan otomatis.</p>
                    <div class="fitur-card-num">01</div>
                </div>
                <div class="fitur-card c2">
                    <div class="fitur-icon fi-gold">✅</div>
                    <h3>Kehadiran &amp; Absensi Digital</h3>
                    <p>Rekap absensi harian oleh guru secara real-time dengan laporan persentase kehadiran otomatis per siswa per mata pelajaran.</p>
                    <div class="fitur-card-num">02</div>
                </div>
                <div class="fitur-card c3">
                    <div class="fitur-icon fi-green">📊</div>
                    <h3>Input &amp; Bobot Nilai</h3>
                    <p>Penilaian terstruktur meliputi nilai harian, UTS, dan UAS dengan konfigurasi bobot fleksibel sesuai kebijakan kurikulum sekolah.</p>
                    <div class="fitur-card-num">03</div>
                </div>
                <div class="fitur-card c4">
                    <div class="fitur-icon fi-purple">🗓️</div>
                    <h3>Jadwal Pelajaran Otomatis</h3>
                    <p>Manajemen jadwal mengajar guru dan belajar siswa yang terorganisir tanpa konflik jam mengajar antar kelas dan ruangan.</p>
                    <div class="fitur-card-num">04</div>
                </div>
                <div class="fitur-card c5">
                    <div class="fitur-icon fi-pink">📄</div>
                    <h3>E-Rapor &amp; Ekspor PDF</h3>
                    <p>Ekspor dokumen rapor siswa dan rekap nilai kelas ke format PDF berstandar resmi secara instan hanya dengan satu klik.</p>
                    <div class="fitur-card-num">05</div>
                </div>
                <div class="fitur-card c6">
                    <div class="fitur-icon fi-cyan">🔐</div>
                    <h3>Multi-Role &amp; Keamanan</h3>
                    <p>Sistem autentikasi berlapis dengan hak akses yang terpisah jelas antara Admin, Guru, dan Siswa sesuai wewenang masing-masing.</p>
                    <div class="fitur-card-num">06</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- STATS --}}
    {{-- ============================================================ --}}
    <div class="lp-stats" id="statistik">
        <div class="lp-container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="number"><span class="count-up" data-target="1200">0</span><span class="unit">+</span></div>
                    <div class="desc">Siswa Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="number"><span class="count-up" data-target="85">0</span><span class="unit">+</span></div>
                    <div class="desc">Guru &amp; Staf Pengajar</div>
                </div>
                <div class="stat-item">
                    <div class="number"><span class="count-up" data-target="36">0</span></div>
                    <div class="desc">Rombongan Belajar</div>
                </div>
                <div class="stat-item">
                    <div class="number"><span class="count-up" data-target="100">0</span><span class="unit">%</span></div>
                    <div class="desc">Lulus PT Favorit</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PROGRAM --}}
    {{-- ============================================================ --}}
    <section class="lp-section lp-program" id="program">
        <div class="lp-container">
            <div style="text-align:center; max-width:520px; margin:0 auto;">
                <div class="section-badge gold">📚 Program Studi</div>
                <h2 class="section-title">Tiga Jurusan <span class="gold">Unggulan</span></h2>
                <p class="section-desc" style="margin: 0 auto;">Pilih jurusan sesuai minat dan bakat Anda didampingi oleh tenaga pengajar berpengalaman dan fasilitas laboratorium modern.</p>
            </div>

            <div class="program-grid">
                <div class="program-card">
                    <div class="program-bg ipa">🔬</div>
                    <div class="program-overlay">
                        <div class="program-tag gold">Sains</div>
                        <h3>Ilmu Pengetahuan Alam</h3>
                        <p>Fisika, Kimia, Biologi, Matematika Peminatan. Persiapan terbaik untuk PTN teknik, kedokteran, dan sains.</p>
                    </div>
                </div>
                <div class="program-card">
                    <div class="program-bg ips">📈</div>
                    <div class="program-overlay">
                        <div class="program-tag purple">Sosial</div>
                        <h3>Ilmu Pengetahuan Sosial</h3>
                        <p>Ekonomi, Sosiologi, Geografi, Sejarah Peminatan. Fondasi kuat untuk fakultas hukum, ekonomi, dan sosial humaniora.</p>
                    </div>
                </div>
                <div class="program-card">
                    <div class="program-bg bahasa">📖</div>
                    <div class="program-overlay">
                        <div class="program-tag green">Humaniora</div>
                        <h3>Bahasa &amp; Budaya</h3>
                        <p>Bahasa Indonesia, Inggris, Mandarin, Sastra Peminatan. Jalur menuju karier internasional di bidang diplomasi dan linguistik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- GALLERY --}}
    {{-- ============================================================ --}}
    <section class="lp-section lp-gallery" id="galeri">
        <div class="lp-container">
            <div style="text-align:center; max-width:520px; margin:0 auto;">
                <div class="section-badge gold">📸 Galeri</div>
                <h2 class="section-title">Kehidupan <span class="gold">Kampus</span></h2>
                <p class="section-desc" style="margin:0 auto;">Aktivitas belajar mengajar, ekstrakurikuler, dan prestasi siswa SMA Cihuy yang dinamis dan penuh semangat.</p>
            </div>

            <div class="gallery-grid">
                <div class="gallery-item main">
                    <img src="/images/school_hero.jpg" alt="Gedung SMA Cihuy">
                    <div class="gallery-item-overlay"><span>📸 Gedung Utama SMA Cihuy</span></div>
                </div>
                <div class="gallery-item">
                    <img src="/images/school_activity.jpg" alt="Kegiatan Belajar">
                    <div class="gallery-item-overlay"><span>📚 Kegiatan Belajar</span></div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder bg3">🏆</div>
                    <div class="gallery-item-overlay" style="opacity:1;"><span>🏆 Prestasi Olimpiade</span></div>
                </div>
                <div class="gallery-item">
                    <img src="/images/school_lab.jpg" alt="Laboratorium Sains">
                    <div class="gallery-item-overlay"><span>🔬 Laboratorium Sains</span></div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-placeholder bg1">🎭</div>
                    <div class="gallery-item-overlay" style="opacity:1;"><span>🎭 Seni &amp; Budaya</span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- TESTIMONY --}}
    {{-- ============================================================ --}}
    <section class="lp-section lp-testimony">
        <div class="lp-container">
            <div style="text-align:center; max-width:520px; margin:0 auto;">
                <div class="section-badge gold">💬 Testimoni</div>
                <h2 class="section-title">Kata Mereka tentang <span class="gold">SMA Cihuy</span></h2>
            </div>

            <div class="testimony-grid">
                <div class="testimony-card">
                    <div class="testimony-stars">★★★★★</div>
                    <p class="testimony-text">"SMA Cihuy benar-benar mengubah hidup saya. Gurunya sangat berdedikasi dan sistem SIAKAD memudahkan saya memantau perkembangan belajar secara langsung. Saya bangga menjadi alumni SMA Cihuy!"</p>
                    <div class="testimony-author">
                        <div class="testimony-avatar ta1">AR</div>
                        <div>
                            <div class="testimony-name">Andi Ramadhan</div>
                            <div class="testimony-role">Alumni 2022 · Mahasiswa ITB</div>
                        </div>
                    </div>
                </div>
                <div class="testimony-card">
                    <div class="testimony-stars">★★★★★</div>
                    <p class="testimony-text">"Sebagai orang tua, saya sangat puas dengan transparansi nilai dan absensi anak saya melalui SIAKAD. Komunikasi dengan pihak sekolah juga sangat mudah dan responsif."</p>
                    <div class="testimony-author">
                        <div class="testimony-avatar ta2">SW</div>
                        <div>
                            <div class="testimony-name">Siti Wahyuni</div>
                            <div class="testimony-role">Orang Tua Siswa Kelas XI IPA</div>
                        </div>
                    </div>
                </div>
                <div class="testimony-card">
                    <div class="testimony-stars">★★★★★</div>
                    <p class="testimony-text">"SIAKAD sangat membantu pekerjaan saya sebagai guru. Input nilai dan absensi jadi jauh lebih efisien. Saya bisa fokus ke kualitas pengajaran tanpa terbebani administrasi manual."</p>
                    <div class="testimony-author">
                        <div class="testimony-avatar ta3">BP</div>
                        <div>
                            <div class="testimony-name">Budi Prasetyo, S.Pd.</div>
                            <div class="testimony-role">Guru Matematika SMA Cihuy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- CTA --}}
    {{-- ============================================================ --}}
    <section class="lp-cta" id="kontak">
        <div class="lp-container">
            <div class="cta-box">
                <div class="section-badge gold" style="margin: 0 auto 24px; display:inline-flex;">🚀 Mulai Sekarang</div>
                <h2>Siap Mengakses Portal<br><span style="color: var(--cihuy-gold-lt);">Akademik Digital?</span></h2>
                <p>Masuk ke akun SIAKAD Anda sekarang untuk memantau jadwal, nilai, kehadiran, dan informasi akademik secara real-time dari mana saja.</p>
                <div class="cta-buttons">
                    <a href="{{ route('login') }}" class="btn-cta-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Masuk ke SIAKAD
                    </a>
                    <a href="#tentang" class="btn-cta-secondary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- FOOTER --}}
    {{-- ============================================================ --}}
    <footer class="lp-footer">
        <div class="lp-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">SMA Cihuy</div>
                    <div class="footer-tagline">Excellence · Character · Innovation</div>
                    <p>Sekolah menengah atas unggulan yang berdedikasi mencetak generasi cerdas, berkarakter, dan siap bersaing di tingkat nasional maupun internasional sejak tahun 2004.</p>
                    <div class="footer-contact">
                        <a href="tel:+6221555019">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            (021) 555-0199
                        </a>
                        <a href="mailto:info@smacihuy.sch.id">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            info@smacihuy.sch.id
                        </a>
                        <a href="#">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Jl. Cihuy Raya No. 1, Bandung
                        </a>
                    </div>
                </div>

                <div>
                    <div class="footer-heading">Sekolah</div>
                    <div class="footer-links">
                        <a href="#tentang">Tentang Kami</a>
                        <a href="#program">Program Studi</a>
                        <a href="#galeri">Galeri</a>
                        <a href="#">Berita &amp; Pengumuman</a>
                        <a href="#">Kalender Akademik</a>
                    </div>
                </div>

                <div>
                    <div class="footer-heading">SIAKAD</div>
                    <div class="footer-links">
                        <a href="{{ route('login') }}">Login Portal</a>
                        <a href="#fitur">Fitur SIAKAD</a>
                        <a href="#">Panduan Pengguna</a>
                        <a href="#">Kebijakan Privasi</a>
                    </div>
                </div>

                <div>
                    <div class="footer-heading">Program</div>
                    <div class="footer-links">
                        <a href="#">IPA — Sains</a>
                        <a href="#">IPS — Sosial</a>
                        <a href="#">Bahasa &amp; Budaya</a>
                        <a href="#">Ekstrakurikuler</a>
                        <a href="#">PPDB Online</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© {{ date('Y') }} <span class="accent">SMA Cihuy</span>. All rights reserved. Powered by SIAKAD SMA.</p>
                <div class="footer-social">
                    <a href="#" title="Instagram">📸</a>
                    <a href="#" title="YouTube">▶</a>
                    <a href="#" title="Facebook">f</a>
                    <a href="#" title="Twitter/X">𝕏</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ============================================================ --}}
    {{-- SCRIPTS --}}
    {{-- ============================================================ --}}
    <script>
        // ---- NAV SCROLL ----
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 60);
        });

        // ---- PARTICLES ----
        (function generateParticles() {
            const container = document.getElementById('particles');
            const colors = ['#c8992b', '#f0c040', '#3b82f6', '#ffffff'];
            for (let i = 0; i < 18; i++) {
                const p = document.createElement('div');
                const size = Math.random() * 6 + 3;
                p.className = 'particle';
                p.style.cssText = `
                    width: ${size}px; height: ${size}px;
                    left: ${Math.random() * 100}%;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    animation-duration: ${Math.random() * 15 + 10}s;
                    animation-delay: ${Math.random() * -20}s;
                `;
                container.appendChild(p);
            }
        })();

        // ---- COUNT UP ANIMATION ----
        function animateCountUp(el) {
            const target = parseInt(el.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = Math.floor(current).toLocaleString('id-ID');
                if (current >= target) clearInterval(timer);
            }, 16);
        }

        const countEls = document.querySelectorAll('.count-up');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    entry.target.dataset.animated = '1';
                    animateCountUp(entry.target);
                }
            });
        }, { threshold: 0.5 });
        countEls.forEach(el => observer.observe(el));

        // ---- SMOOTH SCROLL ----
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // ---- SCROLL REVEAL ----
        const revealEls = document.querySelectorAll('.fitur-card, .program-card, .testimony-card, .about-visi-item, .stat-item');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, i * 80);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        revealEls.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(24px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            revealObserver.observe(el);
        });

        // ---- MOBILE MENU ----
        function toggleMobileMenu() {
            const links = document.querySelector('.lp-nav-links');
            if (links) {
                const isOpen = links.style.display === 'flex';
                links.style.cssText = isOpen
                    ? ''
                    : 'display:flex; flex-direction:column; position:fixed; top:72px; left:0; right:0; background:rgba(13,27,62,0.98); backdrop-filter:blur(20px); padding:24px 32px; gap:16px; z-index:199; border-bottom:1px solid rgba(200,153,43,0.2);';
            }
        }
    </script>

</body>
</html>
