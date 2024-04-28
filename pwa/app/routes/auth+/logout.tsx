import { LoaderFunctionArgs } from "@remix-run/node";
import { authenticator } from "~/auth/auth.server";

export async function loader({ request }: LoaderFunctionArgs) {
  await authenticator.logout(request, { redirectTo: "/" });
}
