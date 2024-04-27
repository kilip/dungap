
export const config = {
  entrypoint: process.env.NEXT_PUBLIC_ENTRYPOINT ?? 'https://localhost',
  authentikId: process.env.AUTH_AUTHENTIK_ID ?? 'change-me',
  authentikSecret: process.env.AUTH_AUTHENTIK_SECRET ?? 'change-me',
  authentikIssuer: process.env.AUTH_AUTHENTIK_ISSUER ?? 'https://localhost/change/me'
};
