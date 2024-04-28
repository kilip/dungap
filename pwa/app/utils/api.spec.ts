import { Session } from "@remix-run/node";
import fetchMock from 'fetch-mock';
import { beforeEach, describe, expect, it } from "vitest";
import { mock } from "vitest-mock-extended";
import { MimeType, RequestMethod } from "~/types/common";
import { fetchApi } from "./api";

const session = mock<Session>();


describe('fetchApi()', () => {
  const device = {
    '@id': 'id',
    nickname: 'nickname',
    ipAddress: 'ip',
    macAddress: 'mac',
  };
  const hubUrl = 'https://mercure/hub';
  const user = {
    accessToken: 'token'
  };

  beforeEach(() => {
    fetchMock.reset();
  });

  it('should handle GET method as default request', async () => {
    fetchMock.get('path:/devices', {
      status: 200,
      headers: {
        Link: `<${hubUrl}>; rel=mercure`
      },
      body: [ device ]
    });

    const r = await fetchApi('/devices', {}, session);
    expect(r).toBeDefined();
    expect(r?.hubURL).toBe(hubUrl);
    expect(r?.data).toEqual([ device ]);
  });

  it('should set authorization on request', async () => {
    session.get.calledWith('user').mockReturnValue(user);
    fetchMock.get(
      {
        url: 'path:/foobar',
        headers: {
          Accept: MimeType.Hydra,
          Authorization: 'Bearer token'
        }
      },
      {
        status: 200,
        headers: {
          Link: `<${hubUrl}>; rel=mercure`
        },
        body: [ device ]
      }
    );

    const d = await fetchApi('/foobar', {}, session);
    expect(d).toBeDefined();
  });

  it('should set PATCH content-type', async () => {
    fetchMock.patch({
      url: 'path:/devices/id',
      headers: {
        'Content-Type': MimeType.Patch
      }
    }, {
      body: [ device ]
    });

    const init = {
      method: RequestMethod.PATCH,
      body: JSON.stringify({ id: 'payload' })
    };
    const d = await fetchApi('/devices/id', init, session);

    expect(d).toBeDefined();
  });

  it('should handle global error', async () => {
    fetchMock.get('path:/devices', 500);

    await expect(async () => {
      const d = await fetchApi('/devices', {}, session);
    }).rejects.toThrow(/^Internal Server Error$/);
  });

  it('should handle json violations error', async () => {
    const body = {
      "hydra:title": 'Bad input',
      "hydra:description": 'Validation error',
      "violations": [
        {
          propertyPath: 'path',
          message: 'hello'
        }
      ]
    };

    const response = new Response(JSON.stringify(body), {
      status: 401,
      statusText: 'Invalid input'
    });

    fetchMock.post('path:/devices', response);

    await expect(async () => {
      await fetchApi('/devices', { method: 'POST' }, session);
    }).rejects.toThrow(/^Bad input$/);
  });
});