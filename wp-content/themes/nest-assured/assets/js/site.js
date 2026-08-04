(function () {
  'use strict';

  const navigationMenus = Array.from(document.querySelectorAll('.na-v2-menu, .na-v2-mobile'));

  const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
  document.querySelectorAll('.na-v2-nav a, .na-v2-menu__panel a, .na-v2-mobile__panel a, .na-v2-footer__links a').forEach((link) => {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#')) {
      return;
    }

    // link.href is an SVGAnimatedString on an SVG anchor, which new URL() cannot
    // parse, so read the attribute and resolve it explicitly.
    let linkUrl;
    try {
      linkUrl = new URL(href, window.location.origin);
    } catch (error) {
      return;
    }

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

    let targetUrl;
    try {
      targetUrl = new URL(link.getAttribute('href'), window.location.origin);
    } catch (error) {
      return;
    }

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

  const mobileNav = document.querySelector('.na-v2-mobile');
  mobileNav?.addEventListener('toggle', () => {
    // Lock the body rather than the root element: iOS Safari ignores overflow
    // hidden on <html> and scrolls the page behind the open panel anyway.
    const locked = mobileNav.open;
    document.body.style.overflow = locked ? 'hidden' : '';
    document.body.style.touchAction = locked ? 'none' : '';
  });

  // The adviser dock follows the reader on every page, so it needs a way to go
  // away. The choice is remembered for the session only.
  const dock = document.querySelector('[data-na-dock]');
  if (dock) {
    let dismissed = false;
    try {
      dismissed = window.sessionStorage.getItem('naDockDismissed') === '1';
    } catch (error) {
      dismissed = false;
    }

    if (dismissed) {
      dock.hidden = true;
    }

    dock.querySelector('[data-na-dock-dismiss]')?.addEventListener('click', () => {
      dock.hidden = true;
      try {
        window.sessionStorage.setItem('naDockDismissed', '1');
      } catch (error) {
        // Storage unavailable (private mode); dismissing for this page is enough.
      }
    });
  }

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

  // Section reveal. Deliberately restrained: major blocks only, once each, and
  // nothing at all if the visitor has asked for reduced motion. Animating every
  // element on scroll is the tell of a template, and it slows reading down.
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  if (!reduceMotion.matches && 'IntersectionObserver' in window) {
    const revealTargets = document.querySelectorAll(
      '.na-v2-section > .na-v2-shell, .na-v2-masthead__grid, .na-calc, .na-v2-close__grid'
    );

    if (revealTargets.length) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            return;
          }
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        });
      }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

      revealTargets.forEach((target, index) => {
        // Anything already in view on load is shown immediately, so the first
        // screen never animates in underneath the reader.
        const box = target.getBoundingClientRect();
        if (box.top < window.innerHeight * 0.9) {
          target.setAttribute('data-na-reveal', '');
          target.classList.add('is-revealed');
          return;
        }

        target.setAttribute('data-na-reveal', '');
        target.style.setProperty('--na-reveal-index', String(index % 3));
        observer.observe(target);
      });
    }
  }

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
      return;
    }

    // Close every open menu, but return focus only to the one that is actually
    // visible at this breakpoint. Focusing each in turn meant focus landed on
    // whichever call happened to run last, including a menu display:none hides.
    navigationMenus.forEach((menu) => {
      if (!menu.open) {
        return;
      }

      menu.open = false;

      const summary = menu.querySelector('summary');
      if (summary && summary.getClientRects().length > 0) {
        summary.focus();
      }
    });
  });

})();
