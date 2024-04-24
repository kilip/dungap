import Link from "next/link";
import { ElementType } from "react";
import { twMerge } from "tailwind-merge";

interface NavItemProps {
  title: string;
  icon: ElementType;
  href?: string;
}

/*
      <ChevronDown
          className={twMerge(
            "ml-auto h-5 w-5 text-zinc-400 group-hover:text-violet-300",
            "dark:text-zinc-600"
          )}
      />
*/
export default function NavItem({ title, icon: Icon, href }: NavItemProps) {
  return (
    <Link
      href={href ?? "#"}
      className={twMerge(
        "group flex items-center gap-3 rounded px-3 py-2 hover:bg-violet-50",
        "dark:hover:bg-zinc-800"
      )}
    >
      <Icon className="h-5 w-5 text-zinc-500" />
      <span
        className={twMerge(
          "font-medium text-zinc-700 group-hover:text-violet-500",
          "dark:text-zinc-200 dark:group-hover:text-violet-300"
        )}
      >
        {title}
      </span>
    </Link>
  );
}