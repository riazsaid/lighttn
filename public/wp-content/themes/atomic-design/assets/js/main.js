/* ============================================================
   THEME JS — atomic-design
   Handles: mobile nav toggle, scroll-shadow on header.
   ============================================================ */

(function () {
    'use strict';

    // Mobile nav toggle
    const toggle = document.querySelector('.site-header__toggle');
    const nav    = document.querySelector('.site-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        // Close nav when clicking a link (single-page feel)
        nav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });

        // Close nav on outside click
        document.addEventListener('click', e => {
            if (!toggle.contains(e.target) && !nav.contains(e.target)) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Add scroll shadow to header
    const header = document.querySelector('.site-header');
    if (header) {
        const onScroll = () => {
            header.classList.toggle('is-scrolled', window.scrollY > 10);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    function initScrollReveal() {
        const nodes = Array.from(document.querySelectorAll('.scroll-reveal'));

        if (!nodes.length) {
            return;
        }

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (reduceMotion || !('IntersectionObserver' in window)) {
            nodes.forEach(node => node.classList.add('revealed'));
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('revealed');
                obs.unobserve(entry.target);
            });
        }, {
            root: null,
            threshold: 0.12,
            rootMargin: '0px 0px -8% 0px'
        });

        nodes.forEach(node => observer.observe(node));
    }

    function initPartnersAffiliationsCarousels() {
        const carousels = document.querySelectorAll('[data-partners-carousel]');

        carousels.forEach(carousel => {
            const viewport = carousel.querySelector('.partners-affiliations-block__viewport');
            const track = carousel.querySelector('.partners-affiliations-block__track');
            const dots = carousel.querySelector('.partners-affiliations-block__dots');
            const cards = track ? Array.from(track.querySelectorAll('.partners-affiliations-block__card')) : [];

            if (!viewport || !track || !dots || cards.length <= 3) {
                return;
            }

            let currentPage = 0;
            let dotButtons = [];

            const getPerPage = () => window.matchMedia('(max-width: 760px)').matches ? 1 : 3;
            const getTrackGap = () => parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap || 0) || 0;
            const getTotalSlides = () => Math.max(cards.length - getPerPage() + 1, 1);

            const setActiveDot = () => {
                dotButtons.forEach((button, index) => {
                    const isActive = index === currentPage;
                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-current', isActive ? 'true' : 'false');
                });
            };

            const moveToPage = page => {
                const totalSlides = getTotalSlides();
                const cardWidth = cards[0].getBoundingClientRect().width;
                const slideWidth = cardWidth + getTrackGap();

                currentPage = Math.max(0, Math.min(page, totalSlides - 1));
                track.style.transform = 'translateX(' + (-currentPage * slideWidth) + 'px)';
                setActiveDot();
            };

            const rebuildDots = () => {
                const totalSlides = getTotalSlides();

                currentPage = Math.min(currentPage, totalSlides - 1);
                dots.innerHTML = '';
                dotButtons = [];

                if (totalSlides <= 1) {
                    dots.hidden = true;
                    track.style.transform = '';
                    return;
                }

                dots.hidden = false;

                for (let index = 0; index < totalSlides; index += 1) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'partners-affiliations-block__dot';
                    button.setAttribute('aria-label', 'Show partner logos set ' + (index + 1));
                    button.addEventListener('click', () => moveToPage(index));
                    dots.appendChild(button);
                    dotButtons.push(button);
                }

                moveToPage(currentPage);
            };

            if (carousel.dataset.partnersCarouselReady === 'true') {
                return;
            }

            carousel.dataset.partnersCarouselReady = 'true';
            rebuildDots();

            let resizeTimer = null;
            window.addEventListener('resize', () => {
                window.clearTimeout(resizeTimer);
                resizeTimer = window.setTimeout(rebuildDots, 120);
            });
        });
    }

    window.addEventListener('load', initPartnersAffiliationsCarousels);
    initPartnersAffiliationsCarousels();
    initScrollReveal();

})();
