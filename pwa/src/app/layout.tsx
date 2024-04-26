import { Theme } from "@radix-ui/themes";
import "@radix-ui/themes/styles.css";
import type { Metadata } from "next";
import { SessionProvider } from "next-auth/react";
import { Inter } from "next/font/google";
import { PropsWithChildren } from "react";
import "../assets/globals.css";
import Layout from "../components/ui/layout/Layout";
import { auth } from "./auth";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "dungap - Homelab Command Center",
  description: "The Command Center for Homelab",
};

export default async function RootLayout({ children }: PropsWithChildren) {
  const session = await auth();

  return (
    <html lang="en">
      <body className={inter.className}>
        <SessionProvider session={session}>
          <Theme>
            <Layout>{children}</Layout>
          </Theme>
        </SessionProvider>
      </body>
    </html>
  );
}
