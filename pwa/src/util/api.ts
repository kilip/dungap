import { Session } from "next-auth";

export async function api<T>(url: string, payload: any, init: RequestInit, session: Session): Promise<T | undefined> {
  init = {
    ...init,
    headers: {
      Authorization: `Bearer ${session.accessToken}`
    }
  };
  const response = await fetch(url, init);

  if (response.ok) {
    return response.json as T;
  }

  return undefined;
}

export async function patchApi<T>(url: string, payload: any, session: Session): Promise<T | undefined> {
  return api(
    url,
    payload, {
    headers: {
      'Content-Type': 'application/merge-patch+json',
    }

  }, session);
}
