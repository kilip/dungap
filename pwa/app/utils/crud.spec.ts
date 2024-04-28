import { Session } from "@remix-run/node";
import { describe, expect, it, vi } from "vitest";
import { mock } from 'vitest-mock-extended';
import { RequestMethod } from "~/types/common";
import { Device } from "~/types/device";
import { FetchResponse, fetchApi } from "./api";
import { Payload, create, remove, update } from "./crud";

vi.mock('./api');
const session = mock<Session>();


describe('create()', () => {
  it('should create new object', async () => {
    const api = await import('./api');
    const expectedResponse = {
      data: {
      },
      hubURL: 'some-url'
    };

    api.fetchApi = vi.fn().mockResolvedValue(expectedResponse);

    const payload: Payload<Device> = {
      nickname: 'test',
      ipAddress: 'ip',
      macAddress: 'mac',
    };
    const response: FetchResponse<Device> | undefined = await create('/devices', payload, session);

    expect(api.fetchApi).toBeCalledWith(
      '/devices',
      {
        method: RequestMethod.POST,
        body: JSON.stringify(payload),
      },
      session
    );
    expect(response).toBeDefined();
    expect(response).toBe(expectedResponse);
  });
});

describe('update()', () => {
  it('should update object', async () => {
    const api = await import('./api');
    const expectedResponse = {
      data: {
      },
      hubURL: 'some-url'
    };
    api.fetchApi = vi.fn().mockResolvedValue(expectedResponse);
    const payload = { nickname: 'test' };
    const r: FetchResponse<Device> | undefined = await update('/devices/id', payload, session);

    expect(api.fetchApi).toHaveBeenCalledOnce();
    expect(api.fetchApi).toBeCalledWith(
      '/devices/id',
      {
        method: 'PATCH',
        body: JSON.stringify(payload)
      },
      session
    );
    expect(r).toBeDefined();
    expect(r).toBe(expectedResponse);
  });
});

describe('remove()', () => {
  it('should delete object', async () => {
    const api = await import('./api');
    api.fetchApi = vi.fn().mockResolvedValue(undefined);

    const r = await remove('/devices/id', session);
    expect(fetchApi).toHaveBeenCalledOnce();
    expect(fetchApi).toHaveBeenCalledWith('/devices/id', {
      method: 'DELETE'
    }, session);
    expect(r).toBeUndefined();
  });
});