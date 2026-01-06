
import fetch from "node-fetch";

const OMADA = process.env.OMADA_BASE;       // https://omada.domain.com
const OP_USER = process.env.OMADA_OP_USER;  // Hotspot Operator
const OP_PASS = process.env.OMADA_OP_PASS;

async function hotspotLogin() {
  const res = await fetch(`${OMADA}/api/v2/hotspot/login`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      name: OP_USER,
      password: OP_PASS
    })
  });

  const cookie = res.headers.get("set-cookie");
  if (!cookie) throw new Error("Hotspot login failed");
  return cookie.split(";")[0];
}

async function authorize(cookie, body) {
  const res = await fetch(`${OMADA}/portal/api/authorize`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Cookie": cookie
    },
    body: JSON.stringify({
      site: body.site,
      clientMac: body.clientMac,
      apMac: body.apMac,
      authType: 0
    })
  });

  return res.json();
}

export default async function handler(req, res) {
  if (req.method !== "POST") return res.status(405).end();

  try {
    const cookie = await hotspotLogin();
    const result = await authorize(cookie, req.body);

    res.json({ success: result?.errorCode === 0 });
  } catch (e) {
    res.status(500).json({ success: false });
  }
}
