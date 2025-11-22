// /scripts/sidebar.js
window.RunazSidebar = (function () {
    async function loadHTML(url) {
      const res = await fetch(url, { cache: 'no-store' });
      if (!res.ok) throw new Error('Failed to load ' + url);
      return res.text();
    }
  
    function wireDrawer() {
      const drawer = document.getElementById('drawer');
      const openBtn = document.getElementById('openDrawer');
      const closeBtn = document.getElementById('closeDrawer');
      const backdrop = document.getElementById('drawerBack');
  
      const open = () => {
        if (!drawer) return;
        drawer.classList.remove('hidden');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      };
      const close = () => {
        if (!drawer) return;
        drawer.classList.add('hidden');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      };
  
      openBtn?.addEventListener('click', open);
      closeBtn?.addEventListener('click', close);
      backdrop?.addEventListener('click', close);
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && drawer && !drawer.classList.contains('hidden')) close(); });
  
      // Close when clicking any link in the drawer (good mobile UX)
      drawer?.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
    }
  
    function highlightActive() {
      const page = location.pathname.split('/').pop() || 'index.html';
      document.querySelectorAll('.dash-aside a, #drawer a').forEach(a => {
        a.classList.toggle('active', a.getAttribute('href') === page);
      });
    }
  
    async function init({ role = 'runner', mountSelector = 'body' } = {}) {
      const base = './partials/';
      const file = role === 'requester' ? '_sidebar-requester.html' : '_sidebar-runner.html';
  
      const html = await loadHTML(base + file);
  
      // Inject right before closing </body> so it’s globally available
      const mount = document.querySelector(mountSelector) || document.body;
      // We insert desktop aside at the start of .min-h-screen flex container if present; else prepend to body
      const shell = document.querySelector('.min-h-screen.flex') || mount;
      shell.insertAdjacentHTML('afterbegin', html);
  
      // Icons
      if (window.feather) feather.replace();
  
      wireDrawer();
      highlightActive();
    }
  
    return { init };
  })();