const MAX_BODY_BYTES = 32 * 1024;
const MAX_TURNSTILE_TOKEN_LENGTH = 2048;
const SITEVERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

const INTENTS = {
  'Project enquiry': {
    subjectLabel: 'Project enquiry',
    fields: [
      'Company or organization',
      'Telephone',
      'Project type',
      'Ideal timing',
    ],
  },
  'Game development conversation': {
    subjectLabel: 'Game development conversation',
    fields: [
      'Studio or team',
      'Project or build link',
      'Game development topic',
      'Project stage',
    ],
  },
} as const;

type ContactIntent = keyof typeof INTENTS;

interface ContactSubmission {
  intent: ContactIntent;
  name: string;
  email: string;
  subject: string;
  message: string;
  details: Array<[label: string, value: string]>;
}

class RequestError extends Error {
  constructor(
    readonly status: number,
    message: string,
  ) {
    super(message);
  }
}

export default {
  async fetch(request: Request, env: Env): Promise<Response> {
    const url = new URL(request.url);

    if (request.method === 'GET' && url.pathname === '/healthz') {
      return new Response('ok', {
        headers: {
          'Cache-Control': 'no-store',
          'Content-Type': 'text/plain; charset=utf-8',
        },
      });
    }

    if (request.method !== 'POST' || url.pathname !== '/') {
      return new Response('Not found', {
        status: 404,
        headers: { 'Cache-Control': 'no-store' },
      });
    }

    const submissionId = crypto.randomUUID();

    try {
      validateOrigin(request, env.EXPECTED_ORIGIN);
      const params = await readFormBody(request);

      if (readOptionalField(params, 'Website', 200) !== '') {
        logEvent('honeypot_rejected', submissionId, request);
        return successRedirect(env.SUCCESS_URL);
      }

      const turnstileToken = readRequiredField(
        params,
        'cf-turnstile-response',
        MAX_TURNSTILE_TOKEN_LENGTH,
      );
      const submission = parseSubmission(params);

      await verifyTurnstile(turnstileToken, request, env);
      const result = await env.EMAIL.send(buildEmail(submission, env));

      console.log(JSON.stringify({
        event: 'contact_email_sent',
        submissionId,
        intent: submission.intent,
        messageId: result.messageId,
        rayId: request.headers.get('CF-Ray'),
      }));

      return successRedirect(env.SUCCESS_URL);
    } catch (error) {
      if (error instanceof RequestError) {
        logEvent('contact_request_rejected', submissionId, request, {
          status: error.status,
          reason: error.message,
        });
        return errorPage(error.status, error.message, env.CONTACT_URL);
      }

      console.error(JSON.stringify({
        event: 'contact_request_failed',
        submissionId,
        error: error instanceof Error ? error.message : 'Unknown error',
        rayId: request.headers.get('CF-Ray'),
      }));
      return errorPage(
        503,
        'I could not send your message just now. Please try again in a moment or email me directly.',
        env.CONTACT_URL,
      );
    }
  },
} satisfies ExportedHandler<Env>;

function validateOrigin(request: Request, expectedOrigin: string): void {
  const origin = request.headers.get('Origin');
  if (origin !== expectedOrigin) {
    throw new RequestError(403, 'This form submission did not come from the portfolio site.');
  }
}

async function readFormBody(request: Request): Promise<URLSearchParams> {
  const contentType = request.headers.get('Content-Type') ?? '';
  if (!contentType.toLowerCase().startsWith('application/x-www-form-urlencoded')) {
    throw new RequestError(415, 'The form submission format was not supported.');
  }

  const contentLength = Number(request.headers.get('Content-Length'));
  if (Number.isFinite(contentLength) && contentLength > MAX_BODY_BYTES) {
    throw new RequestError(413, 'The form submission was too large.');
  }

  if (!request.body) {
    throw new RequestError(400, 'The form submission was empty.');
  }

  const reader = request.body.getReader();
  const chunks: Uint8Array[] = [];
  let totalBytes = 0;

  while (true) {
    const { done, value } = await reader.read();
    if (done) break;
    totalBytes += value.byteLength;
    if (totalBytes > MAX_BODY_BYTES) {
      await reader.cancel();
      throw new RequestError(413, 'The form submission was too large.');
    }
    chunks.push(value);
  }

  const body = new Uint8Array(totalBytes);
  let offset = 0;
  for (const chunk of chunks) {
    body.set(chunk, offset);
    offset += chunk.byteLength;
  }

  return new URLSearchParams(new TextDecoder().decode(body));
}

function parseSubmission(params: URLSearchParams): ContactSubmission {
  const intentValue = readRequiredField(params, 'Inquiry type', 80);
  if (!isContactIntent(intentValue)) {
    throw new RequestError(400, 'The selected conversation type was not recognized.');
  }

  const name = readRequiredField(params, 'name', 120);
  rejectHeaderBreaks(name, 'name');
  const email = readRequiredField(params, 'email', 254).toLowerCase();
  if (!isValidEmail(email)) {
    throw new RequestError(400, 'Please enter a valid email address.');
  }

  const subject = readOptionalField(params, 'Subject', 160);
  rejectHeaderBreaks(subject, 'Subject');
  const message = readRequiredField(params, 'Message', 8_000);
  const details = INTENTS[intentValue].fields
    .map((label): [string, string] => [label, readOptionalField(params, label, 500)])
    .filter((entry) => entry[1] !== '');

  return { intent: intentValue, name, email, subject, message, details };
}

function isContactIntent(value: string): value is ContactIntent {
  return Object.hasOwn(INTENTS, value);
}

