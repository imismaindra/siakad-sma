<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA Cihuy Bandung — Unggul, Berkarakter, Berprestasi</title>
    <meta name="description" content="SMA Cihuy Bandung adalah sekolah menengah atas swasta unggulan di Bandung yang mengedepankan pendidikan berkarakter, prestasi akademik, dan pengembangan diri siswa secara menyeluruh.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─────────────────────────────────────────
           RESET & BASE
        ───────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', 'Noto Sans', 'Segoe UI', sans-serif;
            background: #fff;
            color: #1a1a2e;
            overflow-x: hidden;
        }
        img { display: block; max-width: 100%; }

        /* ─────────────────────────────────────────
           COLOR TOKENS
        ───────────────────────────────────────── */
        :root {
            --navy:      #0f2557;
            --navy-dark: #081535;
            --navy-mid:  #1a3a7e;
            --gold:      #c8922a;
            --gold-lt:   #e8b040;
            --gold-pale: #fdf3e0;
            --red-id:    #cc2828;   /* merah bendera */
            --white:     #ffffff;
            --off-white: #f8f9fc;
            --gray:      #64748b;
            --gray-lt:   #94a3b8;
        }

        /* ─────────────────────────────────────────
           TOPBAR — merah putih strip
        ───────────────────────────────────────── */
        .topbar-strip {
            height: 5px;
            background: linear-gradient(90deg, var(--red-id) 50%, var(--white) 50%);
        }

        /* ─────────────────────────────────────────
           NAVBAR
        ───────────────────────────────────────── */
        .site-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 300;
            background: rgba(15,37,87,0.97);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--gold);
            transition: all 0.35s ease;
        }
        .site-nav.transparent {
            background: rgba(15,37,87,0.55);
            border-bottom-color: rgba(200,146,42,0.35);
        }
        .nav-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 28px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .nav-logo-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--gold);
            background: #fff;
        }
        .nav-logo-txt { line-height: 1.15; }
        .nav-logo-name {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.01em;
        }
        .nav-logo-sub {
            font-size: 0.6rem;
            color: var(--gold-lt);
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }
        .nav-links a {
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            transition: color 0.2s;
            position: relative;
            padding-bottom: 3px;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 2px;
            background: var(--gold-lt);
            border-radius: 2px;
            transition: width 0.3s;
        }
        .nav-links a:hover { color: #fff; }
        .nav-links a:hover::after { width: 100%; }

        /* CTA Button */
        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 22px;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--gold), #a87020);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: all 0.25s;
            box-shadow: 0 4px 14px rgba(200,146,42,0.35);
            white-space: nowrap;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(200,146,42,0.5); }

        .btn-logout-nav {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.7);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.04em;
        }
        .btn-logout-nav:hover { border-color: rgba(255,255,255,0.5); color: #fff; }

        /* Hamburger */
        .nav-burger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
            background: none;
            border: none;
        }
        .nav-burger span { display: block; width: 24px; height: 2px; background: #fff; border-radius: 2px; transition: all 0.3s; }

        /* ─────────────────────────────────────────
           HERO
        ───────────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            padding-top: 80px;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('/images/sma_hero.webp');
            background-size: cover;
            background-position: center 30%;
            transform-origin: center;
            animation: subtle-zoom 18s ease-in-out infinite alternate;
        }
        @keyframes subtle-zoom { from { transform: scale(1); } to { transform: scale(1.06); } }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(8,21,53,0.98) 0%,
                rgba(8,21,53,0.8) 40%,
                rgba(8,21,53,0.45) 75%,
                rgba(8,21,53,0.2) 100%
            );
        }

        .hero-body {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: 40px 0;
        }
        .hero-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 28px;
            width: 100%;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 56px;
            align-items: center;
        }

        /* Floating merah-putih badge */
        .hero-badge-id {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px;
            border-radius: 50px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            margin-bottom: 24px;
            backdrop-filter: blur(12px);
            animation: fadeUp 0.8s ease both;
        }
        .flag-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
        }
        .flag-dot .red { background: #cc2828; height: 50%; width: 100%; display: block; }
        .flag-dot .white { background: #ffffff; height: 50%; width: 100%; display: block; }
        .hero-badge-id p {
            font-size: 0.72rem;
            font-weight: 600;
            color: rgba(255,255,255,0.85);
            letter-spacing: 0.04em;
        }

        .hero-title {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: clamp(2.2rem, 3.8vw, 3.6rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 20px;
            animation: fadeUp 0.8s ease both;
        }
        .accent-gradient {
            background: linear-gradient(135deg, var(--gold-lt) 30%, #ffd066 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 0.94rem;
            color: rgba(255,255,255,0.7);
            max-width: 580px;
            line-height: 1.75;
            margin-bottom: 36px;
            animation: fadeUp 0.8s ease 0.15s both;
        }

        .hero-cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 12px;
            animation: fadeUp 0.8s ease 0.25s both;
        }
        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--gold), #a87020);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: all 0.3s;
            box-shadow: 0 6px 24px rgba(200,146,42,0.3);
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(200,146,42,0.5); }
        
        .btn-hero-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13px 26px;
            border-radius: 8px;
            background: rgba(255,255,255,0.03);
            border: 1.5px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.85);
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            backdrop-filter: blur(12px);
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.6); color: #fff; transform: translateY(-2px); }

        /* Stats Dashboard Card */
        .hero-portal-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 24px;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            position: relative;
            overflow: hidden;
            animation: fadeUp 1s ease 0.35s both;
        }
        .hero-portal-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
        }
        .card-header-glow {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 10px #10b981;
            animation: pulse 1.8s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .header-title {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: rgba(255, 255, 255, 0.6);
        }
        .stats-glass-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .stat-glass-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: all 0.3s ease;
        }
        .stat-glass-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(200, 146, 42, 0.25);
            transform: translateY(-2px);
        }
        .stat-icon-wrapper {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: rgba(200, 146, 42, 0.08);
            color: var(--gold-lt);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-info {
            display: flex;
            flex-direction: column;
        }
        .stat-number {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
        }
        .stat-label {
            font-size: 0.68rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
            letter-spacing: 0.02em;
            margin-top: 2px;
        }

        /* Scroll hint */
        .hero-scroll {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.4);
            z-index: 10;
            animation: bounce 2.2s ease-in-out infinite;
            cursor: pointer;
        }
        @keyframes bounce { 0%,100%{transform:translate(-50%,0)} 50%{transform:translate(-50%,8px)} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }

        /* ─────────────────────────────────────────
           ANNOUNCEMENT TICKER
        ───────────────────────────────────────── */
        .ticker-bar {
            background: var(--navy-dark);
            border-top: 3px solid var(--gold);
            padding: 12px 0;
            overflow: hidden;
            position: relative;
        }
        .ticker-track {
            display: flex;
            gap: 80px;
            white-space: nowrap;
            animation: ticker 35s linear infinite;
        }
        .ticker-track span {
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255,255,255,0.75);
            letter-spacing: 0.04em;
        }
        .ticker-track span strong { color: var(--gold-lt); }
        @keyframes ticker { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }

        /* ─────────────────────────────────────────
           SECTION COMMONS
        ───────────────────────────────────────── */
        .section { padding: 96px 28px; }
        .section-sm { padding: 72px 28px; }
        .container { max-width: 1320px; margin: 0 auto; }

        .sec-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 14px;
            padding: 5px 14px;
            border-radius: 4px;
        }
        .sec-label svg { flex-shrink: 0; width: 14px; height: 14px; }
        .sec-label.gold { background: var(--gold-pale); color: var(--gold); border: 1px solid rgba(200,146,42,0.3); }
        .sec-label.navy { background: #e8eeff; color: var(--navy); border: 1px solid rgba(15,37,87,0.2); }
        .sec-label.red  { background: #ffeaea; color: var(--red-id); border: 1px solid rgba(204,40,40,0.25); }

        .sec-title {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: clamp(1.7rem, 3vw, 2.6rem);
            font-weight: 900;
            color: var(--navy-dark);
            line-height: 1.15;
            margin-bottom: 14px;
        }
        .sec-title .accent { color: var(--gold); }
        .sec-desc { font-size: 0.95rem; color: var(--gray); line-height: 1.85; }

        /* ─────────────────────────────────────────
           ABOUT SECTION (bg putih)
        ───────────────────────────────────────── */
        .about-sec { background: #fff; }
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 72px;
            align-items: center;
        }
        .about-img-wrap { position: relative; }
        .about-img-main {
            width: 100%;
            height: 480px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.15);
        }
        .about-img-float {
            position: absolute;
            bottom: -28px;
            right: -28px;
            width: 54%;
            height: 210px;
            object-fit: cover;
            border-radius: 10px;
            border: 5px solid #fff;
            box-shadow: 0 16px 40px rgba(0,0,0,0.18);
        }
        .about-badge-accred {
            position: absolute;
            top: -20px;
            left: -20px;
            background: linear-gradient(135deg, var(--navy), var(--navy-mid));
            color: #fff;
            padding: 18px 22px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 12px 32px rgba(15,37,87,0.35);
            border: 2px solid var(--gold);
        }
        .about-badge-accred .big { font-family: 'Poppins', 'Segoe UI', sans-serif; font-size: 2.2rem; font-weight: 900; color: var(--gold-lt); line-height: 1; }
        .about-badge-accred .small { font-size: 0.6rem; color: rgba(255,255,255,0.7); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px; }

        .about-content { padding-right: 12px; }
        .about-text { font-size: 0.94rem; color: var(--gray); line-height: 1.9; margin-bottom: 28px; }

        .visi-misi-list { display: flex; flex-direction: column; gap: 14px; }
        .vm-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 18px 20px;
            border-radius: 10px;
            border: 1px solid #e8eef8;
            background: var(--off-white);
            transition: all 0.3s;
        }
        .vm-item:hover { border-color: rgba(200,146,42,0.4); background: var(--gold-pale); transform: translateX(6px); }
        .vm-icon {
            width: 42px; height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .vm-icon.navy  { background: #e8eeff; }
        .vm-icon.gold  { background: var(--gold-pale); }
        .vm-icon.red   { background: #ffeaea; }
        .vm-body h4 { font-size: 0.88rem; font-weight: 800; color: var(--navy-dark); margin-bottom: 4px; }
        .vm-body p  { font-size: 0.8rem; color: var(--gray); line-height: 1.65; }

        /* ─────────────────────────────────────────
           KEUNGGULAN STRIP (navy)
        ───────────────────────────────────────── */
        .keunggulan-sec { background: var(--navy-dark); }
        .keunggulan-grid {
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 2px;
            border-radius: 14px;
            overflow: hidden;
        }
        .keunggulan-item {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            padding: 36px 28px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .keunggulan-item::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-lt));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        .keunggulan-item:hover { background: rgba(255,255,255,0.08); }
        .keunggulan-item:hover::before { transform: scaleX(1); }
        .keunggulan-icon {
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: var(--gold-lt);
        }
        .keunggulan-num {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--gold-lt);
            line-height: 1;
            margin-bottom: 6px;
        }
        .keunggulan-lbl { font-size: 0.78rem; color: rgba(255,255,255,0.5); font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }

        /* ─────────────────────────────────────────
           BERITA DAN INFORMASI (NEWS)
        ───────────────────────────────────────── */
        .berita-sec { background: var(--off-white); }
        .berita-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 32px;
            margin-top: 52px;
        }
        .berita-featured {
            position: relative;
            background: var(--navy-dark);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 480px;
            border: 1px solid rgba(255,255,255,0.06);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }
        .berita-featured:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(200,146,42,0.15);
        }
        .berita-featured-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .berita-featured:hover .berita-featured-bg {
            transform: scale(1.04);
        }
        .berita-featured-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(8,21,53,0.95) 0%, rgba(8,21,53,0.7) 40%, rgba(8,21,53,0.15) 80%, transparent 100%);
        }
        .berita-featured-content {
            position: relative;
            z-index: 10;
            padding: 40px;
        }
        .berita-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 16px;
        }
        .berita-badge.prestasi { background: linear-gradient(135deg, var(--gold), #a87020); color: #fff; }
        .berita-badge.info { background: linear-gradient(135deg, var(--navy-mid), #0d1e3d); color: #fff; }
        .berita-badge.kegiatan { background: linear-gradient(135deg, var(--red-id), #900); color: #fff; }
        .berita-meta {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.6);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .berita-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .berita-featured-title {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: clamp(1.4rem, 2.5vw, 1.85rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 14px;
        }
        .berita-featured-title a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .berita-featured-title a:hover {
            color: var(--gold-lt);
        }
        .berita-featured-desc {
            font-size: 0.88rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.7;
            margin-bottom: 24px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .btn-read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--gold-lt);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: gap 0.3s;
        }
        .btn-read-more:hover {
            gap: 12px;
            color: #fff;
        }

        .berita-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: space-between;
        }
        .berita-item-horizontal {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            border: 1px solid #e5eaf5;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            display: flex;
            gap: 18px;
            align-items: center;
            transition: all 0.35s ease;
        }
        .berita-item-horizontal:hover {
            transform: translateX(6px);
            border-color: rgba(200,146,42,0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }
        .berita-thumb {
            width: 110px;
            height: 110px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
        }
        .berita-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .berita-item-horizontal:hover .berita-thumb img {
            transform: scale(1.08);
        }
        .berita-item-content {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .berita-item-content .berita-badge {
            padding: 3px 10px;
            font-size: 0.6rem;
            margin-bottom: 8px;
            align-self: flex-start;
        }
        .berita-item-title {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--navy-dark);
            line-height: 1.4;
            margin-bottom: 8px;
        }
        .berita-item-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
            background-image: linear-gradient(var(--gold-lt), var(--gold-lt));
            background-size: 0% 2px;
            background-repeat: no-repeat;
            background-position: left bottom;
            transition: background-size 0.3s;
        }
        .berita-item-horizontal:hover .berita-item-title a {
            background-size: 100% 2px;
            color: var(--navy-mid);
        }
        .berita-item-meta {
            font-size: 0.72rem;
            color: var(--gray);
            font-weight: 500;
        }

        /* ─────────────────────────────────────────
           GALERI FOTO
        ───────────────────────────────────────── */
        .galeri-sec { background: #fff; }
        .galeri-grid {
            display: grid;
            grid-template-columns: 5fr 3fr 4fr;
            grid-template-rows: 260px 260px;
            gap: 14px;
            margin-top: 48px;
            border-radius: 14px;
            overflow: hidden;
        }
        .galeri-item {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border-radius: 8px;
        }
        .galeri-item.tall { grid-row: 1 / 3; border-radius: 10px; }
        .galeri-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.55s ease;
        }
        .galeri-item:hover img { transform: scale(1.08); }
        .galeri-cap {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(8,21,53,0.85) 0%, transparent 55%);
            display: flex;
            align-items: flex-end;
            padding: 16px 18px;
            opacity: 0;
            transition: opacity 0.35s;
        }
        .galeri-item:hover .galeri-cap { opacity: 1; }
        .galeri-cap span { font-size: 0.8rem; font-weight: 700; color: #fff; }

        /* ─────────────────────────────────────────
           EKSTRAKURIKULER
        ───────────────────────────────────────── */
        .ekskul-sec { background: var(--off-white); }
        .ekskul-grid {
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 20px;
            margin-top: 48px;
        }
        .ekskul-card {
            background: #fff;
            border: 1px solid #e5eaf5;
            border-radius: 12px;
            padding: 26px 22px;
            text-align: center;
            transition: all 0.3s;
            cursor: default;
        }
        .ekskul-card:hover { border-color: var(--gold); box-shadow: 0 10px 32px rgba(0,0,0,0.09); transform: translateY(-5px); }
        .ekskul-card .ek-icon {
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: var(--navy-mid);
        }
        .ekskul-card h4 { font-size: 0.9rem; font-weight: 800; color: var(--navy-dark); margin-bottom: 6px; }
        .ekskul-card p  { font-size: 0.78rem; color: var(--gray); line-height: 1.6; }

        /* ─────────────────────────────────────────
           FASILITAS
        ───────────────────────────────────────── */
        .fasilitas-sec { background: var(--navy-dark); }
        .fasilitas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
        }
        .fasilitas-list { display: flex; flex-direction: column; gap: 16px; }
        .fasilitas-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 22px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s;
        }
        .fasilitas-item:hover { background: rgba(255,255,255,0.1); border-color: rgba(200,146,42,0.35); transform: translateX(6px); }
        .fasilitas-item .fac-icon {
            width: 46px; height: 46px;
            border-radius: 10px;
            background: rgba(200,146,42,0.12);
            border: 1px solid rgba(200,146,42,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .fasilitas-item h4 { font-size: 0.9rem; font-weight: 700; color: #fff; margin-bottom: 3px; }
        .fasilitas-item p  { font-size: 0.78rem; color: rgba(255,255,255,0.5); }
        .fasilitas-img-wrap { position: relative; }
        .fasilitas-img {
            width: 100%;
            height: 480px;
            object-fit: cover;
            border-radius: 14px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.5);
        }
        .fasilitas-img-badge {
            position: absolute;
            bottom: -20px;
            left: -20px;
            background: linear-gradient(135deg, var(--gold), #a87020);
            color: #fff;
            padding: 16px 22px;
            border-radius: 10px;
            box-shadow: 0 10px 28px rgba(200,146,42,0.4);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }
        .fasilitas-img-badge .big { font-size: 1.6rem; font-weight: 900; }
        .fasilitas-img-badge .small { font-size: 0.62rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.8; }

        /* ─────────────────────────────────────────
           PRESTASI
        ───────────────────────────────────────── */
        .prestasi-sec { background: #fff; }
        .prestasi-timeline { position: relative; margin-top: 52px; }
        .prestasi-timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0; bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--gold), var(--navy));
        }
        .prestasi-row {
            display: flex;
            justify-content: flex-end;
            padding-right: calc(50% + 32px);
            margin-bottom: 36px;
            position: relative;
        }
        .prestasi-row.right {
            justify-content: flex-start;
            padding-right: 0;
            padding-left: calc(50% + 32px);
        }
        .prestasi-dot {
            position: absolute;
            left: 50%;
            top: 24px;
            transform: translate(-50%, -50%);
            width: 16px; height: 16px;
            border-radius: 50%;
            background: var(--gold);
            border: 3px solid #fff;
            box-shadow: 0 0 0 4px rgba(200,146,42,0.25);
            z-index: 2;
        }
        .prestasi-card {
            background: var(--off-white);
            border: 1px solid #e5eaf5;
            border-radius: 10px;
            padding: 20px 24px;
            max-width: 380px;
            width: 100%;
            transition: all 0.3s;
        }
        .prestasi-card:hover { border-color: var(--gold); box-shadow: 0 8px 28px rgba(0,0,0,0.08); }
        .prestasi-year {
            font-size: 0.68rem;
            font-weight: 800;
            color: var(--gold);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .prestasi-card h4 { font-size: 0.9rem; font-weight: 800; color: var(--navy-dark); margin-bottom: 5px; }
        .prestasi-card p  { font-size: 0.8rem; color: var(--gray); }
        .prestasi-badge {
            display: inline-block;
            margin-top: 8px;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.66rem;
            font-weight: 700;
        }
        .pb-gold   { background: #fef3c7; color: #b45309; }
        .pb-silver { background: #f1f5f9; color: #475569; }
        .pb-navy   { background: #e0e7ff; color: #1e3a8a; }

        /* ─────────────────────────────────────────
           TESTIMONY
        ───────────────────────────────────────── */
        .testimony-sec { background: var(--off-white); }
        .testimony-grid {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 24px;
            margin-top: 52px;
        }
        .t-card {
            background: #fff;
            border: 1px solid #e5eaf5;
            border-radius: 14px;
            padding: 30px;
            transition: all 0.3s;
            position: relative;
        }
        .t-card::before {
            content: '"';
            position: absolute;
            top: 16px; right: 22px;
            font-size: 4rem;
            font-family: Georgia, serif;
            color: var(--gold-pale);
            line-height: 1;
        }
        .t-card:hover { border-color: var(--gold); box-shadow: 0 12px 36px rgba(0,0,0,0.08); transform: translateY(-5px); }
        .t-stars { color: var(--gold); font-size: 0.9rem; margin-bottom: 14px; }
        .t-text { font-size: 0.87rem; color: var(--gray); line-height: 1.8; font-style: italic; margin-bottom: 22px; position: relative; z-index: 1; }
        .t-author { display: flex; align-items: center; gap: 12px; }
        .t-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .av1 { background: linear-gradient(135deg, #1e40af, #3b82f6); }
        .av2 { background: linear-gradient(135deg, #065f46, #10b981); }
        .av3 { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
        .t-name { font-size: 0.88rem; font-weight: 800; color: var(--navy-dark); }
        .t-role { font-size: 0.72rem; color: var(--gray-lt); margin-top: 2px; }

        /* ─────────────────────────────────────────
           PPDB / CTA BANNER
        ───────────────────────────────────────── */
        .ppdb-sec {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-dark) 100%);
            border-top: 4px solid var(--gold);
        }
        .ppdb-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            flex-wrap: wrap;
        }
        .ppdb-text h2 {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            font-size: clamp(1.5rem, 2.5vw, 2.2rem);
            font-weight: 900;
            color: #fff;
            margin-bottom: 10px;
        }
        .ppdb-text p { font-size: 0.92rem; color: rgba(255,255,255,0.6); line-height: 1.7; max-width: 520px; }
        .ppdb-actions { display: flex; gap: 14px; flex-wrap: wrap; align-items: center; }
        .btn-ppdb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 30px;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--gold), #a87020);
            color: #fff;
            font-size: 0.88rem;
            font-weight: 800;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s;
            box-shadow: 0 6px 22px rgba(200,146,42,0.4);
        }
        .btn-ppdb:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(200,146,42,0.6); }
        .btn-ppdb-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            border-radius: 6px;
            border: 2px solid rgba(255,255,255,0.3);
            background: transparent;
            color: rgba(255,255,255,0.8);
            font-size: 0.86rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s;
        }
        .btn-ppdb-outline:hover { border-color: rgba(255,255,255,0.6); color: #fff; }

        /* ─────────────────────────────────────────
           FOOTER
        ───────────────────────────────────────── */
        .site-footer {
            background: var(--navy-dark);
            border-top: 1px solid rgba(200,146,42,0.15);
            padding: 72px 28px 32px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2.2fr 1fr 1fr 1.2fr;
            gap: 52px;
            margin-bottom: 56px;
        }
        .footer-brand .logo-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .footer-logo-img { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold); background: #fff; }
        .footer-school-name { font-family: 'Poppins', 'Segoe UI', sans-serif; font-size: 1.1rem; font-weight: 800; color: #fff; }
        .footer-school-city { font-size: 0.65rem; color: var(--gold); font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }
        .footer-brand p { font-size: 0.83rem; color: rgba(255,255,255,0.45); line-height: 1.8; max-width: 270px; }
        .footer-npsn {
            display: inline-block;
            margin-top: 14px;
            padding: 5px 12px;
            border-radius: 5px;
            background: rgba(200,146,42,0.1);
            border: 1px solid rgba(200,146,42,0.25);
            font-size: 0.7rem;
            color: var(--gold-lt);
            font-weight: 600;
            letter-spacing: 0.06em;
        }
        .footer-contact-list { display: flex; flex-direction: column; gap: 10px; margin-top: 18px; }
        .footer-contact-list a {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            transition: color 0.2s;
            line-height: 1.5;
        }
        .footer-contact-list a:hover { color: var(--gold-lt); }
        .footer-contact-list a svg { flex-shrink: 0; margin-top: 2px; }

        .footer-heading {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.25);
            margin-bottom: 18px;
        }
        .footer-links { display: flex; flex-direction: column; gap: 9px; }
        .footer-links a {
            font-size: 0.83rem;
            color: rgba(255,255,255,0.48);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: #fff; }

        .footer-social-head { margin-bottom: 14px; }
        .social-row { display: flex; gap: 10px; margin-bottom: 16px; }
        .social-btn {
            width: 38px; height: 38px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.09);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            text-decoration: none;
            color: rgba(255,255,255,0.5);
            transition: all 0.2s;
        }
        .social-btn:hover { background: rgba(200,146,42,0.2); border-color: rgba(200,146,42,0.4); color: var(--gold-lt); }

        .footer-accred-badges { display: flex; flex-direction: column; gap: 8px; }
        .footer-accred-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 6px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            font-size: 0.72rem;
            color: rgba(255,255,255,0.45);
        }
        .footer-accred-badge .dot { color: var(--gold); }

        .footer-bottom {
            padding-top: 28px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-bottom p { font-size: 0.75rem; color: rgba(255,255,255,0.28); }
        .footer-bottom .accent { color: var(--gold); }

        /* ─────────────────────────────────────────
           MOBILE NAV DRAWER
        ───────────────────────────────────────── */
        .mobile-nav {
            display: none;
            position: fixed;
            top: 73px; left: 0; right: 0;
            background: rgba(8,21,53,0.98);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--gold);
            padding: 24px 28px;
            z-index: 299;
            flex-direction: column;
            gap: 14px;
        }
        .mobile-nav.open { display: flex; }
        .mobile-nav a {
            font-size: 0.9rem;
            font-weight: 700;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .mobile-nav a:hover { color: var(--gold-lt); }

        /* ─────────────────────────────────────────
           SCROLL-REVEAL UTILITY
        ───────────────────────────────────────── */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.65s ease, transform 0.65s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ─────────────────────────────────────────
           RESPONSIVE
        ───────────────────────────────────────── */
        @media (max-width: 1200px) {
            .keunggulan-grid { grid-template-columns: repeat(2,1fr); }
            .about-grid { gap: 48px; }
        }
        @media (max-width: 1024px) {
            .nav-links { display: none; }
            .nav-burger { display: flex; }
            .hero-grid { grid-template-columns: 1fr; gap: 40px; text-align: center; }
            .hero-content-left { display: flex; flex-direction: column; align-items: center; }
            .hero-desc { margin-left: auto; margin-right: auto; }
            .hero-cta-row { justify-content: center; }
            .hero-portal-card { max-width: 500px; margin: 0 auto; width: 100%; }
            .about-grid { grid-template-columns: 1fr; }
            .about-img-float, .about-badge-accred { display: none; }
            .about-img-main { height: 320px; }
            .fasilitas-grid { grid-template-columns: 1fr; gap: 40px; }
            .fasilitas-img { height: 300px; }
            .fasilitas-img-badge { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 36px; }
            .berita-grid { grid-template-columns: 1fr; }
            .prestasi-timeline::before { left: 16px; transform: none; }
            .prestasi-row { padding-right: 0; padding-left: 48px; justify-content: flex-start; }
            .prestasi-row.right { padding-left: 48px; }
            .prestasi-dot { left: 16px; transform: translate(-50%,-50%); }
            .testimony-grid { grid-template-columns: 1fr; }
            .ekskul-grid { grid-template-columns: repeat(2,1fr); }
        }
        @media (max-width: 768px) {
            .section { padding: 64px 20px; }
            .section-sm { padding: 52px 20px; }
            .stats-glass-grid { grid-template-columns: 1fr; gap: 12px; }
            .galeri-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
            .galeri-item.tall { grid-row: auto; }
            .galeri-item { height: 220px; }
            .keunggulan-grid { grid-template-columns: 1fr 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 28px; }
            .ppdb-inner { flex-direction: column; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .ekskul-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 480px) {
            .hero-title { font-size: 2.2rem; }
            .ekskul-grid { grid-template-columns: 1fr; }
            .keunggulan-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    {{-- ══════════════════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════════════════ --}}
    <nav class="site-nav transparent" id="siteNav">
        <div class="topbar-strip"></div>
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="/images/sma_logo.webp" alt="Logo SMA Cihuy" class="nav-logo-img">
                <div class="nav-logo-txt">
                    <div class="nav-logo-name">SMA Cihuy</div>
                    <div class="nav-logo-sub">Bandung · Est. 2004</div>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#berita">Berita</a></li>
                <li><a href="#galeri">Galeri</a></li>
                <li><a href="#fasilitas">Fasilitas</a></li>
                <li><a href="#prestasi">Prestasi</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>

            <div style="display:flex;align-items:center;gap:10px;">
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn-login">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout-nav">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-login">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Portal Akademik
                    </a>
                @endauth
                <button class="nav-burger" id="navBurger" onclick="toggleNav()" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Mobile nav --}}
    <div class="mobile-nav" id="mobileNav">
        <a href="#tentang" onclick="toggleNav()">Tentang Kami</a>
        <a href="#berita" onclick="toggleNav()">Berita &amp; Kegiatan</a>
        <a href="#galeri" onclick="toggleNav()">Galeri</a>
        <a href="#fasilitas" onclick="toggleNav()">Fasilitas</a>
        <a href="#prestasi" onclick="toggleNav()">Prestasi</a>
        <a href="#kontak" onclick="toggleNav()">Kontak</a>
        <a href="{{ route('login') }}" style="color: var(--gold-lt);">→ Portal Akademik (SIAKAD)</a>
    </div>

    {{-- ══════════════════════════════════════════════════
         HERO
    ══════════════════════════════════════════════════ --}}
    <section class="hero" id="beranda">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>

        <div class="hero-body">
            <div class="hero-container">
                <div class="hero-grid">
                    <!-- Left Column: Content Stack -->
                    <div class="hero-content-left">
                        <div class="hero-badge-id">
                            <span class="flag-dot">
                                <span class="red"></span>
                                <span class="white"></span>
                            </span>
                            <p>SMA Cihuy Bandung · Kota Kembang</p>
                        </div>

                        <h1 class="hero-title">
                            Membentuk Generasi<br>
                            <span class="accent-gradient">Unggul &amp; Berkarakter</span>
                        </h1>

                        <p class="hero-desc">
                            Berkomitmen menyelenggarakan pendidikan berkualitas tinggi untuk melahirkan lulusan cerdas, berintegritas, dan siap berkontribusi bagi bangsa.
                        </p>

                        <div class="hero-cta-row">
                            <a href="#tentang" class="btn-hero-primary">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Profil Sekolah
                            </a>
                            <a href="{{ route('login') }}" class="btn-hero-outline">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Portal SIAKAD
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Premium Portal Mockup Card & Stats -->
                    <div class="hero-content-right">
                        <div class="hero-portal-card">
                            <div class="card-header-glow">
                                <span class="pulse-dot"></span>
                                <span class="header-title">SIAKAD Portal Akademik</span>
                            </div>
                            
                            <div class="stats-glass-grid">
                                <!-- Stat 1: Siswa -->
                                <div class="stat-glass-card">
                                    <div class="stat-icon-wrapper">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-number">1,200+</span>
                                        <span class="stat-label">Siswa Aktif</span>
                                    </div>
                                </div>

                                <!-- Stat 2: Guru -->
                                <div class="stat-glass-card">
                                    <div class="stat-icon-wrapper">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-number">85+</span>
                                        <span class="stat-label">Pendidik</span>
                                    </div>
                                </div>

                                <!-- Stat 3: Ekskul -->
                                <div class="stat-glass-card">
                                    <div class="stat-icon-wrapper">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0H8m12 4v6m0-6H4v6m16-6v-1a2 2 0 00-2-2H6a2 2 0 00-2 2v1"/></svg>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-number">20+</span>
                                        <span class="stat-label">Ekskul Aktif</span>
                                    </div>
                                </div>

                                <!-- Stat 4: Akreditasi -->
                                <div class="stat-glass-card">
                                    <div class="stat-icon-wrapper">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <div class="stat-info">
                                        <span class="stat-number">Akred. A</span>
                                        <span class="stat-label">BAN-S/M</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-scroll" onclick="document.getElementById('tentang').scrollIntoView({ behavior: 'smooth' });">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         TICKER
    ══════════════════════════════════════════════════ --}}
    <div class="ticker-bar">
        <div class="ticker-track">
            <span>🏆 <strong>Juara 1</strong> Olimpiade Matematika Tingkat Provinsi Jawa Barat 2024</span>
            <span>📢 <strong>PPDB 2025/2026</strong> — Pendaftaran Gelombang 1 Segera Dibuka</span>
            <span>🎓 <strong>100% Lulusan 2024</strong> diterima di PTN dan PTS Favorit</span>
            <span>🌟 Akreditasi <strong>A (Unggul)</strong> dari BAN-S/M — Nilai Tertinggi se-Kota Bandung</span>
            <span>📚 Program <strong>Beasiswa Prestasi</strong> tersedia untuk siswa berprestasi akademik &amp; non-akademik</span>
            <span>🎭 <strong>Pekan Seni &amp; Budaya SMA Cihuy</strong> — 20 Juli 2025</span>
            <span>🏆 <strong>Juara 1</strong> Olimpiade Matematika Tingkat Provinsi Jawa Barat 2024</span>
            <span>📢 <strong>PPDB 2025/2026</strong> — Pendaftaran Gelombang 1 Segera Dibuka</span>
            <span>🎓 <strong>100% Lulusan 2024</strong> diterima di PTN dan PTS Favorit</span>
            <span>🌟 Akreditasi <strong>A (Unggul)</strong> dari BAN-S/M — Nilai Tertinggi se-Kota Bandung</span>
            <span>📚 Program <strong>Beasiswa Prestasi</strong> tersedia untuk siswa berprestasi akademik &amp; non-akademik</span>
            <span>🎭 <strong>Pekan Seni &amp; Budaya SMA Cihuy</strong> — 20 Juli 2025</span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         TENTANG KAMI
    ══════════════════════════════════════════════════ --}}
    <section class="section about-sec" id="tentang">
        <div class="container">
            <div class="about-grid">
                <div class="about-img-wrap reveal">
                    <div class="about-badge-accred">
                        <div class="big">A</div>
                        <div class="small">Akreditasi<br>Unggul</div>
                    </div>
                    <img src="/images/sma_kelas.webp" alt="Kegiatan Belajar Mengajar SMA Cihuy" class="about-img-main">
                    <img src="/images/sma_upacara.webp" alt="Upacara Bendera SMA Cihuy" class="about-img-float">
                </div>
                <div class="about-content reveal">
                    <div class="sec-label navy"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg> Tentang SMA Cihuy</div>
                    <h2 class="sec-title">Sekolah Swasta Unggulan<br>di <span class="accent">Kota Bandung</span></h2>
                    <p class="about-text">
                        SMA Cihuy berdiri sejak tahun 2004 di bawah naungan Yayasan Pendidikan Cihuy Jaya. Selama lebih dari dua dekade, kami telah mendidik ribuan generasi muda Indonesia menjadi pribadi yang cerdas secara intelektual, matang secara emosional, dan kuat secara moral.
                    </p>
                    <p class="about-text">
                        Kami menerapkan Kurikulum Merdeka dengan pendekatan pembelajaran berbasis proyek (Project-Based Learning) yang mengutamakan kreativitas, kolaborasi, dan berpikir kritis. Didukung tenaga pendidik berpengalaman dan bersertifikat, SMA Cihuy terus berinovasi memberikan yang terbaik.
                    </p>

                    <div class="visi-misi-list">
                        <div class="vm-item">
                            <div class="vm-icon navy">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            </div>
                            <div class="vm-body">
                                <h4>Visi Sekolah</h4>
                                <p>Menjadi sekolah menengah atas terkemuka yang melahirkan generasi beriman, berprestasi, dan berbudaya demi kemajuan Indonesia.</p>
                            </div>
                        </div>
                        <div class="vm-item">
                            <div class="vm-icon gold">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                            </div>
                            <div class="vm-body">
                                <h4>Misi Sekolah</h4>
                                <p>Menyelenggarakan pendidikan berkarakter melalui kurikulum inovatif, pembinaan holistik, dan lingkungan belajar yang kondusif dan menyenangkan.</p>
                            </div>
                        </div>
                        <div class="vm-item">
                            <div class="vm-icon red">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div class="vm-body">
                                <h4>Nilai Utama</h4>
                                <p>Integritas, Disiplin, Inovasi, Gotong Royong — empat pilar yang menjadi fondasi karakter setiap warga SMA Cihuy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         KEUNGGULAN SEKOLAH
    ══════════════════════════════════════════════════ --}}
    <div class="section-sm keunggulan-sec" id="keunggulan">
        <div class="container">
            <div class="keunggulan-grid">
                <div class="keunggulan-item reveal">
                    <div class="keunggulan-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="keunggulan-num"><span class="cu" data-target="1200">0</span>+</div>
                    <div class="keunggulan-lbl">Siswa Aktif</div>
                </div>
                <div class="keunggulan-item reveal">
                    <div class="keunggulan-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22s1-4 4-4h5c3 0 4 4 4 4M9 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="m17 8 2 2 4-4"/></svg>
                    </div>
                    <div class="keunggulan-num"><span class="cu" data-target="85">0</span>+</div>
                    <div class="keunggulan-lbl">Guru &amp; Staf Pendidik</div>
                </div>
                <div class="keunggulan-item reveal">
                    <div class="keunggulan-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34M12 2a4 4 0 0 0-4 4v7a4 4 0 0 0 8 0V6a4 4 0 0 0-4-4z"/></svg>
                    </div>
                    <div class="keunggulan-num"><span class="cu" data-target="150">0</span>+</div>
                    <div class="keunggulan-lbl">Penghargaan &amp; Piala</div>
                </div>
                <div class="keunggulan-item reveal">
                    <div class="keunggulan-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>
                    </div>
                    <div class="keunggulan-num"><span class="cu" data-target="20">0</span>+</div>
                    <div class="keunggulan-lbl">Tahun Berdiri</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         BERITA & KEGIATAN
    ══════════════════════════════════════════════════ --}}
    <section class="section berita-sec" id="berita">
        <div class="container">
            <div style="text-align:center; max-width:560px; margin:0 auto;" class="reveal">
                <div class="sec-label gold"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M16 8h2M16 12h2M16 16h2M6 8h6v8H6z"/></svg> Berita &amp; Kegiatan</div>
                <h2 class="sec-title">Informasi <span class="accent">Terbaru</span></h2>
                <p class="sec-desc">Ikuti perkembangan prestasi, kegiatan akademis, dan pengumuman resmi terbaru dari SMA Cihuy Bandung.</p>
            </div>
            <div class="berita-grid">
                <!-- Berita Utama (Featured News) -->
                <div class="berita-featured reveal">
                    <div class="berita-featured-bg" style="background-image: url('/images/school_activity.webp');"></div>
                    <div class="berita-featured-overlay"></div>
                    <div class="berita-featured-content">
                        <span class="berita-badge prestasi">Prestasi</span>
                        <div class="berita-meta">
                            <span>📅 12 Juni 2026</span>
                            <span>👤 Humas Sekolah</span>
                        </div>
                        <h3 class="berita-featured-title">
                            <a href="#">SMA Cihuy Sabet Juara Umum Olimpiade Sains Nasional (OSN) Tingkat Provinsi 2026</a>
                        </h3>
                        <p class="berita-featured-desc">Siswa-siswi SMA Cihuy Bandung berhasil memborong 5 medali emas, 3 perak, dan 2 perunggu dalam ajang bergengsi OSN tahun ini, membuktikan keunggulan kualitas pendidikan sains sekolah.</p>
                        <a href="#" class="btn-read-more">Baca Selengkapnya →</a>
                    </div>
                </div>

                <!-- Daftar Berita Lainnya -->
                <div class="berita-list reveal">
                    <!-- Berita 2 -->
                    <div class="berita-item-horizontal">
                        <div class="berita-thumb">
                            <img src="/images/school_hero.webp" alt="PPDB SMA Cihuy">
                        </div>
                        <div class="berita-item-content">
                            <span class="berita-badge info">PPDB</span>
                            <h4 class="berita-item-title">
                                <a href="#">Penerimaan Peserta Didik Baru (PPDB) TA 2026/2027 Resmi Dibuka</a>
                            </h4>
                            <div class="berita-item-meta">📅 10 Juni 2026 · Humas</div>
                        </div>
                    </div>

                    <!-- Berita 3 -->
                    <div class="berita-item-horizontal">
                        <div class="berita-thumb">
                            <img src="/images/sma_lab.webp" alt="Lab AI SMA Cihuy">
                        </div>
                        <div class="berita-item-content">
                            <span class="berita-badge info">Fasilitas</span>
                            <h4 class="berita-item-title">
                                <a href="#">SMA Cihuy Resmikan Lab Komputer Baru Berbasis AI untuk Informatika</a>
                            </h4>
                            <div class="berita-item-meta">📅 28 Mei 2026 · Sarpras</div>
                        </div>
                    </div>

                    <!-- Berita 4 -->
                    <div class="berita-item-horizontal">
                        <div class="berita-thumb">
                            <img src="/images/sma_kelas.webp" alt="Kegiatan Kelas SMA Cihuy">
                        </div>
                        <div class="berita-item-content">
                            <span class="berita-badge kegiatan">Kegiatan</span>
                            <h4 class="berita-item-title">
                                <a href="#">Studi Ekskursi &amp; Kunjungan Industri Siswa Kelas XI ke Puspiptek</a>
                            </h4>
                            <div class="berita-item-meta">📅 15 Mei 2026 · Kesiswaan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         GALERI FOTO
    ══════════════════════════════════════════════════ --}}
    <section class="section galeri-sec" id="galeri">
        <div class="container">
            <div style="text-align:center; max-width:520px; margin:0 auto;" class="reveal">
                <div class="sec-label red"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg> Galeri Sekolah</div>
                <h2 class="sec-title">Sekilas Kehidupan <span class="accent">Kampus SMA Cihuy</span></h2>
                <p class="sec-desc">Suasana belajar yang aktif, budaya disiplin, dan semangat berprestasi yang tercermin dalam setiap sudut kehidupan sekolah kami.</p>
            </div>

            <div class="galeri-grid reveal">
                <div class="galeri-item tall">
                    <img src="/images/sma_hero.webp" alt="Gedung Utama SMA Cihuy">
                    <div class="galeri-cap"><span>🏫 Gedung Utama SMA Cihuy</span></div>
                </div>
                <div class="galeri-item">
                    <img src="/images/sma_kelas.webp" alt="Kegiatan Belajar Mengajar">
                    <div class="galeri-cap"><span>📖 Kegiatan Belajar Mengajar</span></div>
                </div>
                <div class="galeri-item">
                    <img src="/images/sma_upacara.webp" alt="Upacara Bendera">
                    <div class="galeri-cap"><span>🇮🇩 Upacara Bendera</span></div>
                </div>
                <div class="galeri-item">
                    <img src="/images/sma_lab.webp" alt="Laboratorium Sains">
                    <div class="galeri-cap"><span>🔬 Laboratorium Sains</span></div>
                </div>
                <div class="galeri-item">
                    <img src="/images/sma_ekskul.webp" alt="Kegiatan Ekstrakurikuler">
                    <div class="galeri-cap"><span>🥁 Ekstrakurikuler</span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         EKSTRAKURIKULER
    ══════════════════════════════════════════════════ --}}
    <section class="section ekskul-sec" id="ekskul">
        <div class="container">
            <div style="text-align:center; max-width:520px; margin:0 auto;" class="reveal">
                <div class="sec-label navy"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg> Ekstrakurikuler</div>
                <h2 class="sec-title">Pengembangan Diri yang <span class="accent">Menyeluruh</span></h2>
                <p class="sec-desc">SMA Cihuy menyediakan 20+ kegiatan ekstrakurikuler yang mengasah bakat, kreativitas, kepemimpinan, dan jiwa sosial siswa di luar jam pelajaran.</p>
            </div>
            <div class="ekskul-grid">
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                    </div>
                    <h4>Marching Band</h4>
                    <p>Ekskul andalan yang telah meraih Juara Nasional sejak 2015</p>
                </div>
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M6.2 6.2c2.4-2.4 6.2-2.4 8.5 0M17.8 17.8c-2.4 2.4-6.2 2.4-8.5 0M1 12h22M12 1v22"/></svg>
                    </div>
                    <h4>Olahraga &amp; Sport</h4>
                    <p>Sepakbola, basket, voli, bulu tangkis, dan atletik dengan fasilitas lapangan penuh</p>
                </div>
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 14.7255 3.09032 17.1962 4.85857 19C5.34449 19.4859 5.58744 20.2148 5.43323 20.8906C5.3341 21.3251 5.6133 21.7483 6.0463 21.8497C6.18485 21.8821 6.32622 21.8984 6.46788 21.8984H12Z"/><circle cx="7.5" cy="10.5" r="1.5"/><circle cx="11.5" cy="7.5" r="1.5"/><circle cx="16.5" cy="9.5" r="1.5"/></svg>
                    </div>
                    <h4>Seni &amp; Kreativitas</h4>
                    <p>Teater, tari tradisional, paduan suara, lukis, dan desain grafis</p>
                </div>
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="8" cy="16" r="2"/><circle cx="16" cy="16" r="2"/><path d="M12 6v5M8 6h8M2 14h1M21 14h1"/></svg>
                    </div>
                    <h4>Robotik &amp; Coding</h4>
                    <p>Pemrograman, desain robot, dan kompetisi teknologi tingkat nasional</p>
                </div>
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M3 20h4M3 4h18v12H3V4z"/><path d="M7 8h10M7 12h4"/></svg>
                    </div>
                    <h4>Jurnalistik &amp; Pers</h4>
                    <p>Majalah dinding, podcast sekolah, dan liputan berita kampus</p>
                </div>
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </div>
                    <h4>Rohani &amp; Sosial</h4>
                    <p>Rohis, kegiatan sosial kemasyarakatan, dan bakti sosial rutin</p>
                </div>
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="2" ry="2"/><path d="M9 3v18M15 3v18M5 9h14M5 15h14"/></svg>
                    </div>
                    <h4>Pramuka &amp; PMR</h4>
                    <p>Kepramukaan dan Palang Merah Remaja sebagai ekskul wajib</p>
                </div>
                <div class="ekskul-card reveal">
                    <div class="ek-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10M12 2a15.3 15.3 0 0 0-4 10 15.3 15.3 0 0 0 4 10M2 12h20"/></svg>
                    </div>
                    <h4>Karya Ilmiah (KIR)</h4>
                    <p>Penelitian ilmiah, lomba LKTI tingkat provinsi dan nasional</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         FASILITAS
    ══════════════════════════════════════════════════ --}}
    <section class="section fasilitas-sec" id="fasilitas">
        <div class="container">
            <div class="fasilitas-grid">
                <div>
                    <div class="sec-label gold" style="color:#fff; background:rgba(200,146,42,0.15); border-color:rgba(200,146,42,0.3);"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M9 3v18M15 3v18M3 9h18M3 15h18"/></svg> Fasilitas Sekolah</div>
                    <h2 class="sec-title" style="color:#fff;">Fasilitas <span style="color:var(--gold-lt);">Lengkap &amp; Modern</span><br>untuk Menunjang Prestasi</h2>
                    <p class="sec-desc" style="color:rgba(255,255,255,0.5); margin-bottom:32px;">SMA Cihuy terus berinvestasi dalam peningkatan sarana dan prasarana demi menciptakan lingkungan belajar yang optimal dan menyenangkan.</p>
                    <div class="fasilitas-list">
                        <div class="fasilitas-item reveal">
                            <div class="fac-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v8M14 2v8M8 10h8M6 20h12M12 10v10M9 20a3 3 0 0 0 6 0"/></svg>
                            </div>
                            <div>
                                <h4>Laboratorium Lengkap</h4>
                                <p>Lab Fisika, Kimia, Biologi, Komputer &amp; Bahasa berstandar modern</p>
                            </div>
                        </div>
                        <div class="fasilitas-item reveal">
                            <div class="fac-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20M4 19.5V5A2.5 2.5 0 0 1 6.5 2.5H20v17H6.5Z"/></svg>
                            </div>
                            <div>
                                <h4>Perpustakaan Digital</h4>
                                <p>Koleksi 15.000+ buku cetak dan ribuan e-book &amp; jurnal ilmiah</p>
                            </div>
                        </div>
                        <div class="fasilitas-item reveal">
                            <div class="fac-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M6 12h12M12 6v12"/></svg>
                            </div>
                            <div>
                                <h4>Lapangan Olahraga</h4>
                                <p>Lapangan sepakbola, basket, voli, dan aula serbaguna berstandar resmi</p>
                            </div>
                        </div>
                        <div class="fasilitas-item reveal">
                            <div class="fac-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><path d="M8 21h8M12 17v4"/></svg>
                            </div>
                            <div>
                                <h4>Kelas Pintar (Smart Class)</h4>
                                <p>36 ruang kelas ber-AC dilengkapi proyektor &amp; layar interaktif</p>
                            </div>
                        </div>
                        <div class="fasilitas-item reveal">
                            <div class="fac-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13a10 10 0 0 1 14 0M8.5 16.5a5 5 0 0 1 7 0M2 9.5a15 15 0 0 1 20 0M12 20h.01"/></svg>
                            </div>
                            <div>
                                <h4>Wi-Fi Kampus Penuh</h4>
                                <p>Akses internet cepat di seluruh area sekolah untuk mendukung belajar digital</p>
                            </div>
                        </div>
                        <div class="fasilitas-item reveal">
                            <div class="fac-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            </div>
                            <div>
                                <h4>UKS &amp; Kantin Sehat</h4>
                                <p>Unit Kesehatan Sekolah aktif dan kantin bersih dengan menu bergizi</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fasilitas-img-wrap reveal">
                    <div class="fasilitas-img-badge">
                        <div class="big">36</div>
                        <div class="small">Ruang<br>Kelas</div>
                    </div>
                    <img src="/images/sma_lab.webp" alt="Fasilitas Laboratorium SMA Cihuy" class="fasilitas-img">
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         PRESTASI
    ══════════════════════════════════════════════════ --}}
    <section class="section prestasi-sec" id="prestasi">
        <div class="container">
            <div style="text-align:center; max-width:520px; margin:0 auto;" class="reveal">
                <div class="sec-label gold"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg> Prestasi &amp; Penghargaan</div>
                <h2 class="sec-title">Jejak <span class="accent">Kebanggaan</span> Kami</h2>
                <p class="sec-desc">Selama 20 tahun beroperasi, SMA Cihuy telah menorehkan ratusan prestasi di tingkat kota, provinsi, dan nasional.</p>
            </div>

            <div class="prestasi-timeline">
                <div class="prestasi-row reveal">
                    <div class="prestasi-dot"></div>
                    <div class="prestasi-card">
                        <div class="prestasi-year">2024 · Nasional</div>
                        <h4>Juara 1 Olimpiade Matematika Nasional</h4>
                        <p>Mewakili Jawa Barat dan meraih medali emas di ajang Kompetisi Sains Nasional (KSN) bidang Matematika.</p>
                        <span class="prestasi-badge pb-gold"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; vertical-align:middle; display:inline-block;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg> Emas Nasional</span>
                    </div>
                </div>
                <div class="prestasi-row right reveal">
                    <div class="prestasi-dot"></div>
                    <div class="prestasi-card">
                        <div class="prestasi-year">2024 · Provinsi Jawa Barat</div>
                        <h4>Juara 1 Marching Band Jawa Barat</h4>
                        <p>Tim Marching Band SMA Cihuy tampil memukau and meraih Juara 1 Kategori Umum di Jawa Barat Open 2024.</p>
                        <span class="prestasi-badge pb-gold"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; vertical-align:middle; display:inline-block;"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34M12 2a4 4 0 0 0-4 4v7a4 4 0 0 0 8 0V6a4 4 0 0 0-4-4z"/></svg> Juara 1 Provinsi</span>
                    </div>
                </div>
                <div class="prestasi-row reveal">
                    <div class="prestasi-dot"></div>
                    <div class="prestasi-card">
                        <div class="prestasi-year">2023 · Nasional</div>
                        <h4>Sekolah Penggerak Kemendikbudristek</h4>
                        <p>SMA Cihuy terpilih sebagai Sekolah Penggerak Angkatan 3 oleh Kemendikbudristek RI sebagai pengakuan inovasi pembelajaran.</p>
                        <span class="prestasi-badge pb-navy"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; vertical-align:middle; display:inline-block;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg> Sekolah Penggerak</span>
                    </div>
                </div>
                <div class="prestasi-row right reveal">
                    <div class="prestasi-dot"></div>
                    <div class="prestasi-card">
                        <div class="prestasi-year">2023 · Kota Bandung</div>
                        <h4>Juara Umum O2SN Kota Bandung</h4>
                        <p>Meraih predikat Juara Umum Olimpiade Olahraga Siswa Nasional (O2SN) tingkat kota Bandung untuk ketiga kalinya.</p>
                        <span class="prestasi-badge pb-gold"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; vertical-align:middle; display:inline-block;"><circle cx="12" cy="12" r="10"/><path d="M6.2 6.2c2.4-2.4 6.2-2.4 8.5 0M17.8 17.8c-2.4 2.4-6.2 2.4-8.5 0M1 12h22M12 1v22"/></svg> Juara Umum O2SN</span>
                    </div>
                </div>
                <div class="prestasi-row reveal">
                    <div class="prestasi-dot"></div>
                    <div class="prestasi-card">
                        <div class="prestasi-year">2022 · Nasional</div>
                        <h4>Juara 2 Karya Ilmiah Remaja Nasional</h4>
                        <p>Tim KIR SMA Cihuy meraih posisi runner-up dalam Lomba Karya Ilmiah Remaja (LKIR) LIPI tingkat nasional.</p>
                        <span class="prestasi-badge pb-silver"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; vertical-align:middle; display:inline-block;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg> Perak Nasional</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         TESTIMONI
    ══════════════════════════════════════════════════ --}}
    <section class="section testimony-sec">
        <div class="container">
            <div style="text-align:center; max-width:520px; margin:0 auto;" class="reveal">
                <div class="sec-label navy"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Kata Mereka</div>
                <h2 class="sec-title">Bangga Menjadi Bagian <span class="accent">SMA Cihuy</span></h2>
            </div>
            <div class="testimony-grid">
                <div class="t-card reveal">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"SMA Cihuy benar-benar tempat terbaik untuk tumbuh. Guru-gurunya tidak hanya mengajar pelajaran, tetapi juga membentuk karakter dan memotivasi kami untuk bermimpi besar. Saya bangga bisa masuk ITB lewat jalur SNBP dari sekolah ini!"</p>
                    <div class="t-author">
                        <div class="t-avatar av1">AR</div>
                        <div>
                            <div class="t-name">Andi Ramadhan</div>
                            <div class="t-role">Alumni 2023 · Mahasiswa Teknik ITB</div>
                        </div>
                    </div>
                </div>
                <div class="t-card reveal">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"Sebagai orang tua, saya sangat lega dan puas menyekolahkan anak di SMA Cihuy. Pendidikan karakter dan disiplinnya luar biasa. Nilai akademik anak saya meningkat pesat, dan ia tumbuh menjadi pribadi yang mandiri dan bertanggung jawab."</p>
                    <div class="t-author">
                        <div class="t-avatar av2">SW</div>
                        <div>
                            <div class="t-name">Siti Wahyuni</div>
                            <div class="t-role">Orang Tua Siswa Kelas XII IPA</div>
                        </div>
                    </div>
                </div>
                <div class="t-card reveal">
                    <div class="t-stars">★★★★★</div>
                    <p class="t-text">"Mengajar di SMA Cihuy adalah panggilan jiwa. Manajemen sekolah sangat mendukung pengembangan profesi guru. Atmosfer kerjanya kolaboratif, fasilitas memadai, dan yang terpenting, siswa-siswi di sini sangat bersemangat belajar."</p>
                    <div class="t-author">
                        <div class="t-avatar av3">BP</div>
                        <div>
                            <div class="t-name">Budi Prasetyo, S.Pd., M.Pd.</div>
                            <div class="t-role">Guru Senior Matematika · 10 Tahun Mengajar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         PPDB CTA
    ══════════════════════════════════════════════════ --}}
    <section class="section-sm ppdb-sec" id="kontak">
        <div class="container">
            <div class="ppdb-inner">
                <div class="ppdb-text reveal">
                    <div class="sec-label gold" style="background:rgba(200,146,42,0.12); color:var(--gold-lt); border-color:rgba(200,146,42,0.3); margin-bottom:14px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/></svg> PPDB 2025/2026</div>
                    <h2>Daftarkan Putra-Putri Anda<br>di <span style="color:var(--gold-lt);">SMA Cihuy</span> Sekarang</h2>
                    <p>Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026 segera dibuka. Bergabunglah dengan keluarga besar SMA Cihuy dan raih masa depan cerah bersama kami. Tersedia program beasiswa prestasi dan beasiswa ekonomi.</p>
                </div>
                <div class="ppdb-actions reveal">
                    <a href="{{ route('login') }}" class="btn-ppdb">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Akses Portal SIAKAD
                    </a>
                    <a href="#tentang" class="btn-ppdb-outline">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Info Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════ --}}
    <footer class="site-footer" id="footer">
        <div class="container">
            <div class="footer-grid">
                {{-- Brand --}}
                <div class="footer-brand">
                    <div class="logo-wrap">
                        <img src="/images/sma_logo.webp" alt="Logo SMA Cihuy" class="footer-logo-img">
                        <div>
                            <div class="footer-school-name">SMA Cihuy</div>
                            <div class="footer-school-city">Kota Bandung · Jawa Barat</div>
                        </div>
                    </div>
                    <p>Sekolah Menengah Atas Swasta Unggulan di Bandung. Mendidik generasi cerdas, berkarakter, dan berprestasi sejak tahun 2004.</p>
                    <div class="footer-npsn">NPSN : 20219876 &nbsp;·&nbsp; NSS : 302026020012</div>
                    <div class="footer-contact-list">
                        <a href="#">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Jl. Cihuy Raya No. 1, Kelurahan Cihuy,<br>Kec. Bandung Selatan, Kota Bandung 40212
                        </a>
                        <a href="tel:+62222220001">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            (022) 222-0001
                        </a>
                        <a href="mailto:info@smacihuy.sch.id">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            info@smacihuy.sch.id
                        </a>
                    </div>
                </div>

                {{-- Links 1 --}}
                <div>
                    <div class="footer-heading">Sekolah</div>
                    <div class="footer-links">
                        <a href="#tentang">Tentang Kami</a>
                        <a href="#berita">Berita Terbaru</a>
                        <a href="#fasilitas">Fasilitas</a>
                        <a href="#prestasi">Prestasi</a>
                        <a href="#ekskul">Ekstrakurikuler</a>
                        <a href="#">Kalender Akademik</a>
                        <a href="#">Pengumuman</a>
                    </div>
                </div>

                {{-- Links 2 --}}
                <div>
                    <div class="footer-heading">Informasi</div>
                    <div class="footer-links">
                        <a href="#">PPDB 2025/2026</a>
                        <a href="#">Beasiswa &amp; Dana</a>
                        <a href="#">Kurikulum Merdeka</a>
                        <a href="#">Alumni</a>
                        <a href="#">Hubungi Kami</a>
                        <a href="{{ route('login') }}">Portal SIAKAD</a>
                    </div>
                </div>

                {{-- Sosmed & Akreditasi --}}
                <div>
                    <div class="footer-heading footer-social-head">Media Sosial</div>
                    <div class="social-row">
                        <a href="#" class="social-btn" title="Instagram">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37zM17.5 6.5h.01"/></svg>
                        </a>
                        <a href="#" class="social-btn" title="YouTube">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 11.54a29 29 0 0 0 .46 5.12 2.78 2.78 0 0 0 1.95 1.96c1.71.46 8.59.46 8.59.46s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96 29 29 0 0 0 .46-5.12 29 29 0 0 0-.46-5.12z"/><polygon points="9.75 15.02 15.5 11.54 9.75 8.07 9.75 15.02"/></svg>
                        </a>
                        <a href="#" class="social-btn" title="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="#" class="social-btn" title="TikTok">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
                        </a>
                    </div>
                    <div class="footer-heading" style="margin-top:20px;">Akreditasi &amp; Legalitas</div>
                    <div class="footer-accred-badges">
                        <div class="footer-accred-badge"><span class="dot">★</span> Akreditasi A — BAN-S/M</div>
                        <div class="footer-accred-badge"><span class="dot">★</span> Kurikulum Merdeka</div>
                        <div class="footer-accred-badge"><span class="dot">★</span> Sekolah Penggerak 2023</div>
                        <div class="footer-accred-badge"><span class="dot">★</span> Terdaftar Dinas Pendidikan</div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© {{ date('Y') }} <span class="accent">SMA Cihuy Bandung</span>. Hak Cipta Dilindungi. Powered by <span class="accent">SIAKAD SMA</span>.</p>
                <p style="color:rgba(255,255,255,0.2);">Yayasan Pendidikan Cihuy Jaya · SK Kemenkumham No. AHU-0001234</p>
            </div>
        </div>
    </footer>

    {{-- ══════════════════════════════════════════════════
         SCRIPTS
    ══════════════════════════════════════════════════ --}}
    <script>
        // NAV scroll behavior
        const nav = document.getElementById('siteNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                nav.classList.remove('transparent');
            } else {
                nav.classList.add('transparent');
            }
        });

        // Mobile nav toggle
        function toggleNav() {
            const mn = document.getElementById('mobileNav');
            mn.classList.toggle('open');
        }
        document.addEventListener('click', (e) => {
            const mn = document.getElementById('mobileNav');
            const burger = document.getElementById('navBurger');
            if (!mn.contains(e.target) && !burger.contains(e.target)) {
                mn.classList.remove('open');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const href = a.getAttribute('href');
                if (href === '#') return;
                e.preventDefault();
                const el = document.querySelector(href);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // Scroll reveal
        const revealEls = document.querySelectorAll('.reveal');
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach((entry, idx) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), idx * 90);
                    revealObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(el => revealObs.observe(el));

        // Count-up animation
        function doCountUp(el) {
            const target = parseInt(el.getAttribute('data-target'));
            const duration = 1800;
            const start = performance.now();
            function step(now) {
                const p = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.floor(ease * target).toLocaleString('id-ID');
                if (p < 1) requestAnimationFrame(step);
                else el.textContent = target.toLocaleString('id-ID');
            }
            requestAnimationFrame(step);
        }
        const countEls = document.querySelectorAll('.cu');
        const countObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target._counted) {
                    entry.target._counted = true;
                    doCountUp(entry.target);
                }
            });
        }, { threshold: 0.5 });
        countEls.forEach(el => countObs.observe(el));
    </script>

</body>
</html>
