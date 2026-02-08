import fetch from 'node-fetch';

export async function handler(event, context) {
  const url = 'https://nba-collection-manager.infinityfreeapp.com/api' + (event.path.replace('/.netlify/functions/proxyPlayers', '') || '');
  
  try {
    const res = await fetch(url + (event.queryStringParameters ? '?' + new URLSearchParams(event.queryStringParameters) : ''));
    const data = await res.text(); // use text first to avoid JSON errors
    return {
      statusCode: 200,
      headers: {
        'Content-Type': 'application/json'
      },
      body: data
    };
  } catch (err) {
    return {
      statusCode: 500,
      body: JSON.stringify({ error: 'Failed to fetch API' })
    };
  }
}
