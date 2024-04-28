import { Link, useLocation } from "@remix-run/react";
import { PropsWithChildren } from "react";

interface Props extends PropsWithChildren {
  linkTo: string;
}

export default function MenuItem({ children, linkTo }: Props) {
  const location = useLocation();

  return (
    <>
      <li>
        <Link
          to={linkTo}
          className={`group relative flex items-center gap-2.5 rounded-md px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4 ${
            location.pathname.includes(linkTo) && "bg-graydark dark:bg-meta-4"
          }`}
        >
          {children}
        </Link>
      </li>
    </>
  );
}
