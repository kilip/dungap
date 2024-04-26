"use client";

import { Heading } from "@radix-ui/themes";
import { PropsWithChildren } from "react";

export function PageHeader({ children }: PropsWithChildren) {
  return <Heading>{children}</Heading>;
}
