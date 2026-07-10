document.addEventListener('DOMContentLoaded', function(){
  const toggle = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  const closeSidebar = () => {
    sidebar?.classList.remove('is-open');
    document.body.classList.remove('sidebar-open');
  };

  if(toggle && sidebar){
    toggle.addEventListener('click', ()=>{
      sidebar.classList.toggle('is-open');
      document.body.classList.toggle('sidebar-open', sidebar.classList.contains('is-open'));
    })
  }
  backdrop?.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', (event) => {
    if(event.key === 'Escape') closeSidebar();
  });

  document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if(!window.confirm(form.dataset.confirm || 'Lanjutkan aksi ini?')) {
        event.preventDefault();
      }
    });
  });

  // small helper for ajax fetch (used by future components)
  window.apiFetch = async function(url, opts={}){
    const res = await fetch(url, Object.assign({headers:{'X-Requested-With':'XMLHttpRequest'}}, opts));
    if(!res.ok) throw new Error('Network error');
    return res.json();
  }
});
