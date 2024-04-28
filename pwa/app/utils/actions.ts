import { Session } from "@remix-run/node";
import { RequestMethod } from "~/types/common";
import { fetchApi } from "~/utils/api";

export type Payload<TData> = Omit<TData, "@id" | "id" | "_id">;

export function create<TData>(id: string, payload: Payload<TData>, session: Session) {
  const init = {
    method: RequestMethod.POST,
    body: JSON.stringify(payload)
  };

  return fetchApi<TData>(id, init, session);
}

export async function update<TData>(id: string, payload: Partial<Payload<TData>>, session: Session) {
  const init = {
    method: 'PATCH',
    body: JSON.stringify(payload)
  };
  return fetchApi<TData>(id, init, session);
}

export async function remove(id: string, session: Session) {
  const init = {
    method: 'DELETE',
  };
  return fetchApi(id, init, session);
}
