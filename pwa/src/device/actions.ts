"use server";

import { Session } from "next-auth";
import { config } from "../util/config";


export async function PowerOn(
  deviceId: string,
  session: Session | null
): Promise<void> {
  "use server";
  const url = `${config.entrypoint}${deviceId}/power-on`;
  const response = await fetch(url, {
    method: "GET",
    headers: {
      Authorization: `Bearer ${session?.accessToken}`,
    },
  });
}
