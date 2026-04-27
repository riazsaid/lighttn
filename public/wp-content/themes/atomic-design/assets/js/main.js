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

    function getWordTokens(text) {
        const normalized = text.replace(/\s+/g, ' ').trim();
        return normalized ? normalized.match(/\S+\s*/g) || [] : [];
    }

    function splitNodeByWords(node, wordsForLeft) {
        if (node.nodeType === Node.TEXT_NODE) {
            const tokens = getWordTokens(node.textContent || '');
            const take = Math.min(wordsForLeft, tokens.length);
            const leftText = tokens.slice(0, take).join('').trim();
            const rightText = tokens.slice(take).join('').trim();

            return {
                leftNode: leftText ? document.createTextNode(leftText + (take < tokens.length ? ' ' : '')) : null,
                rightNode: rightText ? document.createTextNode(rightText) : null,
                wordsUsed: take,
                totalWords: tokens.length,
            };
        }

        if (node.nodeType !== Node.ELEMENT_NODE) {
            return {
                leftNode: null,
                rightNode: null,
                wordsUsed: 0,
                totalWords: 0,
            };
        }

        const leftClone = node.cloneNode(false);
        const rightClone = node.cloneNode(false);
        let wordsUsed = 0;
        let totalWords = 0;

        Array.from(node.childNodes).forEach(child => {
            const result = splitNodeByWords(child, Math.max(wordsForLeft - wordsUsed, 0));
            totalWords += result.totalWords;
            wordsUsed += result.wordsUsed;

            if (result.leftNode) {
                leftClone.appendChild(result.leftNode);
            }

            if (result.rightNode) {
                rightClone.appendChild(result.rightNode);
            }
        });

        return {
            leftNode: leftClone.childNodes.length ? leftClone : null,
            rightNode: rightClone.childNodes.length ? rightClone : null,
            wordsUsed,
            totalWords,
        };
    }

    function buildSplitContent(source, wordsForLeft) {
        const leftWrapper = document.createElement('div');
        const rightWrapper = document.createElement('div');

        Array.from(source.childNodes).forEach(child => {
            const result = splitNodeByWords(child, wordsForLeft);
            wordsForLeft -= result.wordsUsed;

            if (result.leftNode) {
                leftWrapper.appendChild(result.leftNode);
            }

            if (result.rightNode) {
                rightWrapper.appendChild(result.rightNode);
            }
        });

        return {
            leftHTML: leftWrapper.innerHTML,
            rightHTML: rightWrapper.innerHTML,
        };
    }

    function measureSplitHeight(source, leftHTML, width) {
        const measurer = document.createElement('div');
        const sourceStyles = window.getComputedStyle(source);

        measurer.style.position = 'absolute';
        measurer.style.visibility = 'hidden';
        measurer.style.pointerEvents = 'none';
        measurer.style.left = '-9999px';
        measurer.style.top = '0';
        measurer.style.width = width + 'px';
        measurer.style.font = sourceStyles.font;
        measurer.style.lineHeight = sourceStyles.lineHeight;
        measurer.style.letterSpacing = sourceStyles.letterSpacing;
        measurer.style.wordSpacing = sourceStyles.wordSpacing;
        measurer.style.whiteSpace = 'normal';
        measurer.innerHTML = leftHTML;

        document.body.appendChild(measurer);
        const height = measurer.getBoundingClientRect().height;
        document.body.removeChild(measurer);

        return height;
    }

    function balanceTitleDescriptionColumns() {
        const sections = document.querySelectorAll('[data-title-description-columns]');

        sections.forEach(section => {
            const source = section.querySelector('[data-title-description-source]');
            const split = section.querySelector('[data-title-description-split]');
            const leftColumn = section.querySelector('[data-title-description-left]');
            const rightColumn = section.querySelector('[data-title-description-right]');

            if (!source || !split || !leftColumn || !rightColumn) {
                return;
            }

            if (window.innerWidth <= 768) {
                section.classList.remove('is-split');
                split.hidden = true;
                leftColumn.innerHTML = '';
                rightColumn.innerHTML = '';
                return;
            }

            const contentWidth = section.getBoundingClientRect().width;
            const gap = parseFloat(window.getComputedStyle(split).columnGap || window.getComputedStyle(split).gap || 0);
            const columnWidth = (contentWidth - gap) / 2;
            const totalWords = getWordTokens(source.textContent || '').length;

            if (totalWords < 8 || columnWidth <= 0) {
                section.classList.remove('is-split');
                split.hidden = true;
                return;
            }

            const fullHeight = measureSplitHeight(source, source.innerHTML, columnWidth);
            const targetHeight = fullHeight / 2;

            let low = 1;
            let high = totalWords - 1;
            let bestLeftHTML = source.innerHTML;
            let bestRightHTML = '';
            let bestDelta = Number.POSITIVE_INFINITY;

            while (low <= high) {
                const mid = Math.floor((low + high) / 2);
                const candidate = buildSplitContent(source, mid);

                if (!candidate.leftHTML || !candidate.rightHTML) {
                    break;
                }

                const leftHeight = measureSplitHeight(source, candidate.leftHTML, columnWidth);
                const delta = Math.abs(leftHeight - targetHeight);

                if (delta < bestDelta) {
                    bestDelta = delta;
                    bestLeftHTML = candidate.leftHTML;
                    bestRightHTML = candidate.rightHTML;
                }

                if (leftHeight < targetHeight) {
                    low = mid + 1;
                } else {
                    high = mid - 1;
                }
            }

            if (!bestRightHTML) {
                section.classList.remove('is-split');
                split.hidden = true;
                return;
            }

            leftColumn.innerHTML = bestLeftHTML;
            rightColumn.innerHTML = bestRightHTML;
            split.hidden = false;
            section.classList.add('is-split');
        });
    }

    let titleDescriptionResizeTimer = null;
    const onTitleDescriptionResize = () => {
        window.clearTimeout(titleDescriptionResizeTimer);
        titleDescriptionResizeTimer = window.setTimeout(balanceTitleDescriptionColumns, 120);
    };

    window.addEventListener('load', balanceTitleDescriptionColumns);
    window.addEventListener('resize', onTitleDescriptionResize);
    balanceTitleDescriptionColumns();

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

})();
