import { auth } from "@/app/auth";
import { PowerOn } from "@/device/actions";
import { Device } from "@/types/device";
import { fetchApi, FetchResponse } from "@/util/dataAccess";
import { Session } from "next-auth";
import { Show, Props as ShowProps } from "../../../device/components/Show";
interface Props {
  params: {
    id: string;
  };
}

async function getServerSideProps(
  id: string,
  session: Session | null,
  powerOn: typeof PowerOn
): Promise<ShowProps | undefined> {
  try {
    const response: FetchResponse<Device> | undefined = await fetchApi(
      `/devices/${id}`,
      {
        headers: {
          Preload: "/devices/*",
        },
        cache: "no-cache",
      },
      session
    );
    if (!response?.data) {
      throw new Error(`Unable to retrieve data from /books/${id}.`);
    }
    return { data: response.data, hubURL: response.hubURL, powerOn };
  } catch (error) {
    console.log(error);
  }

  return undefined;
}

export default async function Page({ params }: Props) {
  const id = params.id;
  const session: Session | null = await auth();
  const props = await getServerSideProps(params.id, session, PowerOn);
  return <>{props && <Show {...props} />}</>;
}
