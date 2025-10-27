/**
 * Glow Curated - Navigation Behaviors
 * NOTE: Consider bundling and minifying before production release.
 */

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.site-header');
  const nav = document.querySelector('.main-nav');
  const toggle = document.querySelector('.mobile-menu-toggle');
  const navLinks = document.querySelectorAll('.main-nav a');
  const dropdown = document.querySelector('.nav-dropdown');
  const dropdownToggle = document.querySelector('.dropdown-toggle');

  const normalizePath = (path) => {
    if (!path) return '/';
    const url = new URL(path, window.location.origin);
    let normalizedPath = url.pathname;
    if (!normalizedPath.endsWith('/')) {
      normalizedPath = `${normalizedPath}/`;
    }
    return normalizedPath === '//' ? '/' : normalizedPath;
  };

  const setActiveLink = () => {
    const currentPath = normalizePath(window.location.pathname);
    navLinks.forEach((link) => {
      const linkPath = link.getAttribute('href');
      const normalized = normalizePath(linkPath);
      if (normalized === currentPath || (currentPath === '/' && normalized === '/')) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });
  };

  const handleScroll = () => {
    if (window.scrollY > 100) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  };

  setActiveLink();
  handleScroll();

  window.addEventListener('scroll', handleScroll, { passive: true });

  if (toggle) {
    toggle.addEventListener('click', () => {
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      toggle.classList.toggle('active');
      nav.classList.toggle('open');
      if (!expanded) {
        nav.querySelector('a')?.focus({ preventScroll: true });
      }
    });
  }

  navLinks.forEach((link) => {
    link.addEventListener('click', () => {
      if (toggle && toggle.classList.contains('active')) {
        toggle.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
        nav.classList.remove('open');
      }
      if (dropdown) {
        dropdown.classList.remove('open');
      }
    });
  });

  if (dropdownToggle && dropdown) {
    dropdownToggle.addEventListener('click', (event) => {
      event.preventDefault();
      const expanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
      dropdownToggle.setAttribute('aria-expanded', String(!expanded));
      dropdown.classList.toggle('open');
    });

    document.addEventListener('click', (event) => {
      if (!dropdown.contains(event.target)) {
        dropdown.classList.remove('open');
        dropdownToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }
});
