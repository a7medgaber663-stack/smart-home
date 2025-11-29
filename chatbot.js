import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

window.openBot = () => {
  createChat({
    webhookUrl: 'https://catbot17.app.n8n.cloud/webhook/330a1fa3-35e6-4f57-bae1-4975942d2d84/chat'
  });
};
