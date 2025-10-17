<script>
  const btn=document.getElementById('themeToggle');
  btn?.addEventListener('click',()=>{const d=document.documentElement.classList.toggle('dark');localStorage.setItem('runaz-theme', d?'dark':'light');});
</script>