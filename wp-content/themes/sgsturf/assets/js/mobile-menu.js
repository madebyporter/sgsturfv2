document.addEventListener('DOMContentLoaded', function() {
  var header = document.querySelector('.header');
  var mobileMenu = document.querySelector('.mobile-menu');
  var menuTrigger = document.querySelector('.mobile-menu-trigger');
  var siteContent = document.querySelector('.site-content');
  var body = document.body;
  var triggerIcon = menuTrigger.querySelector('i');

  function openMenu() {
    body.classList.add('overflow-hidden');
    header.classList.add('menu-open');
    body.classList.remove('bg-green-pale');
    body.classList.add('bg-black');
    siteContent.classList.add('opacity-10');
    setTimeout(() => {   
      triggerIcon.classList.replace('fa-bars', 'fa-xmark');
      header.classList.add('menu-slide-open');
    }, 300); 
  }

  function closeMenu() {
    header.classList.remove('menu-slide-open');
    setTimeout(() => {  
      header.classList.remove('menu-open');
      body.classList.remove('overflow-hidden');
      triggerIcon.classList.replace('fa-xmark', 'fa-bars');
      siteContent.classList.remove('opacity-10');
      body.classList.add('bg-green-pale');
      body.classList.remove('bg-black');
    }, 600);
  }

  menuTrigger.addEventListener('click', function() {
    if (header.classList.contains('menu-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });
});
