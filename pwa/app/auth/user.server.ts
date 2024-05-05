import { getSession } from "./session.server";

export async function getUserSession(request: Request) {
  return await getSession(request.headers.get('cookie'));
}

