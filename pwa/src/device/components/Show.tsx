"use client";
import TextInput from "@/components/ui/form/TextInput";
import { ActionResponse } from "@/types/actions";
import { Box, Button, Flex, Tabs, Text } from "@radix-ui/themes";
import { NextPage } from "next";
import { Session } from "next-auth";
import { useSession } from "next-auth/react";
import { useState } from "react";
import { PageContainer } from "../../components/ui/PageContainer";
import { powerOff, powerOn } from "../../device/actions";
import { Device } from "../../types/device";
import { useMercure } from "../../util/mercure";

export interface Props {
  data: Device;
  hubURL: string | null;
}

export const Show: NextPage<Props> = ({ data, hubURL }) => {
  const { data: session, status } = useSession();
  const item = useMercure(data, hubURL);
  const [actionResponse, setActionResponse] = useState<ActionResponse>({
    error: undefined,
    success: undefined,
  });

  return (
    <PageContainer
      title={`Device: ${item.nickname || item.hostname || item.ipAddress}`}
    >
      <Tabs.Root defaultValue="action">
        <Tabs.List>
          <Tabs.Trigger value="action" className="cursor-pointer">
            Action
          </Tabs.Trigger>
          <Tabs.Trigger value="settings" className="cursor-pointer">
            Settings
          </Tabs.Trigger>
        </Tabs.List>
        <Box pt="3">
          <Tabs.Content value="action">
            <Flex direction="column" gap="4">
              <Box>
                {item.online && item.features?.includes("PowerOff") && (
                  <Button
                    className="cursor-pointer"
                    onClick={async () => {
                      const response = await powerOff(
                        item["@id"] as string,
                        session as Session
                      );
                      setActionResponse(response);
                    }}
                  >
                    Power Off
                  </Button>
                )}
                {!item.online && item.features?.includes("PowerOff") && (
                  <Button
                    className="cursor-pointer"
                    onClick={async () => {
                      const response = await powerOn(
                        item["@id"] as string,
                        session
                      );
                    }}
                  >
                    Power On
                  </Button>
                )}
              </Box>
              {actionResponse.error && (
                <Box
                  className="bg-red-100 border-red-500 rounded-md border"
                  p="4"
                >
                  <Text as="div" color="red" weight="medium">
                    {actionResponse.error}
                  </Text>
                </Box>
              )}
              {actionResponse.success && (
                <Box
                  className="bg-green-200 border-green-700 rounded-md border drop-shadow-md"
                  p="4"
                >
                  <Text as="div" color="green" weight="medium">
                    {actionResponse.success}
                  </Text>
                </Box>
              )}
            </Flex>
          </Tabs.Content>
          <Tabs.Content value="settings">
            <form method="POST">
              <Flex align="start" direction="column" gap="4">
                <TextInput
                  name="nickname"
                  label="Nickname"
                  defaultValue={item.nickname}
                />
                <TextInput
                  name="hostname"
                  label="Hostname"
                  defaultValue={item.hostname}
                />
                <TextInput
                  name="ipAddress"
                  label="IP Address"
                  defaultValue={item.ipAddress}
                />
                <TextInput
                  name="macAddress"
                  label="MAC Address"
                  defaultValue={item.macAddress}
                />
              </Flex>
            </form>
          </Tabs.Content>
        </Box>
      </Tabs.Root>
    </PageContainer>
  );
};
