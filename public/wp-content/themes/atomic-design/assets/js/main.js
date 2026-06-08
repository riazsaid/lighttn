/* ============================================================
   THEME JS — atomic-design
   Handles: mobile nav toggle, scroll-shadow on header.
   ============================================================ */

(function () {
    'use strict';

    // Prepare primary navigation links for the desktop text animation.
    document.querySelectorAll('.site-nav .menu li a').forEach(link => {
        const text = link.textContent.trim();
        const label = document.createElement('span');

        link.setAttribute('data-text', text);
        link.textContent = '';
        label.textContent = text;
        link.appendChild(label);
    });

    // Mobile nav toggle and submenu accordions.
    const toggle = document.querySelector('.site-header__toggle');
    const nav = document.querySelector('.site-nav');
    const mobileNavQuery = window.matchMedia('(max-width: 68rem)');

    if (toggle && nav) {
        const submenuItems = Array.from(nav.querySelectorAll('.menu-item-has-children'));

        submenuItems.forEach((item, index) => {
            const submenu = item.querySelector(':scope > .sub-menu');

            if (!submenu) {
                return;
            }

            const submenuId = submenu.id || `primary-submenu-${index + 1}`;
            const submenuToggle = document.createElement('button');
            const parentLink = item.querySelector(':scope > a');
            const label = parentLink ? parentLink.textContent.trim() : 'submenu';

            submenu.id = submenuId;
            submenuToggle.className = 'site-nav__submenu-toggle';
            submenuToggle.type = 'button';
            submenuToggle.setAttribute('aria-expanded', 'false');
            submenuToggle.setAttribute('aria-controls', submenuId);
            submenuToggle.setAttribute('aria-label', `Toggle ${label} submenu`);
            submenuToggle.innerHTML = '<span aria-hidden="true"></span>';
            item.insertBefore(submenuToggle, submenu);

            submenuToggle.addEventListener('click', () => {
                const isOpen = item.classList.toggle('is-submenu-open');
                submenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });

        const closeNav = () => {
            nav.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('mobile-nav-open');
            syncNavA11y();
        };

        const syncNavA11y = () => {
            const isHidden = mobileNavQuery.matches && !nav.classList.contains('is-open');

            nav.setAttribute('aria-hidden', isHidden ? 'true' : 'false');
        };

        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('mobile-nav-open', isOpen);
            syncNavA11y();
        });

        nav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeNav);
        });

        document.addEventListener('click', e => {
            if (!toggle.contains(e.target) && !nav.contains(e.target)) {
                closeNav();
            }
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && nav.classList.contains('is-open')) {
                closeNav();
                toggle.focus();
            }
        });

        const handleNavBreakpointChange = e => {
            closeNav();

            if (!e.matches) {
                submenuItems.forEach(item => {
                    item.classList.remove('is-submenu-open');
                    item.querySelector(':scope > .site-nav__submenu-toggle')
                        ?.setAttribute('aria-expanded', 'false');
                });
            }
        };

        if (typeof mobileNavQuery.addEventListener === 'function') {
            mobileNavQuery.addEventListener('change', handleNavBreakpointChange);
        } else if (typeof mobileNavQuery.addListener === 'function') {
            mobileNavQuery.addListener(handleNavBreakpointChange);
        }

        syncNavA11y();
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

    function initConsultationModals() {
        const openButtons = Array.from(document.querySelectorAll('[data-consultation-modal-open]'));

        if (!openButtons.length) {
            return;
        }

        const focusableSelector = [
            'a[href]',
            'button:not([disabled])',
            'input:not([disabled])',
            'select:not([disabled])',
            'textarea:not([disabled])',
            '[tabindex]:not([tabindex="-1"])'
        ].join(',');
        let activeModal = null;
        let activeTrigger = null;

        const getFocusable = modal => Array.from(modal.querySelectorAll(focusableSelector))
            .filter(element => element.offsetParent !== null || element === document.activeElement);

        const closeModal = () => {
            if (!activeModal) {
                return;
            }

            activeModal.hidden = true;
            document.body.classList.remove('consultation-modal-open');

            if (activeTrigger) {
                activeTrigger.focus();
            }

            activeModal = null;
            activeTrigger = null;
        };

        const openModal = button => {
            const modalId = button.getAttribute('aria-controls');
            const modal = modalId ? document.getElementById(modalId) : null;

            if (!modal) {
                return;
            }

            activeModal = modal;
            activeTrigger = button;
            modal.hidden = false;
            document.body.classList.add('consultation-modal-open');

            const focusable = getFocusable(modal);
            const firstFocusable = focusable[0] || modal;
            window.setTimeout(() => firstFocusable.focus(), 0);
        };

        openButtons.forEach(button => {
            button.addEventListener('click', () => openModal(button));
        });

        document.addEventListener('click', event => {
            if (event.target.closest('[data-consultation-modal-close]')) {
                closeModal();
            }
        });

        document.addEventListener('keydown', event => {
            if (!activeModal) {
                return;
            }

            if (event.key === 'Escape') {
                closeModal();
                return;
            }

            if (event.key !== 'Tab') {
                return;
            }

            const focusable = getFocusable(activeModal);

            if (!focusable.length) {
                event.preventDefault();
                activeModal.focus();
                return;
            }

            const firstFocusable = focusable[0];
            const lastFocusable = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === firstFocusable) {
                event.preventDefault();
                lastFocusable.focus();
            } else if (!event.shiftKey && document.activeElement === lastFocusable) {
                event.preventDefault();
                firstFocusable.focus();
            }
        });
    }

    function initScrollReveal() {
        const nodes = Array.from(document.querySelectorAll('.scroll-reveal'));

        if (!nodes.length) {
            return;
        }

        const staggerGroups = [
            '.lighting-audio-services-block__grid',
            '.steps-grid__items',
            '.property-types-grid__items',
            '.why-choose-light-tn__grid',
            '.detail-card-grid__cards',
            '.spotlight-cards__grid',
            '.proof-points__cards',
            '.split-callout__cards',
            '.partners-affiliations-block__track',
            '.testimonials-block__track'
        ];

        staggerGroups.forEach(selector => {
            document.querySelectorAll(selector).forEach(group => {
                Array.from(group.querySelectorAll(':scope > .scroll-reveal')).forEach((item, index) => {
                    item.style.setProperty('--reveal-delay', String(70 + (index * 85)) + 'ms');
                });
            });
        });

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
            if (carousel.dataset.partnersCarouselReady === 'true') {
                return;
            }

            const viewport = carousel.querySelector('.partners-affiliations-block__viewport');
            const track = carousel.querySelector('.partners-affiliations-block__track');
            const dots = carousel.querySelector('.partners-affiliations-block__dots');
            const cards = track ? Array.from(track.querySelectorAll('.partners-affiliations-block__card')) : [];

            if (!viewport || !track || !dots || cards.length <= 3) {
                return;
            }

            let currentPage = 0;
            let dotButtons = [];
            let resizeTimer = null;
            let isDragging = false;
            let hasDragged = false;
            let suppressClick = false;
            let pointerStartX = 0;
            let pointerStartY = 0;
            let dragStartTranslate = 0;

            const getPerPage = () => window.matchMedia('(max-width: 760px)').matches ? 1 : 3;
            const getTrackGap = () => parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap || 0) || 0;
            const getTotalSlides = () => Math.max(cards.length - getPerPage() + 1, 1);
            const getSlideWidth = () => cards[0].getBoundingClientRect().width + getTrackGap();
            const getTranslateForPage = page => -(page * getSlideWidth());

            const setActiveDot = () => {
                dotButtons.forEach((button, index) => {
                    const isActive = index === currentPage;
                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-current', isActive ? 'true' : 'false');
                });
            };

            const moveToPage = page => {
                const totalSlides = getTotalSlides();

                currentPage = ((page % totalSlides) + totalSlides) % totalSlides;
                track.style.transform = 'translateX(' + getTranslateForPage(currentPage) + 'px)';
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
                    button.addEventListener('click', () => {
                        moveToPage(index);
                    });
                    dots.appendChild(button);
                    dotButtons.push(button);
                }

                moveToPage(currentPage);
            };

            const handlePointerDown = event => {
                if (event.button !== undefined && event.button !== 0) {
                    return;
                }

                isDragging = true;
                hasDragged = false;
                pointerStartX = event.clientX;
                pointerStartY = event.clientY;
                dragStartTranslate = getTranslateForPage(currentPage);
                track.classList.add('is-dragging');
                viewport.setPointerCapture?.(event.pointerId);
            };

            const handlePointerMove = event => {
                if (!isDragging) {
                    return;
                }

                const deltaX = event.clientX - pointerStartX;
                const deltaY = event.clientY - pointerStartY;

                if (Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaY) > 10) {
                    return;
                }

                if (Math.abs(deltaX) > 4) {
                    hasDragged = true;
                    event.preventDefault();
                }

                track.style.transform = 'translateX(' + (dragStartTranslate + deltaX) + 'px)';
            };

            const handlePointerUp = event => {
                if (!isDragging) {
                    return;
                }

                const deltaX = event.clientX - pointerStartX;
                const threshold = Math.min(80, Math.max(36, getSlideWidth() * 0.16));

                isDragging = false;
                track.classList.remove('is-dragging');
                viewport.releasePointerCapture?.(event.pointerId);

                if (hasDragged) {
                    suppressClick = true;
                    window.setTimeout(() => {
                        suppressClick = false;
                    }, 0);
                }

                if (Math.abs(deltaX) >= threshold) {
                    moveToPage(currentPage + (deltaX < 0 ? 1 : -1));
                } else {
                    moveToPage(currentPage);
                }
            };

            carousel.dataset.partnersCarouselReady = 'true';
            rebuildDots();

            viewport.addEventListener('pointerdown', handlePointerDown);
            viewport.addEventListener('pointermove', handlePointerMove);
            viewport.addEventListener('pointerup', handlePointerUp);
            viewport.addEventListener('pointercancel', handlePointerUp);
            carousel.addEventListener('click', event => {
                if (!suppressClick) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();
            }, true);

            window.addEventListener('resize', () => {
                window.clearTimeout(resizeTimer);
                resizeTimer = window.setTimeout(rebuildDots, 120);
            });
        });
    }

    function initDesignProcessSections() {
        const sections = document.querySelectorAll('[data-design-process]');
        const desktopMedia = window.matchMedia('(min-width: 1025px)');

        sections.forEach(section => {
            if (section.dataset.designProcessReady === 'true') {
                return;
            }

            const buttons = Array.from(section.querySelectorAll('[data-step-button]'));
            const summaries = Array.from(section.querySelectorAll('[data-step-summary]'));
            const visuals = Array.from(section.querySelectorAll('[data-step-visual]'));
            const visualStack = section.querySelector('.design-process__visual-stack');

            if (!buttons.length || !summaries.length || !visuals.length) {
                return;
            }

            if (visualStack) {
                visualStack.style.setProperty('--design-process-count', String(visuals.length));
            }

            const activateStep = index => {
                buttons.forEach((button, buttonIndex) => {
                    const isActive = buttonIndex === index;
                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                summaries.forEach((summary, summaryIndex) => {
                    const isActive = summaryIndex === index;
                    summary.classList.toggle('is-active', isActive);
                    summary.hidden = !isActive;
                });

                visuals.forEach((visual, visualIndex) => {
                    const isActive = visualIndex === index;
                    visual.classList.toggle('is-active', isActive);
                    if (!desktopMedia.matches) {
                        visual.hidden = !isActive;
                    } else {
                        visual.hidden = false;
                    }
                });
            };

            buttons.forEach((button, index) => {
                button.addEventListener('click', () => activateStep(index));
                button.addEventListener('focus', () => activateStep(index));
                button.addEventListener('mouseenter', () => {
                    if (desktopMedia.matches) {
                        activateStep(index);
                    }
                });
            });

            visuals.forEach((visual, index) => {
                visual.addEventListener('mouseenter', () => {
                    if (desktopMedia.matches) {
                        activateStep(index);
                    }
                });
            });

            section.dataset.designProcessReady = 'true';
            activateStep(0);
        });
    }

    window.addEventListener('load', initPartnersAffiliationsCarousels);
    initPartnersAffiliationsCarousels();
    initDesignProcessSections();
    initConsultationModals();
    initScrollReveal();

})();
