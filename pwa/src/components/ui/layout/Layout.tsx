"use client";

import { PropsWithChildren } from "react";
import Sidebar from "../Sidebar";

export default function Layout({ children }: PropsWithChildren) {
  return (
    <div className="min-h-screen bg-slate-200 lg:grid lg:grid-cols-app">
      <Sidebar />
      <main className="max-w-[100vw] px-4 pb-12 pt-4 lg:col-start-2 lg:px-8 lg:pt-4">
        {/* main content start */}
        {children}
        {/* main content end */}
      </main>
    </div>
  );
}
