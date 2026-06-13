// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            if (overlay) overlay.classList.toggle('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            if (sidebar) sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }

    // Profile Dropdown Toggle
    const profileBtn = document.getElementById('profile-dropdown-btn');
    const profileMenu = document.getElementById('profile-menu');
    const profileArrow = document.getElementById('profile-dropdown-arrow');

    if (profileBtn && profileMenu) {
        const showMenu = () => {
            profileMenu.classList.remove('hidden');
            // Allow browser to register class removal before starting animation
            requestAnimationFrame(() => {
                profileMenu.classList.remove('opacity-0', 'scale-95');
                profileMenu.classList.add('opacity-100', 'scale-100');
            });
            if (profileArrow) {
                profileArrow.classList.add('rotate-180');
            }
        };

        const hideMenu = () => {
            profileMenu.classList.remove('opacity-100', 'scale-100');
            profileMenu.classList.add('opacity-0', 'scale-95');
            if (profileArrow) {
                profileArrow.classList.remove('rotate-180');
            }
            // Hide element after transition completes
            setTimeout(() => {
                if (profileMenu.classList.contains('opacity-0')) {
                    profileMenu.classList.add('hidden');
                }
            }, 150);
        };

        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = profileMenu.classList.contains('hidden') || profileMenu.classList.contains('opacity-0');
            if (isHidden) {
                showMenu();
            } else {
                hideMenu();
            }
        });

        document.addEventListener('click', function (e) {
            if (!profileMenu.classList.contains('hidden')) {
                const isClickInside = profileBtn.contains(e.target) || profileMenu.contains(e.target);
                if (!isClickInside) {
                    hideMenu();
                }
            }
        });

        // Prevent closing dropdown when clicking inside the menu content
        profileMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Auto-dismiss flash messages
    const flashMessages = document.querySelectorAll('[data-flash]');
    flashMessages.forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            el.style.transition = 'all 0.4s ease';
            setTimeout(() => el.remove(), 400);
        }, 5000);
    });

    // Active nav link detection
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        if (link.getAttribute('href') && currentPath.startsWith(link.getAttribute('href')) && link.getAttribute('href') !== '/') {
            link.classList.add('active');
        }
    });

    // Landing page scroll effect
    const navFloating = document.getElementById('nav-floating');
    if (navFloating) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navFloating.classList.add('scrolled');
            } else {
                navFloating.classList.remove('scrolled');
            }
        });
    }

    // Stat card counter animation
    const statNumbers = document.querySelectorAll('[data-count-to]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-count-to'));
                const duration = 1200;
                const step = target / (duration / 16);
                let current = 0;
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    el.textContent = Math.floor(current).toLocaleString('id-ID');
                }, 16);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.2 });
    statNumbers.forEach(el => observer.observe(el));

    // Confirm delete dialogs
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            const message = this.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Form submit with loading state
    document.querySelectorAll('form[data-loading]').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = this.querySelector('[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...`;
            }
        });
    });

    // Animate elements on scroll
    const animateOnScroll = document.querySelectorAll('[data-animate]');
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const animation = entry.target.getAttribute('data-animate');
                entry.target.classList.add(`animate-${animation}`);
                scrollObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    animateOnScroll.forEach(el => {
        el.style.opacity = '0';
        scrollObserver.observe(el);
    });
});
