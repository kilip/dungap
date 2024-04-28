import { Box, Flex, Grid, Heading } from "@radix-ui/themes";
import type { LoaderFunctionArgs, MetaFunction } from "@remix-run/node";
import { useLoaderData } from "@remix-run/react";
import { getSession } from "~/auth/session.server";
import { PagedCollection } from "~/types/collection";
import { Device } from "~/types/device";
import { fetchApi, FetchResponse } from "~/utils/api";
import { useMercure } from "~/utils/mercure";

export const meta: MetaFunction = () => {
  return [
    { title: "dungap - Dashboard" },
    { name: "description", content: "Welcome to Remix!" },
  ];
};

export async function loader({ request }: LoaderFunctionArgs) {
  const session = await getSession(request.headers.get("cookie"));
  const user = session.get("user");
  let devices: PagedCollection<Device> | undefined = undefined;
  let hubUrl: string | null = null;

  const response: FetchResponse<PagedCollection<Device>> | undefined =
    await fetchApi("/devices", {}, session);

  if (response?.data) {
    devices = response.data;
    hubUrl = response.hubURL;
  }

  return {
    user,
    devices,
    hubUrl,
  };
}

export default function Index() {
  // const { user } = useLoaderData<typeof loader>();
  const { devices, hubUrl } = useLoaderData<typeof loader>();
  const collection = useMercure(devices, hubUrl);
  return (
    <Flex direction="column">
      <Heading size="4">Dashboard</Heading>
      <Grid columns="4" gap="2" width="auto">
        {collection?.["hydra:member"].map((device) => (
          <Box key={device.id}>{device.ipAddress}</Box>
        ))}
      </Grid>
    </Flex>
  );
}
