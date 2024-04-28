import { PropsWithChildren, useState } from "react";
import Header from "../header";
import Sidebar from "../sidebar";

export function DefaultLayout({ children }: PropsWithChildren) {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <>
      {/* page wrapper start */}
      <div className="flex h-screen overflow-hidden">
        {/* sidebar start */}
        <Sidebar sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} />
        {/* sidebar end */}
        <div className="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
          <Header sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} />
          <main>
            <div className="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
              {children}
            </div>
          </main>
        </div>
      </div>
      {/* page wrapper start */}
    </>
  );
}
