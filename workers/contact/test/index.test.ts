import { afterEach, describe, expect, it, vi } from 'vitest';
import worker from '../src/index';

const productionValues = {
  CONTACT_RECIPIENT: 'contact@patrickbarnhardt.info',
  CONTACT_SENDER: 'website@form-mail.patrickbarnhardt.info',
  EXPECTED_ORIGIN: 'https://www.patrickbarnhardt.info',
  EXPECTED_HOSTNAME: 'www.patrickbarnhardt.info',
  TURNSTILE_ACTION: 'portfolio-contact',
  SUCCESS_URL: 'https://www.patrickbarnhardt.info/contact/thanks/',
  CONTACT_URL: 'https://www.patrickbarnhardt.info/#contact',
  TURNSTILE_SECRET_KEY: 'test-secret',
} as const;

afterEach(() => {
  vi.unstubAllGlobals();
  vi.restoreAllMocks();
});

describe('contact Worker', () => {
  it('sends a differentiated project enquiry and redirects to the fixed success page', async () => {
    const { env, send } = createEnv();
    const siteverify = mockSiteverify();
    const params = baseParams('Project enquiry');
    params.set('Subject', 'Developer workflow review');
    params.set('Company or organization', 'Example Co');
    params.set('Project type', 'Developer tooling or workflow');

    const response = await worker.fetch(createRequest(params), env);

    expect(response.status).toBe(303);
    expect(response.headers.get('Location')).toBe(productionValues.SUCCESS_URL);
    expect(siteverify).toHaveBeenCalledOnce();
    expect(send).toHaveBeenCalledOnce();

    const email = requireBuilder(send.mock.calls[0]?.[0]);
    expect(email.subject).toBe('[Portfolio] Project enquiry: Developer workflow review');
    expect(email.text).toContain('Company or organization: Example Co');
    expect(email.text).toContain('Project type: Developer tooling or workflow');
    expect(email.text).not.toContain('Game development topic:');
    expect(email.replyTo).toEqual({ email: 'person@example.com', name: 'Test Person' });
  });

  it('sends game-development fields with the game-development subject', async () => {
    const { env, send } = createEnv();
    mockSiteverify();
    const params = baseParams('Game development conversation');
    params.set('Studio or team', 'Indie Team');
    params.set('Game development topic', 'Unreal Engine');
    params.set('Project stage', 'Prototype');

    const response = await worker.fetch(createRequest(params), env);

    expect(response.status).toBe(303);
    const email = requireBuilder(send.mock.calls[0]?.[0]);
    expect(email.subject).toBe('[Portfolio] Game development conversation: No subject provided');
    expect(email.text).toContain('Studio or team: Indie Team');
    expect(email.text).toContain('Game development topic: Unreal Engine');
    expect(email.text).toContain('Project stage: Prototype');
    expect(email.text).not.toContain('Company or organization:');
  });

  it('rejects a token with the wrong hostname before sending email', async () => {
    const { env, send } = createEnv();
    mockSiteverify({ hostname: 'localhost' });

    const response = await worker.fetch(createRequest(baseParams('Project enquiry')), env);

    expect(response.status).toBe(400);
    expect(await response.text()).toContain('unexpected site');
    expect(send).not.toHaveBeenCalled();
  });

  it('rejects a token with the wrong action before sending email', async () => {
    const { env, send } = createEnv();
    mockSiteverify({ action: 'different-form' });

    const response = await worker.fetch(createRequest(baseParams('Project enquiry')), env);

    expect(response.status).toBe(400);
    expect(await response.text()).toContain('did not match this form');
    expect(send).not.toHaveBeenCalled();
  });

  it('silently accepts the honeypot without validating or sending', async () => {
    const { env, send } = createEnv();
    const siteverify = mockSiteverify();
    const params = baseParams('Project enquiry');
    params.set('Website', 'https://spam.example');

    const response = await worker.fetch(createRequest(params), env);

    expect(response.status).toBe(303);
    expect(siteverify).not.toHaveBeenCalled();
    expect(send).not.toHaveBeenCalled();
  });

  it('rejects submissions from another origin', async () => {
    const { env, send } = createEnv();
    const siteverify = mockSiteverify();
    const request = createRequest(baseParams('Project enquiry'), 'https://attacker.example');

    const response = await worker.fetch(request, env);

    expect(response.status).toBe(403);
    expect(siteverify).not.toHaveBeenCalled();
    expect(send).not.toHaveBeenCalled();
  });

  it('rejects line breaks in email header fields', async () => {
    const { env, send } = createEnv();
    const siteverify = mockSiteverify();
    const params = baseParams('Project enquiry');
    params.set('Subject', 'Injected\r\nBcc: target@example.com');

    const response = await worker.fetch(createRequest(params), env);

    expect(response.status).toBe(400);
    expect(siteverify).not.toHaveBeenCalled();
    expect(send).not.toHaveBeenCalled();
  });
});

function createEnv() {
  const send = vi.fn(async (_message: EmailMessage | EmailMessageBuilder): Promise<EmailSendResult> => ({
    messageId: 'test-message-id',
  }));
  const env = {
    ...productionValues,
    EMAIL: { send },
  } satisfies Env;
  return { env, send };
}

function mockSiteverify(overrides: Record<string, unknown> = {}) {
  const siteverify = vi.fn(async () => Response.json({
    success: true,
    hostname: productionValues.EXPECTED_HOSTNAME,
    action: productionValues.TURNSTILE_ACTION,
    ...overrides,
  }));
  vi.stubGlobal('fetch', siteverify);
  return siteverify;
}

function baseParams(intent: 'Project enquiry' | 'Game development conversation'): URLSearchParams {
  return new URLSearchParams({
    'Inquiry type': intent,
    name: 'Test Person',
    email: 'person@example.com',
    Message: 'A detailed test message.',
    Website: '',
    'cf-turnstile-response': 'test-token',
  });
}

function createRequest(
  params: URLSearchParams,
  origin: string = productionValues.EXPECTED_ORIGIN,
): Request {
  return new Request('https://contact.patrickbarnhardt.info/', {
    method: 'POST',
    headers: { Origin: origin },
    body: params,
  });
}

function requireBuilder(message: EmailMessage | EmailMessageBuilder | undefined): EmailMessageBuilder {
  if (!message || !('subject' in message)) {
    throw new Error('Expected a structured email message');
  }
  return message;
}
