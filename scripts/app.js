// app.js
const mockRequests = [
    { title: 'AC servicing', place: 'Igbogbo', budget: 10000, status: 'Awaiting Offers', tone: 'amber' },
    { title: 'Fix leaky kitchen sink', place: 'Ibeshe', budget: 8000, status: 'Open', tone: 'emerald' },
  ];
  
  const mockOffers = [
    { name: 'Oluwatobi', rating: 4.8, price: 9500, note: 'can start today' },
    { name: 'Maryam', rating: 4.9, price: 10000, note: 'tools available' },
  ];
  
  const mockJobs = [
    { title: 'AC servicing for 2 rooms', place: 'Igbogbo', budget: 10000, tag: 'Open', tone: 'emerald' },
    { title: 'Home lesson – JSS2 Math', place: 'Ibeshe', budget: 5000, tag: 'Bidding', tone: 'amber' },
  ];
  
  function badge(tone, text){
    const map = { emerald: ['bg-emerald-100','text-emerald-700'], amber: ['bg-amber-100','text-amber-800'] };
    const [bg, fg] = map[tone] || ['bg-gray-100','text-gray-700'];
    return `<span class="text-xs px-2 py-1 rounded-full ${bg} ${fg}">${text}</span>`;
  }
  
  function renderRequester(){
    const reqBox = document.getElementById('req-requests');
    const offBox = document.getElementById('req-offers');
    if(reqBox){
      reqBox.innerHTML = mockRequests.map(r=>`
        <div class="p-4 border rounded-xl">
          <div class="flex items-start justify-between">
            <div>
              <div class="font-medium">${r.title}</div>
              <div class="text-sm text-gray-600">${r.place} · Budget ₦${r.budget.toLocaleString()}</div>
            </div>
            ${badge(r.tone, r.status)}
          </div>
          <div class="mt-3 flex gap-3">
            <button class="px-3 py-2 rounded-lg border">Edit</button>
            <button class="px-3 py-2 rounded-lg bg-[#FFC52E] font-semibold text-[#111827]">Cancel</button>
          </div>
        </div>
      `).join('');
    }
    if(offBox){
      offBox.innerHTML = mockOffers.map(o=>`
        <div class="p-4 border rounded-xl">
          <div class="flex items-center justify-between">
            <div>
              <div class="font-medium">${o.name} (⭐ ${o.rating})</div>
              <div class="text-sm text-gray-600">₦${o.price.toLocaleString()} · ${o.note}</div>
            </div>
            <div class="flex gap-2">
              <button class="px-3 py-2 rounded-lg border">Message</button>
              <button class="px-3 py-2 rounded-lg bg-[#003d87] text-white font-semibold">Accept</button>
            </div>
          </div>
        </div>
      `).join('');
    }
  }
  
  function renderRunner(){
    const jobBox = document.getElementById('run-jobs');
    if(jobBox){
      jobBox.innerHTML = mockJobs.map(j=>`
        <div class="rounded-2xl bg-white border p-5 hover:shadow-lg transition">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="font-semibold">${j.title}</h3>
              <p class="text-sm text-gray-600">${j.place} · Budget: ₦${j.budget.toLocaleString()}</p>
            </div>
            ${badge(j.tone, j.tag)}
          </div>
          <p class="mt-3 text-sm text-gray-700">Short job description goes here.</p>
          <div class="mt-4 flex items-center gap-3">
            <button class="px-3 py-2 rounded-lg border">Message</button>
            <button class="px-3 py-2 rounded-lg bg-[#FFC52E] font-semibold text-[#111827]">Make Offer</button>
          </div>
        </div>
      `).join('');
    }
  }

  const btn = document.getElementById('themeToggle');
  btn?.addEventListener('click', () => {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('runaz-theme', isDark ? 'dark' : 'light');
  });

  // scripts/app.js
const routes = {
  '#/runner': '/runners/index.html',
  '#/requester': '/requesters/index.html',
  '#/post': '/requesters/post.html',
  '#/categories': '/categories.html',
  '#/how': '/how-it-works.html'
};

async function loadRoute() {
  const path = location.hash || '#/requester';
  const file = routes[path] || routes['#/requester'];
  const main = document.querySelector('[data-router]');
  if (!main) return;
  const html = await fetch(file).then(r => r.text());
  main.innerHTML = html;
  if (window.feather) feather.replace();
}
window.addEventListener('hashchange', loadRoute);
window.addEventListener('DOMContentLoaded', loadRoute);

  