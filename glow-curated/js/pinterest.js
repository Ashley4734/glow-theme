/**
 * Pinterest page enhancements
 * Fetch actual board cover images via Pinterest oEmbed (JSONP) when available.
 */

document.addEventListener('DOMContentLoaded', () => {
  const boardCards = document.querySelectorAll('.board-card[data-board-url]');
  if (!boardCards.length) return;

  const hydrationMode = document.body?.dataset?.pinterestHydration;
  if (hydrationMode === 'manual' || hydrationMode === 'disabled') {
    // Manual mode leaves the placeholder images in place so editors can manage covers directly.
    return;
  }

  let callbackIndex = 0;
  const MAX_QUALITY = 3;

  const isLikelyCollage = (url) => {
    if (typeof url !== 'string') return false;
    try {
      const parsedUrl = new URL(url, window.location.href);
      if (!/\.pinimg\.com$/i.test(parsedUrl.hostname)) {
        return false;
      }

      return /(236x|280x|474x)(?:_rs)?\//i.test(parsedUrl.pathname);
    } catch (error) {
      return false;
    }
  };

  const scoreImageQuality = (source, url) => {
    if (!url) return 0;

    const baseQualityMap = {
      pidgets: MAX_QUALITY,
      pin: MAX_QUALITY - 0.25,
    };

    const baseQuality = baseQualityMap[source] ?? 2;
    const penalty = isLikelyCollage(url) ? 1 : 0;
    return Math.max(1, baseQuality - penalty);
  };

  const preferHighResImage = (url) => {
    if (typeof url !== 'string' || !url) {
      return url;
    }

    try {
      const parsedUrl = new URL(url, window.location.href);
      if (!/\.pinimg\.com$/i.test(parsedUrl.hostname)) {
        return url;
      }

      const pathSegments = parsedUrl.pathname.split('/').map((segment) => {
        if (/^\d+x(\d+)?(_rs)?$/i.test(segment)) {
          return '736x';
        }
        return segment;
      });

      parsedUrl.pathname = pathSegments.join('/');

      return parsedUrl.toString();
    } catch (error) {
      return url;
    }
  };

  const buildPinterestSrcset = (url) => {
    if (typeof url !== 'string' || !url) {
      return null;
    }

    try {
      const parsedUrl = new URL(url, window.location.href);
      if (!/\.pinimg\.com$/i.test(parsedUrl.hostname)) {
        return null;
      }

      const pathSegments = parsedUrl.pathname.split('/');
      const sizeIndex = pathSegments.findIndex((segment) => /^(?:\d+x(?:\d+)?)(?:_rs)?$/i.test(segment));
      if (sizeIndex === -1) {
        return null;
      }

      const desiredSizes = ['474x', '736x'];
      const seen = new Set();
      const candidates = [];

      desiredSizes.forEach((size, index) => {
        const candidateSegments = [...pathSegments];
        candidateSegments[sizeIndex] = size;

        const candidateUrl = new URL(parsedUrl.href);
        candidateUrl.pathname = candidateSegments.join('/');

        const normalized = candidateUrl.toString();
        if (!seen.has(normalized)) {
          seen.add(normalized);
          candidates.push(`${normalized} ${index === 0 ? '1x' : '2x'}`);
        }
      });

      return candidates.length ? candidates.join(', ') : null;
    } catch (error) {
      return null;
    }
  };

  const extractCoverFromHtml = (html) => {
    try {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const image = doc.querySelector('img');
      return image?.getAttribute('src') || null;
    } catch (error) {
      return null;
    }
  };

  const extractBoardSegments = (boardUrl) => {
    try {
      const url = new URL(boardUrl);
      const parts = url.pathname.split('/').filter(Boolean);
      if (parts.length >= 2) {
        return {
          username: parts[0],
          board: parts[1],
        };
      }
    } catch (error) {
      // Ignore parsing issues and fall back to defaults below.
    }
    return null;
  };

  const extractCoverFromPidgets = (data) => {
    const board = data?.data?.board;
    const firstPinImages = data?.data?.pins?.[0]?.images;

    const candidates = [
      { url: board?.image_cover_hd_url, baseQuality: MAX_QUALITY },
      { url: board?.image_cover_url, baseQuality: MAX_QUALITY - 0.25 },
      { url: board?.image_thumbnail_url, baseQuality: MAX_QUALITY - 0.5 },
      { url: firstPinImages?.['736x']?.url, baseQuality: MAX_QUALITY - 0.25 },
      { url: firstPinImages?.['564x']?.url, baseQuality: MAX_QUALITY - 0.5 },
      { url: firstPinImages?.['474x']?.url, baseQuality: MAX_QUALITY - 0.75 },
      { url: firstPinImages?.['237x']?.url, baseQuality: MAX_QUALITY - 1 },
      { url: firstPinImages?.['170x']?.url, baseQuality: MAX_QUALITY - 1.25 },
    ];

    let bestCandidate = null;
    let bestQuality = 0;

    candidates.forEach((candidate) => {
      if (!candidate.url) return;

      let quality = candidate.baseQuality;
      if (isLikelyCollage(candidate.url)) {
        quality -= 1;
      }

      if (!bestCandidate || quality > bestQuality) {
        bestCandidate = candidate.url;
        bestQuality = quality;
      }
    });

    if (!bestCandidate) {
      return null;
    }

    return {
      url: bestCandidate,
      quality: Math.max(1, Math.min(bestQuality, MAX_QUALITY)),
    };
  };

  boardCards.forEach((card) => {
    const boardUrl = card.dataset.boardUrl;
    const pinUrl = card.dataset.pinUrl;
    const img = card.querySelector('img');
    if (!img) return;
    if (!boardUrl && !pinUrl) return;

    const placeholder = img.getAttribute('data-placeholder');
    const attemptEndpoints = [];

    if (pinUrl) {
      attemptEndpoints.push({
        source: 'pin',
        buildUrl: (callbackName, cacheBust) =>
          `https://www.pinterest.com/oembed.json/?url=${encodeURIComponent(pinUrl)}&callback=${callbackName}&_=${cacheBust}`,
      });
    }

    if (boardUrl) {
      attemptEndpoints.push({
        source: 'oembed',
        buildUrl: (callbackName, cacheBust) =>
          `https://www.pinterest.com/oembed.json/?url=${encodeURIComponent(boardUrl)}&callback=${callbackName}&_=${cacheBust}`,
      });

      const boardSegments = extractBoardSegments(boardUrl);
      if (boardSegments) {
        const { username, board } = boardSegments;
        attemptEndpoints.push({
          source: 'pidgets',
          buildUrl: (callbackName, cacheBust) =>
            `https://widgets.pinterest.com/v3/pidgets/boards/${encodeURIComponent(username)}/${encodeURIComponent(board)}/pins/?callback=${callbackName}&_=${cacheBust}`,
        });
      }
    }

    let bestQuality = 0;

    const setImageIfBetter = (url, quality) => {
      if (!url || quality <= bestQuality) {
        return;
      }

      const highResUrl = preferHighResImage(url);
      img.src = highResUrl;

      const pinterestSrcset =
        buildPinterestSrcset(highResUrl) || buildPinterestSrcset(url);

      if (pinterestSrcset) {
        img.srcset = pinterestSrcset;
      } else if (highResUrl !== url) {
        img.srcset = `${highResUrl} 2x, ${url} 1x`;
      } else {
        img.removeAttribute('srcset');
      }

      img.removeAttribute('data-src');

      if (placeholder) {
        img.removeAttribute('data-placeholder');
      }

      bestQuality = quality;
    };

    const tryNextEndpoint = (index) => {
      if (bestQuality >= MAX_QUALITY) {
        return;
      }

      const attempt = attemptEndpoints[index];
      if (!attempt) {
        return;
      }

      const callbackName = `pinterestBoardCallback${callbackIndex++}`;
      const cacheBust = Date.now() + callbackIndex;
      const script = document.createElement('script');
      let cleaned = false;

      const cleanup = () => {
        if (cleaned) return;
        cleaned = true;
        if (script.parentNode) {
          script.parentNode.removeChild(script);
        }
        if (window[callbackName]) {
          delete window[callbackName];
        }
      };

      window[callbackName] = (data) => {
        cleanup();

        let coverUrl = null;
        let quality = 0;

        const directImage =
          data?.thumbnail_url ||
          data?.thumbnailUrl ||
          data?.image_url ||
          data?.image;

        if (directImage) {
          const directQuality = scoreImageQuality(attempt.source, directImage);
          coverUrl = directImage;
          quality = directQuality;
        }

        if (typeof data?.html === 'string') {
          const htmlImage = extractCoverFromHtml(data.html);
          if (htmlImage) {
            const htmlQuality = scoreImageQuality(attempt.source, htmlImage);
            if (!coverUrl || htmlQuality > quality) {
              coverUrl = htmlImage;
              quality = htmlQuality;
            }
          }
        }

        const pidgetsCover = extractCoverFromPidgets(data);
        if (pidgetsCover?.url) {
          const pidgetsQuality =
            attempt.source === 'pidgets'
              ? pidgetsCover.quality
              : Math.min(pidgetsCover.quality, 2);
          if (!coverUrl || pidgetsQuality > quality) {
            coverUrl = pidgetsCover.url;
            quality = pidgetsQuality;
          }
        }

        if (coverUrl) {
          setImageIfBetter(coverUrl, quality);
          if (quality < MAX_QUALITY) {
            tryNextEndpoint(index + 1);
          }
        } else {
          tryNextEndpoint(index + 1);
        }
      };

      script.src = attempt.buildUrl(callbackName, cacheBust);
      script.async = true;
      script.onerror = () => {
        cleanup();
        tryNextEndpoint(index + 1);
      };

      document.body.appendChild(script);
    };

    tryNextEndpoint(0);
  });
});
