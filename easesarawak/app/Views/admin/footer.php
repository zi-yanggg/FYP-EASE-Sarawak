<!-- ===================== AI CHAT WIDGET ===================== -->
<style>
  @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&display=swap');

  /* ── FAB button ── */
  #ai-chat-fab {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 54px;
    height: 54px;
    border-radius: 0;
    background: #1A1A1A;
    color: #F2BE00;
    border: 2px solid #F2BE00;
    box-shadow: 0 4px 18px rgba(242,190,0,.30);
    font-size: 20px;
    cursor: pointer;
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .18s, box-shadow .18s;
    font-family: 'Oxanium', sans-serif;
  }
  #ai-chat-fab:hover {
    background: #F2BE00;
    color: #1A1A1A;
    box-shadow: 0 6px 22px rgba(242,190,0,.50);
  }

  /* ── Chat window ── */
  #ai-chat-window {
    position: fixed;
    bottom: 96px;
    right: 28px;
    width: 370px;
    max-height: 530px;
    border-radius: 0;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
    background: #ffffff;
    border: 1px solid rgba(242,190,0,.35);
    display: flex;
    flex-direction: column;
    z-index: 1049;
    overflow: hidden;
    font-family: 'Oxanium', sans-serif;
  }

  /* ── Header (matches rpt-card-header) ── */
  #ai-chat-header {
    background: #1A1A1A;
    color: #F2BE00;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 800;
    font-size: 14px;
    letter-spacing: .02em;
    border-bottom: 1px solid #111111;
    min-height: 52px;
    box-sizing: border-box;
  }
  #ai-chat-header i { color: #F2BE00; }
  #ai-chat-header .ai-clear-btn {
    background: rgba(242,190,0,.15);
    border: 1px solid rgba(242,190,0,.35);
    color: #F2BE00;
    border-radius: 0;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    cursor: pointer;
    font-family: 'Oxanium', sans-serif;
    transition: background .15s;
  }
  #ai-chat-header .ai-clear-btn:hover {
    background: rgba(242,190,0,.30);
  }

  /* ── Message list ── */
  #ai-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #fffdf5;
    min-height: 200px;
    scrollbar-width: thin;
    scrollbar-color: #F2BE00 #fffdf5;
  }
  #ai-chat-messages::-webkit-scrollbar { width: 4px; }
  #ai-chat-messages::-webkit-scrollbar-track { background: #fffdf5; }
  #ai-chat-messages::-webkit-scrollbar-thumb { background: #F2BE00; border-radius: 0; }

  /* ── Bubbles ── */
  .ai-msg {
    max-width: 84%;
    padding: 9px 13px;
    border-radius: 0;
    font-size: 13px;
    line-height: 1.55;
    white-space: pre-wrap;
    word-break: break-word;
    font-family: 'Oxanium', sans-serif;
  }
  .ai-msg.user {
    background: #F2BE00;
    color: #1A1A1A;
    align-self: flex-end;
    font-weight: 600;
    border-left: 3px solid #d4a700;
  }
  .ai-msg.assistant {
    background: #ffffff;
    color: #111827;
    align-self: flex-start;
    border: 1px solid #F3F4F6;
    border-left: 3px solid #F2BE00;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
  }
  .ai-msg.error {
    background: #fff5f5;
    color: #c0392b;
    border-left: 3px solid #c0392b;
  }
  .ai-typing {
    font-size: 12px;
    color: #9CA3AF;
    font-style: italic;
    align-self: flex-start;
    padding: 4px 0;
    letter-spacing: .03em;
  }

  /* ── Input row ── */
  #ai-chat-input-row {
    display: flex;
    gap: 8px;
    padding: 10px 12px;
    border-top: 1px solid #F3F4F6;
    background: #ffffff;
  }
  #ai-chat-input {
    flex: 1;
    border: 1px solid #F3F4F6;
    border-radius: 0;
    padding: 8px 12px;
    font-size: 13px;
    outline: none;
    resize: none;
    max-height: 80px;
    font-family: 'Oxanium', sans-serif;
    background: #fffdf5;
    color: #111827;
    transition: border-color .15s;
  }
  #ai-chat-input:focus { border-color: #F2BE00; }
  #ai-chat-input::placeholder { color: #9CA3AF; }
  #ai-chat-send {
    background: #F2BE00;
    color: #1A1A1A;
    border: none;
    border-radius: 0;
    padding: 0 15px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 700;
    transition: background .18s;
  }
  #ai-chat-send:hover { background: #d4a700; }
  #ai-chat-send:disabled { background: #F3F4F6; color: #9CA3AF; cursor: not-allowed; }
</style>

<!-- Floating button -->
<button id="ai-chat-fab" title="AI Assistant">
  <i class="fas fa-robot"></i>
</button>

<!-- Chat window -->
<div id="ai-chat-window" style="display:none;">
  <div id="ai-chat-header">
    <span><i class="fas fa-robot me-2"></i>EASE AI Assistant</span>
    <button class="ai-clear-btn" id="ai-clear-btn" title="Clear conversation">Clear</button>
  </div>
  <div id="ai-chat-messages">
    <div class="ai-msg assistant">Hi! I'm your EASE Sarawak AI assistant. Ask me anything about orders, revenue, or dashboard data.</div>
  </div>
  <div id="ai-chat-input-row">
    <textarea id="ai-chat-input" placeholder="Ask a question…" rows="1"></textarea>
    <button id="ai-chat-send"><i class="fas fa-paper-plane"></i></button>
  </div>
</div>