function isValidEmail(value: string): boolean {
  return !value.includes('\r')
    && !value.includes('\n')
    && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

function rejectHeaderBreaks(value: string, name: string): void {
  if (value.includes('\r') || value.includes('\n')) {
    throw new RequestError(400, `The ${name} field contained an invalid line break.`);
  }
}

function readRequiredField(params: URLSearchParams, name: string, maxLength: number): string {
  const value = readOptionalField(params, name, maxLength);
  if (value === '') {
    throw new RequestError(400, `The ${name} field is required.`);
  }
  return value;
}

function readOptionalField(params: URLSearchParams, name: string, maxLength: number): string {
  const values = params.getAll(name);
  if (values.length > 1) {
    throw new RequestError(400, `The ${name} field was submitted more than once.`);
  }

  const value = (values[0] ?? '').trim();
  if (value.length > maxLength) {
    throw new RequestError(400, `The ${name} field was too long.`);
  }
  return value;
}

async function verifyTurnstile(token: string, request: Request, env: Env): Promise<void> {
  const body = {
    secret: env.TURNSTILE_SECRET_KEY,
    response: token,
    remoteip: request.headers.get('CF-Connecting-IP') ?? undefined,
    idempotency_key: crypto.randomUUID(),
  };

  let response: Response;
  try {
    response = await fetch(SITEVERIFY_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
      signal: AbortSignal.timeout(5_000),
    });
  } catch {
    throw new RequestError(503, 'The anti-spam check is temporarily unavailable. Please try again.');
  }

  if (!response.ok) {
    throw new RequestError(503, 'The anti-spam check is temporarily unavailable. Please try again.');
  }

  const result: unknown = await response.json();
  if (!isRecord(result) || result.success !== true) {
    throw new RequestError(400, 'The anti-spam check could not be verified. Please try again.');
  }

  if (result.hostname !== env.EXPECTED_HOSTNAME) {
    throw new RequestError(400, 'The anti-spam check came from an unexpected site.');
  }

  if (result.action !== env.TURNSTILE_ACTION) {
    throw new RequestError(400, 'The anti-spam check did not match this form.');
  }
}

function isRecord(value: unknown): value is Record<string, unknown> {
  return typeof value === 'object' && value !== null && !Array.isArray(value);
}

function buildEmail(submission: ContactSubmission, env: Env): EmailMessageBuilder {
  const intent = INTENTS[submission.intent];
  const summary = submission.subject === '' ? 'No subject provided' : submission.subject;
  const subject = `[Portfolio] ${intent.subjectLabel}: ${summary}`;
  const detailText = submission.details
    .map(([label, value]) => `${label}: ${value}`)
    .join('\n');
  const detailHtml = submission.details
    .map(([label, value]) => `<dt><strong>${escapeHtml(label)}</strong></dt><dd>${escapeHtml(value)}</dd>`)
    .join('');

  return {
    to: env.CONTACT_RECIPIENT,
    from: {
      email: env.CONTACT_SENDER,
      name: 'Patrick Barnhardt portfolio',
    },
    replyTo: {
      email: submission.email,
      name: submission.name,
    },
    subject,
    text: [
      `Inquiry type: ${submission.intent}`,
      `Name: ${submission.name}`,
      `Email: ${submission.email}`,
      `Subject: ${summary}`,
      detailText,
      '',
      'Message:',
      submission.message,
    ].filter((line, index, lines) => line !== '' || lines[index - 1] !== '').join('\n'),
    html: `
      <h1>${escapeHtml(intent.subjectLabel)}</h1>
      <dl>
        <dt><strong>Name</strong></dt><dd>${escapeHtml(submission.name)}</dd>
        <dt><strong>Email</strong></dt><dd><a href="mailto:${escapeHtml(submission.email)}">${escapeHtml(submission.email)}</a></dd>
        <dt><strong>Subject</strong></dt><dd>${escapeHtml(summary)}</dd>
        ${detailHtml}
      </dl>
      <h2>Message</h2>
      <p style="white-space: pre-wrap">${escapeHtml(submission.message)}</p>
    `,
  };
}

function escapeHtml(value: string): string {
  return value.replace(/[&<>"']/g, (character) => {
    const entities: Record<string, string> = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;',
    };
    return entities[character] ?? character;
  });
}

function successRedirect(successUrl: string): Response {
  return new Response(null, {
    status: 303,
    headers: {
      'Cache-Control': 'no-store',
      Location: successUrl,
    },
  });
}

function errorPage(status: number, message: string, contactUrl: string): Response {
  const body = `<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message not sent</title>
    <style>
      :root { color-scheme: dark; font-family: system-ui, sans-serif; }
      body { min-height: 100vh; margin: 0; display: grid; place-items: center; background: #181413; color: #f8f4ef; }
      main { width: min(34rem, calc(100% - 3rem)); padding: 2rem; border-top: .35rem solid #a6192e; background: #241f1d; }
      h1 { margin-top: 0; }
      p { line-height: 1.6; }
      a { color: #fff; }
    </style>
  </head>
  <body>
    <main>
      <h1>Message not sent</h1>
      <p>${escapeHtml(message)}</p>
      <p><a href="${escapeHtml(contactUrl)}">Return to the contact form</a></p>
    </main>
  </body>
</html>`;

  return new Response(body, {
    status,
    headers: {
      'Cache-Control': 'no-store',
      'Content-Security-Policy': "default-src 'none'; style-src 'unsafe-inline'; base-uri 'none'; form-action 'none'",
      'Content-Type': 'text/html; charset=utf-8',
      'Referrer-Policy': 'no-referrer',
      'X-Content-Type-Options': 'nosniff',
    },
  });
}

function logEvent(
  event: string,
  submissionId: string,
  request: Request,
  detail: Record<string, unknown> = {},
): void {
  console.log(JSON.stringify({
    event,
    submissionId,
    rayId: request.headers.get('CF-Ray'),
    ...detail,
  }));
}
