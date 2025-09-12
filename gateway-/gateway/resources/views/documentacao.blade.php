<x-app-layout :route="'Documentação API PIX'">
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.css" rel="stylesheet" />

    <style>
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      h1, h2, h4 {
        color: #1d1d1f;
      }

      .method {
        background: #10b981;
        border-radius: 6px;
        padding: 4px 10px;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        text-transform: uppercase;
      }

      code {
        font-family: 'Fira Code', 'Courier New', monospace;
        font-size: 1rem;
      }

      pre {
        overflow-x: auto;
        padding: 1rem;
        border-radius: 10px;
        background-color: #1e1e1e;
        color: #e0e0e0;
      }

      .endpoint-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
      }

      .endpoint-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .endpoint-section {
        margin-top: 2rem;
      }

      @media (max-width: 768px) {
        .endpoint-card {
          padding: 1.5rem;
        }

        .endpoint-title {
          font-size: 1.2rem;
        }

        h1 {
          font-size: 1.5rem;
        }

        code {
          font-size: 0.95rem;
        }
      }
      .icon-doc {
        color: var(--color-gateway) !important;
      }
      .endpoint-url {
        color: var(--color-gateway) !important;
        font-weight: bold;
        border: 1px solid var(--color-gateway) !important;
        padding: 5px;
        padding-right: 15px;
        border-radius: 8px;
      }
    </style>

    <div class="main-content app-content">
      <div class="px-3 container-fluid px-md-5">
        <div class="mb-5">
          <h1 class="display-5"><i class="icon-doc fa-solid fa-file"></i>&nbsp;Documentação da API PIX</h1>
          <p class="text-muted">Endpoints para Depósito e Saque via PIX com Webhooks.</p>
        </div>

        <!-- PIX IN -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-download"></i> Depósito (PIX IN) <span class="method">POST</span></div>
          <p>Gera um pagamento via QrCode para depósito.</p>
          <p><strong>Endpoint:</strong> <code class="endpoint-url">{{ env('APP_URL') }}/api/wallet/deposit/payment</code></p>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-lock"></i>&nbsp; Cabeçalhos (Headers)</h4>
            <pre class="line-numbers"><code class="language-json">{
    "Content-Type": "application/json",
    "Accept": "application/json"
  }</code></pre>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Corpo da Requisição</h4>
            <pre class="line-numbers"><code class="language-json">{
    "token": "seu_token",
    "secret": "seu_secret",
    "postback": "rota_callback",
    "amount": 100.00,
    "debtor_name": "Nome",
    "email": "redacted@example.invalid",
    "debtor_document_number": "CPF",
    "phone": "Telefone",
    "method_pay": "pix"
  }</code></pre>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-download"></i>&nbsp; Resposta</h4>
            <pre class="line-numbers"><code class="language-json">{
    "idTransaction": "TX123",
    "qrcode": "código copia e cola",
    "qr_code_image_url": "url da imagem"
  }</code></pre>
          </div>
        </div>

        <!-- Webhook PIX IN -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Webhook PIX IN</div>
          <p>Retorno automático na rota <code>postback</code> informada na criação do depósito.</p>

          <h4><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Exemplo de retorno</h4>
          <pre class="line-numbers"><code class="language-json">{
    "status": "paid",
    "idTransaction": "TX123",
    "typeTransaction": "PIX"
  }</code></pre>
        </div>

        <!-- PIX OUT -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Saque (PIX OUT) <span class="method">POST</span></div>
          <p>Realiza um saque para uma chave PIX.</p>
          <p><strong>Endpoint:</strong> <code class="endpoint-url">{{ env('APP_URL') }}/api/pixout</code></p>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-lock"></i>&nbsp; Cabeçalhos (Headers)</h4>
            <pre class="line-numbers"><code class="language-json">{
    "Content-Type": "application/json",
    "Accept": "application/json"
  }</code></pre>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Corpo da Requisição</h4>
            <p><strong>pixKeyType:</strong> 'cpf' | 'cnpj' | 'email' | 'phone' | 'random'</p>
            <pre class="line-numbers"><code class="language-json">{
    "token": "seu_token",
    "secret": "seu_secret",
    "baasPostbackUrl": "url_callback",
    "amount": 100.00,
    "pixKey": "chave_pix",
    "pixKeyType": "cpf"
  }</code></pre>
          </div>

          <div class="endpoint-section">
            <h4><i class="icon-doc fa-solid fa-download"></i>&nbsp; Resposta</h4>
            <pre class="line-numbers"><code class="language-json">{
    "id": "b522a295-e404...",
    "amount": 100,
    "pixKey": "chave",
    "pixKeyType": "cpf",
    "withdrawStatusId": "PendingProcessing",
    "createdAt": "2025-04-19T20:04:53.166Z",
    "updatedAt": "2025-04-19T20:04:53.166Z"
  }</code></pre>
          </div>
        </div>

        <!-- Webhook PIX OUT -->
        <div class="endpoint-card">
          <div class="endpoint-title"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Webhook PIX OUT</div>
          <p>Retorno automático na rota <code>baasPostbackUrl</code> informada na requisição de saque.</p>

          <h4><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Exemplo de retorno</h4>
          <pre class="line-numbers"><code class="language-json">{
    "status": "paid",
    "idTransaction": "TX123",
    "typeTransaction": "PAYMENT"
  }</code></pre>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.js"></script>
  </x-app-layout>
