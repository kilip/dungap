import { Box, Text } from "@radix-ui/themes";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { useLatestState } from "~/states/common";
import { Node } from "~/types/node";
import { useMercure } from "~/utils/mercure";

interface Props {
  node: Node;
  hubUrl: string;
}

function formatUptime(timestamp: string | number | boolean) {
  dayjs.extend(relativeTime);
  const ts = Number(timestamp);
  const ret = dayjs.unix(ts).toNow(true);
  return ret;
}

export default function NodeSummary({ node, hubUrl }: Props) {
  const online = useLatestState(node.states.online, hubUrl);
  const uptime = useLatestState(node.states.uptime, hubUrl);
  const item = useMercure(node, hubUrl);

  return (
    <Box key={item.id}>
      <Text as="div" weight={"bold"}>
        {item.name}
      </Text>
      <Text as="div" size={"1"}>
        {online?.state === "online" ? "online" : "offline"}
        {uptime?.state && ` (${formatUptime(uptime.state)})`}
      </Text>
      <Text as="div" size={"1"}></Text>
    </Box>
  );
}
