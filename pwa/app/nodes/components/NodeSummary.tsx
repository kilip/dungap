import { Box, Text } from "@radix-ui/themes";
import { Node } from "~/types/node";
import { useMercure } from "~/utils/mercure";
import { useOnline } from "../common";

interface Props {
  node: Node;
  hubUrl: string;
}

export default function NodeSummary({ node, hubUrl }: Props) {
  const online = useOnline(node, hubUrl);
  const item = useMercure(node, hubUrl);

  return (
    <Box key={item.id}>
      <Text as="div" weight={"bold"}>
        {item.name}
      </Text>
      <Text as="div" size={"1"}>
        {online ? "online" : "offline"}
      </Text>
      <Text as="div" size={"1"}></Text>
    </Box>
  );
}
