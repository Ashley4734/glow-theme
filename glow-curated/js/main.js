/**
 * Glow Curated - Global Scripts
 * NOTE: Bundle/minify for production.
 */

document.addEventListener('DOMContentLoaded', () => {
  const backToTop = document.querySelector('.back-to-top');

  const handleScroll = () => {
    if (!backToTop) return;
    if (window.scrollY > 500) {
      backToTop.classList.add('visible');
    } else {
      backToTop.classList.remove('visible');
    }
  };

  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();

  backToTop?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  const anchorLinks = document.querySelectorAll('a[href^="#"]');
  anchorLinks.forEach((link) => {
    link.addEventListener('click', (event) => {
      const targetId = link.getAttribute('href')?.substring(1);
      const targetElement = targetId ? document.getElementById(targetId) : null;
      if (targetElement) {
        event.preventDefault();
        targetElement.scrollIntoView({ behavior: 'smooth' });
        targetElement.setAttribute('tabindex', '-1');
        targetElement.focus({ preventScroll: true });
        setTimeout(() => targetElement.removeAttribute('tabindex'), 500);
      }
    });
  });

  const lazyImages = document.querySelectorAll('img[data-src]');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          const dataSrc = img.getAttribute('data-src');
          if (dataSrc) {
            img.setAttribute('src', dataSrc);
            img.removeAttribute('data-src');
          }
          obs.unobserve(img);
        }
      });
    }, { rootMargin: '200px 0px' });

    lazyImages.forEach((img) => observer.observe(img));
  } else {
    lazyImages.forEach((img) => {
      const dataSrc = img.getAttribute('data-src');
      if (dataSrc) {
        img.setAttribute('src', dataSrc);
        img.removeAttribute('data-src');
      }
    });
  }

  const animatedSections = document.querySelectorAll('[data-animate]');
  if ('IntersectionObserver' in window) {
    const animationObserver = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });

    animatedSections.forEach((section) => animationObserver.observe(section));
  } else {
    animatedSections.forEach((section) => section.classList.add('animated'));
  }
});
