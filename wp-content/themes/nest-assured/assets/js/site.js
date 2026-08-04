(function () {
  'use strict';

  const navigationMenus = Array.from(document.querySelectorAll('.na-nav-menu, .na-mobile-nav, .na-v2-menu, .na-v2-mobile'));

  const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
  document.querySelectorAll('.site-nav a, .na-nav-menu__panel a, .na-mobile-nav__panel a, .site-footer__links a, .na-v2-nav a, .na-v2-menu__panel a, .na-v2-mobile__panel a, .na-v2-footer__links a').forEach((link) => {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#')) {
      return;
    }

    const linkUrl = new URL(link.href, window.location.origin);
    if (linkUrl.origin !== window.location.origin || linkUrl.hash) {
      return;
    }

    const linkPath = linkUrl.pathname.replace(/\/+$/, '') || '/';
    if (linkPath === currentPath) {
      link.setAttribute('aria-current', 'page');
    }
  });

  window.NestAssuredTrack = function (eventName, details) {
    const payload = Object.assign({
      event: `na_${eventName}`,
      page_path: window.location.pathname
    }, details || {});

    document.dispatchEvent(new CustomEvent('na:analytics', { detail: payload }));
    if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push(payload);
    }
  };

  document.addEventListener('click', (event) => {
    const target = event.target instanceof Element ? event.target : null;
    const link = target?.closest('a[href*="/enquire/"]');
    if (!link) {
      return;
    }

    const targetUrl = new URL(link.href, window.location.origin);
    window.NestAssuredTrack('product_to_enquiry', {
      topic: targetUrl.searchParams.get('topic') || 'general',
      link_text: link.textContent.trim()
    });
  });

  document.addEventListener('click', (event) => {
    navigationMenus.forEach((menu) => {
      if (menu.open && !menu.contains(event.target)) {
        menu.open = false;
      }
    });
  });

  const mobileNav = document.querySelector('.na-v2-mobile') || document.querySelector('.na-mobile-nav');
  mobileNav?.addEventListener('toggle', () => {
    document.documentElement.style.overflow = mobileNav.open ? 'hidden' : '';
  });

  // Reading-progress bar on guide articles. Progressive enhancement: if this does
  // not run, the page simply renders without the bar.
  if (document.body.classList.contains('na-editorial-guide')) {
    const bar = document.createElement('div');
    bar.className = 'na-reading-progress';
    bar.setAttribute('aria-hidden', 'true');
    document.body.prepend(bar);

    let ticking = false;
    const update = () => {
      const doc = document.documentElement;
      const max = doc.scrollHeight - window.innerHeight;
      const pct = max > 0 ? Math.min(100, Math.max(0, (window.scrollY / max) * 100)) : 0;
      bar.style.width = pct.toFixed(1) + '%';
      ticking = false;
    };

    window.addEventListener('scroll', () => {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(update);
      }
    }, { passive: true });
    update();
  }

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
      return;
    }

    navigationMenus.forEach((menu) => {
      if (menu.open) {
        menu.open = false;
        menu.querySelector('summary')?.focus();
      }
    });
  });

})();
