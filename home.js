document.addEventListener('DOMContentLoaded', function () {
  const hamburger = document.getElementById('hamburger');
  const navMenu = document.getElementById('navMenu');
  const aboutNavLink = document.getElementById('aboutNavLink');

  if (!hamburger || !navMenu) {
    return;
  }

  const navLinks = navMenu.querySelectorAll('a');

  // Toggle menu on hamburger click.
  hamburger.addEventListener('click', function (e) {
    e.stopPropagation();
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
  });

  // Close menu when a nav link is clicked.
  navLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      navMenu.classList.remove('active');
      hamburger.classList.remove('active');
    });
  });

  // Close menu when clicking outside.
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.navbar')) {
      navMenu.classList.remove('active');
      hamburger.classList.remove('active');
    }
  });

  // Navigate to About page from the Home navbar.
  if (aboutNavLink) {
    aboutNavLink.addEventListener('click', function (e) {
      e.preventDefault();
      window.location.href = 'about.html';
    });
  }
});
