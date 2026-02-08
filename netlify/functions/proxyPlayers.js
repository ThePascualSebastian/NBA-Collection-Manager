export async function handler(event, context) {
  // Remove the function prefix from the path
  const path = event.path.replace("/.netlify/functions/proxyPlayers", "");
  const query = event.queryStringParameters
    ? "?" + new URLSearchParams(event.queryStringParameters)
    : "";
  const url = `https://nba-collection-manager.infinityfreeapp.com/api${path}${query}`;

  try {
    const response = await fetch(url); // native fetch
    const data = await response.text(); // get text first
    return {
      statusCode: response.status,
      headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
      },
      body: data,
    };
  } catch (err) {
    return {
      statusCode: 500,
      body: JSON.stringify({ error: "Failed to fetch API", details: err.message }),
    };
  }
}
