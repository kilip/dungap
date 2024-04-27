import Authentik from "@auth/core/providers/authentik";
import NextAuth, { Session, User } from "next-auth";
import { AdapterUser } from "next-auth/adapters";
import { JWT } from "next-auth/jwt";
import { Provider } from "next-auth/providers";
import invariant from "tiny-invariant";
import { config } from "../util/config";

declare module "next-auth" {

  interface Session {
    accessToken?: string;
    error?: string;
    user?: User;
  }
}

declare module "next-auth/jwt" {
  interface JWT {
    refreshToken?: string;
    accessToken?: string;
    accessTokenExpires?: number;
    error?: string;
    user?: User | AdapterUser;
  }
}

const providers: Provider[] = [
  /*
  Credentials({
    credentials: {
      username: {},
      password: {},
    },
    authorize: async (credentials) => {
      let user = null;

      const url = config.entrypoint + '/login-check';

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          username: credentials.username,
          password: credentials.password
        })
      });

      if (response.ok) {
        const json = await response.json();
        user = json.user;
        user.token = json.token;
      }

      if (!user) {
        throw new Error('User not found');
      }
      return user;
    }
  }),
  Google
  */
  Authentik({
    clientId: config.authentikId,
    clientSecret: config.authentikSecret,
    issuer: config.authentikIssuer,
    authorization: {
      params: {
        scope: "openid profile email offline_access"
      }
    }
  })
];

export const providerMap = providers.map((provider) => {
  if (typeof provider === "function") {
    const providerData = provider();
    return { id: providerData.id, name: providerData.name };
  } else {
    return { id: provider.id, name: provider.name };
  }
});

async function refreshAccessToken(token: JWT): Promise<JWT> {
  try {
    const url = `https://authentik.itstoni.com/application/o/token/?` +
      new URLSearchParams({
        clientId: config.authentikId,
        clientSecret: config.authentikSecret,
        grant_type: 'refresh_token',
        refresh_token: token.refreshToken as string
      });
    const response = await fetch(url, {
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      method: "POST"
    });
    const refreshedTokens = await response.json();

    if (!response.ok) {
      throw refreshedTokens;
    }

    return {
      ...token,
      accessToken: refreshedTokens.access_token,
      accessTokenExpires: Date.now() + refreshedTokens.expires_in * 1000,
      refreshToken: refreshedTokens.refresh_token ?? token.refreshToken,
      error: undefined
    };

  } catch (error) {
    return {
      ...token,
      error: 'RefreshAccessTokenError'
    };
  }

}

export const { handlers: { GET, POST }, signIn, signOut, auth } = NextAuth({
  pages: {
    // signIn: '/auth/login'
  },
  providers,
  callbacks: {
    async jwt({ token, user, account }): Promise<JWT> {
      if (account && user) {
        invariant(account.expires_in);
        return {
          accessToken: account.access_token,
          accessTokenExpires: Date.now() + account.expires_in * 1000,
          refreshToken: account.refresh_token,
          error: undefined,
          user,
        };
      }
      if (token.accessTokenExpires && (Date.now() < token.accessTokenExpires)) {
        return token;
      }

      return await refreshAccessToken(token);
    },
    session({ token, session }): Session {
      if (token) {
        session.user = token.user as User & AdapterUser;
        session.accessToken = token.accessToken;
        session.error = token.error;
      }
      return session;
    }
  }
});
