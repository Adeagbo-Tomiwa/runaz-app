// auth.js — Complete registration wizard
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
  if(!form) return; // only on register page

  let step = 1; 
  const maxStep = 5;
  const backBtn = $('#backBtn');
  const nextBtn = $('#nextBtn');
  const submitBtn = $('#submitBtn');
  const stepHint = $('#stepHint');

  const showStep = (n)=>{
    step = Math.min(Math.max(1,n), maxStep);
    $$('#registerForm section[data-step]').forEach(sec=>{
      sec.classList.toggle('hidden', Number(sec.dataset.step)!==step);
    });
    
    // Update progress cards with visual feedback
    $$('#steps .step').forEach((li,i)=>{
      if(i < step-1){
        // Completed steps
        li.classList.add('opacity-100');
        li.classList.remove('opacity-60');
        const div = li.querySelector('div');
        div?.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-500');
      } else if(i === step-1){
        // Current step
        li.classList.add('opacity-100');
        li.classList.remove('opacity-60');
        const div = li.querySelector('div');
        div?.classList.add('ring-2', 'ring-runaz-blue');
        div?.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-500');
      } else {
        // Future steps
        li.classList.remove('opacity-100');
        li.classList.add('opacity-60');
        const div = li.querySelector('div');
        div?.classList.remove('ring-2', 'ring-runaz-blue', 'bg-green-50', 'dark:bg-green-900/20', 'border-green-500');
      }
    });

    backBtn.disabled = (step===1);
    backBtn.style.display = (step===1) ? 'none' : 'block';
    nextBtn.classList.toggle('hidden', step===maxStep);
    submitBtn.classList.toggle('hidden', step!==maxStep);

    const hints = {
      1:'Choose your role and create login details',
      2:'Enter your personal and contact information',
      3:'Upload ID documents and selfie for verification',
      4:'Complete your profile details',
      5:'Review all information and submit'
    };
    stepHint.textContent = hints[step] || '';

    // Scroll to top smoothly
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  // Enhanced validation with error messages
  const validateStep = ()=>{
    if(step===1){
      const roleInput = form.querySelector('input[name="role"]:checked');
      const role = roleInput ? roleInput.value : '';
      const email = form.email.value;
      const phone = form.phone.value;
      const pass = form.password.value; 
      const conf = form.password_confirm.value;
      
      if(!role){ 
        showToast('Please select a role (Requester or Runner)', 'error'); 
        return false; 
      }
      if(!email || !email.includes('@')){ 
        showToast('Please enter a valid email address', 'error'); 
        return false; 
      }
      if(!phone){ 
        showToast('Please enter your phone number', 'error'); 
        return false; 
      }
      if(pass.length < 6){ 
        showToast('Password must be at least 6 characters', 'error'); 
        return false; 
      }
      if(pass!==conf){ 
        showToast('Passwords do not match', 'error'); 
        return false; 
      }
    }
    
    if(step===2){
      const required = ['first_name','last_name','dob','gender','address','city','state','lga'];
      for(const n of required){ 
        if(!form[n].value){ 
          showToast('Please complete all required personal information fields', 'error'); 
          form[n].focus();
          return false; 
        } 
      }
      
      // Validate date of birth (must be 18+)
      const dob = new Date(form.dob.value);
      const today = new Date();
      const age = today.getFullYear() - dob.getFullYear();
      if(age < 18){
        showToast('You must be at least 18 years old to register', 'error');
        return false;
      }
    }
    
    if(step===3){
      const required = ['id_type','id_number','selfie','id_front','id_back'];
      for(const n of required){ 
        if(!form[n].value){ 
          showToast('Please complete all KYC fields and upload required documents', 'error'); 
          return false; 
        } 
      }
      
      // Validate file uploads
      const files = ['selfie', 'id_front', 'id_back'];
      for(const f of files){
        const file = form[f].files[0];
        if(!file){
          showToast(`Please upload ${f.replace('_', ' ')}`, 'error');
          return false;
        }
        // Check file size (5MB max)
        if(file.size > 5242880){
          showToast(`${f.replace('_', ' ')} must be less than 5MB`, 'error');
          return false;
        }
        // Check file type
        if(!file.type.startsWith('image/')){
          showToast(`${f.replace('_', ' ')} must be an image file`, 'error');
          return false;
        }
      }
    }
    
    if(step===4){
      const roleInput = form.querySelector('input[name="role"]:checked');
      const selectedRole = roleInput ? roleInput.value : '';
      
      if(selectedRole === 'runner'){
        const checkedCategories = $$('input[name="categories"]:checked', form);
        
        if(!checkedCategories || checkedCategories.length === 0){ 
          showToast('Please select at least one service category', 'error');
          return false;
        }
      }
    }
    
    if(step===5){
      if(!$('#agree').checked){ 
        showToast('Please agree to Terms & Privacy Policy to continue', 'error'); 
        return false; 
      }
    }
    
    return true;
  };

  // Role-specific toggles
  const updateRoleUI = ()=>{
    const roleInput = form.querySelector('input[name="role"]:checked');
    const role = roleInput ? roleInput.value : '';
    const isRunner = role==='runner';
    
    const profileTitle = $('#profileTitle');
    const runnerFields = $('#runnerFields');
    const requesterFields = $('#requesterFields');
    
    if(profileTitle) profileTitle.textContent = isRunner? 'Runner Profile' : 'Requester Preferences';
    if(runnerFields) runnerFields.classList.toggle('hidden', !isRunner);
    if(requesterFields) requesterFields.classList.toggle('hidden', isRunner);
  };
  
  $$('input[name="role"]').forEach(r=> r.addEventListener('change', ()=>{ 
    updateRoleUI(); 
    // Add visual feedback
    r.closest('label').classList.add('ring-2', 'ring-runaz-blue');
    $$('input[name="role"]').forEach(other => {
      if(other !== r) other.closest('label').classList.remove('ring-2', 'ring-runaz-blue');
    });
  }));

  // Enhanced image preview with validation
  const preview = (input, imgId)=>{
    input?.addEventListener('change', e=>{
      const f=e.target.files?.[0]; 
      if(!f) return;
      
      // Validate file size
      if(f.size > 5242880){
        showToast('File size must be less than 5MB', 'error');
        input.value = '';
        return;
      }
      
      // Validate file type
      if(!f.type.startsWith('image/')){
        showToast('Please upload an image file (JPG, PNG)', 'error');
        input.value = '';
        return;
      }
      
      const url=URL.createObjectURL(f);
      $('#kycPreview').classList.remove('hidden');
      const img = $(imgId);
      img.src=url; 
      img.alt=f.name; 
      img.classList.add('w-full','h-40','object-cover');
    });
  }
  preview(form.selfie, '#prevSelfie');
  preview(form.id_front, '#prevFront');
  preview(form.id_back, '#prevBack');

  // Password confirmation validation
  const passwordConfirm = form.password_confirm;
  passwordConfirm?.addEventListener('input', function(){
    const password = form.password.value;
    if(this.value && this.value !== password){
      this.setCustomValidity('Passwords do not match');
    } else {
      this.setCustomValidity('');
    }
  });

  // Navigation
  backBtn.addEventListener('click', ()=> showStep(step-1));
  nextBtn.addEventListener('click', ()=>{ 
    if(validateStep()) {
      showStep(step+1);
      if(step === 5) buildReview(); // Build review when entering step 5
    }
  });

  // Form submission with enhanced error handling - SINGLE HANDLER ONLY
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Final validation
    if(!validateStep()) return;

    // Build FormData from the form element (includes files)
    const fd = new FormData(form);

    // Show loading state
    submitBtn.disabled = true;
    nextBtn.disabled = true;
    backBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Creating account...';
    stepHint.textContent = 'Submitting your registration...';

    try {
      // Make API call - adjust path based on your folder structure
      const res = await fetch('../api/register.php', {
        method: 'POST',
        body: fd
      });

      // Check if response is JSON
      const contentType = res.headers.get('content-type');
      if(!contentType || !contentType.includes('application/json')){
        throw new Error('Server returned invalid response');
      }

      const data = await res.json();

      if (data.success) {
        showToast(data.message || 'Account created successfully! Redirecting...', 'success');
        
        // Redirect after 2 seconds
        setTimeout(() => {
          window.location.href = data.redirect || '../login/';
        }, 2000);
      } else {
        throw new Error(data.message || 'Registration failed');
      }
      
    } catch (err) {
      console.error('Registration error:', err);
      showToast(err.message || 'Network error — please try again.', 'error');
      
      // Reset buttons
      submitBtn.disabled = false;
      nextBtn.disabled = false;
      backBtn.disabled = (step===1);
      submitBtn.textContent = originalText;
      stepHint.textContent = '';
    }
  });

  // Build Review box when entering step 5
  const buildReview = ()=>{
    const box = $('#reviewBox'); 
    if(!box) return;
    
    // Get the selected role properly
    const roleInput = form.querySelector('input[name="role"]:checked');
    const role = roleInput ? roleInput.value : '';
    
    const pairs = [
      ['Role', role ? role.charAt(0).toUpperCase() + role.slice(1) : '—'],
      ['Email', form.email.value || '—'],
      ['Phone', form.phone.value || '—'],
      ['Full Name', (form.first_name.value + ' ' + form.last_name.value).trim() || '—'],
      ['Date of Birth', form.dob.value || '—'],
      ['Gender', form.gender.value || '—'],
      ['Address', `${form.address.value}, ${form.city.value}, ${form.state.value}` || '—'],
      ['LGA', form.lga.value || '—'],
      ['ID Type', form.id_type.value || '—'],
      ['ID Number', form.id_number.value || '—']
    ];
    
    if(form.alt_phone.value){
      pairs.push(['Alternate Phone', form.alt_phone.value]);
    }
    
    if(form.referral.value){
      pairs.push(['Referral', form.referral.value]);
    }
    
    if(role==='runner'){
      const cats = $$('input[name="categories"]:checked').map(i=>i.value).join(', ');
      pairs.push(['Service Categories', cats||'—']);
      pairs.push(['Skills', form.skills.value||'—']);
      pairs.push(['Hourly Rate', form.rate.value ? `₦${form.rate.value}/hr` : '—']);
      pairs.push(['Experience', form.experience.value ? `${form.experience.value} years` : '—']);
      if(form.bio.value) pairs.push(['Bio', form.bio.value.substring(0, 100) + (form.bio.value.length > 100 ? '...' : '')]);
      if(form.availability.value) pairs.push(['Availability', form.availability.value]);
    } else {
      pairs.push(['Service Address', form.default_address.value||'—']);
      pairs.push(['Prefer Verified', form.prefer_verified.value||'Yes']);
      pairs.push(['Budget Preference', form.budget_pref.value||'Flexible']);
      if(form.notes.value) pairs.push(['Notes', form.notes.value.substring(0, 100) + (form.notes.value.length > 100 ? '...' : '')]);
    }
    
    // Files uploaded confirmation
    const filesUploaded = [];
    if(form.selfie.files[0]) filesUploaded.push('Selfie');
    if(form.id_front.files[0]) filesUploaded.push('ID Front');
    if(form.id_back.files[0]) filesUploaded.push('ID Back');
    pairs.push(['Documents', filesUploaded.join(', ') + ' ✓']);
    
    box.innerHTML = pairs.map(([k,v])=>`
      <div class="p-3 rounded-xl border dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">${k}</div>
        <div class="font-medium text-sm">${v||'—'}</div>
      </div>
    `).join('');
  };

  // Toast notification system
  function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const colors = {
      success: 'bg-green-500',
      error: 'bg-red-500',
      info: 'bg-blue-500'
    };
    
    toast.className = `fixed top-4 right-4 ${colors[type] || colors.info} text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-slide-in max-w-md`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
      toast.style.transition = 'opacity 0.3s';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  }

  // Initialize
  showStep(1);
  
  // Load saved theme
  const savedTheme = localStorage.getItem('runaz-theme');
  if(savedTheme === 'dark'){
    document.documentElement.classList.add('dark');
  }
})();

// Add required CSS for animations
const style = document.createElement('style');
style.textContent = `
/* Toast animation */
@keyframes slide-in {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.animate-slide-in {
  animation: slide-in 0.3s ease-out;
}

/* Loading spinner */
@keyframes spin {
  to { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Step completed styling */
.step.completed > div {
  background: linear-gradient(135deg, #10B981 0%, #059669 100%);
  color: white;
  border-color: #10B981;
}
`;
document.head.appendChild(style);