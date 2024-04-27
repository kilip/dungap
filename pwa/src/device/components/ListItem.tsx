import { Box, Card, Flex, Text } from "@radix-ui/themes";
import classNames from "classnames";
import { LucideComputer } from "lucide-react";
import moment from "moment";
import { useRouter } from "next/navigation";
import { Device } from "../../types/device";

interface Props {
  device: Device;
}

export default function ListItem({ device }: Props) {
  const online = device.online;
  const router = useRouter();
  return (
    <Card
      key={device["@id"]}
      variant="surface"
      onClick={() => router.push(`${device["@id"]}`)}
      className={classNames({
        "cursor-pointer": true,
        "bg-green-600 border-green-950": device.online,
        "bg-slate-100 border-slate-800": !device.online,
      })}
    >
      <Flex align="center" direction="row" gap="2">
        <Box>
          <LucideComputer />
        </Box>
        <Flex direction="column">
          <Box>
            <Text as="div" size="2" weight="bold" align="center">
              {device.nickname || device.hostname || device.ipAddress}
            </Text>
            <Text>{device.uptime && moment(device.uptime).toNow(true)}</Text>
          </Box>
        </Flex>
      </Flex>
    </Card>
  );
}
