(function () {
    'use strict';

    // Visible card count by inner width.
    // This slider lives inside a 2-column layout (Google badge + slider),
    // and the design wants 3 cards visible on non-mobile.
    var BREAKPOINTS = [640, 99999];

    function getVisibleCount(innerWidth) {
        if (innerWidth < 640) return 1;
        return 3;
    }

    function initSlider(container) {
        var inner = container.querySelector('.testimonials-slider__inner');
        var track = container.querySelector('.testimonials-slider__track');
        var cards = container.querySelectorAll('.testimonial-card');
        var prevBtn = container.querySelector('.testimonials-slider__btn--prev');
        var nextBtn = container.querySelector('.testimonials-slider__btn--next');
        var dotsEl = container.querySelector('.testimonials-slider__dots');

        if (!inner || !track || !cards.length) return;

        var totalCards = cards.length;
        var visibleCount = getVisibleCount(inner.offsetWidth);
        var totalPages = Math.max(1, Math.ceil(totalCards / visibleCount));
        var currentPage = 0;
        var cardWidth = 0;
        var trackWidth = 0;
        var gapPx = 0;

        function readGapPx() {
            // Use the CSS variable defined in testimonials.css for consistency.
            var raw = window.getComputedStyle(container).getPropertyValue('--slider-gap');
            gapPx = parseFloat(raw);
            if (isNaN(gapPx)) gapPx = 16;
        }

        function buildDots() {
            dotsEl.innerHTML = '';
            for (var i = 0; i < totalPages; i++) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'testimonials-slider__dot' + (i === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Go to testimonial page ' + (i + 1));
                dot.setAttribute('aria-current', i === 0 ? 'true' : 'false');
                dot.dataset.page = i;
                dotsEl.appendChild(dot);
            }
        }

        function updateLayout() {
            var w = inner.offsetWidth;
            readGapPx();
            visibleCount = getVisibleCount(w);
            totalPages = Math.max(1, Math.ceil(totalCards / visibleCount));
            currentPage = Math.min(currentPage, totalPages - 1);

            cardWidth = (w - (visibleCount - 1) * gapPx) / visibleCount;
            trackWidth = totalCards * cardWidth + (totalCards - 1) * gapPx;

            track.style.width = trackWidth + 'px';
            track.style.transform = 'translateX(0)';

            for (var i = 0; i < cards.length; i++) {
                cards[i].style.width = cardWidth + 'px';
            }

            buildDots();
            goToPage(currentPage);
            setButtons();
        }

        function goToPage(page) {
            currentPage = page;
            var offset = (cardWidth + gapPx) * visibleCount * currentPage;
            track.style.transform = 'translateX(-' + offset + 'px)';

            var dots = dotsEl.querySelectorAll('.testimonials-slider__dot');
            dots.forEach(function (d, i) {
                d.classList.toggle('is-active', i === currentPage);
                d.setAttribute('aria-current', i === currentPage ? 'true' : 'false');
            });

            setButtons();
        }

        function setButtons() {
            if (prevBtn) prevBtn.disabled = currentPage === 0;
            if (nextBtn) nextBtn.disabled = currentPage >= totalPages - 1;
        }

        function next() {
            if (currentPage < totalPages - 1) goToPage(currentPage + 1);
        }

        function prev() {
            if (currentPage > 0) goToPage(currentPage - 1);
        }

        prevBtn && prevBtn.addEventListener('click', prev);
        nextBtn && nextBtn.addEventListener('click', next);

        dotsEl.addEventListener('click', function (e) {
            var dot = e.target.closest('.testimonials-slider__dot');
            if (dot && dot.dataset.page !== undefined) goToPage(parseInt(dot.dataset.page, 10));
        });

        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(updateLayout, 150);
        });

        if (typeof ResizeObserver !== 'undefined') {
            var ro = new ResizeObserver(function () {
                updateLayout();
            });
            ro.observe(inner);
        }

        updateLayout();
    }

    function init() {
        var sliders = document.querySelectorAll('[data-testimonials-slider]');
        sliders.forEach(function (el) {
            if (el.dataset.sliderInit === 'true') return;
            el.dataset.sliderInit = 'true';
            initSlider(el);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    document.addEventListener('content-loaded', init);
})();
