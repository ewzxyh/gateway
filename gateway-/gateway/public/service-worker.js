// Service Worker corrigido - não intercepta APIs
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);
  
  // NÃO interceptar rotas de API
  if (url.pathname.startsWith('/api/')) {
    return;
  }
  
  // NÃO interceptar métodos que não sejam GET
  if (event.request.method !== 'GET') {
    return;
  }
  
  // Para outras requisições GET, pode implementar cache se necessário
  // Por enquanto, deixar passar normalmente
});

// Força ativação imediata do service worker atualizado
self.addEventListener('install', event => {
  console.log('Service Worker instalado');
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  console.log('Service Worker ativado');
  event.waitUntil(self.clients.claim());
});
