<script>

(function(){
  const $ = (s, root=document) => root.querySelector(s);
  const $$ = (s, root=document) => Array.from(root.querySelectorAll(s));

  // Dark mode toggle (header)
  const tbtn = $('#themeToggle');
  tbtn && tbtn.addEventListener('click', ()=>{
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('runaz-theme', isDark ? 'dark' : 'light');
  });

  const form = $('#registerForm');
  if(!form) return; // only on register.html

  let step = 1; const maxStep = 5;
  const backBtn = $('#backBtn');
  const nextBtn = $('#nextBtn');
  const submitBtn = $('#submitBtn');
  const stepHint = $('#stepHint');

  const showStep = (n)=>{
    step = Math.min(Math.max(1,n), maxStep);
    $$('#registerForm section[data-step]').forEach(sec=>{
      sec.classList.toggle('hidden', Number(sec.dataset.step)!==step);
    });
    // progress cards
    $$('#steps .step').forEach((li,i)=>{
      li.classList.toggle('opacity-100', i < step);
      li.classList.toggle('opacity-60', i >= step);
      li.classList.toggle('ring-2', i === step-1);
      li.classList.toggle('ring-runaz-blue/50', i === step-1);
    });

    backBtn.disabled = (step===1);
    nextBtn.classList.toggle('hidden', step===maxStep);
    submitBtn.classList.toggle('hidden', step!==maxStep);

    const hints = {
      1:'Choose your role and create login details',
      2:'Enter your personal/contact information',
      3:'Upload ID and selfie for KYC',
      4:'Complete your profile',
      5:'Review and submit'
    };
    stepHint.textContent = hints[step] || '';
  };

  // Basic per-step validation
  const validateStep = ()=>{
    if(step===1){
      const role = form.role.value;
      const pass = form.password.value; const conf = form.password_confirm.value;
      if(!role){ alert('Select a role'); return false; }
      if(pass!==conf){ alert('Passwords do not match'); return false; }
    }
    if(step===2){
      const required = ['first_name','last_name','dob','gender','address','city','state','lga'];
      for(const n of required){ if(!form[n].value){ alert('Please complete personal information'); return false; } }
    }
    if(step===3){
      const required = ['id_type','id_number','selfie','id_front','id_back'];
      for(const n of required){ if(!form[n].value){ alert('Please complete KYC and uploads'); return false; } }
    }
    if(step===4){
      if(form.role.value==='runner'){
        // No hard requirement, but at least one category helps
        const anyCat = $$('input[name="categories"]:checked', form).length>0;
        if(!anyCat){ if(!confirm('No category selected. Continue anyway?')) return false; }
      }
    }
    if(step===5){
      if(!$('#agree').checked){ alert('Please agree to Terms & Privacy'); return false; }
    }
    return true;
  };

  // Role-specific toggles
  const updateRoleUI = ()=>{
    const role = form.role.value;
    const isRunner = role==='runner';
    $('#profileTitle').textContent = isRunner? 'Runner profile' : 'Requester preferences';
    $('#runnerFields').classList.toggle('hidden', !isRunner);
    $('#requesterFields').classList.toggle('hidden', isRunner);
  };
  $$('input[name="role"]').forEach(r=> r.addEventListener('change', ()=>{ updateRoleUI(); }));

  // Simple previews for KYC images
  const preview = (input, imgId)=>{
    input?.addEventListener('change', e=>{
      const f=e.target.files?.[0]; if(!f) return; const url=URL.createObjectURL(f);
      $('#kycPreview').classList.remove('hidden');
      $(imgId).src=url; $(imgId).alt=f.name; $(imgId).classList.add('w-full','h-40','object-cover');
    });
  }
  preview(form.selfie, '#prevSelfie');
  preview(form.id_front, '#prevFront');
  preview(form.id_back, '#prevBack');

  backBtn.addEventListener('click', ()=> showStep(step-1));
  nextBtn.addEventListener('click', ()=>{ if(validateStep()) showStep(step+1); });

// replace demo submit handler with real AJAX submission
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  // final client-side validation before sending
  if(!validateStep()) return;

  // Build FormData from the form element (includes files)
  const fd = new FormData(form);

  // If categories are checkboxes, FormData will append all checked values automatically.
  // Show a small disabled UI while we submit
  nextBtn.disabled = true;
  backBtn.disabled = true;
  stepHint.textContent = 'Submitting…';

  try {
    const res = await fetch('/api/register.php', {
      method: 'POST',
      body: fd
    });
    const data = await res.json();

    if (data.success) {
      // optionally show a nice message
      alert(data.message || 'Account created. Redirecting…');
      window.location.href = data.redirect || '/';
    } else {
      alert('Error: ' + (data.error || 'Unknown error'));
      nextBtn.disabled = false;
      backBtn.disabled = (step===1);
      stepHint.textContent = '';
    }
  } catch (err) {
    console.error(err);
    alert('Network error — please try again.');
    nextBtn.disabled = false;
    backBtn.disabled = (step===1);
    stepHint.textContent = '';
  }
});


  // Build Review box when entering step 5
  const buildReview = ()=>{
    const box = $('#reviewBox'); if(!box) return;
    const role = form.role.value;
    const pairs = [
      ['Role', role], ['Email', form.email.value], ['Phone', form.phone.value],
      ['Full name', form.first_name.value+' '+form.last_name.value],
      ['DOB', form.dob.value], ['Gender', form.gender.value],
      ['Address', `${form.address.value}, ${form.city.value}, ${form.state.value}`],
      ['ID Type', form.id_type.value], ['ID Number', form.id_number.value]
    ];
    if(role==='runner'){
      const cats = $$('input[name="categories"]:checked').map(i=>i.value).join(', ');
      pairs.push(['Categories', cats||'—'], ['Rate', form.rate.value||'—'], ['Experience', form.experience.value||'—']);
    } else {
      pairs.push(['Default Address', form.default_address.value||'—'], ['Verified Only', form.prefer_verified.value||'—']);
    }
    box.innerHTML = pairs.map(([k,v])=>`<div class="p-3 rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-900"><div class="text-xs text-gray-500">${k}</div><div class="font-medium">${v||'—'}</div></div>`).join('');
  };

  // Watch step changes to build review
  const obs = new MutationObserver(()=>{ if(step===5) buildReview(); });
  obs.observe(document.body,{subtree:true,attributes:true,attributeFilter:['class']});

  // init
  showStep(1);
})();

</script>