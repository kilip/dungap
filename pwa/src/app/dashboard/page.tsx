import { Section } from "@radix-ui/themes";
import { Session } from "next-auth";
import { PageContainer } from "../../components/ui/PageContainer";
import { List, Props as ListProps } from "../../device/components/List";
import { PagedCollection } from "../../types/collection";
import { Device } from "../../types/device";
import { fetchApi, FetchResponse } from "../../util/dataAccess";
import { auth } from "../auth";

async function getServerSideProps(
  session: Session | null
): Promise<ListProps | undefined> {
  const filters = {};
  const page = 1;
  try {
    const response: FetchResponse<PagedCollection<Device>> | undefined =
      await fetchApi("/devices", {}, session);

    if (!response?.data) {
      throw new Error("Can not fetch from /devices");
    }
    return { data: response.data, hubURL: response.hubURL, page, filters };
  } catch (error) {
    console.log(error);
  }

  return undefined;
}

export default async function Home() {
  const session: Session | null = await auth();
  const props = await getServerSideProps(session);

  return (
    <PageContainer title="Dashboard">
      <Section p="2">{props && <List {...props} />}</Section>
    </PageContainer>
  );
}
