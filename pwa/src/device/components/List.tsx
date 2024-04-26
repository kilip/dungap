"use client";
import { Box, Card, Flex, Grid, Link, Text } from "@radix-ui/themes";
import { NextPage } from "next";
import { PagedCollection } from "../../types/collection";
import { Device } from "../../types/device";
import { useMercure } from "../../util/mercure";

export interface Props {
  data: PagedCollection<Device> | null;
  hubURL: string | null;
  filters: {};
  page: number;
}

export const List: NextPage<Props> = ({
  data,
  hubURL,
  filters,
  page,
}: Props) => {
  const collection = useMercure(data, hubURL);
  return (
    <Grid columns="3" gap="2">
      {!!collection && !!collection["hydra:member"] && (
        <>
          {collection["hydra:member"].length !== 0 &&
            collection["hydra:member"].map((device) => (
              <Link href={device["@id"]} key={device["@id"]}>
                <Card key={device["@id"]} variant="surface">
                  <Flex align="center">
                    <Box>
                      <Text as="div" size="2" weight="bold" align="center">
                        {device.nickname || device.hostname || device.ipAddress}
                      </Text>
                      <Text as="div" size="2" color="gray">
                        {device.online ? "online" : "offline"}
                      </Text>
                    </Box>
                  </Flex>
                </Card>
              </Link>
            ))}
        </>
      )}
    </Grid>
  );
};
