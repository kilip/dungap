import { Session } from "@remix-run/node";

const MIME_TYPE = "application/ld+json";
const ENTRYPOINT = process.env.API_ENTRYPOINT ?? 'http://api';

export interface FetchResponse<TData> {
  hubURL: string | null;
  data: TData;
  text: string;
}

const extractHubURL = (response: Response): null | string => {
  const linkHeader = response.headers.get("Link");
  if (!linkHeader) return null;

  const matches = linkHeader.match(
    /<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/
  );

  return matches && matches[ 1 ] ? (new URL(matches[ 1 ], ENTRYPOINT)).toString() : null;
};

interface Violation {
  message: string;
  propertyPath: string;
}


export interface FetchError {
  message: string;
  status: string;
  fields: { [ key: string ]: string; };
}

export async function fetchApi<TData>(
  id: string,
  init: RequestInit = {},
  session: Session
): Promise<FetchResponse<TData> | undefined> {


  if (typeof init.headers === "undefined") init.headers = {};

  if (typeof init.method === 'undefined') init.method = 'GET';

  let defaultHeaders = {};
  if (init.body) {
    defaultHeaders = {
      'Accept': MIME_TYPE,
      'Content-Type': init.method === 'PATCH' ? 'application/merge-patch+json' : MIME_TYPE,
    };
  }

  if (session && session.get('user')) {

    defaultHeaders = {
      ...defaultHeaders,
      Authorization: `Bearer ${session.get('user').accessToken}`
    };
  }

  init.headers = {
    ...defaultHeaders,
    ...init.headers,
  };

  const r = await fetch(ENTRYPOINT + id, init);
  if (r.status === 204) return undefined;

  const text = await r.text();
  const json = JSON.parse(text);

  if (r.ok) {
    return {
      hubURL: extractHubURL(r),
      data: json,
      text
    };
  }

  const errorMessage = json[ 'hydra.title' ];
  const status = json[ "hydra:description" ] || r.statusText;

  if (!json.violations) throw Error(errorMessage);

  const fields: { [ key: string ]: string; } = {};
  json.violations.map(
    (violation: Violation) =>
      (fields[ violation.propertyPath ] = violation.message)
  );

  throw { message: errorMessage, status, fields } as FetchError;
}