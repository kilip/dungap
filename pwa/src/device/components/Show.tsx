"use client";
import { PageContainer } from "@/components/ui/PageContainer";
import { Device } from "@/types/device";
import { useMercure } from "@/util/mercure";
import { Box, Flex, Tabs, Text, TextField } from "@radix-ui/themes";
import { NextPage } from "next";
import { useSession } from "next-auth/react";
import { PowerOn } from "../actions";

export interface Props {
  data: Device;
  hubURL: string | null;
  powerOn: typeof PowerOn;
}

export const Show: NextPage<Props> = ({ data, hubURL, powerOn }) => {
  const { data: session, status } = useSession();
  const item = useMercure(data, hubURL);
  return (
    <PageContainer
      title={`Device: ${item.nickname || item.hostname || item.ipAddress}`}
    >
      <Tabs.Root defaultValue="action">
        <Tabs.List>
          <Tabs.Trigger value="action">Action</Tabs.Trigger>
          <Tabs.Trigger value="settings">Settings</Tabs.Trigger>
        </Tabs.List>
        <Box pt="3">
          <Tabs.Content value="action">
            <button
              onClick={async () =>
                await powerOn(item["@id"] as string, session)
              }
            >
              Power On
            </button>
          </Tabs.Content>
          <Tabs.Content value="settings">
            <form method="POST">
              <Flex align="start" direction="column" gap="4">
                <Box minWidth="300px">
                  <Text as="label" htmlFor="nickname" size="3" weight="bold">
                    Nickname
                  </Text>
                  <TextField.Root
                    id="nickname"
                    variant="surface"
                    radius="medium"
                    defaultValue={item.nickname}
                  />
                </Box>
                <Box minWidth="300px">
                  <Text as="label" htmlFor="hostname">
                    Hostname
                  </Text>
                  <TextField.Root
                    id="hostname"
                    variant="surface"
                    radius="medium"
                    defaultValue={item.hostname}
                  ></TextField.Root>
                </Box>
                <Box minWidth="300px">
                  <Text as="label" htmlFor="ipAddress">
                    IP Address
                  </Text>
                  <TextField.Root
                    id="ipAddress"
                    variant="surface"
                    radius="medium"
                    defaultValue={item.ipAddress}
                  ></TextField.Root>
                </Box>
              </Flex>
            </form>
          </Tabs.Content>
        </Box>
      </Tabs.Root>
    </PageContainer>
  );
};
