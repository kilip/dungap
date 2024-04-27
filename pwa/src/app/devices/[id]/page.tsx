import { Session } from "next-auth";
import { powerOff, powerOn } from "../../../device/actions";
import { Show, Props as ShowProps } from "../../../device/components/Show";
import { Device } from "../../../types/device";
import { fetchApi, FetchResponse } from "../../../util/dataAccess";
import { auth } from "../../auth";
interface Props {
  params: {
    id: string;
  };
}

async function getServerSideProps(
  id: string,
  session: Session | null,
  powerOn: typeof powerOn,
  powerOff: typeof powerOff
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
      throw new Error(`Unable to retrieve data from /devices/${id}.`);
    }
    return { data: response.data, hubURL: response.hubURL, powerOn, powerOff };
  } catch (error) {
    console.log(error);
  }

  return undefined;
}

export default async function Page({ params }: Props) {
  const id = params.id;
  const session: Session | null = await auth();
  const props = await getServerSideProps(params.id, session, powerOn, powerOff);
  return <>{props && <Show {...props} />}</>;
}
