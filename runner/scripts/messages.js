// scripts/messages.js
window.ChatApp = (function () {
    let state = {
      role: 'runner',         // or 'runner'
      activeId: null,
      you: { id: 'me', name: 'You', avatar: 'https://i.pravatar.cc/100?img=1' },
      attachments: []
    };
  
    const el = sel => document.querySelector(sel);
    const els = sel => Array.from(document.querySelectorAll(sel));
  
    function fmtTime(ts){
      const d = new Date(ts);
      return d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
    }
  
    function bubbleHTML(m){
      const isMe = m.from.id === state.you.id;
      const att = (m.attachments||[]).map(src => `<img src="${src}" class="attachment" alt="">`).join('');
      return `
        <div class="flex ${isMe?'justify-end':''}">
          <div>
            ${m.text ? `<div class="bubble ${isMe?'bubble-out':'bubble-in'}">${m.text}</div>` : ''}
            ${att ? `<div class="mt-1 flex gap-2 justify-${isMe?'end':'start'}">${att}</div>` : ''}
            <div class="meta ${isMe?'text-right':''}">${fmtTime(m.ts)}</div>
          </div>
        </div>`;
    }
  
    async function loadConversations(q=''){
      const list = await MockAPI.listConversations(q);
      const html = list.map(c => `
        <li class="conv-item ${c.id===state.activeId?'active':''}" data-id="${c.id}">
          <img class="w-10 h-10 rounded-full" src="${c.with.avatar}" alt="">
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
              <div class="conv-title">${c.with.name}</div>
              ${c.unread ? `<span class="pill">${c.unread}</span>`:''}
            </div>
            <div class="conv-sub">${c.job ? c.job + ' • ' : ''}${c.last||'—'}</div>
          </div>
        </li>
      `).join('');
      el('#conversations').innerHTML = html || `<div class="px-4 py-8 text-center text-sm text-gray-500">No conversations</div>`;
      if (window.feather) feather.replace();
  
      // click handlers
      els('#conversations .conv-item').forEach(item=>{
        item.addEventListener('click', ()=> openThread(item.dataset.id));
      });
    }
  
    async function openThread(id){
      state.activeId = id;
      els('#conversations .conv-item').forEach(n=>n.classList.toggle('active', n.dataset.id===id));
      const msgs = await MockAPI.getThread(id);
  
      // header from selected conversation
      const li = el(`#conversations .conv-item[data-id="${id}"]`);
      const name = li?.querySelector('.conv-title')?.textContent || '';
      const avatar = li?.querySelector('img')?.src || '';
      el('#headName').textContent = name;
      el('#headAvatar').src = avatar;
      el('#headMeta').textContent = 'Secure messaging · ' + new Date().toLocaleDateString();
  
      // body
      el('#threadBody').innerHTML = msgs.map(bubbleHTML).join('');
      el('#threadBody').scrollTop = el('#threadBody').scrollHeight;
    }
  
    function wireComposer(){
      const ta = el('#msgInput');
      const file = el('#fileInput');
      const att = el('#attachments');
  
      // auto-grow textarea
      ta.addEventListener('input', ()=>{
        ta.style.height = 'auto';
        ta.style.height = Math.min(120, ta.scrollHeight) + 'px';
      });
  
      el('#attachBtn').addEventListener('click', ()=> file.click());
      file.addEventListener('change', ()=>{
        state.attachments = Array.from(file.files||[]);
        if (!state.attachments.length){ att.classList.add('hidden'); att.innerHTML=''; return; }
        att.classList.remove('hidden');
        const previews = state.attachments.map(f=>URL.createObjectURL(f));
        att.innerHTML = `<div class="flex gap-2 mt-2">${previews.map(src=>`<img class="attachment" src="${src}">`).join('')}</div>`;
      });
  
      el('#composer').addEventListener('submit', async (e)=>{
        e.preventDefault();
        const text = ta.value.trim();
        if (!text && state.attachments.length===0) return;
  
        // convert attachments to blobs (mock upload)
        const urls=[];
        for (const f of state.attachments){
          urls.push(URL.createObjectURL(f));
        }
  
        const msg = await MockAPI.sendMessage(state.activeId, state.you, text, urls);
        el('#threadBody').insertAdjacentHTML('beforeend', bubbleHTML(msg));
        el('#threadBody').scrollTop = el('#threadBody').scrollHeight;
  
        ta.value=''; ta.style.height='auto';
        state.attachments=[]; el('#attachments').classList.add('hidden'); el('#attachments').innerHTML='';
  
        // refresh left list (last message preview)
        loadConversations(el('#convSearch').value.trim());
      });
    }
  
    function wireSearch(){
      el('#convSearch').addEventListener('input', e=>{
        loadConversations(e.target.value.trim());
      });
    }
  
    async function init(opts={}){
      state.role = opts.role || 'requester';
      wireComposer();
      wireSearch();
      await loadConversations();
      // open first thread by default
      const first = el('#conversations .conv-item');
      if (first) openThread(first.dataset.id);
    }
  
    return { init };
  })();