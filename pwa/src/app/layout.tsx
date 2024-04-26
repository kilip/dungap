import "@/assets/globals.css";
import Layout from "@/components/ui/layout/Layout";
import { Theme } from "@radix-ui/themes";
import "@radix-ui/themes/styles.css";
import type { Metadata } from "next";
import { Inter } from "next/font/google";
import { PropsWithChildren } from "react";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "dungap - Homelab Command Center",
  description: "The Command Center for Homelab",
};

export default function RootLayout({ children }: PropsWithChildren) {
  return (
    <html lang="en">
      <body className={inter.className}>
        <Theme>
          <Layout>{children}</Layout>
        </Theme>
      </body>
    </html>
  );
}
