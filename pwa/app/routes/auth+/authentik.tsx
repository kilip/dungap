import { LoaderFunctionArgs } from "@remix-run/node";
import { authenticator } from "~/auth/auth.server";

export const loader = ({ request }: LoaderFunctionArgs) => {
  return authenticator.authenticate("authentik", request);
};
