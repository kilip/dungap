import { Authenticator } from "remix-auth";
import { AuthentikProfile, AuthentikStrategy } from "./authentik.server";
import { sessionStorage } from "./session.server";

export interface User {
  profile: AuthentikProfile;
  accessToken: string;
  refreshToken?: string;
}

const authenticator = new Authenticator<User>(sessionStorage);

authenticator.use(new AuthentikStrategy(
  {
    clientID: process.env.AUTHENTIK_CLIENT_ID ?? "clientID",
    clientSecret: process.env.AUTHENTIK_CLIENT_SECRET ?? "clientSecret",
    audience: process.env.AUTHENTIK_AUDIENCE ?? "audience",
    baseURL: process.env.AUTHENTIK_BASE_URL ?? "baseURL",
    callbackURL: process.env.AUTHENTIK_CALLBACK_URL ?? "callbackURL",
    scope: [ "openid", "email", "profile", "offline_access" ]
  },
  async ({
    accessToken,
    refreshToken,
    profile,
  }) => {
    return {
      profile,
      accessToken,
      refreshToken,
    };
  }
));

export { authenticator };
