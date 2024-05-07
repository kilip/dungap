import { useOutletContext } from "@remix-run/react";
import { User } from "../auth.server";

export type UserContextType = { user: User | null; };
export function useUser() {
  return useOutletContext<UserContextType>();
}
