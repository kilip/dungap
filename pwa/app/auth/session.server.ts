import { createCookieSessionStorage } from "@remix-run/node";

const AUTH_SECRET = process.env.AUTH_SECRET ?? 's3cr3t';

export const sessionStorage = createCookieSessionStorage({
  cookie: {
    name: '_session',
    sameSite: 'lax',
    path: '/',
    httpOnly: true,
    secrets: [ AUTH_SECRET ],
    secure: process.env.NODE_ENV === 'production'
  }
});

export const { getSession, commitSession, destroySession } = sessionStorage;