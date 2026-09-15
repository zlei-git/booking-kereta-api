import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Animated number counter: rolls from 0 to main number smoothly on scroll
function animateCounters() {
    const counterElements = document.querySelectorAll('[data-counter-target]');
    if (!counterElements.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-counter-target'), 10) || 0;
                const prefix = el.getAttribute('data-counter-prefix') || '';
                const suffix = el.getAttribute('data-counter-suffix') || '';
                const duration = parseInt(el.getAttribute('data-counter-duration'), 10) || 1600;
                const startTime = performance.now();

                function updateCounter(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                    const currentVal = Math.floor(easeProgress * target);
                    
                    el.textContent = `${prefix}${currentVal}${suffix}`;

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        el.textContent = `${prefix}${target}${suffix}`;
                    }
                }

                requestAnimationFrame(updateCounter);
                obs.unobserve(el);
            }
        });
    }, { threshold: 0.2 });

    counterElements.forEach(el => observer.observe(el));
}

// Scroll Reveal observer (Hardware Accelerated 60 FPS)
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.reveal-init, .reveal-left-init, .reveal-right-init, .reveal-scale-init');
    if (!revealElements.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealElements.forEach(el => observer.observe(el));
}

// Active Section Tracker (Section 01, 02, 03, 04)
function initSectionTracker() {
    const sections = document.querySelectorAll('section[id]');
    const sectionIndexDisplay = document.getElementById('active-section-number');
    const sectionNameDisplay = document.getElementById('active-section-name');
    const sectionPills = document.querySelectorAll('[data-section-target]');

    if (!sections.length || !sectionIndexDisplay) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                const num = entry.target.getAttribute('data-section-index') || '01';
                const name = entry.target.getAttribute('data-section-title') || 'Reservasi';

                if (sectionIndexDisplay) {
                    sectionIndexDisplay.textContent = num;
                }
                if (sectionNameDisplay) {
                    sectionNameDisplay.textContent = name;
                }

                sectionPills.forEach(pill => {
                    const target = pill.getAttribute('data-section-target');
                    if (target === id) {
                        pill.classList.add('bg-[#121212]', 'text-white', 'border-[#121212]', 'scale-110');
                        pill.classList.remove('bg-white', 'text-[#5E5C56]', 'border-[#DAD6CD]');
                    } else {
                        pill.classList.remove('bg-[#121212]', 'text-white', 'border-[#121212]', 'scale-110');
                        pill.classList.add('bg-white', 'text-[#5E5C56]', 'border-[#DAD6CD]');
                    }
                });
            }
        });
    }, { threshold: 0.35 });

    sections.forEach(s => observer.observe(s));
}

document.addEventListener('DOMContentLoaded', () => {
    animateCounters();
    initScrollReveal();
    initSectionTracker();
});

Alpine.start();
