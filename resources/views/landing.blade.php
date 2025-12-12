<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IRM Maquinarias S.R.L.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    :root{--accent:#c59a2b;--accent-2:#7b5f1a;--muted:#6b7280}
    body{font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;
        /* Compuesto de fondo: degradado suave + patrón repetido muy sutil para textura */
        background:
            linear-gradient(180deg,#f6f8fb 0%, #ffffff 100%),
            repeating-linear-gradient(45deg, rgba(11,20,34,0.01) 0px, rgba(11,20,34,0.01) 1px, transparent 1px, transparent 32px);
        background-attachment:fixed;
        color:#0f172a}
        /* Smooth scrolling and offset for fixed header */
        html{scroll-behavior:smooth}
        /* Ensure anchored sections are visible below the fixed navbar */
        .about-section, #mision-vision, #envios, #contacto{scroll-margin-top:80px}
        @media(max-width:767px){ .about-section, #mision-vision, #envios, #contacto{scroll-margin-top:64px} }

    /* Section visual polish: soft background palette that combines with the gold accent */
        :root{--bg-soft-1:#fbfbfc;--bg-soft-2:#f6f8fb;--bg-ivory:#fffbf6;--text-strong:#0b1220}

        /* Section headings: elegant and consistent */
        .section-heading{display:flex;align-items:center;gap:14px;margin-bottom:1rem}
        .section-heading h3{font-weight:700;color:var(--text-strong);margin:0;font-size:1.45rem;letter-spacing:0.2px}
        .section-heading .accent-bar{width:56px;height:6px;border-radius:4px;background:linear-gradient(90deg,var(--accent),var(--accent-2));box-shadow:0 6px 18px rgba(197,154,43,0.08)}
        .section-lead{color:#4b5563;max-width:70ch;margin-bottom:1.2rem}
        /* Make sections use the full available width (body spans full width). Keep inner padding for legibility.
    .section-center{width:90% !important;max-width:none !important;margin:0;padding:1.25rem 2rem;background:linear-gradient(180deg,rgba(255,255,250,0.6),rgba(250,249,245,0.6));border-radius:0}
    @media(max-width:991px){.section-center{padding:1rem 1rem}} */

    /* Center content at 90% width while keeping section backgrounds full-bleed */
    .section-center{width:90% !important;max-width:none !important;margin:0 auto;padding:1.25rem 2rem;background:transparent;border-radius:0}
    @media(max-width:991px){.section-center{width:95%;padding:1rem 1rem;border-radius:0}}

    /* Reveal animation for elements entering the viewport (supports per-element delay via --reveal-delay) */
    .reveal{opacity:0;transform:translateY(18px);transition:opacity .7s cubic-bezier(.2,.9,.2,1),transform .7s cubic-bezier(.2,.9,.2,1);transition-delay:var(--reveal-delay,0s)}
    .reveal.in-view{opacity:1;transform:none}

        /* About: subtle ivory -> soft white gradient */
        .about-section{background:linear-gradient(180deg,var(--bg-ivory),#ffffff);padding:4.5rem 0;border-top:1px solid rgba(15,23,42,0.03);border-bottom:1px solid rgba(15,23,42,0.03)}
        .about-text p{color:#374151;font-size:1.03rem}

        /* Mission & Vision: light cool background with card elevated */
        .mv-section{background:linear-gradient(180deg,#f8fbff,#ffffff);padding:4rem 0}
        .mv-section .card-hero{background:linear-gradient(180deg,#ffffff,#fbfcff);border:1px solid rgba(11,20,34,0.04)}

        /* Envíos: warm accent background strip behind the feature — ligera variación más cálida y textura sutil */
        .envios-section{
            background:linear-gradient(90deg,#fff7ef,#ffffff); /* tono ligeramente más cálido */
            padding:3.5rem 0;
            position:relative;
            overflow:visible;
        }
        /* textura sutil encima del degradado para dar calidez sin distraer */
        .envios-section:before{
            content:"";
            position:absolute;
            inset:0;
            background:repeating-linear-gradient(135deg, rgba(197,154,43,0.02) 0 2px, transparent 2px 40px);
            pointer-events:none;
            z-index:0;
        }
        /* asegurar que el contenido quede por encima de la textura */
        .envios-section .container{position:relative;z-index:2}

        #envios.feature{background:transparent;border:none}
        #envios .feature{background:linear-gradient(180deg,#ffffff,#fffdfb);border-left:6px solid rgba(197,154,43,0.12);padding:1.25rem}

        /* Subtle card styles for all sections on wide screens */
        @media(min-width:992px){
            .about-section .container, .mv-section .container, .envios-section .container{max-width:1100px}
        }

    /* Navbar */
    /* Fondo negro elegante para el menú: fondo sólido con sutil desenfoque, mejor espaciado y botones redondeados */
    .navbar-transparent{background:#000;position:relative;z-index:60;transition:background-color .35s ease,box-shadow .35s ease,backdrop-filter .35s;backdrop-filter:blur(6px);border-bottom:1px solid rgba(255,255,255,0.03)}
    .navbar-solid{background:linear-gradient(180deg,#000,#050505);box-shadow:0 12px 30px rgba(2,6,23,0.45);backdrop-filter:blur(6px)}

    .navbar-brand .brand{font-weight:800;color:var(--accent);letter-spacing:0.8px;font-size:0.98rem;text-transform:uppercase}
    .navbar .navbar-brand img{height:44px;object-fit:contain}

    /* Nav links: elegant spacing and hover lift with gold accent */
    /* Forzar blanco para asegurar contraste sobre el fondo negro */
    .navbar .nav-link{color:#ffffff !important;margin-left:.5rem;margin-right:.5rem;padding:.45rem .5rem;transition:color .18s,transform .18s}
    .navbar .nav-link:hover{color:var(--accent) !important;transform:translateY(-2px)}
    .navbar .nav-link.active{color:var(--accent) !important;font-weight:600}

    /* Buttons in navbar: rounded, subtle shadow */
    .navbar .btn.btn-primary{background:linear-gradient(90deg,var(--accent),#7c3aed);border:none;padding:.46rem .9rem;border-radius:10px;box-shadow:0 6px 18px rgba(124,58,237,0.12)}
    .navbar .btn.btn-outline-light{border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.92);border-radius:10px;padding:.42rem .8rem}

    /* Toggler: small rounded button with subtle border */
    .navbar-toggler{border-radius:8px;border:1px solid rgba(255,255,255,0.08);padding:.22rem}
    .navbar-toggler .navbar-toggler-icon{filter:invert(1) brightness(2);width:22px;height:18px}

    @media (min-width:992px){ .navbar{padding-top:.6rem;padding-bottom:.6rem} }
    @media (max-width:991px){ .navbar{padding:.45rem .6rem} }

    /* Mobile-specific navbar improvements: clear menu icon, larger touch targets and solid collapsed background */
    @media (max-width:991px){
        .navbar-toggler{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);padding:.36rem}
        .navbar-toggler-icon{filter:none;width:22px;height:18px;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'><path stroke='white' stroke-width='2.8' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/></svg>");background-size:22px 18px}
        /* Ensure collapsed menu is a distinct, readable panel */
        /* Make collapsed menu act like an off-canvas / dropdown panel that is visible on mobile */
        .navbar-collapse{
            background:#000;padding:.9rem;border-radius:10px;margin-top:.65rem;box-shadow:0 18px 40px rgba(2,6,23,0.45);
            position:absolute;top:100%;left:0;right:0;margin-left:auto;margin-right:auto;width:calc(100% - 2rem);
            z-index:120;max-height:72vh;overflow:auto;backdrop-filter:blur(6px);
        }
        .navbar-nav .nav-link{padding:.75rem 1rem;font-size:1.02rem;border-radius:8px;margin-bottom:.35rem}
        .navbar-nav .nav-link:hover{background:rgba(255,255,255,0.03)}
        /* Make CTA button full-width inside collapsed menu for better access */
        .navbar-collapse .btn{width:100%;display:block;text-align:center}
    }

        /* Hero */
        .main-hero{min-height:72vh;display:flex;align-items:center;position:relative;background-size:cover;background-position:center center;background-repeat:no-repeat;border-bottom:1px solid rgba(15,23,42,0.04)}
        /* overlay más oscuro para legibilidad */
        .main-hero:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg, rgba(3,10,34,0.68), rgba(3,10,34,0.22));backdrop-filter:contrast(.96);}
        .hero-inner{position:relative;z-index:2;color:#fff;padding:4rem 0}
        .hero-title{font-size:2.6rem;font-weight:800;letter-spacing:0.6px;line-height:1.05}
        .hero-sub{color:rgba(255,255,255,0.95);margin-top:.8rem;font-size:1.05rem;max-width:56ch}
        .hero-cta a{min-width:170px}

        /* Scroll down indicator */
        .scroll-down{position:absolute;left:50%;transform:translateX(-50%);bottom:18px;z-index:60}
        .scroll-down .arrow{width:36px;height:36px;border-radius:999px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;color:#fff;backdrop-filter:blur(2px);box-shadow:0 8px 20px rgba(2,6,23,0.2)}
        .scroll-down .arrow i{animation:scrollY 1.4s infinite}
        @keyframes scrollY{0%{transform:translateY(0)}50%{transform:translateY(6px)}100%{transform:translateY(0)}}

        /* Sections */
        .about-section{background:linear-gradient(180deg,#ffffff,#fbfbfc);padding:4rem 0;border-top:1px solid rgba(15,23,42,0.04);border-bottom:1px solid rgba(15,23,42,0.04)}
        .about-text h3{font-weight:700;margin-bottom:1rem;color:#0f172a}
        .about-text p{color:#374151;line-height:1.8;font-size:1rem;margin-bottom:1rem}
        .about-image img{border-radius:12px;box-shadow:0 18px 40px rgba(2,6,23,0.08);max-height:420px;object-fit:contain;width:auto}

        /* Elegant image panel used across sections */
        .img-panel{width:100%;max-width:560px;border-radius:14px;overflow:hidden;box-shadow:0 20px 50px rgba(2,6,23,0.08);background:linear-gradient(180deg,#ffffff,#fbfbfc);border:1px solid rgba(15,23,42,0.04);display:flex;align-items:center;justify-content:center;padding:14px}
        .img-panel img{width:100%;height:100%;max-height:420px;object-fit:cover;display:block}
        .img-panel.is-logo{background:transparent;padding:6px}
        .img-panel.is-logo img{object-fit:contain;max-width:260px}

        /* Make image panels match the adjacent text height on wide screens */
    .about-section .row, .card-hero .row { align-items: stretch; }
    .about-text{display:flex;flex-direction:column;justify-content:center;flex:1;min-height:1px}
    .about-image{display:flex;align-items:stretch;flex:1;min-height:1px}
    /* make the about panel larger on desktop */
    .about-image .img-panel{height:100%;padding:12px;max-width:760px}
    .about-image .img-panel img{max-height:520px;object-fit:cover;width:auto;height:100%}
    .about-image .img-panel.is-logo img{max-width:360px;height:auto}
    /* ensure mission/vision panel also stretches */
    #mision-vision .row{align-items:stretch}
    #mision-vision .img-panel{height:100%;padding:12px}

        /* Mobile: stack normally and avoid forced heights */
        @media(max-width:767px){
            .img-panel{max-height:260px;height:auto}
            .img-panel img{height:auto}
        }

        /* Card and features */
        .card-hero{background:#fff;border-radius:12px;box-shadow:0 12px 30px rgba(15,23,42,0.06);padding:1.5rem}
        .feature{background:white;border-radius:12px;padding:1.5rem;box-shadow:0 8px 24px rgba(15,23,42,0.04)}
    .cta-primary{background:linear-gradient(90deg,var(--accent),#7c3aed);border:none;color:#fff}
    /* Elegant footer */
    footer.site-footer{background:linear-gradient(180deg,#071025,#0b1422);color:rgba(255,255,255,0.85);padding:3.5rem 0;border-top:1px solid rgba(255,255,255,0.03)}
    footer.site-footer a{color:rgba(255,255,255,0.85);text-decoration:none}
    footer.site-footer .footer-logo{height:56px;object-fit:contain}
    footer.site-footer .footer-title{font-weight:700;color:var(--accent);letter-spacing:0.4px}
    footer.site-footer .footer-links a{display:block;margin-bottom:.45rem;color:rgba(255,255,255,0.78)}
    footer.site-footer .social a{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,0.04);color:inherit;margin-right:.5rem}
    footer.site-footer .contact-item{color:rgba(255,255,255,0.78);margin-bottom:.6rem}
    footer.site-footer .small-muted{color:rgba(255,255,255,0.55)}

        /* Responsive tweaks */
        @media(min-width:992px){.hero-title{font-size:3.6rem}}
        @media(max-width:767px){
            .hero-title{font-size:1.6rem}
            .hero-sub{display:block}
            .hero-inner{padding:2rem 0}
            .hero-cta .btn{padding-left:1rem;padding-right:1rem}
            .about-image img{max-height:260px}
        }
    </style>
</head>
<body>

    <header class="navbar-transparent fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark p-2">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ asset('images/logo.png') }}" alt="logo" class="img-fluid" style="height:40px;object-fit:contain;margin-right:.5rem">
                    <span class="ms-1 brand">IRM Maquinarias S.R.L.</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sobre">Nosotros</a></li>
                        <li class="nav-item"><a class="nav-link" href="#envios">Envíos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                        <li class="nav-item ms-2"><a class="btn btn-sm btn-primary" href="{{ route('login') }}" target="_blank" rel="noopener">Entrar al Sistema</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <?php
        // Usar la imagen de excavadora proporcionada como fondo principal si existe.
        if (file_exists(public_path('images/mining-excavator-1736293_1280.jpg'))) {
            $heroImg = asset('images/mining-excavator-1736293_1280.jpg');
        } elseif(file_exists(public_path('images/fondo.jpg'))){
            // fallback local alternativo si se desea
            $heroImg = asset('images/fondo.jpg');
        } elseif(file_exists(public_path('images/hero-graphic.png'))){
            $heroImg = asset('images/hero-graphic.png');
        } else {
            $heroImg = null;
        }
    ?>
    <section class="main-hero" <?php echo $heroImg ? "style=\"background-image: url('{$heroImg}');\"" : ''; ?> >
        <div class="container hero-inner">
            <div class="row">
                <div class="col-lg-7">
                    <h1 class="hero-title">IRM Maquinarias S.R.L.</h1>
                    <p class="hero-sub">Venta de accesorios y repuestos premium para maquinaria pesada — soluciones para los sectores industrial, minero, agrícola y de construcción.</p>

                    <div class="mt-4 hero-cta">
                        <a href="#sobre" class="btn btn-lg cta-primary me-2"><i class="fas fa-box-open me-2"></i> Ver catálogo</a>
                        <a href="https://wa.me/51976390506" target="_blank" rel="noopener" class="btn btn-lg btn-outline-light"><i class="fab fa-whatsapp me-2"></i> Contactar por WhatsApp</a>
                    </div>

                    <!-- scroll down indicator -->
                    <div class="scroll-down">
                        <a href="#sobre" class="arrow" aria-label="Bajar">
                            <i class="fas fa-chevron-down"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SOBRE: full width section -->
    <?php
        if (file_exists(public_path('images/sobre.jpg'))) {
            $sobreImg = asset('images/sobre.jpg');
        } elseif (file_exists(public_path('images/sobre.png'))) {
            $sobreImg = asset('images/sobre.png');
        } elseif (file_exists(public_path('images/logo.png'))) {
            $sobreImg = asset('images/logo.png');
        } else {
            $sobreImg = null;
        }
    ?>
    <section id="sobre" class="about-section">
        <div class="container section-center reveal" style="--reveal-delay:0s">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 about-text">
                    <div class="section-heading reveal" style="--reveal-delay:0s">
                        <div class="accent-bar"></div>
                        <h3>Sobre nosotros</h3>
                    </div>
                    <p class="reveal" style="--reveal-delay:0.12s">
                        IRM Maquinarias S.R.L. es una empresa especializada en la comercialización de maquinaria de alto rendimiento, así como en la distribución de repuestos y accesorios de calidad premium para los sectores industrial, minero, agrícola y de construcción.
                    </p>
                    <p class="reveal" style="--reveal-delay:0.24s">
                        Con un enfoque integral, brindamos soluciones que combinan tecnología, durabilidad y precisión, asegurando el óptimo desempeño de cada equipo. Nuestro catálogo de repuestos originales y accesorios especializados garantiza a los clientes continuidad operativa, eficiencia y una prolongada vida útil de sus maquinarias.
                    </p>
                    <p class="reveal" style="--reveal-delay:0.36s">
                        Nos caracterizamos por ofrecer un servicio personalizado, soporte técnico especializado y atención inmediata, respaldados por un sólido compromiso con la excelencia. En IRM Maquinarias S.R.L. trabajamos para ser un aliado estratégico en cada proyecto, proporcionando productos confiables y soluciones pensadas para las necesidades más exigentes del mercado.
                    </p>
                </div>
                <div class="col-12 col-md-6 about-image d-flex justify-content-center">
                    @if($sobreImg)
                        <?php $isLogo = strpos($sobreImg, 'logo.png') !== false; ?>
                        <div class="img-panel <?php echo $isLogo ? 'is-logo' : ''; ?> reveal" style="--reveal-delay:0.48s">
                            <img src="{{ $sobreImg }}" alt="Sobre IRM Maquinarias S.R.L." class="img-fluid">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- MISION Y VISION: full width -->
    <?php
        // Prefer the provided bulldozer image if present, then fall back to other mission images
        if (file_exists(public_path('images/bulldozer-2195329_1280.jpg'))) {
            $mvImg = asset('images/bulldozer-2195329_1280.jpg');
        } elseif (file_exists(public_path('images/mision_vision.jpg'))) {
            $mvImg = asset('images/mision_vision.jpg');
        } elseif (file_exists(public_path('images/mision.jpg'))) {
            $mvImg = asset('images/mision.jpg');
        } else {
            $mvImg = null;
        }
    ?>
    <section class="container-fluid py-5 mv-section">
        <div class="container section-center reveal" style="--reveal-delay:0s">
            <div id="mision-vision" class="card-hero w-100">
                <div class="row align-items-center">
                    <div class="col-12 col-md-7">
                        <div class="section-heading reveal" style="--reveal-delay:0s">
                            <div class="accent-bar"></div>
                            <h3>Misión</h3>
                        </div>
                        <p class="reveal" style="--reveal-delay:0.12s">
                            Brindar soluciones integrales en maquinaria, repuestos y accesorios de alta calidad, ofreciendo a nuestros clientes productos confiables y un servicio técnico especializado que garantice eficiencia, seguridad y continuidad operativa. Trabajamos con compromiso, profesionalismo y mejora continua para contribuir al éxito y desarrollo de cada uno de nuestros clientes.
                        </p>

                        <div class="section-heading reveal mt-3" style="--reveal-delay:0.24s">
                            <div class="accent-bar"></div>
                            <h3>Visión</h3>
                        </div>
                        <p class="reveal" style="--reveal-delay:0.36s">
                            Ser una empresa líder y referente en el mercado nacional en la provisión de maquinaria, repuestos y accesorios, reconocida por su excelencia, innovación y servicio personalizado; consolidándonos como un aliado estratégico para los sectores industrial, minero, agrícola y de construcción, impulsando su crecimiento sostenible.
                        </p>
                    </div>

                    <div class="col-12 col-md-5 d-flex justify-content-center">
                        @if($mvImg)
                            <?php $mvIsLogo = strpos($mvImg, 'logo.png') !== false; ?>
                            <div class="img-panel <?php echo $mvIsLogo ? 'is-logo' : ''; ?> reveal" style="--reveal-delay:0.48s">
                                <img src="{{ $mvImg }}" alt="Misión y Visión" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ENVIOS: full width -->
    <?php
        $enviosImg = file_exists(public_path('images/envios.jpg')) ? asset('images/envios.jpg') : (file_exists(public_path('images/envios.png')) ? asset('images/envios.png') : null);
    ?>
    <section class="container-fluid py-5 envios-section">
        <div class="container section-center reveal" style="--reveal-delay:0s">
            <div id="envios" class="feature">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-3 d-none d-md-block">
                        @if($enviosImg)
                            <div class="img-panel" style="max-width:160px;padding:8px">
                                <img src="{{ $enviosImg }}" alt="Envíos" class="img-fluid" style="max-height:120px;object-fit:contain">
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="section-heading reveal" style="--reveal-delay:0s">
                            <div class="accent-bar"></div>
                            <h3>Envíos</h3>
                        </div>
                        <p class="mb-2 reveal" style="--reveal-delay:0.12s">
                            Ofrecemos servicios de envío nacional e internacional, con embalaje seguro y opciones de transporte adaptadas al tipo de carga. Contamos con seguimiento de pedidos y coordinación logística para asegurar la llegada rápida y segura de repuestos y accesorios a su destino.
                        </p>
                        <div class="mt-2 d-flex gap-2 reveal" style="--reveal-delay:0.24s">
                            <a href="tel:+51974179198" class="btn btn-outline-primary btn-sm"><i class="fas fa-phone me-2"></i>Llamar</a>
                            <a href="https://wa.me/51976390506" target="_blank" rel="noopener" class="btn btn-success btn-sm"><i class="fab fa-whatsapp me-2"></i>WhatsApp</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="contacto" class="site-footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="IRM logo" class="footer-logo img-fluid">
                        <div>
                            <div class="footer-title">IRM Maquinarias S.R.L.</div>
                            <div class="small-muted">Maquinaria, repuestos y accesorios premium</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <h6 class="mb-2">Contacto</h6>
                    <div class="contact-item"><i class="fas fa-map-marker-alt me-2"></i>AV. ATAHUALPA NRO. 725, CAJAMARCA</div>
                    <div class="contact-item"><i class="fas fa-phone me-2"></i><a href="tel:+51974179198">974 179 198</a></div>
                    <div class="contact-item"><i class="fab fa-whatsapp me-2"></i><a href="https://wa.me/51976390506" target="_blank" rel="noopener">WhatsApp</a></div>
                    <div class="contact-item"><i class="fas fa-id-card me-2"></i>RUC 20570639553</div>
                </div>

                <div class="col-12 col-md-4">
                    <h6 class="mb-2">Enlaces rápidos</h6>
                    <div class="footer-links">
                        <a href="#sobre">Sobre nosotros</a>
                        <a href="#mision-vision">Misión y Visión</a>
                        <a href="#envios">Envíos</a>
                        <a href="#contacto">Contacto</a>
                    </div>

                    <div class="mt-3 social">
                        <a href="#" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                        <a href="#" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://wa.me/51976390506" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center small-muted">
                    © {{ date('Y') }} IRM Maquinarias S.R.L. — Todos los derechos reservados.
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // navbar background on scroll
        (function(){
            const header = document.querySelector('header');
            function onScroll(){
                if(window.scrollY > 40){ header.classList.add('navbar-solid'); header.classList.remove('navbar-transparent'); }
                else { header.classList.remove('navbar-solid'); header.classList.add('navbar-transparent'); }
            }
            onScroll();
            window.addEventListener('scroll', onScroll);
        })();
        
        // Reveal on scroll using IntersectionObserver
        (function(){
            const io = new IntersectionObserver((entries)=>{
                entries.forEach(e=>{
                    if(e.isIntersecting){ e.target.classList.add('in-view'); io.unobserve(e.target); }
                })
            },{threshold:0.16});
            document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
        })();
    </script>
</body>
</html>
