// scripts/api.mock.js
window.MockAPI = (function () {
    const users = {
      you:  { id: 'me',   name: 'You', avatar: 'https://i.pravatar.cc/100?img=1' },
      u1:   { id: 'u1',   name: 'Adeyemi Plumbing', avatar: 'https://i.pravatar.cc/100?img=8' },
      u2:   { id: 'u2',   name: 'Chioma Beauty',    avatar: 'https://i.pravatar.cc/100?img=11' },
      u3:   { id: 'u3',   name: 'RapidFix Tech',    avatar: 'https://i.pravatar.cc/100?img=3' },
    };
  
    let conversations = [
      { id:'c1', with: users.u1, last:'We can come by 3pm today.', unread:2, updatedAt: Date.now()-1000*60*5, job:'Fix leaking sink' },
      { id:'c2', with: users.u2, last:'Thank you! Payment received.', unread:0, updatedAt: Date.now()-1000*60*60*3, job:'Makeup for event' },
      { id:'c3', with: users.u3, last:'Sharing a quick quote now.', unread:1, updatedAt: Date.now()-1000*60*60*26, job:'Wiring check' },
    ];
  
    const msgStore = {
      c1: [
        { id:'m1', from: users.u1, text:'Hello, saw your request.', ts: Date.now()-1000*60*60 },
        { id:'m2', from: users.you, text:'Hi! Can you fix today?', ts: Date.now()-1000*60*50 },
        { id:'m3', from: users.u1, text:'We can come by 3pm today.', ts: Date.now()-1000*60*5 },
      ],
      c2: [
        { id:'m1', from: users.u2, text:'I’ve finished. Please confirm.', ts: Date.now()-1000*60*60*2 },
        { id:'m2', from: users.you, text:'Confirmed — great job!', ts: Date.now()-1000*60*60*2+5000 },
        { id:'m3', from: users.u2, text:'Thank you! Payment received.', ts: Date.now()-1000*60*60*2+8000 },
      ],
      c3: [
        { id:'m1', from: users.u3, text:'Sharing a quick quote now.', ts: Date.now()-1000*60*60*25 },
      ],
    };
  
    const delay = (v, ms=250) => new Promise(r=>setTimeout(()=>r(v), ms));
  
    return {
      async listConversations(q='') {
        let list = conversations
          .slice()
          .sort((a,b)=>b.updatedAt-a.updatedAt);
        if (q) list = list.filter(c =>
          c.with.name.toLowerCase().includes(q.toLowerCase()) ||
          (c.job||'').toLowerCase().includes(q.toLowerCase())
        );
        return delay(list);
      },
      async getThread(id) {
        return delay((msgStore[id]||[]).slice().sort((a,b)=>a.ts-b.ts));
      },
      async sendMessage(id, fromUser, text, attachments=[]) {
        const m = { id:'m'+Math.random().toString(36).slice(2,7), from: fromUser, text, ts: Date.now(), attachments };
        msgStore[id] = (msgStore[id]||[]).concat(m);
        const conv = conversations.find(c=>c.id===id);
        if (conv) { conv.last = text || (attachments.length ? 'Sent an attachment' : ''); conv.updatedAt = m.ts; conv.unread = 0; }
        return delay(m, 120);
      }
    };
  })();