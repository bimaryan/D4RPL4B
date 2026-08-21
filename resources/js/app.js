import { animate, inView, stagger } from "motion";

// Respect reduced motion
const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function initMotion() {
  if (prefersReduced) {
    document.querySelectorAll('[data-motion]').forEach(el => {
      el.style.opacity = '1';
      el.style.transform = 'none';
    });
    return;
  }

  // HERO — staggered entrance (framer motion style)
  const heroBadge = document.querySelector('[data-motion="hero-badge"]');
  const heroTitle = document.querySelectorAll('[data-motion="hero-title"] span');
  const heroDesc = document.querySelector('[data-motion="hero-desc"]');
  const heroCta = document.querySelector('[data-motion="hero-cta"]');
  const heroVisual = document.querySelector('[data-motion="hero-visual"]');
  const heroStats = document.querySelectorAll('[data-motion="hero-stats"] > div');

  if (heroBadge) animate(heroBadge, { opacity: [0, 1], y: [12, 0] }, { duration: 0.6, easing: [0.16, 1, 0.3, 1] });
  if (heroTitle.length) animate(heroTitle, { opacity: [0, 1], y: [24, 0] }, { duration: 0.7, delay: stagger(0.08, { start: 0.15 }), easing: [0.16, 1, 0.3, 1] });
  if (heroDesc) animate(heroDesc, { opacity: [0, 1], y: [12, 0] }, { duration: 0.6, delay: 0.45, easing: [0.16, 1, 0.3, 1] });
  if (heroCta) animate(heroCta, { opacity: [0, 1], y: [12, 0] }, { duration: 0.6, delay: 0.55, easing: [0.16, 1, 0.3, 1] });
  if (heroVisual) animate(heroVisual, { opacity: [0, 1], y: [16, 0], scale: [0.98, 1] }, { duration: 0.8, delay: 0.3, easing: [0.16, 1, 0.3, 1] });
  if (heroStats.length) animate(heroStats, { opacity: [0, 1], y: [10, 0] }, { duration: 0.5, delay: stagger(0.08, { start: 0.65 }), easing: [0.16, 1, 0.3, 1] });

  // Generic inView helper
  const reveal = (selector, opts = {}) => {
    inView(selector, (target) => {
      animate(target, { opacity: [0, 1], y: [18, 0] }, { duration: 0.6, easing: [0.16, 1, 0.3, 1], ...opts });
    }, { margin: "0px 0px -10% 0px", amount: 0.15 });
  };

  const staggerReveal = (selector, opts = {}) => {
    inView(selector, (target) => {
      const items = target.querySelectorAll(opts.child || ':scope > *');
      if (!items.length) return;
      animate(items, { opacity: [0, 1], y: [18, 0] }, { duration: 0.5, delay: stagger(0.07, { start: opts.start || 0 }), easing: [0.16, 1, 0.3, 1] });
    }, { margin: "0px 0px -8% 0px", amount: 0.12 });
  };

  // Section headers — fade up
  reveal('[data-motion="section-header"]');
  
  // Roster cards — stagger
  staggerReveal('[data-motion="roster-grid"]', { child: '.motion-roster-card', start: 0.05 });
  reveal('[data-motion="roster-badge"]');

  // Projects cards — stagger with scale
  inView('[data-motion="projects-grid"]', (target) => {
    const cards = target.querySelectorAll('.motion-project-card');
    animate(cards, { opacity: [0, 1], y: [20, 0], scale: [0.97, 1] }, { duration: 0.55, delay: stagger(0.08), easing: [0.16, 1, 0.3, 1] });
  }, { margin: "0px 0px -10% 0px", amount: 0.15 });

  // Academic — two panels slide
  inView('[data-motion="academic"]', (target) => {
    const left = target.querySelector('[data-motion="academic-schedule"]');
    const right = target.querySelector('[data-motion="academic-announcements"]');
    if (left) animate(left, { opacity: [0, 1], y: [16, 0] }, { duration: 0.6, easing: [0.16, 1, 0.3, 1] });
    if (right) animate(right, { opacity: [0, 1], y: [16, 0] }, { duration: 0.6, delay: 0.12, easing: [0.16, 1, 0.3, 1] });
  }, { margin: "0px 0px -10% 0px", amount: 0.2 });

  // Announcements items stagger inside
  inView('[data-motion="announcements-list"]', (target) => {
    const items = target.querySelectorAll('.motion-announcement');
    if (items.length) animate(items, { opacity: [0, 1], x: [12, 0] }, { duration: 0.45, delay: stagger(0.06), easing: [0.16, 1, 0.3, 1] });
  }, { margin: "0px 0px -10% 0px" });

  // Gallery — masonry stagger with scale
  inView('[data-motion="gallery-grid"]', (target) => {
    const items = target.querySelectorAll('.motion-gallery-item');
    animate(items, { opacity: [0, 1], y: [18, 0], scale: [0.97, 1] }, { duration: 0.6, delay: stagger(0.09), easing: [0.16, 1, 0.3, 1] });
  }, { margin: "0px 0px -10% 0px", amount: 0.15 });

  // Navbar scroll — framer motion style spring (subtle)
  const nav = document.querySelector('[data-motion="navbar"]');
  if (nav) {
    let lastY = window.scrollY;
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      const diff = Math.abs(y - lastY);
      if (diff > 2) {
        nav.style.transform = `translateZ(0)`;
        lastY = y;
      }
    }, { passive: true });
  }

  // Parallax subtle for hero visual on scroll
  const heroImg = document.querySelector('[data-motion="hero-visual"] img');
  if (heroImg && !prefersReduced) {
    window.addEventListener('scroll', () => {
      const s = window.scrollY * 0.08;
      if (s < 120) heroImg.style.transform = `translateY(${s * 0.15}px) scale(1.02)`;
    }, { passive: true });
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initMotion);
} else {
  initMotion();
}
