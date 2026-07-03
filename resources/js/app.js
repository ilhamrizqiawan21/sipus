document.addEventListener('DOMContentLoaded', function(){
  const toggle = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  if(toggle && sidebar){
    toggle.addEventListener('click', ()=>{
      sidebar.classList.toggle('is-open');
    })
  }

  // small helper for ajax fetch (used by future components)
  window.apiFetch = async function(url, opts={}){
    const res = await fetch(url, Object.assign({headers:{'X-Requested-With':'XMLHttpRequest'}}, opts));
    if(!res.ok) throw new Error('Network error');
    return res.json();
  }
});
