import { Heading } from "@radix-ui/themes";
import type { LoaderFunctionArgs, MetaFunction } from "@remix-run/node";
import { getSession } from "~/auth/session.server";

export const meta: MetaFunction = () => {
  return [
    { title: "New Remix App" },
    { name: "description", content: "Welcome to Remix!" },
  ];
};

export async function loader({ request }: LoaderFunctionArgs) {
  const session = await getSession(request.headers.get("cookie"));
  const user = session.get("user");
  return {
    user,
  };
}

export default function Index() {
  // const { user } = useLoaderData<typeof loader>();
  return (
    <>
      <Heading size="4">Devices</Heading>
    </>
  );
}
