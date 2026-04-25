document.addEventListener('DOMContentLoaded', function () {
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');

  window.openLightbox = function (element) {
    const card = element.closest('.gallery-card');
    const image = card ? card.querySelector('img') : null;

    if (!image) {
      return;
    }

    lightboxImg.src = image.src;
    lightboxImg.alt = image.alt;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.closeLightbox = function () {
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
  };

  lightbox.addEventListener('click', function (event) {
    if (event.target === lightbox) {
      window.closeLightbox();
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && lightbox.classList.contains('active')) {
      window.closeLightbox();
    }
  });
});