<script>
(function () {
  const fab     = document.getElementById('ai-chat-fab');
  const win     = document.getElementById('ai-chat-window');
  const msgs    = document.getElementById('ai-chat-messages');
  const input   = document.getElementById('ai-chat-input');
  const sendBtn = document.getElementById('ai-chat-send');
  const clearBtn= document.getElementById('ai-clear-btn');

  // Toggle window
  fab.addEventListener('click', () => {
    win.style.display = win.style.display === 'none' || win.style.display === '' ? 'flex' : 'none';
    if (win.style.display === 'flex') { input.focus(); scrollBottom(); }
  });

  // Send on Enter (Shift+Enter = new line)
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
  });
  sendBtn.addEventListener('click', sendMessage);

  // Auto-grow textarea
  input.addEventListener('input', () => {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 80) + 'px';
  });

  // Clear history
  clearBtn.addEventListener('click', () => {
    fetch('<?= base_url('admin/ai-chat/clear') ?>', { method: 'DELETE' })
      .then(() => {
        msgs.innerHTML = '<div class="ai-msg assistant">Conversation cleared. How can I help you?</div>';
      });
  });

  function scrollBottom() {
    msgs.scrollTop = msgs.scrollHeight;
  }

  function appendMsg(role, text) {
    const div = document.createElement('div');
    div.className = 'ai-msg ' + role;
    div.textContent = text;
    msgs.appendChild(div);
    scrollBottom();
    return div;
  }

  function sendMessage() {
    const text = input.value.trim();
    if (!text || sendBtn.disabled) return;

    appendMsg('user', text);
    input.value = '';
    input.style.height = 'auto';
    sendBtn.disabled = true;

    const typing = document.createElement('div');
    typing.className = 'ai-typing';
    typing.textContent = 'Thinking…';
    msgs.appendChild(typing);
    scrollBottom();

    fetch('<?= base_url('admin/ai-chat') ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify({ message: text })
    })
    .then(r => r.json())
    .then(data => {
      typing.remove();
      if (data.error) {
        appendMsg('error', 'Error: ' + data.error);
      } else {
        appendMsg('assistant', data.reply);
      }
    })
    .catch(() => {
      typing.remove();
      appendMsg('error', 'Network error. Please try again.');
    })
    .finally(() => { sendBtn.disabled = false; input.focus(); });
  }
})();
</script>
<!-- ============================================================ -->

 <!-- <footer class="footer">
     <div class="container-fluid d-flex justify-content-between">
         <nav class="pull-left">
             <ul class="nav">
                 <li class="nav-item">
                     <a class="nav-link" href="http://www.themekita.com">
                         ThemeKita
                     </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#"> Help </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#"> Licenses </a>
                 </li>
             </ul>
         </nav>
         <div class="copyright">
             2024, made with <i class="fa fa-heart heart text-danger"></i> by
             <a href="http://www.themekita.com">ThemeKita</a>
         </div>
         <div>
             Distributed by
             <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
         </div>
     </div>
 </footer> -->
 </div>
 </div>
 <!--   Core JS Files   -->
 <script src="<?= base_url('assets/js/admin/core/jquery-3.7.1.min.js') ?>"></script>
 <script src="<?= base_url('assets/js/admin/core/popper.min.js') ?>"></script>
 <script src="<?= base_url('assets/js/admin/core/bootstrap.min.js') ?>"></script>

 <!-- jQuery Scrollbar -->
 <script src="<?= base_url('assets/js/admin/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>

 <!-- Chart JS -->
 <script src="<?= base_url('assets/js/admin/plugin/chart.js/chart.min.js') ?>"></script>

 <!-- jQuery Sparkline -->
 <script src="<?= base_url('assets/js/admin/plugin/jquery.sparkline/jquery.sparkline.min.js') ?>"></script>

 <!-- Chart Circle -->
 <script src="<?= base_url('assets/js/admin/plugin/chart-circle/circles.min.js') ?>"></script>

 <!-- Datatables -->
 <script src="<?= base_url('assets/js/admin/plugin/datatables/datatables.min.js') ?>"></script>

 <!-- Bootstrap Notify -->
 <script src="<?= base_url('assets/js/admin/plugin/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

 <!-- jQuery Vector Maps -->
 <script src="<?= base_url('assets/js/admin/plugin/jsvectormap/jsvectormap.min.js') ?>"></script>
 <script src="<?= base_url('assets/js/admin/plugin/jsvectormap/world.js') ?>"></script>

 <!-- Sweet Alert -->
 <script src="<?= base_url('assets/js/admin/plugin/sweetalert/sweetalert.min.js') ?>"></script>

 <!-- Kaiadmin JS -->
 <script src="<?= base_url('assets/js/admin/kaiadmin.min.js') ?>"></script>

 <!-- Kaiadmin DEMO methods, don't include it in your project! -->
 <!-- <script src="assets/js/admin/demo.js"></script> -->
 <script>
     $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
         type: "line",
         height: "70",
         width: "100%",
         lineWidth: "2",
         lineColor: "#177dff",
         fillColor: "rgba(23, 125, 255, 0.14)",
     });

     $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
         type: "line",
         height: "70",
         width: "100%",
         lineWidth: "2",
         lineColor: "#f3545d",
         fillColor: "rgba(243, 84, 93, .14)",
     });

     $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
         type: "line",
         height: "70",
         width: "100%",
         lineWidth: "2",
         lineColor: "#ffa534",
         fillColor: "rgba(255, 165, 52, .14)",
     });
 </script>
 </body>

 </html>