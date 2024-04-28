import {
  Links,
  Meta,
  Outlet,
  Scripts,
  ScrollRestoration,
  useLoaderData,
} from "@remix-run/react";

import { Theme } from "@radix-ui/themes";
import radixcss from "@radix-ui/themes/styles.css?url";
import { LinksFunction, LoaderFunctionArgs } from "@remix-run/node";
import { PropsWithChildren, useEffect, useState } from "react";
import tailwindcss from "./assets/main.css?url";
import satoshicss from "./assets/satoshi.css?url";
import { User } from "./auth/auth.server";
import { getSession } from "./auth/session.server";
import { UserContextType } from "./types/common";
import { GeneralErrorBoundary } from "./ui/ErrorBoundary";

export const links: LinksFunction = () => [
  { rel: "stylesheet", href: satoshicss },
  { rel: "stylesheet", href: tailwindcss },
  { rel: "stylesheet", href: radixcss },
];

export async function loader({ request }: LoaderFunctionArgs) {
  const session = await getSession(request.headers.get("cookie"));
  const user: User | null = session.get("user") ?? null;
  return {
    user,
  };
}

type RootContextType = UserContextType;

export default function App() {
  const { user: userSession } = useLoaderData<typeof loader>();
  const [user, setUser] = useState<User | null>(null);

  useEffect(() => {
    setUser(userSession);
  }, [userSession]);

  return (
    <Document>
      <Outlet context={{ user } satisfies RootContextType} />
    </Document>
  );
}

function Document({ children }: PropsWithChildren) {
  return (
    <html lang="en">
      <head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <Meta />
        <Links />
      </head>
      <body>
        <Theme>{children}</Theme>
        <ScrollRestoration />
        <Scripts />
      </body>
    </html>
  );
}

export function ErrorBoundary() {
  return (
    <Document>
      <GeneralErrorBoundary />
    </Document>
  );
}
