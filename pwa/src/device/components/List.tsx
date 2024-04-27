"use client";
import { Grid } from "@radix-ui/themes";
import { NextPage } from "next";
import { PagedCollection } from "../../types/collection";
import { Device } from "../../types/device";
import { useMercure } from "../../util/mercure";
import ListItem from "./ListItem";

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
              <ListItem device={device} key={device["@id"]} />
            ))}
        </>
      )}
    </Grid>
  );
};
