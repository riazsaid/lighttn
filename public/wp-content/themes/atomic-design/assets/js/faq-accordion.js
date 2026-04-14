(function () {
    'use strict';

    function initFAQAccordions() {
        const faqBlocks = document.querySelectorAll('.faq-accordion-block');

        faqBlocks.forEach((block) => {
            if (block.dataset.faqInitialized === 'true') {
                return;
            }

            block.dataset.faqInitialized = 'true';

            const faqItems = block.querySelectorAll('[data-faq-item]');

            faqItems.forEach((item) => {
                const button = item.querySelector('.faq-question');
                const answer = item.querySelector('.faq-answer');

                if (!button || !answer) {
                    return;
                }

                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);

                newButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleFAQ(item, newButton, answer);
                });

                newButton.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleFAQ(item, newButton, answer);
                    }
                });
            });
        });
    }

    function toggleFAQ(item, button, answer) {
        const isActive = item.classList.contains('active');

        if (isActive) {
            item.classList.remove('active');
            button.setAttribute('aria-expanded', 'false');
            answer.setAttribute('hidden', '');
            answer.style.maxHeight = answer.scrollHeight + 'px';
            setTimeout(() => {
                answer.style.maxHeight = '0';
            }, 10);
            return;
        }

        item.classList.add('active');
        button.setAttribute('aria-expanded', 'true');
        answer.removeAttribute('hidden');
        answer.style.maxHeight = '0';

        setTimeout(() => {
            answer.style.maxHeight = answer.scrollHeight + 'px';
        }, 10);

        setTimeout(() => {
            answer.style.maxHeight = 'none';
        }, 300);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFAQAccordions);
    } else {
        initFAQAccordions();
    }

    document.addEventListener('content-loaded', initFAQAccordions);
})();
