(function () {
  'use strict';

  const navigationMenus = Array.from(document.querySelectorAll('.na-nav-menu, .na-mobile-nav'));

  const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
  document.querySelectorAll('.site-nav a, .na-nav-menu__panel a, .na-mobile-nav__panel a, .site-footer__links a').forEach((link) => {
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

  const mobileNav = document.querySelector('.na-mobile-nav');
  mobileNav?.addEventListener('toggle', () => {
    document.documentElement.style.overflow = mobileNav.open ? 'hidden' : '';
  });

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
