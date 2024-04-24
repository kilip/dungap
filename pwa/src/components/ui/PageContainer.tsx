"use client";

import { Box, Container, Heading } from "@radix-ui/themes";
import { PropsWithChildren } from "react";

export interface Props extends PropsWithChildren {
  title: string;
}

export function PageContainer({ children, title }: Props) {
  return (
    <Box>
      <Container align="left">
        <Heading>{title}</Heading>
      </Container>
      <Container align="left" mt="4" className="rounded-md bg-white" p="4">
        {children}
      </Container>
    </Box>
  );
}
