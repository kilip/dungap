import type { LoaderFunctionArgs, MetaFunction } from "@remix-run/node";
import { Outlet } from "@remix-run/react";
import { getSession } from "~/auth/session.server";
import { DefaultLayout } from "~/ui/layout/DefaultLayout";

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
    <DefaultLayout>
      <Outlet />
    </DefaultLayout>
  );
}
