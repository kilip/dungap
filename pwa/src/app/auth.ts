import { config } from "@/util/config";
import NextAuth, { DefaultSession, Session } from "next-auth";
import { Provider } from "next-auth/providers";
import Credentials from "next-auth/providers/credentials";
import Google from "next-auth/providers/google";

declare module "next-auth" {
  interface User {
    roles: string[];
    token: string;
  }

  interface Session {
    accessToken: string;
    userId: string;
    user: {
      token: string;
    } & DefaultSession[ 'user' ];
  }
}

const providers: Provider[] = [
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
];

export const providerMap = providers.map((provider) => {
  if (typeof provider === "function") {
    const providerData = provider();
    return { id: providerData.id, name: providerData.name };
  } else {
    return { id: provider.id, name: provider.name };
  }
});

export const { handlers: { GET, POST }, signIn, signOut, auth } = NextAuth({
  pages: {
    // signIn: '/auth/login'
  },
  providers,
  callbacks: {
    jwt({ token, user, account }) {
      if (user) {
        token.id = user.id;
        token.accessToken = user.token;
      } else if (account) {
        token.accessToken = account.access_token;
        if (account.expires_in) {
          token.exp = Math.floor(Date.now() / 1000 + account.expires_in);
        }
        token.refreshToken = account.refresh_token;

      }
      return token;
    },
    session({ token, session }): Session {
      session.accessToken = token.accessToken as string;
      session.userId = token.id as string;
      return session;
    }
  }
});
