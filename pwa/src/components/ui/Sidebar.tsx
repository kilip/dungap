import * as Collapsible from "@radix-ui/react-collapsible";
import { Button } from "@radix-ui/themes";
import { Cog, ComputerIcon, Menu } from "lucide-react";
import { useSession } from "next-auth/react";
import NavItem from "./sidebar/NavItem";

export default function Sidebar() {
  const { data: session, status } = useSession();
  return (
    <Collapsible.Root className="fixed left-0 right-0 top-0 z-20 flex w-full flex-col gap-6 border-b border-zinc-200 bg-white p-4 data-[state=open]:bottom-0 dark:border-zinc-800 dark:bg-zinc-900 lg:static lg:right-auto lg:w-auto lg:border-r lg:px-5 lg:py-8 lg:data-[state=closed]:bottom-0">
      <Collapsible.Trigger asChild className="lg:hidden">
        <Button type="button" variant="ghost">
          <Menu className="h-6 w-6" />
        </Button>
      </Collapsible.Trigger>
      <Collapsible.CollapsibleContent
        forceMount
        className="flex flex-1 flex-col gap-6 data-[state=closed]:hidden lg:data-[state=closed]:flex"
      >
        <nav className="space-y-0.5">
          <NavItem title="Dashboard" icon={Cog} href="/dashboard" />
          <NavItem title="Devices" icon={ComputerIcon} href="/devices" />
          {status == "authenticated" ? (
            <NavItem
              title="Logout"
              icon={ComputerIcon}
              href="/api/auth/signout"
            />
          ) : (
            <NavItem
              title="Login"
              icon={ComputerIcon}
              href="/api/auth/signin"
            />
          )}
        </nav>
      </Collapsible.CollapsibleContent>
    </Collapsible.Root>
  );
}
