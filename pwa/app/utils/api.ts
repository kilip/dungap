import { Session } from "@remix-run/node";
import { MimeType } from "~/types/common";

export const MIME_TYPE = "application/ld+json";
export const ENTRYPOINT = process.env.API_ENTRYPOINT ?? 'https://localhost';

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

  let defaultHeaders = {
    Accept: MimeType.Hydra,
  };

  if (init.body) {
    defaultHeaders = Object.assign(defaultHeaders, {
      'Content-Type': init.method === 'PATCH' ? 'application/merge-patch+json' : MimeType.Hydra
    });
  }

  if (session && session.get('user')) {
    defaultHeaders = Object.assign(defaultHeaders, {
      Authorization: `Bearer ${session.get('user').accessToken}`
    });
  }

  init.headers = {
    ...defaultHeaders,
    ...init.headers,
  };

  const r = await fetch(ENTRYPOINT + id, init);
  if (r.status === 204) return undefined;

  const text = await r.text();
  let json = {
    'hydra:title': r.statusText,
    'hydra:description': r.statusText,
    violations: []
  };

  try {
    json = JSON.parse(text);
  } catch (error) {
  }


  if (r.status < 400) {
    const json = JSON.parse(text);
    return {
      hubURL: extractHubURL(r),
      data: json,
      text
    };
  }


  const errorMessage = json[ 'hydra:title' ];
  const status = json[ "hydra:description" ] || r.statusText;

  if (!json.violations) throw Error(errorMessage);

  const fields: { [ key: string ]: string; } = {};
  json.violations.map(
    (violation: Violation) =>
      (fields[ violation.propertyPath ] = violation.message)
  );

  throw { message: errorMessage, status, fields } as FetchError;
}