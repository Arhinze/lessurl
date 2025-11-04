<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link rel="icon" type="image/x-icon" href="static/favicon.png"/>
  <title>HaystackPay — Pitch Deck</title>
  <style>
    :root{
      --bg:#0f1724; --card:#0b1220; --muted:#9aa6bf; --accent:#7c3aed; --glass: rgba(255,255,255,0.04);
      --maxw:1000px;
    }
    *{box-sizing:border-box}
    html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,Helvetica,Arial; background:linear-gradient(180deg,#071129 0%, #081026 60%); color:#e6eef8}
    .wrap{max-width:var(--maxw);margin:28px auto;padding:20px}
    header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
    .brand{display:flex;gap:14px;align-items:center}
    .logo{width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,var(--accent),#06b6d4);display:flex;align-items:center;justify-content:center;font-weight:700;color:white;box-shadow:0 6px 18px rgba(124,58,237,0.25)}
    h1{font-size:20px;margin:0}
    p.lead{color:var(--muted);margin:0;font-size:13px}

    /* slides */
    .deck{background:var(--card);border-radius:14px;padding:28px;box-shadow:0 10px 30px rgba(2,6,23,0.6);min-height:520px;display:grid;grid-template-rows:1fr auto}
    .slide{display:none;padding:12px 18px}
    .slide.active{display:block}
    .slide h2{margin:0 0 8px;font-size:22px}
    .slide p{color:var(--muted);line-height:1.5}
    .columns{display:flex;gap:18px;margin-top:12px}
    .col{flex:1;background:var(--glass);padding:12px;border-radius:10px}
    .muted{color:var(--muted);font-size:13px}

    /* nav */
    .nav{display:flex;align-items:center;justify-content:space-between;padding-top:14px}
    .controls{display:flex;gap:10px}
    button{background:transparent;border:1px solid rgba(255,255,255,0.06);color:inherit;padding:10px 14px;border-radius:10px;cursor:pointer}
    button.primary{background:linear-gradient(90deg,var(--accent),#06b6d4);border:none;color:white;box-shadow:0 8px 20px rgba(7,10,33,0.5)}
    .dots{display:flex;gap:6px;align-items:center}
    .dot{width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,0.06)}
    .dot.active{background:var(--accent);box-shadow:0 6px 18px rgba(124,58,237,0.2)}

    /* responsive */
    @media(max-width:720px){.wrap{padding:12px} .deck{min-height:620px} .columns{flex-direction:column}}

    /* slide-specific styles */
    .title-big{font-size:34px;margin:6px 0 10px}
    .table{width:100%;border-collapse:collapse}
    .table td{padding:8px;border-bottom:1px dashed rgba(255,255,255,0.03)}

    footer.small{color:var(--muted);font-size:12px;margin-top:10px;text-align:right}
    .tag{font-size:12px;padding:6px 8px;border-radius:999px;background:rgba(255,255,255,0.03);display:inline-block}
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <div class="brand">
        <div class="logo"><img src="static/logo.png" style="width:50px;height:auto"/></div>
        <div>
          <h1>HaystackPay — Pitch Deck</h1>
          <p class="lead">Send money without typing — Talk. Tap. Snap.</p>
        </div>
      </div>
      <div>
        <span class="tag">Fintech · AI ·</span>
      </div>
    </header>

    <main class="deck" role="main">

      <!-- Slide 1: Title -->
      <section class="slide active" data-index="0">
        <div>
          <div class="title-big">HaystackPay</div>
          <h2>Send money without typing — talk, tap, or snap.</h2>
          <p class="muted">Founder: Francis Arinze · Industry: Fintech / AI Automation</p>

          <div class="columns" style="margin-top:20px;">
            <div class="col">
              <strong>Tagline(s)</strong>
              <p class="muted">HaystackPay — Pay without typing. Talk. Tap. Snap. Done.</p>
              <p class="muted">HaystackPay — Get it done with ease.</p>
            </div>
            <div class="col">
              <strong>Stage</strong>
              <p class="muted">MVP in development · Registered business</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Slide 2: Problem -->
      <section class="slide" data-index="1">
        <h2>The Problem</h2>
        <p>Making transfers is still slow, manual, and error-prone. Users must memorize or copy long account numbers; one wrong digit causes failed or misdirected transfers. Interfaces remain complex for many users, and existing fintechs still require typing and multi-step navigation.</p>

        <div class="columns">
          <div class="col">
            <strong>Consequences</strong>
            <p class="muted">Transfer errors, time wasted, and poor user experience — especially for less technical users and busy merchants.</p>
          </div>
          <div class="col">
            <strong>Opportunity</strong>
            <p class="muted">Reinvent the payment interface — reduce steps, lower errors, and increase transaction velocity.</p>
          </div>
        </div>
      </section>

      <!-- Slide 3: Solution -->
      <section class="slide" data-index="2">
        <h2>Our Solution</h2>
        <p>HaystackPay simplifies transactions using AI: users can <strong>Talk</strong> (voice commands), <strong>Snap</strong> (image recognition/OCR), or <strong>Tap</strong> (email/phone identifiers). The app extracts, verifies, and prompts for confirmation before executing transfers.</p>

        <div style="margin-top:14px" class="columns">
          <div class="col">
            <strong>Key features</strong>
            <ul class="muted">
              <li>Voice-initiated transfers</li>
              <li>Automatic OCR for account numbers</li>
              <li>Pay via email or phone</li>
              <li>Transfer to multiple users at once</li>
            </ul>
          </div>
          <div class="col">
            <strong>Value</strong>
            <p class="muted">Less friction, fewer errors, faster transactions — an intuitive payments layer on top of existing banking rails.</p>
          </div>
        </div>
      </section>

      <!-- Slide 4: How it works -->
      <section class="slide" data-index="3">
        <h2>How it Works</h2>
        <ol class="muted">
          <li>User speaks, snaps, or selects contact.</li>
          <li>AI extracts amount + beneficiary and verifies bank details.</li>
          <li>App shows smart confirmation prompt.</li>
          <li>On confirmation, the payment is routed via partner PSP.</li>
        </ol>

        <div style="margin-top:12px" class="columns">
          <div class="col"><strong>AI Layer</strong><p class="muted">ASR/NLP for voice (Whisper/Google), OCR for images (Textract/Vision).</p></div>
          <div class="col"><strong>Payments</strong><p class="muted">Partner with licensed PSPs for settlement and reconciliation.</p></div>
        </div>
      </section>

      <!-- Slide 5: Market -->
      <section class="slide" data-index="4">
        <h2>Market Opportunity</h2>
        <p>Rapid growth in digital payments across Africa; large addressable market of smartphone users, SMEs, and everyday consumers who need fast, error-free transfers.</p>
        <table class="table muted" style="margin-top:12px">
          <tr><td>Target customers</td><td>Millennials, Gen Z, SMEs, market traders</td></tr>
          <tr><td>Wedge</td><td>Frictionless interface: Voice + OCR + Phone/Email payments</td></tr>
        </table>
      </section>

      <!-- Slide 6: Business model -->
      <section class="slide" data-index="5">
        <h2>Business Model</h2>
        <div class="columns">
          <div class="col"><strong>Revenue streams</strong>
            <p class="muted">Transaction fees, premium features (automations), API licensing for merchants/fintechs.</p>
          </div>
          <div class="col"><strong>Scalability</strong>
            <p class="muted">White-label UX layer for banks/PSPs; expand across African markets via PSP partnerships.</p>
          </div>
        </div>
      </section>

      <!-- Slide 7: Competition -->
      <section class="slide" data-index="6">
        <h2>Competitive Advantage</h2>
        <p class="muted">We focus on interface innovation rather than rewards. Our AI-driven UX differentiates us from incumbents who compete mainly on pricing and incentives.</p>

        <table class="table muted" style="margin-top:10px">
          <tr><td>Feature</td><td>Opay</td><td>Kuda</td><td>PalmPay</td><td>HaystackPay</td></tr>
          <tr><td>Voice payments</td><td>—</td><td>—</td><td>—</td><td>✓</td></tr>
          <tr><td>Image OCR</td><td>—</td><td>—</td><td>—</td><td>✓</td></tr>
          <tr><td>Pay via phone/email</td><td>—</td><td>✓</td><td>—</td><td>✓</td></tr>
          <tr><td>Bulk transfer</td><td>—</td><td>-</td><td>—</td><td>✓</td></tr>
        </table>
      </section>

      <!-- Slide 8: Traction -->
      <section class="slide" data-index="7">
        <h2>Traction & Roadmap</h2>
        <div class="columns">
          <div class="col"><strong>Current</strong>
            <p class="muted">Prototypes & UI mockups. Landing page + waitlist. Early API partner outreach.</p>
          </div>
          <div class="col"><strong>Next 6 months</strong>
            <p class="muted">MVP launch, 1,000 early adopters, formal PSP integration.</p>
          </div>
        </div>
      </section>

      <!-- Slide 9: GTM -->
      <section class="slide" data-index="8">
        <h2>Go-to-Market</h2>
        <ul class="muted">
          <li>Seed launch with campuses & SMEs</li>
          <li>Referral incentives and merchant partnerships</li>
          <li>Integration with POS agents and micro-influencers</li>
        </ul>
      </section>

      <!-- Slide 10: Ask -->
      <section class="slide" data-index="9">
        <h2>The Ask</h2>
        <p class="muted">Seeking funding, regulatory introductions, and API facilitation. Funds will be used for product development, licensing, and go-to-market activities.</p>

        <div style="margin-top:18px;display:flex;gap:10px;flex-wrap:wrap">
          <div class="tag">Product dev</div>
          <div class="tag">PSP integration</div>
          <div class="tag">Marketing & Launch</div>
        </div>

        <footer class="small">HaystackPay — Pay without typing. Talk. Tap. Snap. Done.</footer>
      </section>

      <nav class="nav" aria-label="Slide controls">
        <div class="controls">
          <button id="prev">◀ Prev</button>
          <button id="next" class="primary">Next ▶</button>
        </div>
        <div class="dots" id="dots" aria-hidden="false"></div>
      </nav>

    </main>
  </div>

  <script>
    const slides = Array.from(document.querySelectorAll('.slide'));
    const dots = document.getElementById('dots');
    let idx = 0;

    function renderDots(){
      dots.innerHTML='';
      slides.forEach((s,i)=>{
        const d = document.createElement('div'); d.className='dot'+(i===idx?' active':''); d.addEventListener('click',()=>go(i)); dots.appendChild(d);
      })
    }
    function show(i){
      slides.forEach(s=>s.classList.remove('active'));
      slides[i].classList.add('active'); idx=i; renderDots();
    }
    function go(i){ if(i<0) i=0; if(i>slides.length-1) i=slides.length-1; show(i); }
    document.getElementById('next').addEventListener('click',()=>go(idx+1));
    document.getElementById('prev').addEventListener('click',()=>go(idx-1));
    // keyboard
    window.addEventListener('keydown',e=>{ if(e.key==='ArrowRight') go(idx+1); if(e.key==='ArrowLeft') go(idx-1); });
    // init
    renderDots(); show(0);
  </script>
</body>
</html>