import { ActionFunctionArgs } from "@remix-run/node";
import { getUserSession } from "~/auth/user.server";
import { PagedCollection } from "~/types/collection";
import { Attribute } from "~/types/node";
import { fetchApi, FetchResponse } from "~/utils/api";

export async function action({ params, request }: ActionFunctionArgs) {
  const session = await getUserSession(request);

  const response: FetchResponse<PagedCollection<Attribute>> | undefined =
    await fetchApi(`/nodes/${params.nodeId}/attributes`, {}, session);

  return {
    attributes: response?.data,
    hubUrl: response?.hubURL,
  };
}
